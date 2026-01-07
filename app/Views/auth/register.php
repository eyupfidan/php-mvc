<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow">
            <div class="card-header">
                <h4 class="mb-0">Kayıt Ol</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= base_url('/register') ?>">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="name" class="form-label">Ad Soyad</label>
                        <input type="text"
                               class="form-control"
                               id="name"
                               name="name"
                               value="<?= e($_SESSION['_old']['name'] ?? '') ?>"
                               required>
                    </div>

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
                        <small class="text-muted">En az 6 karakter</small>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Şifre Tekrar</label>
                        <input type="password"
                               class="form-control"
                               id="password_confirmation"
                               name="password_confirmation"
                               required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Kayıt Ol</button>
                </form>

                <hr>

                <p class="text-center mb-0">
                    Zaten hesabınız var mı?
                    <a href="<?= base_url('/login') ?>">Giriş Yap</a>
                </p>
            </div>
        </div>
    </div>
</div>
<?php unset($_SESSION['_old']); ?>