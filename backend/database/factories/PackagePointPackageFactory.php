<?php

namespace Database\Factories;

use Alomgyar\PackagePoints\Entity\Enum\Status;
use Alomgyar\PackagePoints\Models\PackagePointPackage;
use Alomgyar\PackagePoints\Models\PackagePointPartner;
use Alomgyar\PackagePoints\Models\PackagePointShop;
use Illuminate\Database\Eloquent\Factories\Factory;

class PackagePointPackageFactory extends Factory
{
    protected $model = PackagePointPackage::class;

    public function definition()
    {
        $statuses = Status::toArray();

        return [
            'code' => $this->faker->randomNumber(6),
            'partner_id' => array_rand(PackagePointPartner::all()->pluck('id')->toArray()),
            'shop_id' => array_rand(PackagePointShop::all()->pluck('id')->toArray()),
            'customer' => $this->faker->name,
            'email' => $this->faker->email,
            'mail_sent_at' => null,
            'collected' => null,
            'status' => array_rand($statuses),
        ];
    }
}
