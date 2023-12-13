<?php

namespace Alomgyar\PickUpPoints\Providers;

use Alomgyar\PickUpPoints\Interfaces\Provider;
use Alomgyar\PickUpPoints\Model\PickUpPoint;
use GuzzleHttp\Client;

class Packeta implements Provider
{
    const URL = 'https://www.zasilkovna.cz/api/v4/f79afaeef01ca16e/branch.json?lang=hu';

    const PROVIDER = 'packeta';

    const PROVIDER_NAME = 'Packeta';

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

        foreach ($data->data as $point) {
            if($point->country === "hu") {
                PickUpPoint::updateOrCreate(
                    [
                        'provider' => self::PROVIDER,
                        'provider_name' => self::PROVIDER_NAME,
                        'provider_id' => $point->id,
                    ],
                    [
                        'name' => $point->name,
                        'city' => $point->city,
                        'address' => $point->street,
                        'zip' => $point->zip,
                        'long' => $point->longitude,
                        'lat' => $point->latitude,
                        'open' => is_string($point->openingHours->compactShort)
                            ? $point->openingHours->compactShort
                            : "",
                        'description' => '',
                        'status' => $point->status->statusId == 1,
                    ]
                );
            }
        }
    }
}
