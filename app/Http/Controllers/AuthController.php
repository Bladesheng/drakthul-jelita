<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function index(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $password = $request->input('password');

        if ($password !== env('ADMIN_PASSWORD')) {
            return redirect('https://www.youtube.com/watch?v=dQw4w9WgXcQ');
        }

        Cookie::queue(
            cookie()->forever(
                'admin_token',
                $password,
                null,
                null,
                true,
                true,
                false,
                'Strict'
            )
        );

        return to_route('screenshots.index');
    }

    public function logout(): RedirectResponse
    {
        Cookie::queue(Cookie::forget('admin_token'));

        return to_route('screenshots.index');
    }
}
