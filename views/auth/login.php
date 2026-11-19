<main class="login">
  <h1>Iniciar Sesión</h1>

  <?php if (!empty($error)): ?>
    <p class="mensaje-error" role="alert"><?= e($error) ?></p>
  <?php endif; ?>

  <form method="post" action="<?= e(baseUrl('login')) ?>" autocomplete="on">
    <input type="hidden" name="csrf_token" value="<?= e(\App\Core\Auth::csrfToken()) ?>">

    <label for="id">ID de empleado</label>
    <input type="number" id="id" name="id" placeholder="Ingresa tu ID" required>

    <label for="contraseña">Contraseña</label>
    <input type="password" id="contraseña" name="contraseña" placeholder="Ingresa tu contraseña" required>

    <button type="submit">Entrar</button>
  </form>
</main>
