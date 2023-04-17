<?php

namespace App\Http\Controllers\Concrete;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class CacheController extends Controller
{

    /**
     * @var mixed $type -> cache obj name
     * @var mixed $value
     *
     * @return void
     */
    static function setCache($type, $value): void
    {
        Cache::put($type, $value, 600);
    }

    /**
     * @var mixed $type -> cache obj name
     *
     * @return mixed
     */
    static function getCache($type): mixed
    {
        return Cache::get($type);
    }

    /**
     * @var mixed $type -> cache obj name
     *
     * @return void
     */
    static function delCache($type): void
    {
        Cache::forget($type);
    }
}
