<?php
// كلمة السر للدخول
$password = "gnm123";

if (!isset($_GET['pass']) || $_GET['pass'] !== $password) {
  die("🔐 Unauthorized access.");
}

// قراءة المنتجات
$products = json_decode(file_get_contents("products.json"), true);
$messages = json_decode(file_get_contents("messages.json"), true);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>CyberGNM Admin Panel</title>
  <style>
    body { font-family: Arial; padding: 20px; background: #f4f4f4; }
    h1, h2 { color: #333; }
    .card { background: #fff; padding: 15px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 0 5px #ccc; }
    input, textarea { width: 100%; padding: 10px; margin-bottom: 10px; border-radius: 5px; border: 1px solid #ccc; }
    button { padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 5px; }
  </style>
</head>
<body>

<h1>👮‍♂️ CyberGNM Admin Panel</h1>

<h2>➕ Add New Product</h2>
<form method="POST" action="add_product.php?pass=<?= $password ?>">
  <input name="name" placeholder="Product Name" required>
  <textarea name="description" placeholder="Description" required></textarea>
  <input name="price" placeholder="Price" required>
  <input name="download" placeholder="Download Link" required>
  <button type="submit">Add Product</button>
</form>

<h2>🧾 All Products</h2>
<?php foreach ($products as $p): ?>
  <div class="card">
    <strong><?= htmlspecialchars($p['name']) ?></strong><br>
    <?= htmlspecialchars($p['description']) ?><br>
    💵 <?= htmlspecialchars($p['price']) ?><br>
    🔗 <a href="<?= htmlspecialchars($p['download']) ?>" target="_blank">Download</a>
  </div>
<?php endforeach; ?>

<h2>📨 Support Messages</h2>
<?php foreach ($messages as $m): ?>
  <div class="card">
    <strong>👤 <?= htmlspecialchars($m['name']) ?></strong> |
    📧 <?= htmlspecialchars($m['email']) ?> |
    🌐 <?= htmlspecialchars($m['lang']) ?><br>
    <p>📝 <?= nl2br(htmlspecialchars($m['message'])) ?></p>
  </div>
<?php endforeach; ?>

</body>
</html>
