<?php

namespace App\Services\Service;

use App\Enums\Status;
use App\Services\HttpService;
use Curl\Curl;

abstract class Service
{

    abstract function start(array $params = []);

    final protected function setOutput(Status $status, array $data = null): array
    {
        if ($status == Status::ERROR) {
            return ['status' => $status->value, 'errors' => $data];
        } else {
            return ['status' => $status->value, 'data' => $data];
        }
    }

}
