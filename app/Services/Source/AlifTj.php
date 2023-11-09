<?php

namespace App\Services\Source;

use App\Enums\Status;
use DOMDocument;
use DOMXPath;

class AlifTj extends Source
{
    const SOURCE_URL = 'https://alif.tj/';

    public function start(mixed $input): array
    {
        $url = 'https://alif.tj/api/rates/history?currency=rub&date=2023-11-05';

        $headers = array(
            'authority: alif.tj',
            'accept: application/json, text/plain, */*',
            'accept-language: ru,en-US;q=0.9,en;q=0.8',
            'cookie: roistat_visit=8408620; roistat_first_visit=8408620; roistat_visit_cookie_expire=1209600; roistat_is_need_listen_requests=0; roistat_is_save_data_in_cookie=1; sbjs_migrations=1418474375998%3D1; sbjs_current_add=fd%3D2023-11-04%2021%3A38%3A37%7C%7C%7Cep%3Dhttps%3A%2F%2Falif.tj%2F%7C%7C%7Crf%3D%28none%29; sbjs_first_add=fd%3D2023-11-04%2021%3A38%3A37%7C%7C%7Cep%3Dhttps%3A%2F%2Falif.tj%2F%7C%7C%7Crf%3D%28none%29; sbjs_current=typ%3Dtypein%7C%7C%7Csrc%3D%28direct%29%7C%7C%7Cmdm%3D%28none%29%7C%7C%7Ccmp%3D%28none%29%7C%7C%7Ccnt%3D%28none%7C%7C%7Ctrm%3D%28none)',
            'if-none-match: "9zlm6wm44dav"',
            'referer: https://alif.tj/',
            'sec-ch-ua: "Chromium";v="118", "Google Chrome";v="118", "Not=A?Brand";v="99"',
            'sec-ch-ua-mobile: ?1',
            'sec-ch-ua-platform: "Android"',
            'sec-fetch-dest: empty',
            'sec-fetch-mode: cors',
            'sec-fetch-site: same-origin',
        );
        foreach ($headers as $header) {
            list($key, $value) = explode(':', $header);
            $this->httpRequest->setHeader($key, trim($value));

        }

        $response = $this->httpRequest->get($url);
        return [];
    }

    private function parse(string $html)
    {

    }


}
