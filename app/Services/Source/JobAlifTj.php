<?php

namespace App\Services\Source;

use App\Enums\Status;
use App\Services\HttpService;
use DOMDocument;
use DOMXPath;

class JobAlifTj extends Source
{
    const SOURCE_URL = 'https://job.alif.tj/';

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


    public function getList($page = null)
    {
        $url = self::SOURCE_URL . 'vacancies';
        if (!is_null($page)) {
            $url .= '?page=' . $page;
        }
        $agent = HttpService::generateUserAgent();
        $nonMatch = HttpService::generateSimilarString('1c6c8-uzJENc9VKMeribYHqVHA1gmh4fE');
        $params = [
            'headers' => [
                'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
                'accept-language' => 'ru,en-US;q=0.9,en;q=0.8',
                'cache-control' => 'max-age=0',
                'if-none-match' => '"' . $nonMatch . '"',
                'sec-ch-ua' => HttpService::generateUniqueSecChUa(),
                'sec-ch-ua-mobile' => '?1',
                'sec-ch-ua-platform' => '"' . $agent->platform . '"',
                'sec-fetch-dest' => 'document',
                'sec-fetch-mode' => 'navigate',
                'sec-fetch-site' => 'same-origin',
                'sec-fetch-user' => '?1',
                'upgrade-insecure-requests' => '1',
                'cookie' => 'sbjs_migrations=1418474375998%3D1; sbjs_current_add=fd%3D2023-11-04%2021%3A38%3A37%7C%7C%7Cep%3Dhttps%3A%2F%2Falif.tj%2F%7C%7C%7Crf%3D%28none%29; sbjs_first_add=fd%3D2023-11-04%2021%3A38%3A37%7C%7C%7Cep%3Dhttps%3A%2F%2Falif.tj%2F%7C%7C%7Crf%3D%28none%29; sbjs_current=typ%3Dtypein%7C%7C%7Csrc%3D%28direct%29%7C%7C%7Cmdm%3D%28none%29%7C%7C%7Ccmp%3D%28none%29%7C%7C%7Ccnt%3D%28none%7C%7C%7Ctrm%3D%28none%29; sbjs_first=typ%3Dtypein%7C%7C%7Csrc%3D%28direct%29%7C%7C%7Cmdm%3D%28none%29%7C%7C%7Ccmp%3D%28none%29%7C%7C%7Ccnt%3D%28none%29%7C%7C%7Ctrm%3D%28none%29; _ga=GA1.1.690063182.1699115918; _ym_uid=1699115918762667396; _ym_d=1699115918; _fbp=fb.1.1699115919116.1098521632; _ga_9K0TGLVRQH=GS1.1.1699116456.1.0.1699116462.54.0.0; sbjs_udata=vst%3D1%7C%7C%7Cuip%3D%28none%29%7C%7C%7Cuag%3DMozilla%2F5.0%20%28Linux%3B%20Android%206.0%3B%20Nexus%5%20Build%2FMRA58N%29%20AppleWebKit%2F537.36%20%28KHTML%2C%20like%20Gecko%29%20Chrome%2F118.0.0.0%20Mobile%20Safari%2F537.36; _ga_BLQBGQT4HF=GS1.1.1699115917.1.1.1699117723.22.0.0; _ga_40X8K5YG51=GS1.1.1699115918.1.1.1699117723.22.0.0; auth.strategy=local; _gcl_au=1.1.144180370.1699272151; roistat_visit=119583; roistat_first_visit=119583; roistat_visit_cookie_expire=1209600; roistat_is_need_listen_requests=0; roistat_is_save_data_in_cookie=1; roistat_marker=seo_google_; roistat_marker_old=seo_google_; _ym_isad=2; _ym_visorc=w; ___dc=4c5caf37-b13a-45fa-94ff-3465efe276d0; roistat_call_tracking=1; roistat_emailtracking_email=null; roistat_emailtracking_tracking_email=null; roistat_emailtracking_emails=%5B%5D; roistat_cookies_to_resave=roistat_ab%2Croistat_ab_submit%2Croistat_visit%2Croistat_marker%2Croistat_marker_old%2Croistat_call_tracking%2Croistat_emailtracking_email%2Croistat_emailtracking_tracking_email%2Croistat_emailtracking_emails; auth.redirect=%2Faccount%2Fapplications%2Fnew%3Fvacancy%3Dstazer-v-otdel-po-rabote-s-problemnymi-kreditami-qRv6; _ga_9KQD2E2G27=GS1.1.1699272159.1.1.1699272722.0.0.0',
                'Referer' => 'https://www.google.com/',
                'Referrer-Policy' => 'strict-origin-when-cross-origin',
                'user-agent' => $agent->agent
            ]
        ];

        $response = $this->httpRequest->get($url, $params);
        if ($response->getStatusCode() === 200) {
            $body = $response->getBody()->getContents();
            file_put_contents('advlist.html', $body);
            $data = $this->parse($body);
            var_dump($data);
        }

    }

    private function parse(string $html)
    {
        $parser = \HTMLDomParser\DomFactory::load($html);
        $aList = $parser->find('a');
        $links = [];
        foreach ($aList as $a) {
            try {
                $url = $a->getAttribute('href');
                $url = parse_url($url);
                if (isset($url['path']) && mb_strpos($url['path'], '/vacancies/') !== false) {
                    $links[] = $url['path'];
                }

            } catch (\Exception $exception) {
                continue;
            }

        }
        return $links;
    }


}
