<?php

namespace App\Services\Source;

use App\Enums\Status;
use App\Services\HttpService;
use GuzzleHttp\Client;

abstract class Source
{
    protected Client $httpRequest;


    public function __construct()
    {
        $this->httpRequest = (new HttpService())->getClient();
    }

    final protected function setOutput(Status $status, array $data = null): array
    {
        if ($status == Status::ERROR) {
            return ['status' => $status->value, 'errors' => $data];
        } else {
            return ['status' => $status->value, 'data' => $data];
        }
    }

    abstract public function start(mixed $input): array;
}
