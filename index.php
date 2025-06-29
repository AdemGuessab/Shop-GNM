<?php
session_start();

$langs = ['en' => 'English', 'fr' => 'Français', 'ar' => 'العربية'];
$lang = $_GET['lang'] ?? $_SESSION['lang'] ?? 'en';
if (!in_array($lang, array_keys($langs))) $lang = 'en';
$_SESSION['lang'] = $lang;

$products = json_decode(file_get_contents("products.json"), true);

$username = $_SESSION['user'] ?? null;
$role = $_SESSION['role'] ?? null;

function buy_text($lang) {
    return match ($lang) {
        'fr' => "Acheter maintenant",
        'ar' => "اشترِ الآن",
        default => "Buy Now",
    };
}
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>" <?= $lang === 'ar' ? 'dir="rtl"' : '' ?>>
<head>
  <meta charset="UTF-8" />
  <title>CyberGNM Store</title>
  <style>
    body { font-family: sans-serif; margin: 40px; background: #f8f8f8; <?= $lang === 'ar' ? 'text-align: right;' : '' ?> }
    .topnav { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .nav-links a { margin: 0 10px; text-decoration: none; color: #333; font-weight: bold; }
    .product { background: #fff; border: 1px solid #ccc; padding: 20px; margin-bottom: 15px; border-radius: 8px; }
    a.button { background: #28a745; color: white; padding: 8px 12px; text-decoration: none; border-radius: 4px; }
  </style>
</head>
<body>

<div class="topnav">
  <div class="nav-links">
    <a href="my-products.php"><?= $lang === 'ar' ? '📦 مشترياتي' : ($lang === 'fr' ? '📦 Mes Achats' : '📦 My Products') ?></a>
    <?php if ($role === 'admin'): ?>
      <a href="admin.php"><?= $lang === 'ar' ? '⚙️ لوحة الإدارة' : ($lang === 'fr' ? '⚙️ Admin Panel' : '⚙️ Admin Panel') ?></a>
    <?php endif; ?>
    <?php if ($username): ?>
      <a href="auth.php?logout=1"><?= $lang === 'ar' ? 'تسجيل خروج' : ($lang === 'fr' ? 'Déconnexion' : 'Logout') ?></a>
    <?php else: ?>
      <a href="auth.php"><?= $lang === 'ar' ? 'تسجيل الدخول / التسجيل' : ($lang === 'fr' ? 'Connexion / Inscription' : 'Login / Register') ?></a>
    <?php endif; ?>
  </div>
  <div class="lang-switch">
    🌐 
    <?php foreach ($langs as $code => $label): ?>
      <?php if ($code === $lang): ?>
        <strong><?= $label ?></strong>
      <?php else: ?>
        <a href="?lang=<?= $code ?>"><?= $label ?></a>
      <?php endif; ?>
      <?php if (next($langs)) echo " | "; ?>
    <?php endforeach; ?>
  </div>
</div>

<h1>🛍️ CyberGNM Products</h1>

<?php foreach ($products as $product): ?>
  <div class="product">
    <h2><?= htmlspecialchars($product['name']) ?></h2>
    <p><?= htmlspecialchars($product['description'][$lang]) ?></p>
    <p><strong>Price:</strong> <?= htmlspecialchars($product['price']) ?> USD</p>
    <a class="button" href="buy.php?id=<?= htmlspecialchars($product['id']) ?>">
      💳 <?= buy_text($lang) ?>
    </a>
  </div>
<?php endforeach; ?>

</body>
</html>
