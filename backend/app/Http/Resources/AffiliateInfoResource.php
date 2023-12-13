<?php

namespace App\Http\Resources;

use App\Services\AffiliateService;
use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

class AffiliateInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user = request()->user();
        $affiliate = $user->is_affiliate ? $user->affiliate : null;
        $balance = (new AffiliateService)->getCustomerBalance($user);
        return [
            'inputs' => [
                'code' => $affiliate?->code ?? '',
                'name' => $affiliate?->name ?? '',
                'country' => $affiliate?->country ?? '',
                'zip' => $affiliate?->zip ?? '',
                'city' => $affiliate?->city ?? '',
                'address' => $affiliate?->address ?? '',
                'vat' => $affiliate?->vat ?? '',
            ],
            'balance' => $balance ?? 0
        ];
    }
}