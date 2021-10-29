<?php

namespace App\Controllers;

use Rakit\Validation\Validator;

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
        if (is_array($headers) && count($headers))
            foreach ($headers as $header)
                header($header);

        http_response_code($code);
        echo json_encode($data);
        exit();
    }

    protected function validate ($inputs = [], $rules = [], $messages = []) {
        $validator = new Validator();
        $validation = $validator->make($inputs, $rules, $messages);
        $validation->validate();
        if ($validation->fails())
            $this->response(['error' => true, 'type' => 'validation', 'errors' => $validation->errors()->toArray()], 422);
    }

    protected function request ()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (is_null($data)) $this->response([], 400);
        return $data;
    }
}