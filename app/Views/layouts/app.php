<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? env('APP_NAME')) ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { padding-top: 70px; }
        .navbar-brand { font-weight: bold; }
    </style>
</head>
<body>

<?php include __DIR__ . '/../partials/header.php'; ?>

<main class="container py-4">
    <?php include __DIR__ . '/../partials/alerts.php'; ?>

    <?= $content ?>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>