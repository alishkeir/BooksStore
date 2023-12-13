<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'writer' => $this->customer->username,
            'comment' => $this->comment,
            'customer_id' => $this->customer_id,
            'entity_type' => $this->getEntityStringName($this->entity_type),
            'entity_id' => $this->getEntityID($this),
            'published_at' => $this->updated_at,
        ];
    }
}
