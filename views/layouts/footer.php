<?php if (!empty($scripts) && is_array($scripts)): ?>
  <?php foreach ($scripts as $script): ?>
    <script src="<?= e(baseUrl($script)) ?>" defer></script>
  <?php endforeach; ?>
<?php endif; ?>
</body>

</html>
