<?php

namespace Alomgyar\PickUpPoints\Providers;

use Alomgyar\PickUpPoints\Exception\ScraperError;
use Alomgyar\PickUpPoints\Interfaces\Provider;
use Alomgyar\PickUpPoints\Model\PickUpPoint;

class PickPackPoint implements Provider
{
    const URL = 'https://online.sprinter.hu/terkep/data.json';

    const PROVIDER = 'pick_pack_point';

    const PROVIDER_NAME = 'Pick Pack Point';

    public function collect()
    {
        $this->collectFromProvider();
    }

    private function collectFromProvider()
    {
        $content = file_get_contents(self::URL);
        $content = trim($content, chr(239).chr(187).chr(191));

        $data = json_decode($content);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $errNum = json_last_error();
            throw new ScraperError("Json parse error code({$errNum})", $errNum);
        }

        PickUpPoint::where(['provider' => self::PROVIDER])->update(['status' => false]);

        foreach ($data as $point) {
            PickUpPoint::updateOrCreate(
                [
                    'provider' => self::PROVIDER,
                    'provider_name' => self::PROVIDER_NAME,
                    'provider_id' => $point->shopCode,
                ],
                [
                    'name' => $point->shopName,
                    'city' => $point->city,
                    'address' => $this->decorateAddress($point->address),
                    'zip' => $point->zipCode,
                    'long' => $point->lng,
                    'lat' => $point->lat,
                    'open' => $this->decorateOpen($point->openTimes),
                    'description' => null,
                    'status' => true,
                ]
            );
        }
    }

    private function decorateAddress($address): string
    {
        $address = explode(',', $address);

        unset($address[0]);
        unset($address[1]);

        return implode(',', $address);
    }

    private function decorateOpen($openTimes)
    {
        $monday = $openTimes->monday->isOpen ? $openTimes->monday->from.' '.$openTimes->monday->to : 'Zárva';
        $tuesday = $openTimes->tuesday->isOpen ? $openTimes->tuesday->from.' '.$openTimes->tuesday->to : 'Zárva';
        $wednesday = $openTimes->wednesday->isOpen ? $openTimes->wednesday->from.' '.$openTimes->wednesday->to : 'Zárva';
        $thursday = $openTimes->thursday->isOpen ? $openTimes->thursday->from.' '.$openTimes->thursday->to : 'Zárva';
        $friday = $openTimes->friday->isOpen ? $openTimes->friday->from.' '.$openTimes->friday->to : 'Zárva';
        $saturday = $openTimes->saturday->isOpen ? $openTimes->saturday->from.' '.$openTimes->saturday->to : 'Zárva';
        $sunday = $openTimes->sunday->isOpen ? $openTimes->sunday->from.' '.$openTimes->sunday->to : 'Zárva';

        return "Hétfő: {$monday}, Kedd: {$tuesday}, Szerda: {$wednesday}, Csütrötök: {$thursday}, Péntek: {$friday}, Szombat: {$saturday}, Vasárnap: {$sunday}";
    }
}
