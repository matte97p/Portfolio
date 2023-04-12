<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class CacheController extends Controller  {

    /**
     * @var mixed $type -> cache obj name
     * @var mixed $value
     *
     * @return void
     */
    static function setCache($type, $value)
    {
        Cache::put($type, $value, 600);
    }

    /**
     * @var mixed $type -> cache obj name
     *
     * @return mixed
     */
    static function getCache($type)
    {
        return Cache::get($type);
    }

    /**
     * @var mixed $type -> cache obj name
     *
     * @return void
     */
    static function delCache($type)
    {
        Cache::forget($type);
    }
}
