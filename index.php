<?php
session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
  header("Location: login.html");
  exit;
}

$products = json_decode(file_get_contents("products.json"), true);
$messages = json_decode(file_get_contents("messages.json"), true);
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Admin Panel</title></head>
<body>
<h1>CyberGNM Admin Panel</h1>
<p><a href='logout.php'>🔓 Logout</a></p>

<h2>Add Product</h2>
<form method="POST" action="add_product.php">
  <input name="name" placeholder="Product Name"><br>
  <textarea name="description" placeholder="Description"></textarea><br>
  <input name="price" placeholder="Price"><br>
  <input name="download" placeholder="Download Link"><br>
  <button type="submit">Add</button>
</form>

<h2>Products</h2>
<?php foreach ($products as $p): ?>
  <div>
    <strong><?= $p["name"] ?></strong><br>
    <?= $p["description"] ?><br>
    💵 <?= $p["price"] ?><br>
    <a href="<?= $p["download"] ?>">Download</a>
  </div><hr>
<?php endforeach; ?>

<h2>Support Messages</h2>
<?php foreach ($messages as $m): ?>
  <div>
    👤 <?= $m["name"] ?> (<?= $m["email"] ?>) - 🌐 <?= $m["lang"] ?><br>
    📝 <?= nl2br($m["message"]) ?>
  </div><hr>
<?php endforeach; ?>
</body>
</html>
