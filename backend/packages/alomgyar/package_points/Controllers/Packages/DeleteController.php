<?php

namespace Alomgyar\PackagePoints\Controllers\Packages;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class DeleteController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        return redirect()
            ->route('package-points.partner.index')
            ->with('warning', 'A törlés sikeres volt.');
    }
}
