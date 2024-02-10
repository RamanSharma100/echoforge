<?php

namespace App\Controllers;

use App\Models\User;
use Forge\core\Controllers\Auth;
use Forge\core\Controllers\Controller;

use function Forge\core\dd;

class UserController extends Controller
{

    public function store($request, $response)
    {
        $data = $request->getBody();

        $errors = $request->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if (count($errors) > 0) {
            return $response->render('login', [
                'errors' => $errors,
                'old' => $data
            ]);
        }

        $user = User::where('email', "=", $data['email'])->first();

        if (!$user || !Auth::attempt($data['email'], $data['password'])) {
            return $response->render('login', [
                'errors' => ['Invalid email or password. Please try again.'],
                'old' => $data
            ]);
        }

        return $response->redirect('/')->flash('success', 'You are now logged in.');
    }

    public function register($request, $response)
    {
        return $response->render('register', [
            'errors' => [],
            'old' => []
        ]);
    }

    public function storeRegister($request, $response)
    {
        $data = $request->getBody();

        $errors = $request->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email',
            'age' => 'required|integer',
            'phone' => 'required|string',
            'password' => 'required|string'
        ]);

        if (count($errors) > 0) {
            return $response->render('register', [
                'errors' => $errors,
                'old' => $data
            ]);
        }

        dd($data);
    }
}
