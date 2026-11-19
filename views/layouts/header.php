<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?= e(baseUrl('assets/css/styles.css')) ?>">
  <title><?= e($title ?? 'Proyecto Tienda') ?></title>
</head>

<body>
<?php if (!empty($flash) && is_array($flash)): ?>
  <div class="mensaje-<?= e($flash['type'] === 'exito' ? 'exito' : 'error') ?>" role="alert">
    <?= e($flash['message'] ?? '') ?>
  </div>
<?php endif; ?>
