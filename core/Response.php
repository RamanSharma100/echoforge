<?php

namespace Forge\core;

use Closure;

class Response
{
    public function setStatusCode(int $code)
    {
        http_response_code($code);
    }

    public function json(array $data, int $code)
    {
        $this->setStatusCode($code);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
