<?php

declare(strict_types=1);

/**
 * Route Tanımları
 *
 * Format: $router->get('/path', 'Controller@method')->middleware('name');
 * Parametre: /posts/{id}
 * Middleware: auth, guest, csrf, role:admin
 */

// Ana sayfa
$router->get('/', 'HomeController@index');

// Auth (misafir kullanıcılar)
$router->get('/login', 'AuthController@loginForm')->middleware('guest');
$router->post('/login', 'AuthController@login')->middleware(['guest', 'csrf']);
$router->get('/register', 'AuthController@registerForm')->middleware('guest');
$router->post('/register', 'AuthController@register')->middleware(['guest', 'csrf']);
$router->post('/logout', 'AuthController@logout')->middleware(['auth', 'csrf']);

// Posts (giriş yapmış kullanıcılar)
$router->group(['auth'], function($router) {
    $router->get('/posts', 'PostController@index');
    $router->get('/posts/create', 'PostController@create');
    $router->post('/posts', 'PostController@store')->middleware('csrf');
    $router->get('/posts/{id}', 'PostController@show');
    $router->get('/posts/{id}/edit', 'PostController@edit');
    $router->put('/posts/{id}', 'PostController@update')->middleware('csrf');
    $router->delete('/posts/{id}', 'PostController@destroy')->middleware('csrf');
});