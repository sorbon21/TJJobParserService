<?php

namespace App\Services\Service;

use App\Services\HttpService;
use App\Services\Source\NbtTj;
use Curl\Curl;

class CurrencyExchangeService extends Service
{

    public function __construct(private NbtTj $nbtTj)
    {
    }


    public function start(array $params = [])
    {
        return $this->nbtTj->start($params);
    }
}
