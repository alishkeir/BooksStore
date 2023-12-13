<?php

namespace Database\Seeders;

use Alomgyar\Templates\Templates;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // slug subject description section
        $templates = [
            ['reset-password-notification', 'Jelszó Emlékeztető kérelem', "<a href='%URL%'>KATTINTS IDE</a>", 'Rendszer'],
            ['password-reset-successful', 'Sikeres jelszó változtatás!', 'Sikeresen megváltoztattad a jelszavad', 'Rendszer'],
            ['customer-verify', 'Erősítsd meg az email címed!', "<a href='%URL%'>KATTINTS IDE</a>", 'Rendszer'],
            ['customer-verified', 'Megerősített email cím!', 'Megerősített email cím!', 'Rendszer'],
            ['invoice_generation_error', 'Számlagenerálás közben hiba lépett fel', 'Számlagenerálás közben hiba lépett fel', 'Rendszer'],
            ['invoice_generation_fail', 'Számlagenerálás közben végzetes hiba lépett fel', 'Számlagenerálás közben végzetes hiba lépett fel', 'Rendszer'],

            ['contact', '# Új üzenet érkezett a(z) %STORE_NAME% weboldalról', '<b>Tárgy:</b> %SUBJECT% <br><b>Név:</b> %NAME% <br><b>Email:</b> %EMAIL% <br><b>Message:</b> <br>%MESSAGE%', 'Értesítők'],
            ['lost-email', 'Elveszett kosár', '<p><b>Kedves %CUSTOMER_NAME%,</b></p><p><b>Ön által kosárba hagyott termékek:</b></p><p>%PRODUCT_TABLE%</p>', 'Értesítők'],

            ['checkout', 'Sikeres vásárlás - %ORDER_ID%', '<p><b>Kedves %CUSTOMER_NAME%,</b></p><p>Köszönjük, hogy rendelésével megtisztel minket, amit munkatársaink hamarossan feldolgoznak.</p><p>Kérjük bolti megrendeléssel kapcsolatos kérdésével a kiválaszott boltot keresse.</p><p>Vásárlása állapotáról változásáról emailben értesítjük</p><p><br></p>', 'Rendelés állapot változás'],
            ['checkout_ebook', 'checkout_ebook', '<p>only ebook content</p>', 'Email részlet'],
            ['status_processing', 'Rendelés feldolgozás alatt - %ORDER_ID%', 'Rendelés feldolgozás alatt - %ORDER_ID%', 'Rendelés állapot változás'],
            ['status_waiting_for_shipping', 'Rendelés összekészítve - %ORDER_ID%', 'Rendelés összekészítve - %ORDER_ID%', 'Rendelés állapot változás'],
            ['status_shipping', 'Rendelés szálítás alatt - %ORDER_ID%', 'Rendelés szálítás alatt - %ORDER_ID%', 'Rendelés állapot változás'],
            ['status_landed', 'Rendelés átvehető - %ORDER_ID%', 'Rendelés átvehető - %ORDER_ID%', 'Rendelés állapot változás'],
            ['status_completed', 'Rendelés sikeres - %ORDER_ID%', 'Rendelés sikeres - %ORDER_ID%', 'Rendelés állapot változás'],
            ['transfer_info', '', 'Számlaszám, etc, előre utalás', 'Email részlet'],
            ['product-has-normal-status', 'Termék értesítő - %PRODUCT_AUTHORS% %PRODUCT_TITLE%', 'Kedves %CUSTOMER_NAME%, <br /><br />Szeretnénk értesíteni, hogy az általad kívánság listára rakott %PRODUCT_TITLE% (%PRODUCT_AUTHORS%) mostantól elérhető a shoppunkban.<br /><br />További szép napot kívánunk.', 'Értesítők'],
            ['author_new_book', 'Új könyv jelent meg %AUTHOR_NAME%', '<p>Kedves %NAME%</p><p>Korábban vásároltál %AUTHOR_NAME% könyvet, úgy goldoljuk, hogy a következő megjelenés előtt álló könyve is érekes lehet: <a href="%PRODUCT_URL%">%PRODUCT_TITLE%</a></p><p>Amennyiben nem szeretnél értsítést kapni %AUTHOR_NAME% új könyveiről, <a href="%UNSUBESCRIBE_URL%">itt</a> le tudsz iratkozni.</p>', 'Értesítők'],

            ['product-orderable', 'Termék elérhető - %PRODUCT_AUTHORS% %PRODUCT_TITLE%', 'Kedves %CUSTOMER_NAME%, <br /><br />Szeretnénk értesíteni, hogy az általad eőrendelt %PRODUCT_TITLE% (%PRODUCT_AUTHORS%) mostantól elérhető a shoppunkban.<br /><br />További szép napot kívánunk.', 'Értesítők'],

        ];
        $stores = [0, 1, 2];
        foreach ($templates as $slug => $sc) {
            foreach ($stores as $store) {
                DB::table('templates')->insert([
                    'title' => $sc[1],
                    'subject' => $sc[1],
                    'slug' => $sc[0],
                    'description' => $sc[2] ?? $sc[1],
                    'section' => $sc[3],
                    'store' => $store,
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        /**
         * @descroption single templates
         */
        $singleTemplates = [
            ['package_point_mail_shipping', 'Álomgyár Könyvesboltok - %CODE% számú rendelésed szállíás alatt', '<p>Kedves <b>%NAME%</b>,</p><p>a <a href="%PARTNER_LINK%" style="color: #e62934">%PARTNER_NAME%</a> webshopjában megrendelt %CODE% számú csomagod átadtuk a %SHOP_NAME% számára, amennyiben a kiszállítással kapcsolatban kérdése lenne, kérjük velük vegye fel a kapcsolatot:</p><table><tr><td>Boltunk címe:</td><td>%SHOP_NAME% (%SHOP_ADDRESS%)</td></tr><tr><td>Telefonszám:</td><td>%SHOP_PHONE%</td></tr><tr><td>E-mail:</td><td>%SHOP_EMAIL%</td></tr></table><p>Üdvözlettel,<br />Álomgyár Könyvesboltok</p>', 'Értesítők'],
            ['package_point_mail_arrived', 'Álomgyár Könyvesboltok - %CODE% számú rendelésed átvehető', '<p>Kedves <b>%NAME%</b>,</p><p>a <a href="%PARTNER_LINK%" style="color: #e62934">%PARTNER_NAME%</a> webshopjában megrendelt %CODE% számú csomagod átvehető a %SHOP_NAME% könyvesboltban, az alábbi helyen: </p><table><tr><td>Boltunk címe:</td><td>%SHOP_NAME% (%SHOP_ADDRESS%)</td></tr><tr><td>Telefonszám:</td><td>%SHOP_PHONE%</td></tr><tr><td>E-mail:</td><td>%SHOP_EMAIL%</td></tr><tr><td>Nyitvatartás:</td><td>%SHOP_OPEN%</td></tr></table><p>A könyvesboltokban lehetőség van készpénzes és bankártyás fizetésre is.</p><p>Várunk szeretettel,<br />Álomgyár Könyvesboltok</p>', 'Értesítők'],
        ];

        foreach ($singleTemplates as $template) {
            DB::table('templates')->insert([
                'title' => $template[1],
                'subject' => $template[1],
                'slug' => $template[0],
                'description' => $template[2] ?? $template[1],
                'section' => $template[3],
                'store' => 0,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
