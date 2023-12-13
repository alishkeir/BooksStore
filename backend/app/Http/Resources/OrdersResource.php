<?php

namespace App\Http\Resources;

use Alomgyar\Countries\Country;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrdersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_number,
            'created_at' => $this->created_at,
            'total_amount' => $this->total_amount,
            'status' => $this->status_color,
            'order_items' => OrderItemsResource::collection($this->orderItems),
            'shipping_fee' => $this->shipping_fee,
            'payment_fee' => $this->payment_fee,
            'billing_address' => [
                'full_name' => $this->billingAddress?->full_name,
                'zip_code' => $this->billingAddress?->zip_code,
                'city' => $this->billingAddress?->city,
                'street' => $this->billingAddress?->street,
                'street_nr' => $this->billingAddress?->street_nr,
            ],
            'shipping_address' => [
                'full_name' => $this->shippingAddress?->full_name ?? $this->shipping_details->name ?? null,
                'zip_code' => $this->shippingAddress?->zip_code ?? $this->shipping_details->zip ?? null,
                'city' => $this->shippingAddress?->city ?? $this->shipping_details->county ?? null,
                'street' => $this->shippingAddress?->street ?? $this->shipping_details->address ?? null,
                'street_nr' => $this->shippingAddress?->street_nr,
            ],
            'country' => new CountryResource(Country::find($this->country_id)),

            'payment_method' => $this->paymentMethod?->name,
            'shipping_method' => $this->shippingMethod?->name,
            'invoice_url' => ! empty($this->invoice_url) ? $this->invoice_url : null,
        ];
    }
}
