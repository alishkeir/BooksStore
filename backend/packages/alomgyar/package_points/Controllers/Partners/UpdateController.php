<?php

namespace Alomgyar\PackagePoints\Controllers\Partners;

use Alomgyar\PackagePoints\Models\PackagePointPartner;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function __invoke(Request $request, PackagePointPartner $partner): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $partner->update($request->all());

        return redirect()->back()->with('success', 'Adatok módosítása sikeres volt.');
    }
}
