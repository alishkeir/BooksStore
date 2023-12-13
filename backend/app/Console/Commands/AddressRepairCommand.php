<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AddressRepairCommand extends Command
{
    protected $mapAddress = [
        1 => 'last_name',
        2 => 'first_name',
        3 => 'business_name', // company
        4 => 'address_phone', // phone
        // type billing
        5 => 'address', // billing-address
        6 => 'city', // billing-city
        7 => 'zip_code', // billing-zip
        //            8 => 'billing-phone',
        // type shipping
        9 => 'address', // delivery-address
        10 => 'city', // delivery-city
        11 => 'zip_code', // delivery-zip
        12 => 'comment', // 'delivery-msg',
        13 => 'delivery-company', // bool
        // entity_type 2
        14 => 'billing-company', // bool
        15 => 'vat_number', // billing-company-number
        18 => 'country_id', // billing-country
        // type shipping
        19 => 'country_id', // delivery-country
    ];

    protected $mapShippingAddress = [
        1 => 'last_name',
        2 => 'first_name',
        4 => 'address_phone', // phone
        9 => 'address', // delivery-address
        10 => 'city', // delivery-city
        11 => 'zip_code', // delivery-zip
        12 => 'comment', // 'delivery-msg',
        19 => 'country_id', // delivery-country
    ];

    protected $mapBillingAddress = [
        1 => 'last_name',
        2 => 'first_name',
        3 => 'business_name', // company
        4 => 'address_phone', // phone
        5 => 'address', // billing-address
        6 => 'city', // billing-city
        7 => 'zip_code', // billing-zip
        //        14 => 'billing-company', // bool
        15 => 'vat_number', // billing-company-number
        18 => 'country_id', // billing-country
    ];

    protected $customersAlom;

    protected $countries;

    protected array $oldShippingAddress = [];

    protected array $oldBillingAddress = [];

    protected int $page = 0;

    protected int $take = 1000;

    protected int $total = 0;

    protected $signature = 'sync:address-repair-alom {page=0}';

    protected $description = 'Check old address fields and update the customers addresses (alom)';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->customersAlom = DB::table('customers')->select('id', 'old_id', 'email')->where('store', 0)->get();
        $this->countries = DB::table('countries')->select('id', 'code')->get();
        $this->page = $this->argument('page') ?? 0;
        $this->repairAlomgyarCustomersAddresses();
        // Legyűjtjük a old_user_field_value táblából a régi user adatokat
        // A customers táblából a régi id és store = 0 segítségével lekérjük az új id-t
        // Az addresses táblában a role_id és role = customer segítségével ellenőrizzük az adatokat és eltérés esetén update-eljük
        // Ha nincs bejegyzése, akkor létrehozzuk
        // Az olcsókönyveknél ugyanez csak a customers táblában store = 1 és a old_olcso_user_field_value tábla
    }

    private function repairAlomgyarCustomersAddresses()
    {
        $olds = DB::select(DB::raw(' SELECT COUNT(*) as count FROM old_user '));
        $this->total = $olds[0]->count / $this->take;

        for ($i = $this->page; $i <= $this->total; $i++) {
            $oldUserIDs = DB::table('old_user_field_value')->select('user_field_value_user_id')->distinct()->skip($this->take * $this->page)->take($this->take)->get();
            foreach ($oldUserIDs as $oldUserID) {
                $oldUserValues = DB::table('old_user_field_value')->where('user_field_value_user_id',
                    $oldUserID->user_field_value_user_id)->get();
                $newCustomer = $this->customersAlom->where('old_id', $oldUserID->user_field_value_user_id)->first();
                if (! $newCustomer) {
                    continue;
                }
                $shippingAddress = [
                    'last_name' => '',
                    'first_name' => '',
                    'city' => '',
                    'zip_code' => '',
                    'address' => '',
                    'address_phone' => '',
                    'address_email' => $newCustomer->email,
                    'comment' => '',
                    'country_id' => $this->countries->where('code', 'HU')->first()->id,
                    'type' => 'shipping',
                    'role' => 'customer',
                    'entity_type' => 1,
                    'role_id' => $newCustomer->id,
                ];

                $billingAddress = [
                    'last_name' => '',
                    'first_name' => '',
                    'business_name' => '',
                    'vat_number' => '',
                    'city' => '',
                    'zip_code' => '',
                    'address' => '',
                    'address_phone' => '',
                    'address_email' => $newCustomer->email,
                    'comment' => '',
                    'country_id' => $this->countries->where('code', 'HU')->first()->id,
                    'type' => 'billing',
                    'role' => 'customer',
                    'entity_type' => 1,
                    'role_id' => $newCustomer->id,
                ];

                foreach ($oldUserValues as $oldUserValue) {
                    if (isset($this->mapShippingAddress[$oldUserValue->user_field_value_field_id])) {
                        $shippingAddress[$this->mapShippingAddress[$oldUserValue->user_field_value_field_id]] = $oldUserValue->user_field_value_value;
                        if (empty($shippingAddress['country_id'])) {
                            $shippingAddress['country_id'] = $this->countries->where('code', 'HU')->first()->id;
                        }
                        $shippingAddress['country_id'] = $this->countries->where('code', $shippingAddress['country_id'])->first()->id ?? $this->countries->where('code',
                            'HU')->first()->id;
                        $shippingAddress['address'] = Str::limit($shippingAddress['address'], 250);
                    }
                    if (isset($this->mapBillingAddress[$oldUserValue->user_field_value_field_id])) {
                        $billingAddress[$this->mapBillingAddress[$oldUserValue->user_field_value_field_id]] = $oldUserValue->user_field_value_value;
                        if (empty($billingAddress['country_id'])) {
                            $billingAddress['country_id'] = $this->countries->where('code', 'HU')->first()->id;
                        }
                        $billingAddress['country_id'] = $this->countries->where('code',
                            $billingAddress['country_id'])->first()->id ?? $this->countries->where('code',
                                'HU')->first()->id;

                        if ($oldUserValue->user_field_value_field_id === 14 && $oldUserValue->user_field_value_value) {
                            $billingAddress['entity_type'] = 2;
                        }
                        $billingAddress['address'] = Str::limit($billingAddress['address'], 250);
                    }
                }
                if (! empty($billingAddress['city']) && ! empty($billingAddress['address'])) {
                    $address = DB::table('addresses')->where('role', 'customer')
                                 ->where('role_id', $newCustomer->id)
                                 ->whereNull('updated_at')
                                 ->where('type', 'billing')
                                 ->where('role_id', '<', 71500)
                                 ->update($billingAddress);
                    $this->info($this->page.' / '.$newCustomer->id.' / '.$address.' updated');
                }
                if (! empty($shippingAddress['city']) && ! empty($shippingAddress['address'])) {
                    $address = DB::table('addresses')->where('role', 'customer')
                                 ->where('role_id', $newCustomer->id)
                                 ->whereNull('updated_at')
                                 ->where('type', 'shipping')
                                 ->where('role_id', '<', 71500)
                                 ->update($shippingAddress);
                    $this->info($this->page.' / '.$newCustomer->id.' / '.$address.' updated');
                }
                $this->info($this->page.' / '.$newCustomer->id);
            }

            $this->page++;
            $this->info($this->page.' / '.$this->total);
        }
    }
}
