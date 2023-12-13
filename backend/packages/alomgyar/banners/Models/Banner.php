<?php

namespace Alomgyar\Banners\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'main_banner',
        'main_banner_title',
        'main_banner_url',
        'main_hero_banner',
        'main_hero_banner_title',
        'main_hero_banner_url',
        'shop_id',
        'status',
    ];

    public function getMainBannerAsWebp()
    {
        $ext = pathinfo($this->main_banner, PATHINFO_EXTENSION);
        $main_banner_name = rtrim($this->main_banner, $ext);

        return $main_banner_name.'webp';
    }

    public function getMainHeroBannerAsWebp()
    {
        $ext = pathinfo($this->main_banner, PATHINFO_EXTENSION);
        $main_hero_banner_name = rtrim($this->main_hero_banner, $ext);

        return $main_hero_banner_name.'webp';
    }
}
