<?php

namespace App\Http\Resources;

use App\Services\AffiliateService;
use Illuminate\Http\Resources\Json\JsonResource;

class AffiliateBalanceResource extends JsonResource
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
        $balance = (new AffiliateService)->getCustomerBalance($user);
        return [
            'balance' => $balance
        ];
    }
}
