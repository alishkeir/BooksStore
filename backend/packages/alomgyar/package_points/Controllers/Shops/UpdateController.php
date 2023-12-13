<?php

namespace Alomgyar\PackagePoints\Controllers\Shops;

use Alomgyar\PackagePoints\Models\PackagePointShop;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function __invoke(Request $request, PackagePointShop $shop): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $data = request()->all();

        $shop->update($data);

        return redirect()->back()->with('success', 'Adatok módosítása sikeres volt.');
    }
}
