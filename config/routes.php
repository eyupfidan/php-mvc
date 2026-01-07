<?php

declare(strict_types=1);



// Ana sayfa
$router->get('/', 'HomeController@index');

// Auth (misafir kullanıcılar)
$router->get('/login', 'AuthController@loginForm')->middleware('guest');
$router->post('/login', 'AuthController@login')->middleware(['guest', 'csrf']);
$router->get('/register', 'AuthController@registerForm')->middleware('guest');
$router->post('/register', 'AuthController@register')->middleware(['guest', 'csrf']);
$router->post('/logout', 'AuthController@logout')->middleware(['auth', 'csrf']);

