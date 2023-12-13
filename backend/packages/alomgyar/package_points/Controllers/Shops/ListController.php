<?php

namespace Alomgyar\PackagePoints\Controllers\Shops;

use Alomgyar\PackagePoints\Models\PackagePointShop;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class ListController extends Controller
{
    public function __invoke(): Response
    {
        $shops = PackagePointShop::all();

        return response()->view('package_points::shops.list', compact('shops'));
    }
}
