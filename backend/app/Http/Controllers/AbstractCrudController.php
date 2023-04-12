<?php

namespace App\Http\Controllers;

use GuzzleHttp\Utils;
use GuzzleHttp\Client;
use App\Utils\Logger\Logger;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use GuzzleHttp\RequestOptions;
use Illuminate\Routing\Controller as BaseController;

/**
 * @author Matteo Perino
 */
abstract class AbstractCrudController extends BaseController
{
    protected $test_environment = false;
    protected ?Client $client = null;
    protected ?Request $request = null;
    protected ?Logger $logger = null;

    public function __construct(Request $request)
    {
        $this->test_environment = isset($request->header()["testing"][0]) ?? false;
        $this->request = $request;

        $data = $this->request->json() ?? null;
        $uri = $this->request->url() ?? null;

        $this->logger = new Logger(
            env('APP_NAME'). 'Internal', //filename
            '' //subpath
        );
    }

    /**
     * @var Request $request
     * @return Response
     * @throws Exception
     */
    abstract protected function create(Request $request):JsonResponse;  //C
    abstract protected function index(Request $request):JsonResponse;   //R
    abstract protected function update(Request $request):JsonResponse;  //U
    abstract protected function delete(Request $request):JsonResponse;  //D

    /**
     * @todo
     *
     * @return void
     */
    protected function request()
    {
        $data = $this->request->json() ?? [];
        $uri = $this->request->url();

        $logger = $this->logger;
        $process_mark = $logger->getProcessMark();

        // log request
        $this->log(
            $this->getRequestLogEntry( $data, $uri ),
            $process_mark,
            $logger::ACTION_REQUEST
        );
    }

    /**
     * Log request object @todo
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
