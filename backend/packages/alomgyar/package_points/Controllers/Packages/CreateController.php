<?php

namespace Alomgyar\PackagePoints\Controllers\Packages;

use Alomgyar\PackagePoints\Models\PackagePointPackage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class CreateController extends Controller
{
    public function __invoke(): Response
    {
        $package = new PackagePointPackage();
        $formRoute = route('package-points.package.store');

        return response()->view('package_points::packages.form', compact('package', 'formRoute'));
    }
}
