<?php

namespace Database\Seeders;

use Alomgyar\Settings\Settings;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            'altalanos' => [
                'contact_email' => [
                    'title' => 'Kapcsolat címzett admin e-mail',
                    'primary' => 'X webshop@alomgyar.hu',
                ],
            ],
            'alomgyar' => [
                'free_shipping_limit_alomgyar' => [
                    'title' => 'Ingyenes szállítás értékküszöb (Ft)',
                    'primary' => '6000',
                ],
                'free_shipping_banner_show_alomgyar' => [
                    'title' => 'Látszódik-e az ingyenes kiszállítás csík',
                    'primary' => '1',
                    'secondary' => 'checkbox',
                ],
                'default_discount_rate_alomgyar' => [
                    'title' => 'Alapkedvezmény mértéke (%)',
                    'primary' => '15',
                ],
                'new_product_discount_alomgyar' => [
                    'title' => 'Új könyv kedvezmény mértéke (%)',
                    'primary' => '25',
                ],
                'notification_title_alomgyar' => [
                    'title' => 'Extra üzenet CÍME a boltjaink oldalon',
                    'primary' => 'Karácsonyi nyitvatartás',
                ],
                'notification_description_alomgyar' => [
                    'title' => 'Extra üzenet SZÖVEGE a boltjaink oldalon',
                    'primary' => 'Ezüstvasárnap és Aranyvasárnap minden boltunk 11:00 - 22:00 között tart nyitva. Szeretettel várunk mindenkit.',
                ],
                'home_category_id_alomgyar' => [
                    'title' => 'Főoldalon megjelenő kategória termékei',
                    'primary' => 1,
                ],
                'order_mail_bcc' => [
                    'title' => 'Rendelés másolat küldése',
                    'primary' => 'info@alomgyar.hu',
                ],
            ],
            'olcsokonyvek' => [
                'free_shipping_limit_olcsokonyvek' => [
                    'title' => 'Ingyenes szállítás értékküszöb (Ft)',
                    'primary' => '9000',
                ],
                'free_shipping_banner_show_olcsokonyvek' => [
                    'title' => 'Látszódik-e az ingyenes kiszállítás csík',
                    'primary' => '1',
                    'secondary' => 'checkbox',
                ],
                'default_discount_rate_olcsokonyvek' => [
                    'title' => 'Alapkedvezmény mértéke (%)',
                    'primary' => '15',
                ],
                'new_product_discount_olcsokonyvek' => [
                    'title' => 'Új könyv kedvezmény mértéke (%)',
                    'primary' => '25',
                ],
                'notification_title_olcsokonyvek' => [
                    'title' => 'Extra üzenet CÍME a boltjaink oldalon',
                    'primary' => 'Karácsonyi nyitvatartás',
                ],
                'notification_description_olcsokonyvek' => [
                    'title' => 'Extra üzenet SZÖVEGE a boltjaink oldalon',
                    'primary' => 'Ezüstvasárnap és Aranyvasárnap minden boltunk 11:00 - 22:00 között tart nyitva. Szeretettel várunk mindenkit.',
                ],
                'home_category_id_olcsokonyvek' => [
                    'title' => 'Főoldalon megjelenő kategória termékei',
                    'primary' => 1,
                ],
                'common' => [
                    'title' => 'Rendelésről másolat küldése az alábbi email címre',
                    'order_mail_bcc' => 'info@olcsokonyvek.hu',
                ],
            ],
            'nagyker' => [
                'free_shipping_limit_nagyker' => [
                    'title' => 'Ingyenes szállítás értékküszöb (Ft)',
                    'primary' => '99999999',
                ],
                'free_shipping_banner_show_nagyker' => [
                    'title' => 'Látszódik-e az ingyenes kiszállítás csík',
                    'primary' => '0',
                    'secondary' => 'checkbox',
                ],
                'default_discount_rate_nagyker' => [
                    'title' => 'Alapkedvezmény mértéke (%)',
                    'primary' => '15',
                ],
                'shipping_method_home_multiplier_nagyker' => [
                    'title' => 'Minden megkezdett ÖSSZEG után szorzódik a szállítás díj',
                    'primary' => '20000',
                ],
                'new_product_discount_nagyker' => [
                    'title' => 'Új könyv kedvezmény mértéke (%)',
                    'primary' => '25',
                ],
                'notification_title_nagyker' => [
                    'title' => 'Extra üzenet CÍME a boltjaink oldalon',
                    'primary' => 'Karácsonyi nyitvatartás',
                ],
                'notification_description_nagyker' => [
                    'title' => 'Extra üzenet SZÖVEGE a boltjaink oldalon',
                    'primary' => 'Ezüstvasárnap és Aranyvasárnap minden boltunk 11:00 - 22:00 között tart nyitva. Szeretettel várunk mindenkit.',
                ],
                'home_category_id_nagyker' => [
                    'title' => 'Főoldalon megjelenő kategória termékei',
                    'primary' => 1,
                ],
            ],
        ];

        foreach ($settings as $section => $options) {
            foreach ($options as $key => $option) {
                try {
                    Settings::create([
                        'section' => $section,
                        'key' => $key,
                        'title' => $option['title'],
                        'primary' => $option['primary'],
                        'secondary' => $option['secondary'] ?? '',
                        'status' => 1,
                    ]);
                } catch (QueryException $queryException) {
                    continue;
                }
            }
        }
    }
}
