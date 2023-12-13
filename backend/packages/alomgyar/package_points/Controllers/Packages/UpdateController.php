<?php

namespace Alomgyar\PackagePoints\Controllers\Packages;

use Alomgyar\PackagePoints\Entity\Enum\Status;
use Alomgyar\PackagePoints\Models\PackagePointPackage;
use Alomgyar\PackagePoints\Services\PackagePointMailService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class UpdateController extends Controller
{
    public function __invoke(Request $request, PackagePointPackage $package, PackagePointMailService $mailService): RedirectResponse
    {
        $this->validate($request, [
            'customer' => 'required',
            'email' => 'required|email',
            'code' => 'required',
        ]);

        $package->fill($request->all());

        if ($package->isDirty('status') && (Status::from($package->status)->equals(Status::shipping()))) {
            $mailService->sendShippingMail($package);
        }

        if ($package->isDirty('status') && (Status::from($package->status)->equals(Status::arrived()))) {
            $mailService->sendArrivedMail($package);
        }

        if ($package->isDirty('status') && (Status::from($package->status)->equals(Status::completed()))) {
            $package->collected = Carbon::now();
        }

        $package->save();

        return redirect()->back()->with('success', 'Adatok módosítása sikeres volt.');
    }
}
