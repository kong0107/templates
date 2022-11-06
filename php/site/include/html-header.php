<?php require 'start.php'; ?>
<!DOCTYPE html>
<html lang="<?= SITE_LANG ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITE_NAME ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/kong-util/dist/all.js"></script>
    <script>
        kongUtil.use();
    </script>
</head>
<body>
    <div class="container">
        <header>
            <a href="./"><?= SITE_NAME ?></a>
        </header>
        <nav>
            <menu></menu>
        </nav>
        <main>
