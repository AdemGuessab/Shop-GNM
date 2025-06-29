<?php
session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "user") {
  header("Location: login.html?redirect=buy.php&id=" . $_GET['id']);
  exit;
}

// تحميل المنتج من id
$products = json_decode(file_get_contents("products.json"), true);
$product = null;
foreach ($products as $p) {
  if ($p['id'] == $_GET['id']) {
    $product = $p;
    break;
  }
}

if (!$product) {
  die("Product not found");
}
?>

<h2>You're about to buy: <?= $product['name'] ?></h2>
<p>Price: <?= $product['price'] ?></p>
<a href="<?= $product['download'] ?>">✅ Confirm & Download</a>
