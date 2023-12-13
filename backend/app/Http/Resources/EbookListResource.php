<?php

namespace App\Http\Resources;

use Alomgyar\Customers\Customer;
use App\Http\Traits\ImageTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EbookListResource extends JsonResource
{
    use ImageTrait;

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->cover) {
            $this->cover = $this->getOptimizedImage($this->cover);
        }

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'state' => $this->state == 1 ? 'preorder' : 'normal',
            'price_list' => $this->prices?->price_list,
            'price_sale' => $this->prices?->price_sale,
            'discount_percent' => $this->prices?->discount_percent,
            'cover' => $this->cover,
            'authors' => $this->author->isNotEmpty() ? AuthorResource::collection($this->author) : [],
            'mobi_url' => Customer::whichStore(request()->user()).$this->mobi_url,
            'mobi_size' => $this->mobi_size,
            'epub_url' => Customer::whichStore(request()->user()).$this->epub_url,
            'epub_size' => $this->epub_size,
        ];
    }
}
