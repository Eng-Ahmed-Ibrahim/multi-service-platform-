<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;


class LocationService
{
    public function get_location_name($lat, $lng, $lang = 'en')
    {
        $apiKey = env('GOOGLE_MAPS_API_KEY');
        $response = Http::get("https://maps.googleapis.com/maps/api/geocode/json", [
            'latlng' => "$lat,$lng",
            'language' => $lang,
            'key' => $apiKey,
        ]);

        $results = $response['results'];

        if (count($results) > 0) {
            foreach ($results as $result) {
                // تجاهل الـ Plus Codes
                if (!str_contains($result['formatted_address'], '+')) {
                    return $result['formatted_address'];
                }
            }

            // fallback: return first result anyway
            return $results[0]['formatted_address'];
        }

        return null;
    }
}
