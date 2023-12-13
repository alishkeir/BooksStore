<?php

namespace Alomgyar\PackagePoints\Controllers\Shops;

use Alomgyar\PackagePoints\Models\PackagePointShop;
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
        $data = request()->all();

        PackagePointShop::create($data);

        return redirect()->route('package-points.shops.list')
                         ->with('success', 'Shop létrehozása sikeres volt.');
    }
}
