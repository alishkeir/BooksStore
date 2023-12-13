<?php

namespace Alomgyar\PackagePoints\Controllers\Packages;

use Alomgyar\PackagePoints\Models\PackagePointPackage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class EditController extends Controller
{
    public function __invoke(PackagePointPackage $package): Response
    {
        $formRoute = route('package-points.package.update', $package);

        return response()
            ->view('package_points::packages.form', compact('package', 'formRoute'));
    }
}
