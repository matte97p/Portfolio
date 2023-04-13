<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Utils;
use GuzzleHttp\Client;
use App\Utils\Logger\Logger;
use Illuminate\Http\Request;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
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

        $this->logger = new Logger(
            env('APP_NAME') . '_internal', //filename
            'internal/' //subpath
        );
    }

    /**
     * @var Request $request
     * @return Response
     * @throws Exception
     */
    abstract protected function create(Request $request): JsonResponse; //C
    abstract protected function read(Request $request): JsonResponse;   //R
    abstract protected function update(Request $request): JsonResponse; //U
    abstract protected function delete(Request $request): JsonResponse; //D

    /**
     * @todo
     *
     * @return void
     */
    protected function request()
    {
        $logger = $this->logger;
        $process_mark = $logger->getProcessMark();

        // log request
        $this->log(
            $this->getRequestLogEntry(),
            $process_mark,
            $logger::ACTION_REQUEST
        );
    }

    /**
     * Log request object @todo
     *
     * @return string
     *
     * @throws \Exception
     */
    private function getRequestLogEntry(): string
    {
        $entry_content = '';

        if( !empty(json_decode($this->request->getContent(), true)) ){
            $entry_content = Utils::jsonEncode(json_decode($this->request->getContent(), true));
        }

        if(!empty($this->request->header())) $entry_content .= "\nHeaders: " . json_encode($this->request->header());

        $endpoint = $this->request->fullUrl();

        $log_entry = $this->subText() . 'Request to ' . $endpoint . "\n" . $entry_content;

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

    private function subText(): string
    {
        return ($this->test_environment ?'[TEST]':'') . ('[User:' . (Auth::id()??'Anon') . ']');
    }
}
