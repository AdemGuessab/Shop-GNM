<?php
session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "user") {
  header("Location: login.html");
  exit;
}

$username = $_SESSION["user"];
$purchases = file_exists("purchases.json") ? json_decode(file_get_contents("purchases.json"), true) : [];
$products = file_exists("products.json") ? json_decode(file_get_contents("products.json"), true) : [];

$user_products = $purchases[$username] ?? [];

echo "<h1>📦 My Products</h1>";

foreach ($products as $p) {
  if (in_array($p['id'], $user_products)) {
    echo "<div>";
    echo "<h3>{$p['name']}</h3>";
    echo "<p>{$p['description']}</p>";
    echo "<a href='{$p['download']}' target='_blank'>⬇️ Download</a>";
    echo "</div><hr>";
  }
}
