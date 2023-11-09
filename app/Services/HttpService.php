<?php

namespace App\Services;

use GuzzleHttp\Client;

class HttpService
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getClient(): Client
    {
        return $this->client;
    }


    public static function generateUserAgent(): object
    {
        $operatingSystems = [
            'Windows NT 10.0',
            'Windows NT 6.1',
            'Macintosh',
            'Linux; Android 6.0',
            'iPhone; CPU iPhone OS 14_0',
        ];

        $browsers = [
            'Chrome/118.0.0.0',
            'Firefox/100.0',
            'Safari/537.36',
            'Edge/100.0',
            'Opera/80.0',
        ];

        $platforms = [
            'Win64',
            'Win32',
            'MacIntel',
            'Linux x86_64',
            'iPhone',
        ];

        $os = $operatingSystems[array_rand($operatingSystems)];
        $browser = $browsers[array_rand($browsers)];
        $platform = $platforms[array_rand($platforms)];
        return (object)['agent' => "Mozilla/5.0 ($os) AppleWebKit/537.36 (KHTML, like Gecko) $browser $platform", 'os' => $os, 'browser' => $browser, 'platform' => $platform];
    }

    public static function generateUniqueSecChUa(): string
    {
        $browsers = [
            '"Chromium";v="118", "Google Chrome";v="118", "Not=A?Brand";v="99"',
            '"Firefox";v="100", "Mozilla";v="5.0"',
            '"Edge";v="100", "Spartan";v="5.0"',
            '"Opera";v="70", "Presto";v="2.1"',
            '"Safari";v="14.0", "Macintosh";v="Intel Mac OS X 10_15"',
        ];
        return $browsers[array_rand($browsers)];
    }

    public static function generateRandomString($length): string
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $randomString = '';
        $charactersLength = strlen($characters);
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    public static function generateSimilarString($string): string
    {
        mt_srand();
        $characters = $string;
        $randomString = '';
        $charactersLength = strlen($characters);
        for ($i = 0; $i < $charactersLength; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
