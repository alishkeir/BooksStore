<?php

namespace Alomgyar\PackagePoints\Controllers\Partners;

use Alomgyar\PackagePoints\Models\PackagePointPartner;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class EditController extends Controller
{
    public function __invoke(PackagePointPartner $partner): Response
    {
        $formRoute = route('package-points.partners.update', $partner);

        return response()
            ->view('package_points::partners.form', compact('partner', 'formRoute'));
    }
}
