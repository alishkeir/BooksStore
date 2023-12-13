<?php

namespace Alomgyar\PackagePoints\Controllers\Partners;

use Alomgyar\PackagePoints\Models\PackagePointPartner;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $partner = PackagePointPartner::create($request->only('name', 'link', 'email', 'phone'));

        return redirect()->route('package-points.partners.list')
            ->with('success', 'Partner ('.$partner->name.') létrehozása sikeres volt.');
    }
}
