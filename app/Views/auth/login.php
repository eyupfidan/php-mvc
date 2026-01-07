<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow">
            <div class="card-header">
                <h4 class="mb-0">Giriş Yap</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= base_url('/login') ?>">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email"
                               class="form-control"
                               id="email"
                               name="email"
                               value="<?= e($_SESSION['_old']['email'] ?? '') ?>"
                               required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Şifre</label>
                        <input type="password"
                               class="form-control"
                               id="password"
                               name="password"
                               required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Giriş Yap</button>
                </form>

                <hr>

                <p class="text-center mb-0">
                    Hesabınız yok mu?
                    <a href="<?= base_url('/register') ?>">Kayıt Ol</a>
                </p>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <small class="text-muted">
                    <strong>Test Hesapları:</strong><br>
                    Admin: admin@test.com / password<br>
                    User: user@test.com / password
                </small>
            </div>
        </div>
    </div>
</div>
<?php unset($_SESSION['_old']); ?>