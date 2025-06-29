
<?php
$products = json_decode(file_get_contents("products.json"), true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>CyberGNM Shop</title>
</head>
<body>
  <h1>🛒 CyberGNM Shop</h1>
  <?php foreach ($products as $prod): ?>
    <div style="border:1px solid #ccc; padding:10px; margin:10px;">
      <h3><?= htmlspecialchars($prod['name']) ?></h3>
      <p><?= htmlspecialchars($prod['description']) ?></p>
      <p><strong>💵 <?= $prod['price'] ?></strong></p>
      <a href="<?= $prod['download'] ?>">🔗 Download</a>
    </div>
  <?php endforeach; ?>
  <a href="support.html">📩 Contact Support</a>
</body>
</html>
