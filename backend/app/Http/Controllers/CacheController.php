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
     * Test Redis server
     *
     * @return Redis
     */
    public function redis_test(){
        try{
            $redis = Redis::connect('127.0.0.1', 5432);
            return $redis;
        }catch(\Predis\Connection\ConnectionException $e){
            return response('error connection redis');
        }
    }

    /**
     * Redis set cache
     *
     * @var mixed $type -> cache obj name
     * @var mixed $value
     *
     * @return void
     */
    static function setCache($type, $value){
        Cache::store('redis')->put(trim($type) . (Auth::guard('web')->id() ?? Auth::guard('api')->id()), $value, 600);
    }

    /**
     * Redis get cache
     *
     * @var mixed $type -> cache obj name
     *
     * @return mixed
     */
    static function getCache($type){
        return Cache::store('redis')->get($type . Auth::guard('api')->id());
    }

    /**
     * Redis get cache
     *
     * @var mixed $type -> cache obj name
     *
     * @return void
     */
    static function delCache($type){
        Cache::forget($type);
    }
}
