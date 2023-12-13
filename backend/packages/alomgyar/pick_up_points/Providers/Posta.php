<?php

namespace Alomgyar\PickUpPoints\Providers;

use Alomgyar\PickUpPoints\Interfaces\Provider;
use Alomgyar\PickUpPoints\Model\PickUpPoint;
use GuzzleHttp\Client;

class Posta implements Provider
{
    const URL = 'https://www.posta.hu/szolgaltatasok/posta-srv-postoffice/rest/postoffice/list?searchField=&searchText=&timestamp=0&types=posta,postamachine'; // a postaautomata típusa: postamachine

    const PROVIDER = 'posta';

    const PROVIDER_NAME = 'Posta';

    public function collect()
    {
        $this->collectFromProvider();
    }

    private function collectFromProvider()
    {
        $client = new Client();

        $res = $client->request('GET', self::URL);

        $data = json_decode($res->getBody());

        if (! $data) {
            return;
        }

        PickUpPoint::where(['provider' => self::PROVIDER])->update(['status' => false]);

        foreach ($data->items as $point) {
            if (! $point->lat || ! $point->lng) {
                continue;
            }

            PickUpPoint::updateOrCreate(
                [
                    'provider' => self::PROVIDER,
                    'provider_name' => self::PROVIDER_NAME,
                    'provider_id' => $point->id,
                ],
                [
                    'provider_type' => $point->type,
                    'name' => $point->name,
                    'city' => $point->city,
                    'address' => $point->address,
                    'zip' => $point->zipCode,
                    'long' => $point->lng,
                    'lat' => $point->lat,
                    'open' => $point->status === 'closed' ? 'Zárva' : 'Nyitva',
                    'description' => null,
                    'status' => true,
                ]
            );
        }
    }
}
