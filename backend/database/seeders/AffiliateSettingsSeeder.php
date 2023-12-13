<?php

namespace Database\Seeders;

use Alomgyar\Settings\Settings;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class AffiliateSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $affiliateSettings = [
            [
                'key' => 'affiliate_track_period',
                'title' => 'Link nyomonkövetési időszaka (napokban)',
                'primary' => '14',
                'section' => 'affiliate',
            ],
            [
                'key' => 'affiliate_commission_percentage',
                'title' => 'Értékesítési jutalék mértéke (százalékban)',
                'primary' => '2',
                'section' => 'affiliate',
            ],
            [
                'key' => 'minimum_redeem_amount',
                'title' => 'Jóváírás igénylés minimum összege (Ft)',
                'primary' => '20000',
                'section' => 'affiliate',
            ],
            [
                'key' => 'redeems_per_year',
                'title' => 'Jóváírás igénylés gyakorisága/év',
                'primary' => '2',
                'section' => 'affiliate',
            ],
        ];
        foreach ($affiliateSettings as $setting) {
            try {
                Settings::create($setting);
            } catch (QueryException $queryException) {
                Log::error($queryException->getMessage());
            }
        }
    }
}
