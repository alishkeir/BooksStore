<?php

namespace Alomgyar\PickUpPoints\Services;

use Alomgyar\PickUpPoints\Job\PickUpPointScraperJob;
use Alomgyar\PickUpPoints\Providers\DPD;
use Alomgyar\PickUpPoints\Providers\Easybox;
use Alomgyar\PickUpPoints\Providers\FoxPost;
use Alomgyar\PickUpPoints\Providers\GLS;
use Alomgyar\PickUpPoints\Providers\Packeta;
use Alomgyar\PickUpPoints\Providers\PickPackPoint;
use Alomgyar\PickUpPoints\Providers\Posta;

class PickUpPointsService
{
    protected array $providers = [
        DPD::class,
        FoxPost::class,
        //GLS::class,
        PickPackPoint::class,
        Posta::class,
        Easybox::class,
        Packeta::class
    ];

    public function run()
    {
        foreach ($this->providers as $provider) {
            dispatch(new PickUpPointScraperJob($provider));
        }
    }
}
