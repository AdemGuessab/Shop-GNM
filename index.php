<?php
$products = file_exists("products.json") ? json_decode(file_get_contents("products.json"), true) : [];
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>CyberGNM - Products</title>
</head>
<body>
  <h1>🛍️ CyberGNM Store</h1>
  <p><a href="login.html">🔐 Login</a> | <a href="register.html">🆕 Register</a></p>
  <hr>

  <?php foreach ($products as $p): ?>
    <div>
      <h3><?= htmlspecialchars($p["name"]) ?></h3>
      <p><?= nl2br(htmlspecialchars($p["description"])) ?></p>
      <p>💵 Price: <?= htmlspecialchars($p["price"]) ?></p>
      <a href="buy.php?id=<?= $p["id"] ?>">Buy Now</a>
    </div>
    <hr>
  <?php endforeach; ?>
</body>
</html>
