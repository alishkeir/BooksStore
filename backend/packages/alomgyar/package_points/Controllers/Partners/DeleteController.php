<?php

namespace Alomgyar\PackagePoints\Controllers\Partners;

use Alomgyar\PackagePoints\Models\PackagePointPartner;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class DeleteController extends Controller
{
    public function __invoke(PackagePointPartner $partner): RedirectResponse
    {
        $partner->delete();

        return redirect()
            ->route('package-points.partners.list')
            ->with('success', 'A törlés sikeres volt.');
    }
}
