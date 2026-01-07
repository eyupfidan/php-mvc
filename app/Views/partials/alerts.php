<?php
$success = $_SESSION['_flash']['success'] ?? null;
$error = $_SESSION['_flash']['error'] ?? null;
$errors = $_SESSION['_flash']['errors'] ?? null;

unset($_SESSION['_flash']);
?>

<?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?= e($success) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?= e($error) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($errors): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <ul class="mb-0">
            <?php foreach ($errors as $message): ?>
                <li><?= e($message) ?></li>
            <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>