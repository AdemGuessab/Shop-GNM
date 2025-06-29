<?php
session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
  header("Location: auth.php");
  exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $new = [
    "id" => rand(100, 999),
    "name" => $_POST["name"],
    "description" => [
      "en" => $_POST["desc_en"],
      "fr" => $_POST["desc_fr"],
      "ar" => $_POST["desc_ar"]
    ],
    "price" => $_POST["price"],
    "download" => $_POST["download"],
    "image" => $_POST["image"]
  ];
  $products = file_exists("products.json") ? json_decode(file_get_contents("products.json"), true) : [];
  $products[] = $new;
  file_put_contents("products.json", json_encode($products, JSON_PRETTY_PRINT));
  echo "<p>✅ Product added successfully.</p>";
}
?>

<h2>➕ Add New Product</h2>
<form method="POST">
  <input name="name" placeholder="Product Name" required><br>
  <input name="price" placeholder="Price" required><br>
  <input name="download" placeholder="Download Link" required><br>
  <input name="image" placeholder="Image Filename"><br>
  <textarea name="desc_en" placeholder="Description (EN)"></textarea><br>
  <textarea name="desc_fr" placeholder="Description (FR)"></textarea><br>
  <textarea name="desc_ar" placeholder="Description (AR)"></textarea><br>
  <button type="submit">Add Product</button>
</form>
