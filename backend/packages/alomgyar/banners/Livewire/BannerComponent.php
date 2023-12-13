<?php

namespace Alomgyar\Banners\Livewire;

use Alomgyar\Banners\Models\Banner;
use Livewire\Component;
use Livewire\WithFileUploads;

class BannerComponent extends Component
{
    use WithFileUploads;

    public int $storeId = 0;

    public $mainBanner;

    public $mainHeroBanner;

    public Banner $model;

    protected $rules = [
        'model.main_banner_title' => 'required',
        'model.main_banner_url' => 'required',
        'model.main_hero_banner_title' => 'required',
        'model.main_hero_banner_url' => 'required',
    ];

    public function render()
    {
        return view('banners::components.form');
    }

    public function mount(): void
    {
        $this->model = Banner::where('shop_id', $this->storeId)->first();
    }

    public function setStoreId(int $storeId): void
    {
        $this->model = Banner::where('shop_id', $storeId)->first();
    }

    public function saveHero()
    {
        if ($this->mainBanner) {
            $this->validate([
                'mainBanner' => 'sometimes|image|max:1024',
            ]);

            $this->model->main_banner = $this->mainBanner->storePublicly('banner', 'public');
        }

        $this->model->save();
    }

    public function saveMainHero()
    {
        if ($this->mainHeroBanner) {
            $this->validate([
                'mainHeroBanner' => 'sometimes|image|max:1024',
            ]);

            $this->model->main_hero_banner = $this->mainHeroBanner->storePublicly('banner', 'public');
        }

        $this->model->save();
    }
}
