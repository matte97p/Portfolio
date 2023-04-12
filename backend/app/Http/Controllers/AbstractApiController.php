<?php

namespace App\Http\Controllers;

use GuzzleHttp\Utils;
use GuzzleHttp\Client;
use App\Utils\Logger\Logger;
use Illuminate\Http\Request;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * @author Matteo Perino
 */
abstract class AbstractApiController extends BaseController
{
    const UA = 'Portfolio';

    protected $test_environment = false;
    protected ?Client $client = null;
    protected ?Logger $logger = null;

    public function __construct(Request $request)
    {
        $this->test_environment = isset($request->header()["testing"][0]) ?? false;

        $this->logger = new Logger(
            'PortfolioApi', //filename
            '' //subpath
        );
    }

    /**
     * @return Client
     *
     * @throws \Exception
     */
    public function getClient(): Client
    {
        if( is_null($this->client) ){
            if( empty($this->getBaseUri()) )
            {
                throw new \Exception('No base URI defined in '.static::class);
            }

            $this->client = new Client([
                'base_uri'                      => $this->getBaseUri(),
                RequestOptions::TIMEOUT         => 20,
                RequestOptions::CONNECT_TIMEOUT => 10,
                RequestOptions::VERIFY          => false, // NEVER DO THAT PLEASEEEEEEE -> set a certificate as docs [https://docs.guzzlephp.org/en/stable/request-options.html]
                RequestOptions::HEADERS => [
                    'User-Agent' => static::UA,
                    'Accept' => '*/*'
                ],
            ]);
        }

        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient(Client $client): void
    {
        $this->client = $client;
    }


    /**
     * @return string Returns the proper base uri
     */
    abstract protected function getBaseUri():string;

    /**
     * Does a request, merging default parameters that would be otherwise dropped by Guzzle.
     *
     * @param string $method
     * @param string $uri
     * @param array $data
     *
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function request(string $method, string $uri, array $data = [] )
    {
        $data = array_merge_recursive( $this->getClient()->getConfig(), $data );

        $logger = $this->logger;
        $process_mark = $logger->getProcessMark();

        $request = new GuzzleRequest(
            $method,
            $uri,
            $data[ RequestOptions::HEADERS ],
            $data[ RequestOptions::BODY ] ?? null
        );

        // log request
        $this->log(
            $this->getRequestLogEntry( $data, $uri ),
            $process_mark,
            $logger::ACTION_REQUEST
        );

        try {
            $response = $this->getClient()->send( $request, $data );
        } catch ( ClientException $exception ) {
            $response = $exception->getResponse();
        } catch ( ConnectException $exception ) {
            $response = $exception->getMessage();
        }

        // log response
        $this->log(
            $this->getResponseLogEntry( $response, $uri ),
            $process_mark,
            $logger::ACTION_RESPONSE
        );

        if(isset($exception)){
            throw $exception;
        }

        return $response;
    }

    /**
     * Log request object
     *
     * @param array $options
     * @param string $uri
     *
     * @return string
     *
     * @throws \Exception
     */
    private function getRequestLogEntry( array $options, string $uri ): string
    {
        $entry_content = '';

        if( isset($options[RequestOptions::FORM_PARAMS]) ){
            $entry_content = \http_build_query($options[RequestOptions::FORM_PARAMS], '', '&');
        }
        elseif ( isset($options[RequestOptions::JSON]) )
        {
            $entry_content = Utils::jsonEncode($options[RequestOptions::JSON]);
        }
        elseif( isset($options[RequestOptions::BODY]) ){
            $entry_content = $options[RequestOptions::BODY];
        }

        if(isset($options[ RequestOptions::HEADERS ])) $entry_content .= "\nHeaders: " . json_encode($options[ RequestOptions::HEADERS ]);

        $endpoint = $this->getClient()->getConfig('base_uri').$uri;

        if( isset($options[RequestOptions::QUERY]) ){
            $endpoint .= '?'.\http_build_query($options[RequestOptions::QUERY],null,'&');
        }

        $log_entry = 'Request to ' . ($this->test_environment ?:'['.$this->test_environment.']') . $endpoint . "\n" . $entry_content;

        return $log_entry;
    }

    /**
     * Log response object
     *
     * @param string|ResponseInterface $response
     * @param string $uri
     *
     * @return string
     *
     * @throws \Exception
     */
    private function getResponseLogEntry( $response, string $uri ): string
    {
        $body = null;
        if(is_string($response)) {
            $body = $response;
        } else if(is_object($response)) {
            $interfaces = class_implements($response);
            if(isset($interfaces[ResponseInterface::class])) {
                $body = (string) $response->getBody() . "\nHeaders: " . json_encode($response->getHeaders());
            }
        }

        if(is_null($body)) {
            throw new \Exception("Unexpected response type. Expected string or ResponseInterface, " . gettype($response) . " given");
        }

        $log_entry = 'Response from ' . ($this->test_environment ?:'['.$this->test_environment.']') . $this->getClient()->getConfig('base_uri'). $uri . "\n" . $body;

        return $log_entry;
    }

    /**
     * Log action
     *
     * @param string $log_entry
     * @param string $process_mark
     * @param string $action
     *
     * @return void
     */
    private function log( string $log_entry, string $process_mark, string $action = 'request' )
    {
        $this->logger->info( $log_entry, [$process_mark] );
    }
}
