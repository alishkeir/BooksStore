<?php

namespace Alomgyar\PickUpPoints\Providers;

use Alomgyar\PickUpPoints\Interfaces\Provider;
use Alomgyar\PickUpPoints\Model\PickUpPoint;
use GuzzleHttp\Client;

class GLS implements Provider
{
    const URL = 'https://online.gls-hungary.com/psmap/psmap_getdata.php?ctrcode=HU&action=getList&dropoff=true';

    const PROVIDER = 'gls';

    const PROVIDER_NAME = 'GLS';

    public function collect()
    {
        $this->collectFromProvider();
    }

    private function collectFromProvider(): void
    {
        $client = new Client();

        $res = $client->request('GET', self::URL);

        $data = json_decode($res->getBody());

        if (! $data) {
            return;
        }

        PickUpPoint::where(['provider' => self::PROVIDER])->update(['status' => false]);

        foreach ($data as $point) {
            PickUpPoint::updateOrCreate(
                [
                    'provider' => self::PROVIDER,
                    'provider_name' => self::PROVIDER_NAME,
                    'provider_id' => $point->pclshopid,
                ],
                [
                    'name' => $point->name,
                    'city' => $point->city,
                    'address' => $point->address,
                    'zip' => $point->zipcode,
                    'long' => $point->geolng,
                    'lat' => $point->geolat,
                    'open' => null,
                    'description' => null,
                    'status' => true,
                ]
            );
        }
    }
}
