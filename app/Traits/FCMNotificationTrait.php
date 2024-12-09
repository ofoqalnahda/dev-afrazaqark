<?php

namespace App\Traits;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

trait FCMNotificationTrait
{
    protected function sendFCMNotification(string $token, string $title, string $body, array $data = null): array
    {
        $messaging = (new Factory)
            ->withServiceAccount(config('firebase.credentials'))
            ->createMessaging();

        $message = CloudMessage::withTarget('token', $token)
            ->withNotification([
                'title' => $title,
                'body' => $body,
            ]);

        if ($data) {
            $message = $message->withData($data);
        }

        try {
            $response = $messaging->send($message);
            return [
                'success' => true,
                'response' => $response,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
