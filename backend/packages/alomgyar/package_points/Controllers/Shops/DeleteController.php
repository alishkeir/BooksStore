<?php

namespace Alomgyar\PackagePoints\Controllers\Shops;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class DeleteController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        return redirect()
            ->route('package-points.shop.index')
            ->with('warning', 'A törlés sikeres volt.');
    }
}
