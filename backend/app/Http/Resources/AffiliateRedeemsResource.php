<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class AffiliateRedeemsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->redeem_file_name,
            'amount' => $this->amount,
            'link' => $this->redeem_file_url,
            'date' => Carbon::create($this->created_at)->format(config('pamadmin.date-format')),
        ];
    }
}
