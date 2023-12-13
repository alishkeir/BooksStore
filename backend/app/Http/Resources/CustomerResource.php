<?php

namespace App\Http\Resources;

use Alomgyar\Carts\Cart;
use Alomgyar\Settings\Settings;
use App\Helpers\SettingsHelper;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $settingKeys = ['affiliate_track_period', 'affiliate_commission_percentage', 'minimum_redeem_amount', 'redeems_per_year'];
        $affiliateSettings = SettingsHelper::getSettingsByKeys($settingKeys);
        return [
            'id' => $this->id,
            'status' => $this->status,
            'email' => $this->email,
            'store' => $this->store,
            'marketing_accepted' => $this->marketing_accepted,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'username' => $this->username,
            'phone' => $this->phone,
            'author_follow_up' => $this->author_follow_up,
            'comment_follow_up' => $this->comment_follow_up,
            'preorder' => [
                'preorder_items' => $this->preorders ? CustomerPreorderResource::collection($this->preorders) : [],
            ],
            'cart' => new CartPageResource($this->cart ?? new Cart()),
            'is_affiliate' => $this->is_affiliate,
            'affiliate_settings' => $affiliateSettings
        ];
    }
}
