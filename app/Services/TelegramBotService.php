<?php

namespace App\Services;

use GuzzleHttp\Client;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\FileUpload\InputFile;

class TelegramBotService
{
    private Api $bot;
    private $chatId;

    /**
     * @throws TelegramSDKException
     */
    public function __construct(string $apiKey, $chatId)
    {
        $this->bot = new Api($apiKey);
        $this->chatId = $chatId;
    }

    public function getBot(): Api
    {
        return $this->bot;
    }

    public function createAdd($message)
    {
        $this->bot->sendMessage(['chat_id' => $this->chatId, 'text' => $message]);
    }

    /**
     * @throws TelegramSDKException
     */
    public function senAlboom($urls)
    {
        $media = [];
        foreach ($urls as $url) {
            sleep(5);
            $media[] = [
                'type' => 'photo',
                'media' => InputFile::create(null, "./" . $this->downloadImage($url)),
            ];
            sleep(2);
        }
        $this->bot->sendMediaGroup(['chat_id' => $this->chatId, 'media' => json_encode($media)]);
    }

    public function sendPhoto($url, $caption)
    {

    }

    public function downloadImage($url)
    {
        $content = file_get_contents($url);
        $fName = md5($content) . '.jpg';
        file_put_contents($fName, $content);
        ImageService::cropImage($fName, 0.3);
        return $fName;
    }


}
