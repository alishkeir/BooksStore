<?php

namespace Alomgyar\PackagePoints\Controllers\Packages;

use Alomgyar\PackagePoints\Entity\Enum\Status;
use Alomgyar\PackagePoints\Models\PackagePointPackage;
use Alomgyar\PackagePoints\Services\PackagePointMailService;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __invoke(Request $request, PackagePointMailService $mailService): RedirectResponse
    {
        $this->validate($request, [
            'customer' => 'required',
            'email' => 'required|email',
            'code' => 'required',
        ]);

        $package = PackagePointPackage::create($request->all());

        if (Status::from($package->status)->equals(Status::arrived())) {
            $package->collected = Carbon::now();
            $package->save();
        }

        if (Status::from($package->status)->equals(Status::shipping())) {
            $mailService->sendShippingMail($package);
        }

        if (Status::from($package->status)->equals(Status::completed())) {
            $mailService->sendArrivedMail($package);
        }

        return redirect()->route('package-points.package.list')
            ->with('success', 'Csomag létrehozása sikeres volt.');
    }
}
