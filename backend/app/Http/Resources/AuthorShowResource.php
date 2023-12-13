<?php

namespace App\Http\Resources;

use App\Http\Traits\ImageTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorShowResource extends JsonResource
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
        $perPage = 20;
        $page = $request['body']['page'];
        $count = $this->products()->active()->thisStore()->count();
        $products = $this->products()
                         ->active()
                         ->thisStore()
                         ->orderBy('created_at', 'DESC')
                         ->offset($perPage * ($page - 1))
                         ->limit($perPage)
                         ->get();

        if ($this->cover) {
            $this->cover = $this->getOptimizedImage($this->cover);
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'cover' => $this->cover,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'subscribed' => 0,
            'books' => ProductListResource::collection($products),
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $count,
                'last_page' => $count <= $page * $perPage,
            ],
        ];
    }
}
