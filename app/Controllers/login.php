<?php

namespace App\Controllers;

class Login
{

    public function store($request, $response)
    {
        $data = $request->getBody();

        $validation = $request->validate($request, [
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        if (count($validation) > 0) {
            return $response->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validation
            ], 422);
        }

        echo "<pre>";
        var_dump($data);
        echo "</pre>";
    }
}
