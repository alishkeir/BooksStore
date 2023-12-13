<?php

namespace Alomgyar\PackagePoints\Controllers\Partners;

use Alomgyar\PackagePoints\Models\PackagePointPartner;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class ListController extends Controller
{
    public function __invoke(): Response
    {
        $partners = PackagePointPartner::all();

        return response()->view('package_points::partners.list', compact('partners'));
    }
}
