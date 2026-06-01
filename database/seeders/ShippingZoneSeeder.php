<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShippingZone;

class ShippingZoneSeeder extends Seeder
{
    public function run(): void
    {
        $zones = [
            [
                'name' => 'Paris & Île-de-France',
                'code' => 'ile_de_france',
                'price' => 10.00,
                'delivery_time' => '1-2 jours',
                'is_active' => true,
            ],
            [
                'name' => 'France (hors Île-de-France)',
                'code' => 'france_other',
                'price' => 0.00, // Gratuit par défaut
                'delivery_time' => '2-3 jours',
                'is_active' => true,
            ],
            [
                'name' => 'Union Européenne',
                'code' => 'eu',
                'price' => 15.00,
                'delivery_time' => '3-5 jours',
                'is_active' => true,
            ],
            [
                'name' => 'Europe (hors Union Européenne)',
                'code' => 'europe_non_eu',
                'price' => 20.00,
                'delivery_time' => '5-7 jours',
                'is_active' => true,
            ],
            [
                'name' => 'Amérique du Nord & Sud',
                'code' => 'americas',
                'price' => 25.00,
                'delivery_time' => '7-10 jours',
                'is_active' => true,
            ],
            [
                'name' => 'Afrique',
                'code' => 'africa',
                'price' => 30.00,
                'delivery_time' => '10-15 jours',
                'is_active' => true,
            ],
            [
                'name' => 'Asie & Océanie',
                'code' => 'asia',
                'price' => 30.00,
                'delivery_time' => '10-15 jours',
                'is_active' => true,
            ],
        ];

        foreach ($zones as $zone) {
            ShippingZone::updateOrCreate(['code' => $zone['code']], $zone);
        }
    }
}
