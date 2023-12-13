<?php

namespace Alomgyar\PickUpPoints\Providers;

use Alomgyar\PickUpPoints\Interfaces\Provider;
use Alomgyar\PickUpPoints\Model\PickUpPoint;
use GuzzleHttp\Client;

class FoxPost implements Provider
{
    const URL = 'http://cdn.foxpost.hu/foxpost_terminals_extended_v3.json';

    const PROVIDER = 'fox_post';

    const PROVIDER_NAME = 'FoxPost';

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
                    'provider_id' => $point->place_id,
                ],
                [
                    'name' => $point->name,
                    'city' => $point->city,
                    'address' => $point->street,
                    'zip' => $point->zip,
                    'long' => $point->geolng,
                    'lat' => $point->geolat,
                    'open' => $this->decorateOpen($point->open),
                    'description' => $point->findme,
                    'status' => true,
                ]
            );
        }
    }

    private function decorateOpen($open): string
    {
        $spritStr = 'Hétfő: %s, Kedd: %s, Szerda: %s, Csütörtök: %s, Péntek: %s, Szombat: %s, Vasárnap: %s';

        return sprintf(
            $spritStr,
            $open->hetfo,
            $open->kedd,
            $open->szerda,
            $open->csutortok,
            $open->pentek,
            $open->szombat,
            $open->vasarnap);
    }
}
