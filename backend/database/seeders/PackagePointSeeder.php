<?php

namespace Database\Seeders;

use Alomgyar\PackagePoints\Models\PackagePointPartner;
use Alomgyar\PackagePoints\Models\PackagePointShop;
use Illuminate\Database\Seeder;

class PackagePointSeeder extends Seeder
{
    public function run()
    {
        $partners = [
            ['Scolar Kiadó', 'www.scolar.hu', 'scolar@scolar.hu', '+36 1 466 7648'],
            ['Books & Stuff Bt. (Élőhalottak.hu)', 'www.elohalottak.hu', 'info@elohalottak.hu', '+36203346463'],
            ['Olcsókönyvek.hu', 'www.olcskokonyvek.hu', 'info@olcsokonyvek.hu', '+3616142918'],
            ['G-ADAM Könyv- és Lapkiadó Kft.', 'www.sportkonyvek.hu', 'iroda@gadam.hu', '06305663982'],
            ['Kalliopé Kiadó', 'kalliopekiado.hu', 'konyv@kalliopekiado.hu', '06-30/261-2987'],
        ];

        PackagePointPartner::truncate();

        foreach ($partners as $partner) {
            PackagePointPartner::create([
                'name' => $partner[0],
                'link' => $partner[1],
                'email' => $partner[2],
                'phone' => $partner[3],
            ]);
        }

        $shops = [
            ['Álomgyár Könyvesboltok (BUDAPEST / Blaha)', '1072 Budapest, Rákóczi út 42.', 'NYITVATARTÁS\r\n\r\nHétfő 09:00 – 19:00\r\nKedd 09:00 – 19:00\r\nSzerda 09:00 – 19:00\r\nCsütörtök 09:00 – 19:00\r\nPéntek 09:00 – 19:00\r\n ', '+36 1 612 4446', 'blaha@alomgyar.hu'],
            ['Álomgyár Könyvesboltok (DEBRECEN)', '4025 Debrecen, Piac utca 49-51.', 'NYITVATARTÁS\r\n\r\nHétfő 09:00 – 18:00\r\nKedd 09:00 – 18:00\r\nSzerda 09:00 – 18:00\r\nCsütörtök 09:00 – 18:00\r\nPéntek 09:00 – 18:00\r\n \r\n \r\n\r\n', '+36 52 786 876', 'debrecen@alomgyar.hu'],
            ['Álomgyár Könyvesboltok (SZEGED)', '6720 Szeged, Jókai utca 7-9. (nagyáruház passzázs)', 'NYITVATARTÁS\r\n\r\nHétfő 09:00 – 18:00\r\nKedd 09:00 – 18:00\r\nSzerda 09:00 – 18:00\r\nCsütörtök 09:00 – 18:00\r\nPéntek 09:00 – 18:00\r\n ', '+36 62 220 150', 'szeged@alomgyar.hu'],
            ['SPRINTER futárszolgálat', '1097 Budapest, Táblás utca 39.', '', '06 1 881 2615', 'info@sprinter.hu'],
            ['Álomgyár Könyvesboltok (MISKOLC)', '3525 Miskolc, Széchenyi István út 25.', 'NYITVATARTÁS\r\n\r\nHétfő 09:00 – 18:00\r\nKedd 09:00 – 18:00\r\nSzerda 09:00 – 18:00\r\nCsütörtök 09:00 – 18:00\r\nPéntek 09:00 – 18:00\r\n ', '+36 46 541 230', 'miskolc@alomgyar.hu'],
            ['Álomgyár Könyvesboltok (SZEKSZÁRD)', '7100 Szekszárd, Széchenyi utca 26', 'NYITVATARTÁS\r\n\r\nHétfő 09:00 – 18:00\r\nKedd 09:00 – 18:00\r\nSzerda 09:00 – 18:00\r\nCsütörtök 09:00 – 18:00\r\nPéntek 09:00 – 18:00', '+36 74 676 585', 'szekszard@alomgyar.hu'],
            ['Álomgyár Könyvesboltok (ESZTERGOM)', '2500 Esztergom, Széchenyi tér 21.', 'NYITVATARTÁS\r\n\r\nHétfő 09:00 – 18:00\r\nKedd 09:00 – 18:00\r\nSzerda 09:00 – 18:00\r\nCsütörtök 09:00 – 18:00\r\nPéntek 09:00 – 18:00', '+3616143476 ', 'esztergom@alomgyar.hu'],
            ['Álomgyár Könyvesboltok (KAZINCBARCIKA)', '3700 Kazincbarcika, Egressy út 22.', 'NYITVATARTÁS\r\n\r\nHétfő 09:00 – 18:00\r\nKedd 09:00 – 18:00\r\nSzerda 09:00 – 18:00\r\nCsütörtök 09:00 – 18:00\r\nPéntek 09:00 – 18:00', '+36-48-821 375', 'kazincbarcika@alomgyar.hu'],
            ['Álomgyár Könyvesboltok (BÉKÉSCSABA)', '5600 Békéscsaba, Andrássy út 20.', 'NYITVATARTÁS\r\n\r\nHétfő 09:00 – 18:00\r\nKedd 09:00 – 18:00\r\nSzerda 09:00 – 18:00\r\nCsütörtök 09:00 – 18:00\r\nPéntek 09:00 – 18:00', '+36-66-643 428', 'bekescsaba@alomgyar.hu'],
            ['Álomgyár Könyvesboltok (TATA)', '2890 Tata, Ady Endre u. 11.', 'NYITVATARTÁS\r\n\r\nHétfő 09:00 – 18:00\r\nKedd 09:00 – 18:00\r\nSzerda 09:00 – 18:00\r\nCsütörtök 09:00 – 18:00\r\nPéntek 09:00 – 18:00\r\n', '+3616143476 ', 'tata@alomgyar.hu'],
            ['Álomgyár Könyvesboltok (ZALAEGERSZEG)', '8900 Zalaegerszeg, Széchenyi tér 7.', 'NYITVATARTÁS\r\n\r\nHétfő 09:00 – 18:00\r\nKedd 09:00 – 18:00\r\nSzerda 09:00 – 18:00\r\nCsütörtök 09:00 – 18:00\r\nPéntek 09:00 – 18:00\r\n ', '+3616143476 ', 'zalaegerszeg@alomgyar.hu'],
            ['Álomgyár Könyvesboltok (NAGYKANIZSA)', '8800 Nagykanizsa, Csengery út 1-3.', 'NYITVATARTÁS\r\n\r\nHétfő 09:00 – 18:00\r\nKedd 09:00 – 18:00\r\nSzerda 09:00 – 18:00\r\nCsütörtök 09:00 – 18:00\r\nPéntek 09:00 – 18:00\r\n \r\n ', '+36 93 363 735', 'nagykanizsa@alomgyar.hu'],
            ['Álomgyár Könyvesboltok (PAKS)', '7030 Paks, Dózsa György út 21.', 'NYITVATARTÁS\r\n\r\nHétfő 09:00 – 18:00\r\nKedd 09:00 – 18:00\r\nSzerda 09:00 – 18:00\r\nCsütörtök 09:00 – 18:00\r\nPéntek 09:00 – 18:00', '+3675315715', 'paks@alomgyar.hu'],
            ['Álomgyár Könyvesboltok (MOSÓNMAGYARÓVÁR)', '9200 Mosonmagyaróvár, Magyar utca 31.', 'NYITVATARTÁS\r\n\r\nHétfő 09:00 – 18:00\r\nKedd 09:00 – 18:00\r\nSzerda 09:00 – 18:00\r\nCsütörtök 09:00 – 18:00\r\nPéntek 09:00 – 18:00', '+36 96 252 424', 'mosonmagyarovar@alomgyar.hu'],
            ['Álomgyár Könyvesboltok (NYÍREGYHÁZA)', '4400 Nyíregyháza, Széchenyi utca 15.', 'NYITVATARTÁS\r\n\r\nHétfő 09:00 – 18:00\r\nKedd 09:00 – 18:00\r\nSzerda 09:00 – 18:00\r\nCsütörtök 09:00 – 18:00\r\nPéntek 09:00 – 18:00', '+36 42 333 753', 'nyiregyhaza@alomgyar.hu'],
            ['Álomgyár Könyvesboltok (VÁC)', '2600 Vác, Március 15. tér 16.', '', '+36 30 119 2070 ', 'vac@alomgyar.hu'],
        ];

        PackagePointShop::truncate();

        foreach ($shops as $shop) {
            PackagePointShop::create([
                'name' => $shop[0],
                'address' => $shop[1],
                'open' => $shop[2],
                'phone' => $shop[3],
                'email' => $shop[4],
            ]);
        }
    }
}
