<?php

namespace Alomgyar\PackagePoints\Controllers\Shops;

use Alomgyar\PackagePoints\Models\PackagePointShop;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class CreateController extends Controller
{
    public function __invoke(): Response
    {
        $shop = new PackagePointShop();
        $formRoute = route('package-points.shops.store');

        return response()->view('package_points::shops.form', compact('shop', 'formRoute'));
    }
}
