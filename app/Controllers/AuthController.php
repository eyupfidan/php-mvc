<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function loginForm(): void
    {
        $this->view('auth/login', ['title' => 'Giriş Yap']);
    }

    public function login(): void
    {
        $validator = new Validator($_POST);

        if (!$validator->validate([
            'email' => 'required|email',
            'password' => 'required'
        ])) {
            $_SESSION['_flash']['errors'] = $validator->errors();
            $_SESSION['_old'] = $_POST;
            $this->redirect('/login');
        }

        $user = User::findBy('email', $_POST['email']);

        if (!$user || !$user->verifyPassword($_POST['password'])) {
            $_SESSION['_flash']['error'] = 'Email veya şifre hatalı.';
            $_SESSION['_old'] = $_POST;
            $this->redirect('/login');
        }

        Auth::login($user);
        $_SESSION['_flash']['success'] = 'Hoş geldiniz, ' . $user->name . '!';
        $this->redirect('/posts');
    }

    public function registerForm(): void
    {
        $this->view('auth/register', ['title' => 'Kayıt Ol']);
    }

    public function register(): void
    {
        $validator = new Validator($_POST);

        if (!$validator->validate([
            'name' => 'required|min:2|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ])) {
            $_SESSION['_flash']['errors'] = $validator->errors();
            $_SESSION['_old'] = $_POST;
            $this->redirect('/register');
        }

        $user = User::create([
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'role' => 'user'
        ]);

        Auth::login($user);
        $_SESSION['_flash']['success'] = 'Kayıt başarılı! Hoş geldiniz.';
        $this->redirect('/posts');
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect('/login');
    }
}