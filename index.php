<?php
session_start();

$productsFile = sys_get_temp_dir() . "/products.json";
$uploadDir = sys_get_temp_dir() . "/uploads/";

$products = file_exists($productsFile) ? json_decode(file_get_contents($productsFile), true) : [];
if (!is_array($products)) $products = [];

$publicProducts = array_filter($products, fn($p) => !empty($p['published']));
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>منتجات CyberGNM</title>
  <style>
    body { font-family: sans-serif; direction: rtl; margin: 20px; }
    .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
    .card { border: 1px solid #ccc; padding: 10px; border-radius: 8px; background: #f9f9f9; }
    img { width: 100%; height: auto; max-height: 160px; object-fit: cover; }
    h3 { margin: 10px 0 5px; }
    .price { color: green; font-weight: bold; }
  </style>
</head>
<body>

<h1>المنتجات المتاحة</h1>

<div class="grid">
  <?php foreach ($publicProducts as $product): ?>
    <div class="card">
      <?php if (!empty($product['image'])): ?>
        <img src="data:image/jpeg;base64,<?= base64_encode(file_get_contents($uploadDir . $product['image'])) ?>" alt="">
      <?php endif; ?>
      <h3><?= htmlspecialchars($product['name']) ?></h3>
      <p class="price">$<?= htmlspecialchars($product['price']) ?></p>
      <a href="<?= htmlspecialchars($product['download']) ?>" target="_blank">تحميل</a>
    </div>
  <?php endforeach; ?>
</div>

</body>
</html>
