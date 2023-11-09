<?php

namespace App\Services\Source;

use App\Services\HttpService;
use Sunra\PhpSimple\HtmlDomParser;

class SomonTj extends Source
{
    const SOURCE_URL = 'https://m.somon.tj';

    public function start(mixed $input): array
    {
        return [];
    }

    public function getList(string $slug)
    {
        $url = self::SOURCE_URL . "/{$slug}";
        $csrfToken = $this->generateCsrfToken();
        $sessionId = $this->generateSessionId();
        $response = $this->httpRequest->get($url, [
            'headers' => $this->createHeader(null, $csrfToken, $sessionId),
            'decode_content' => 'gzip',
        ]);
        $body = $response->getBody()->getContents();
        return $this->parseAdvList($body);
    }

    public function getAdv(string $slug)
    {
        $data = [];
        sleep(2);
        $url = self::SOURCE_URL . "/adv/{$slug}/";
        $csrfToken = $this->generateCsrfToken();
        $sessionId = $this->generateSessionId();
        $response = $this->httpRequest->get($url, [
            'headers' => $this->createHeader($slug, $csrfToken, $sessionId),
            'decode_content' => 'gzip',
        ]);
        if ($response->getStatusCode() === 200) {
            $responseContent = $response->getBody()->getContents();
            $data = $this->parseAdv($responseContent);
        }
        return $data;
    }

    private function parseAdvList($html)
    {
        $adv = [];
        $parser = \HTMLDomParser\DomFactory::load($html);
        $list = $parser->find('a');
        foreach ($list as $item) {
            if (mb_strpos($item->getAttribute('href'), '/adv/') !== false) {
                $url = parse_url($item->getAttribute('href'));
                $adv[] = str_replace(['/', '\"', 'adv'], '', $url['path']);
            }

        }
        return array_values(array_unique($adv));
    }

    private function parseAdv(string $html)
    {
        $data = [];
        $parser = \HTMLDomParser\DomFactory::load($html);
        $price = $parser->findOne('div.announcement-price');
        $description = $parser->findOne('div.announcement-description');
        $title = $parser->findOne('h1.title-announcement[itemprop="name"]');
        $place = $parser->findOne('a.announcement__location[itemprop="areaServed"]');
        $data = $this->getArr($price, $data, $description, $title, $place);
        $tagList = $parser->find('ul.breadcrumbs li');
        foreach ($tagList as $index => $tag) {
            if ($index > 0 && $tag) {
                $data['tags'][] = "#" . trim(str_replace([' ', ','], '', $tag->text()));
            }
        }
        $paramsList = $parser->find('ul.chars-column li');
        foreach ($paramsList as $param) {
            if ($param) {
                list($key, $value) = explode(':', trim($param->text()));
                $data['params'][] = [trim($key) => trim($value)];
            }
        }

        if (empty($data)) {
            $price = $parser->findOne('div.item__price');
            $description = $parser->findOne('div.item-text[itemprop="description"]');
            $title = $parser->findOne('h1.item__title[itemprop="name"]');
            $place = $parser->findOne('div.city-bar span[itemprop="address"]');
            $data = $this->getArr($price, $data, $description, $title, $place);
            $tagList = $parser->find('div.item__breadcrumbs a');
            foreach ($tagList as $index => $tag) {
                if ($index > 0 && $tag) {
                    $data['tags'][] = "#" . trim(str_replace([' ', ','], '', $tag->text()));
                }
            }
            $keyList = $parser->find('ul.item-table li .t1');
            $valueList = $parser->find('ul.item-table li .t2');
            foreach ($keyList as $index => $key) {
                if ($key) {
                    $data['params'][$index] = [$key->text()];
                }
            }
            foreach ($valueList as $index => $value) {
                if ($value) {
                    if (isset($data['params'][$index][0])) {
                        $data['params'][$index] = [trim($data['params'][$index][0]) => trim($value->text())];
                    }

                }
            }
        }

        return $data;


    }

    public function getPhone(string $slug)
    {
        $url = self::SOURCE_URL . "/phone_check/{$slug}/";
        $csrfToken = $this->generateCsrfToken();
        $sessionId = $this->generateSessionId();
        $response = $this->httpRequest->post($url, [
            'headers' => $this->createHeader($slug, $csrfToken, $sessionId),
            'form_params' => ['csrfmiddlewaretoken' => $csrfToken],
            'decode_content' => 'gzip',
        ]);
        if ($response->getStatusCode() === 200) {
            $responseContent = $response->getBody()->getContents();
            return json_decode($responseContent, true);
        }
        return [];
    }

