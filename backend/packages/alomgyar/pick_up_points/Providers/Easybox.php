<?php

namespace Alomgyar\PickUpPoints\Providers;

use Alomgyar\PickUpPoints\Interfaces\Provider;
use Alomgyar\PickUpPoints\Model\PickUpPoint;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Easybox implements Provider
{
    const PROVIDER = 'easybox';

    const PROVIDER_NAME = 'Easybox';

    protected $token;

    public function __construct()
    {
        $this->login();
    }

    public function collect()
    {
        $this->collectFromProvider();
    }

    private function collectFromProvider(): void
    {
        $res = Http::acceptJson()
            ->withHeaders([
                'X-AUTH-TOKEN' => $this->token,
            ])
            ->get(config('services.easybox.base_url').'/api/locker/lockers');

        Log::channel('easybox')->info("EndPoint: /api/locker/lockers, Response: $res");

        if ($res->status() != 200) {
            return;
        }
        $data = $res->json();

        PickUpPoint::where(['provider' => self::PROVIDER])->update(['status' => false]);

        foreach ($data as $point) {
            PickUpPoint::updateOrCreate(
                [
                    'provider' => self::PROVIDER,
                    'provider_name' => self::PROVIDER_NAME,
                    'provider_id' => $point['lockerId'],
                ],
                [
                    'name' => $point['name'],
                    'city' => $point['city'],
                    'address' => $point['address'],
                    'zip' => $point['postalCode'],
                    'long' => $point['lng'],
                    'lat' => $point['lat'],
                    'open' => $this->decorateOpen($point['schedule']),
                    'description' => '',
                    'status' => true,
                ]
            );
        }
    }

    private function decorateOpen($open): string
    {
        $spritStr = 'Hétfő: %s, Kedd: %s, Szerda: %s, Csütörtök: %s, Péntek: %s, Szombat: %s, Vasárnap: %s';

        $res = [];

        foreach ($open as $day) {
            switch ($day['day']) {
                case 1:
                    $res['hetfo'] = $day['openingHour'].' - '.$day['closingHour'];
                    break;
                case 2:
                    $res['kedd'] = $day['openingHour'].' - '.$day['closingHour'];
                    break;
                case 3:
                    $res['szerda'] = $day['openingHour'].' - '.$day['closingHour'];
                    break;
                case 4:
                    $res['csutortok'] = $day['openingHour'].' - '.$day['closingHour'];
                    break;
                case 5:
                    $res['pentek'] = $day['openingHour'].' - '.$day['closingHour'];
                    break;
                case 6:
                    $res['szombat'] = $day['openingHour'].' - '.$day['closingHour'];
                    break;
                case 7:
                    $res['vasarnap'] = $day['openingHour'].' - '.$day['closingHour'];
                    break;
                default:
                    break;
            }
        }

        return sprintf(
            $spritStr,
            $res['hetfo'] ?? 'Zárva',
            $res['kedd'] ?? 'Zárva',
            $res['szerda'] ?? 'Zárva',
            $res['csutortok'] ?? 'Zárva',
            $res['pentek'] ?? 'Zárva',
            $res['szombat'] ?? 'Zárva',
            $res['vasarnap'] ?? 'Zárva',
        );
    }

    private function login()
    {
        $this->token = cache()->remember('easybox_token', config('services.easybox.lifetime'), function () {
            $res = Http::acceptJson()
                ->asForm()
                ->withHeaders([
                    'X-AUTH-USERNAME' => config('services.easybox.username'),
                    'X-AUTH-PASSWORD' => config('services.easybox.password'),
                ])
                ->post(config('services.easybox.base_url').'/api/authenticate', [
                    'remember_me' => 'true',
                ]);
            Log::channel('easybox')->info("EndPoint: /api/authenticate, Response: $res");
            if ($res->status() != 200) {
                return;
            }

            return $res->json()['token'] ?? '';
        });
    }
}
