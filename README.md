# Mini MVC Framework

PHP 8.2 + MySQL ile geliştirilmiş basit bir MVC framework.

## 🎯 Nedir?

Büyük php frameworklerin yapısını ve Owasp güvenlik anlamak ve uygulamak için oluşturulmuştur.

## ✨ Özellikler

- Router (GET/POST/PUT/DELETE)
- ORM (Laravel benzeri ama daha basit)
- View + Layout sistemi
- Authentication (Login/Register)
- Authorization (Admin/User rolleri)
- Validation
- CSRF, XSS, SQL Injection koruması
- Basit CRUD işlemleri Post (GET POST UPDATE DELETE)

## 🔐 OWASP Güvenlik Kuralları

| # | Tehdit | Durum | Çözüm |
|---|--------|-------|-------|
| 1 | SQL Injection | ✅ | PDO prepared statements, `$fillable` |
| 2 | Broken Authentication | ✅ | `password_hash/verify`, session regeneration |
| 3 | XSS | ✅ | `e()` helper ile output escape |
| 4 | CSRF | ✅ | Token + `hash_equals()` doğrulama |
| 5 | Security Misconfiguration | ✅ | `.env` gizli, debug modu kontrollü |
| 6 | Sensitive Data Exposure | ✅ | Şifreler hash'li, `.htaccess` koruması |
| 7 | Broken Access Control | ✅ | Middleware + `canEdit()` yetki kontrolü |
| 8 | Session Fixation | ✅ | Login'de `Session::regenerate()` |

## 🚀 Kurulum
```bash
# 1. Bağımlılıkları yükle
composer install

# 2. Konfigürasyon
cp .env.example .env
# .env dosyasını düzenle (DB bilgileri)

# 3. Veritabanını oluştur
mysql -u root -p < database/schema.sql

# 4. Çalıştır
php -S localhost:8000 -t public
```

## 🔑 Test Hesapları

| Rol | Email | Şifre |
|-----|-------|-------|
| Admin | admin@test.com | password |
| User | user@test.com | password |

## 📖 Hızlı Kullanım

### Route
```php
// config/routes.php
$router->get('/posts', 'PostController@index');
$router->get('/posts/{id}', 'PostController@show');
$router->post('/posts', 'PostController@store')->middleware(['auth', 'csrf']);
```

### Controller
```php
class PostController extends Controller
{
    public function index(): void
    {
        $posts = Post::all();
        $this->view('posts/index', ['posts' => $posts]);
    }
}
```

### Model
```php
class Post extends ORM
{
    protected static string $table = 'posts';
    protected static array $fillable = ['user_id', 'title', 'body'];
}

// Kullanım
Post::all();
Post::find(1);
Post::create(['title' => '...', 'body' => '...', 'user_id' => 1]);
$post->update(['title' => 'Yeni']);
$post->delete();
```

### Validation
```php
$validator = new Validator($_POST);
$validator->validate([
    'email' => 'required|email|unique:users',
    'password' => 'required|min:6'
]);
```

### Auth
```php
Auth::login($user);
Auth::logout();
Auth::check();
Auth::user();
Auth::isAdmin();
```

### View
```php
<?= e($title) ?>              // XSS koruması
<?= csrf_field() ?>           // CSRF token
<?= base_url('/posts') ?>     // URL oluştur
```

## 📁 Yapı
```
app/
├── Core/           # Framework çekirdeği
├── Controllers/    # Controller'lar
├── Models/         # Model'ler
├── Middleware/     # Middleware'ler
└── Views/          # View dosyaları
config/routes.php   # Route tanımları
public/index.php    # Giriş noktası
```
