<?php

namespace App\Controllers;

class Controller
{
    /**
     * @param $data
     * @param int $code
     * @param array $headers
     *
     */
    protected function response ($data, int $code = 202, array $headers = [])
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code($code);
        echo $data;
    }
}