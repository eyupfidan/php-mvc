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
        $validator = new Validator($this->request->all());

        if (!$validator->validate([
            'email' => 'required|email',
            'password' => 'required'
        ])) {
            $this->flash('errors', $validator->errors());
            $this->old($this->request->all());
            $this->redirect('/login');
        }

        $user = User::findBy('email', $this->request->post('email'));

        if (!$user || !$user->verifyPassword($this->request->post('password'))) {
            $this->flash('error', 'Email veya şifre hatalı.');
            $this->old($this->request->all());
            $this->redirect('/login');
        }

        Auth::login($user);
        $this->flash('success', 'Hoş geldiniz, ' . $user->name . '!');
        $this->redirect('/posts');
    }

    public function registerForm(): void
    {
        $this->view('auth/register', ['title' => 'Kayıt Ol']);
    }

    public function register(): void
    {
        $validator = new Validator($this->request->all());

        if (!$validator->validate([
            'name' => 'required|min:2|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ])) {
            $this->flash('errors', $validator->errors());
            $this->old($this->request->all());
            $this->redirect('/register');
        }

        $user = User::create([
            'name' => $this->request->post('name'),
            'email' => $this->request->post('email'),
            'password' => password_hash($this->request->post('password'), PASSWORD_DEFAULT),
            'role' => 'user'
        ]);

        Auth::login($user);
        $this->flash('success', 'Kayıt başarılı! Hoş geldiniz.');
        $this->redirect('/posts');
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect('/login');
    }
}