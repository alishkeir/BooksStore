<?php

namespace App\Http\Controllers;

use Alomgyar\Shops\Shop;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StoreListApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        $shops = Shop::select('title')->activeOfflineShops()->orderBy('title')->pluck('title')->toArray();
        array_unshift($shops, 'WEBSHOP');

        return response()->json($shops, Response::HTTP_OK);
    }
}
