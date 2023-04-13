<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use App\Utils\Logger\Logger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class AbstractGenericController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $test_environment = false;
    protected ?Client $client = null;
    protected ?Logger $logger = null;
    protected static $errors = [];

    public function __construct(Request $request)
    {
        $this->test_environment = isset($request->header()["testing"][0]) ?? false;
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
    protected function log( string $log_entry, string $process_mark, string $action = 'request' )
    {
        $this->logger->info( $log_entry, [$process_mark] );
    }

    protected function subText(): string
    {
        return ($this->test_environment ?'[TEST]':'') . ('[User:' . (Auth::id()??'Anon') . ']');
    }
}
