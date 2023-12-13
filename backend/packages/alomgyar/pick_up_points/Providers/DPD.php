<?php

namespace Alomgyar\PickUpPoints\Providers;

use Alomgyar\PickUpPoints\Interfaces\Provider;
use Alomgyar\PickUpPoints\Model\PickUpPoint;
use GuzzleHttp\Client;

class DPD implements Provider
{
    const URL = 'https://weblabel.dpd.hu/dpd_wow/parcelshop_info.php?username=demo&password=o2Ijwe2!';

    const PROVIDER = 'dpd';

    const PROVIDER_NAME = 'DPD';

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

        foreach ($data->parcelshops as $point) {
            if(str_starts_with($point->parcelshop_id,"HU")) {
                PickUpPoint::updateOrCreate(
                    [
                        'provider' => self::PROVIDER,
                        'provider_name' => self::PROVIDER_NAME,
                        'provider_id' => $point->parcelshop_id,
                    ],
                    [
                        'name' => $point->company,
                        'city' => $point->city,
                        'address' => $point->street,
                        'zip' => $point->pcode,
                        'long' => $point->gpslong,
                        'lat' => $point->gpslat,
                        'open' => null,
                        'description' => '',
                        'status' => true,
                    ]
                );
            }
        }
    }
}
