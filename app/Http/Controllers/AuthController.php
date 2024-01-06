<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostLoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function postLogin(PostLoginRequest $request)
{
    $credentials = $request->only('email_or_phone', 'password');

    $fieldType = filter_var($credentials['email_or_phone'], FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';
    $credentials[$fieldType] = $credentials['email_or_phone'];
    unset($credentials['email_or_phone']);

    if (Auth::attempt($credentials)) {
        return redirect('dashboard')->with('success', 'Welcome ' . auth()->user()->name);
    }

    return redirect('login')->with('failed', 'Incorrect email / password');
}

    public function logout()
    {
        $name = auth()->user()->name;
        Auth::logout();
        return redirect('login')->with('success', 'Logout success, goodbye ' . $name);
    }
}
