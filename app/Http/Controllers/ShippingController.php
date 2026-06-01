<?php

namespace App\Http\Controllers;

use App\Models\ShippingZone;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function estimate(Request $request)
    {
        $country = strtoupper(trim($request->query('country', '')));
        $region = trim($request->query('region', ''));

        if (empty($country)) {
            return response()->json([
                'success' => false,
                'message' => 'Pays requis pour le calcul.',
            ], 400);
        }

        $zone = self::getZoneForCountryAndRegion($country, $region);

        if (!$zone) {
            return response()->json([
                'success' => false,
                'available' => false,
                'message' => 'Livraison non disponible pour ce pays ou cette région.',
            ]);
        }

        return response()->json([
            'success' => true,
            'available' => true,
            'price' => (float)$zone->price,
            'delivery_time' => $zone->delivery_time,
            'zone_name' => $zone->name,
            'is_free' => (float)$zone->price == 0,
        ]);
    }

    public static function getZoneForCountryAndRegion(string $country, string $region): ?ShippingZone
    {
        // 1. France & Île-de-France
        if ($country === 'FR') {
            $isIDF = false;
            $cleanRegion = mb_strtolower($region, 'UTF-8');
            
            // Check common variants of IDF
            if (
                str_contains($cleanRegion, 'ile-de-france') || 
                str_contains($cleanRegion, 'ile de france') || 
                str_contains($cleanRegion, 'île-de-france') || 
                str_contains($cleanRegion, 'île de france') || 
                str_contains($cleanRegion, 'paris') || 
                str_contains($cleanRegion, 'idf') ||
                str_contains($cleanRegion, 'val-de-marne') ||
                str_contains($cleanRegion, 'seine-saint-denis') ||
                str_contains($cleanRegion, 'hauts-de-seine') ||
                str_contains($cleanRegion, 'seine-et-marne') ||
                str_contains($cleanRegion, 'yvelines') ||
                str_contains($cleanRegion, 'essonne') ||
                str_contains($cleanRegion, 'val-d\'oise')
            ) {
                $isIDF = true;
            }

            if ($isIDF) {
                $zone = ShippingZone::where('code', 'ile_de_france')->first();
                if ($zone && $zone->is_active) {
                    return $zone;
                }
            } else {
                $zone = ShippingZone::where('code', 'france_other')->first();
                if ($zone && $zone->is_active) {
                    return $zone;
                }
            }
        }

        // 2. Map other countries to their respective zones
        $zoneCode = self::mapCountryToZoneCode($country);
        if ($zoneCode) {
            $zone = ShippingZone::where('code', $zoneCode)->first();
            if ($zone && $zone->is_active) {
                return $zone;
            }
        }

        return null;
    }

    private static function mapCountryToZoneCode(string $country): ?string
    {
        // List of European Union countries
        $euCountries = [
            'BE', 'DE', 'IT', 'ES', 'NL', 'LU', 'PT', 'IE', 'AT', 'FI', 'SE', 'DK', 
            'PL', 'HU', 'CZ', 'SK', 'RO', 'BG', 'HR', 'SI', 'EE', 'LV', 'LT', 'CY', 'MT', 'GR'
        ];

        if (in_array($country, $euCountries)) {
            return 'eu';
        }

        // List of other European countries (non-EU)
        $europeNonEu = [
            'CH', 'NO', 'GB', 'IS', 'LI', 'UA', 'BY', 'MD', 'AL', 'ME', 'RS', 'MK', 'BA', 'AD', 'MC', 'SM', 'VA'
        ];

        if (in_array($country, $europeNonEu)) {
            return 'europe_non_eu';
        }

        // Americas (North & South)
        $americas = [
            'US', 'CA', 'MX', 'BR', 'AR', 'CO', 'CL', 'PE', 'VE', 'EC', 'BO', 'PY', 'UY', 'PA', 'CR', 'JM', 'PR', 'HT', 'DO', 'GT', 'HN', 'SV', 'NI'
        ];

        if (in_array($country, $americas)) {
            return 'americas';
        }

        // Africa
        $africa = [
            'DZ', 'MA', 'TN', 'EG', 'ZA', 'NG', 'KE', 'SN', 'CI', 'CM', 'CD', 'MG', 'GH', 'AO', 'MZ', 'UG', 'SD', 'LY', 'MR', 'ML', 'NE', 'TD', 'BF', 'GN', 'LR', 'SL', 'TG', 'BJ', 'GA', 'CG', 'BI', 'RW', 'UG', 'TZ', 'ZM', 'ZW', 'NA', 'BW', 'SZ', 'LS', 'MW', 'SO', 'ET', 'DJ', 'ER'
        ];

        if (in_array($country, $africa)) {
            return 'africa';
        }

        // Asia & Oceania
        $asiaOceania = [
            'CN', 'JP', 'IN', 'AU', 'NZ', 'SG', 'KR', 'TH', 'VN', 'ID', 'MY', 'PH', 'PK', 'BD', 'LK', 'NP', 'MM', 'KH', 'LA', 'TW', 'HK', 'MO', 'IL', 'SA', 'AE', 'TR', 'IR', 'IQ', 'JO', 'LB', 'SY', 'YE', 'OM', 'QA', 'BH', 'KW', 'KZ', 'UZ', 'TM', 'KG', 'TJ', 'AF', 'FJ', 'PG', 'SB', 'VU'
        ];

        if (in_array($country, $asiaOceania)) {
            return 'asia';
        }

        return null;
    }
}