    private function generateCsrfToken(): string
    {
        $csrfToken = 'bPhOv5O6pX7zPKM0AAFI3aL8J39FLONyye8kTJllF6uzqww9KzoYri0p8Fe5NokS';
        return HttpService::generateSimilarString($csrfToken);
    }

    private function generateSessionId(): string
    {
        $sessionId = '68d5qsi09z5le14f5ye232cwpdb9fore';
        return HttpService::generateSimilarString($sessionId);
    }

    private function createHeader(string $slug = null, $csrfToken, $sessionId): array
    {
        $_gid = "GA1.2.1676888680." . HttpService::generateSimilarString("1699168393");
        $_ym_uid = HttpService::generateSimilarString('1694842930409189661');
        $_ym_d = HttpService::generateSimilarString('1699168393');
        $_fbp = "fb.1." . HttpService::generateSimilarString("1699168396034") . "." . HttpService::generateSimilarString("1820736419");
        $referer = empty($slug) ? self::SOURCE_URL : self::SOURCE_URL . "/adv/{$slug}/";
        return [
            'Accept' => 'application/json, text/javascript, */*; q=0.01',
            'Accept-Language' => 'ru,en-US;q=0.9,en;q=0.8',
            'Connection' => 'keep-alive',
            'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
            'Cookie' => "csrftoken={$csrfToken};version_flavour=mobile; sessionid={$sessionId}; _gid={$_gid}; _ym_uid={$_ym_uid}; _ym_d={$_ym_d}; _fbp={$_fbp}; agree=1; cities=511; city_home=undefined; _ym_isad=2; _ym_visorc=b;",
            'Origin' => self::SOURCE_URL,
            'Referer' => $referer,
            'Sec-Fetch-Dest' => 'empty',
            'Sec-Fetch-Mode' => 'cors',
            'Sec-Fetch-Site' => 'same-origin',
            'User-Agent' => HttpService::generateUserAgent()->agent,
            'X-Requested-With' => 'XMLHttpRequest',
            'sec-ch-ua' => HttpService::generateUniqueSecChUa(),
            'sec-ch-ua-mobile' => '?1',
            'sec-ch-ua-platform' => '"' . HttpService::generateUserAgent()->platform . '"',
        ];
    }

    private function parseListWithImages($html): array
    {
        $data = json_decode($html, true);
        $advertArray = [];
        $dom = \HTMLDomParser\DomFactory::load($data['listing']);
        $advertElements = $dom->find('div.advert');
        foreach ($advertElements as $advertElement) {
            $advertInfo = [];
            $imageElements = $advertElement->find('a[data-background]');
            $images = [];
            foreach ($imageElements as $imageElement) {
                $images[] = $imageElement->getAttribute('data-background');
                $link = $imageElement->getAttribute('href');
                $advertInfo['slug'] = $this->clearAdvLink($link);
            }
            $advertInfo['images'] = $images;
            $priceElement = $advertElement->findOne('.advert__content-price', 0);
            $advertInfo['price'] = trim($priceElement->text());
            $titleElement = $advertElement->findOne('.advert__content-title', 0);
            $advertInfo['title'] = trim($titleElement->text());
            $dateElement = $advertElement->findOne('.advert__content-date', 0);
            $placeElement = $advertElement->findOne('.advert__content-place', 0);
            $advertInfo['date'] = trim($dateElement->text());
            $advertInfo['place'] = trim($placeElement->text());
            $advertArray[] = $advertInfo;
        }
        return $advertArray;
    }


    private function clearAdvLink(string $link): string
    {
        return str_replace(['/', 'adv'], '', $link);
    }

    /**
     * @param \HTMLDomParser\Node|\HTMLDomParser\Contracts\NodeContract|null $price
     * @param array $data
     * @param \HTMLDomParser\Node|\HTMLDomParser\Contracts\NodeContract|null $description
     * @param \HTMLDomParser\Node|\HTMLDomParser\Contracts\NodeContract|null $title
     * @param \HTMLDomParser\Node|\HTMLDomParser\Contracts\NodeContract|null $place
     * @return array
     */
    private function getArr(\HTMLDomParser\Node|\HTMLDomParser\Contracts\NodeContract|null $price, array $data, \HTMLDomParser\Node|\HTMLDomParser\Contracts\NodeContract|null $description, \HTMLDomParser\Node|\HTMLDomParser\Contracts\NodeContract|null $title, \HTMLDomParser\Node|\HTMLDomParser\Contracts\NodeContract|null $place): array
    {
        if ($price) $data['price'] = trim($price->text());
        if ($description) $data['description'] = trim($description->text());
        if ($title) $data['title'] = $title->text();
        if ($place) $data['place'] = $place->text();
        return $data;
    }
}
