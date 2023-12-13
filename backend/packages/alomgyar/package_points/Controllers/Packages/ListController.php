<?php

namespace Alomgyar\PackagePoints\Controllers\Packages;

use Alomgyar\PackagePoints\Models\PackagePointPackage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ListController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $packages = PackagePointPackage::filter($request)->paginate(25);

        return response()->view('package_points::packages.list', compact('packages'));
    }
}
