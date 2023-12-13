<?php

namespace Alomgyar\PackagePoints\Controllers\Shops;

use Alomgyar\PackagePoints\Models\PackagePointShop;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class EditController extends Controller
{
    public function __invoke(PackagePointShop $shop): Response
    {
        $formRoute = route('package-points.shops.update', $shop);

        return response()
            ->view('package_points::shops.form', compact('shop', 'formRoute'));
    }
}
