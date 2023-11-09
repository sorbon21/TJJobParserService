<?php

namespace App\Services\Source;

use App\Enums\Status;
use DOMDocument;
use DOMXPath;

class NbtTj extends Source
{
    const SOURCE_URL = 'https://www.nbt.tj';

    public function start(mixed $input): array
    {
        $response = $this->httpRequest->get(self::SOURCE_URL . '/tj/');
        if ($this->httpRequest->getHttpStatusCode() === 200) {
            $parsedData = $this->parse($response);
            return $this->setOutput(Status::SUCCESS, $parsedData);
        } else {
            return $this->setOutput(Status::ERROR);
        }
    }

    private function parse(string $html)
    {
        $dom = new DOMDocument;
        @$dom->loadHTML($html);
        $xpath = new DOMXPath($dom);
        $rows = $xpath->query('//table[@class="kursTable"]//tr');
        $data = [];
        foreach ($rows as $row) {
            $cells = $xpath->query('.//td', $row);
            if ($cells->length === 3) {
                $flagImg = $cells[0]->getElementsByTagName('img')->item(0)->getAttribute('src');
                $currency = $cells[1]->nodeValue;
                $rate = $cells[2]->nodeValue;
                $data['items'][] = [
                    'flagImg' => self::SOURCE_URL . $flagImg,
                    'currency' => $currency,
                    'rate' => $rate,
                ];
            } elseif ($cells->length == 1) {
                $data['date'] = trim(str_replace('дар', '', $cells[0]->nodeValue));
            }
        }
        return $data;
    }
}
