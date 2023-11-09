<?php

namespace App\Services;

use GuzzleHttp\Client;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;

class AddService
{

    public static function create(array $data): string
    {
        $message = "📝 **** {$data['title']} ****\n\n";
        $message .= "💰 **Цена:** {$data['price']}\n\n";
        $message .= "📍 **Местоположение: #{$data['place']}\n\n\n";
        $message .= "📄 **Описание:**\n{$data['description']}\n";
        if (!empty($data['tags'])) {
            $tags = implode(' ', $data['tags']);
            $message .= "\n\n🏷 **Теги:** {$tags}\n\n";
        }
        if (!empty($data['params'])) {
            $message .= "🔍 **Условия:**\n";
            foreach ($data['params'] as $param) {
                foreach ($param as $key => $value) {
                    $message .= "  - $key: #$value\n";
                }
            }
            $message .= "\n";
        }
        $message .= "\n\n";

        if (isset($data['phone']['phone'], $data['phone']['tel']) && is_array($data['phone'])) {
            $message .= "📞 **Телефон:** {$data['phone']['tel']}\n\n";
        }
        return $message;
    }

}
