<?php

namespace App\Http\Resources;

use App\Http\Traits\ImageTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{
    use ImageTrait;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->main_banner) {
            $this->main_banner = $this->getOptimizedImage($this->main_banner);
        }
        if ($this->main_hero_banner) {
            $this->main_hero_banner = $this->getOptimizedImage($this->main_hero_banner);
        }

        return [
            'main_banner' => [
                'cover' => $this->main_banner,
                'title' => $this->main_banner_title,
                'link' => $this->main_banner_url,
            ],
            'main_hero_banner' => [
                'cover' => $this->main_hero_banner,
                'title' => $this->main_hero_banner_title,
                'link' => $this->main_hero_banner_url,
            ],
        ];
    }
}
