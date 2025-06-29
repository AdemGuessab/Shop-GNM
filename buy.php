<?php
session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "user") {
  header("Location: auth.php?redirect=buy.php?id=" . $_GET['id']);
  exit;
}

$id = $_GET['id'] ?? null;
$products = json_decode(file_get_contents("products.json"), true);
$product = null;

foreach ($products as $p) {
  if ($p['id'] == $id) {
    $product = $p;
    break;
  }
}

if (!$product) die("❌ Product not found.");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $purchases = file_exists("purchases.json") ? json_decode(file_get_contents("purchases.json"), true) : [];
  $username = $_SESSION["user"];
  if (!in_array($id, $purchases[$username] ?? [])) {
    $purchases[$username][] = $id;
    file_put_contents("purchases.json", json_encode($purchases, JSON_PRETTY_PRINT));
  }
  header("Location: my-products.php");
  exit;
}
?>

<h2>🛒 Confirm Purchase: <?= htmlspecialchars($product["name"]) ?></h2>
<p>Price: <?= htmlspecialchars($product["price"]) ?> USD</p>
<form method="POST">
  <button type="submit">✅ Confirm Payment</button>
</form>
