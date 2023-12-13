<?php

namespace Alomgyar\PackagePoints\Controllers\Partners;

use Alomgyar\PackagePoints\Models\PackagePointPartner;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class CreateController extends Controller
{
    public function __invoke(): Response
    {
        $partner = new PackagePointPartner();
        $formRoute = route('package-points.partners.store');

        return response()->view('package_points::partners.form', compact('partner', 'formRoute'));
    }
}
