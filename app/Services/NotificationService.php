<?php

namespace App\Services;

use App\Models\FcmTokens;
use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Http;


class NotificationService
{
    public static function push_notification($data)
    {
        $user = $data['user'];

        // Get the FCM tokens for the user
        $fcms = FcmTokens::where("account_id", $user->id)->where("account_type", get_class($user))->get();
        $data['fcms'] = $fcms;
        $data["trs"] = "Helpers";  // This line is not needed unless you're using it elsewhere

        $title = $data['title'];
        $model_type = $data['model_type'];
        $model_id = $data['model_id'];
        $description = $data['description'];
        $provider_id = $data['provider_id'] ?? null;
        $user_id = $data['user_id'] ?? null;
        $request_id = $data['request_id'] ?? null;

        $results = [];  // To collect responses from each notification

        // Loop through each FCM token and send the notification
        foreach ($fcms as $item) {
            $fcm = $item->token;

            // Retrieve the credentials for Firebase
            // $credentialsFilePath = Http::get(storage_path('app/json/homeandcar-c3577-92c504bf8a39.json')); // in server
            $client = new \Google\Client();
            $client->setAuthConfig(storage_path('app/json/homeandcar-c3577-92c504bf8a39.json'));
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
            $client->refreshTokenWithAssertion();
            $token = $client->getAccessToken();
            $access_token = $token['access_token'];

            // Set the headers for Firebase Cloud Messaging (FCM)
            $headers = [
                "Authorization: Bearer $access_token",
                'Content-Type: application/json'
            ];

            // Prepare the data payload for the notification
            $data = [
                "message" => [
                    "token" => $fcm,
                    "notification" => [
                        "title" => (string) $title,
                        "body" => (string) $description,
                    ],
                    "data" => [
                        "title" => (string) $title,
                        "body" => (string) $description,
                        "model_id" => (string) $model_id,
                        "model_type" => (string) $model_type,
                        "provider_id" => (string) ($provider_id ?? ""),
                        "user_id" => (string) ($user_id ?? ""),
                        "request_id" => (string) ($request_id ?? ""),
                    ],
                ]
            ];

            $payload = json_encode($data);

            // Send the notification via cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/homeandcar-c3577/messages:send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_VERBOSE, true); // Enable verbose output for debugging
            $response = curl_exec($ch);
            $err = curl_error($ch);
            curl_close($ch);

            // Collect the response for each notification
            if ($err) {
                $results[] = ['error' => 'Curl Error: ' . $err];
            } else {
                $results[] = ['response' => json_decode($response, true)];
            }
        }

        // Return a response after sending all notifications
        // return response()->json([
        //     'message' => 'Notifications have been sent',
        //     'results' => $results
        // ], 200);
    }
}
