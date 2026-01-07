<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

class AuthController extends Controller
{
    public function loginForm(): void
    {
        echo "<h1>Login</h1><a href='/'>← Ana Sayfa</a>";
    }

    public function login(): void
    {
        echo "Login işlemi";
    }

    public function registerForm(): void
    {
        echo "<h1>Register</h1><a href='/'>← Ana Sayfa</a>";
    }

    public function register(): void
    {
        echo "Register işlemi";
    }

    public function logout(): void
    {
        echo "Logout işlemi";
    }
}