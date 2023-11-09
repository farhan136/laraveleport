<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CacheController extends Controller
{
    public function index()
    {
        $data = Cache::get('test');
        if ($data) {
            dd($data);
        } else {
            dd("There is no cache");
        }
    }

    public function set_cache($cache = "defaultData")
    {
        Cache::put('test', $cache, 50);

        return redirect('/cache');
    }

    public function delete_cache()
    {
        Cache::forget('test');

        return redirect('/cache');
    }
}
