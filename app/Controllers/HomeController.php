<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        echo "<h1>Mini MVC Framework</h1>";
        echo "<p>Router çalışıyor!</p>";
        echo "<ul>";
        echo "<li><a href='/login'>/login</a></li>";
        echo "<li><a href='/register'>/register</a></li>";
        echo "<li><a href='/posts'>/posts</a></li>";
        echo "<li><a href='/posts/123'>/posts/123</a></li>";
        echo "<li><a href='/notfound'>/notfound (404)</a></li>";
        echo "</ul>";
    }
}