<?php
session_start();
ob_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: auth.php");
    exit;
}

$productsFile = __DIR__ . "/products.json";
$uploadDir = __DIR__ . "/uploads/";

if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0775, true);
}

// تحميل المنتجات من الملف JSON
$products = file_exists($productsFile) ? json_decode(file_get_contents($productsFile), true) : [];

if (!is_array($products)) {
    $products = [];
}

// إضافة منتج جديد
if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $newId = count($products) ? max(array_column($products, 'id')) + 1 : 1;
    $imageName = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $safeName = time() . '_' . basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $safeName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            die("خطأ أثناء رفع الصورة.");
        }

        $imageName = $safeName;
    }

    $newProduct = [
        "id" => $newId,
        "name" => $_POST["name"],
        "description" => [
            "en" => $_POST["desc_en"],
            "fr" => $_POST["desc_fr"],
            "ar" => $_POST["desc_ar"]
        ],
        "price" => $_POST["price"],
        "download" => $_POST["download"],
        "image" => $imageName
    ];

    $products[] = $newProduct;

    if (file_put_contents($productsFile, json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) === false) {
        die("فشل في حفظ بيانات المنتجات. تحقق من صلاحيات الملف.");
    }

    header("Location: admin.php");
    exit;
}

// تعديل منتج موجود
if (isset($_POST['action']) && $_POST['action'] === 'edit') {
    $editId = intval($_POST['id']);
    foreach ($products as &$product) {
        if ($product['id'] === $editId) {
            $product['name'] = $_POST["name"];
            $product['description']['en'] = $_POST["desc_en"];
            $product['description']['fr'] = $_POST["desc_fr"];
            $product['description']['ar'] = $_POST["desc_ar"];
            $product['price'] = $_POST["price"];
            $product['download'] = $_POST["download"];

            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $safeName = time() . '_' . basename($_FILES['image']['name']);
                $targetPath = $uploadDir . $safeName;

                if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    die("خطأ أثناء رفع الصورة الجديدة.");
                }

                $product['image'] = $safeName;
            }
            break;
        }
    }
    unset($product); // تحرير المرجع

    if (file_put_contents($productsFile, json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) === false) {
        die("فشل في حفظ بيانات المنتجات بعد التعديل. تحقق من صلاحيات الملف.");
    }

    header("Location: admin.php");
    exit;
}

// لتحرير منتج معين (عرض بياناته في النموذج)
$editProduct = null;
if (isset($_GET['edit'])) {
    $editId = intval($_GET['edit']);
    foreach ($products as $p) {
        if ($p['id'] === $editId) {
            $editProduct = $p;
            break;
        }
    }
}

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8" />
<title>لوحة الإدارة - CyberGNM</title>
<style>
  body { font-family: sans-serif; margin: 40px; direction: rtl; }
  table { border-collapse: collapse; width: 100%; margin-bottom: 30px; }
  th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
  img { max-width: 80px; max-height: 50px; }
  form { background: #f9f9f9; padding: 15px; border-radius: 8px; max-width: 600px; margin: auto; }
  input, textarea { width: 100%; margin-bottom: 10px; padding: 6px; }
  button { padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; }
  a.button-edit { padding: 6px 10px; background: #007bff; color: white; border-radius: 4px; text-decoration: none; }
</style>
</head>
<body>

<h1>لوحة الإدارة - إدارة المنتجات</h1>

<table>
  <thead>
    <tr>
      <th>المعرف</th>
      <th>الصورة</th>
      <th>الاسم</th>
      <th>السعر (USD)</th>
      <th>رابط التنزيل</th>
      <th>تعديل</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($products as $p): ?>
      <tr>
        <td><?= $p['id'] ?></td>
        <td>
          <?php if (!empty($p['image'])): ?>
            <img src="uploads/<?= htmlspecialchars($p['image']) ?>" alt="صورة المنتج" />
          <?php else: ?>
            لا توجد صورة
          <?php endif; ?>
        </td>
        <td><?= htmlspecialchars($p['name']) ?></td>
        <td><?= htmlspecialchars($p['price']) ?></td>
        <td><a href="<?= htmlspecialchars($p['download']) ?>" target="_blank">تحميل</a></td>
        <td><a href="?edit=<?= $p['id'] ?>" class="button-edit">تعديل</a></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<h2><?= $editProduct ? "تعديل المنتج #" . $editProduct['id'] : "إضافة منتج جديد" ?></h2>

<form method="POST" enctype="multipart/form-data">
  <input type="hidden" name="action" value="<?= $editProduct ? 'edit' : 'add' ?>" />
  <?php if ($editProduct): ?>
    <input type="hidden" name="id" value="<?= $editProduct['id'] ?>" />
  <?php endif; ?>

  <label>اسم المنتج:</label>
  <input type="text" name="name" required value="<?= $editProduct ? htmlspecialchars($editProduct['name']) : '' ?>" />

  <label>السعر (USD):</label>
  <input type="text" name="price" required value="<?= $editProduct ? htmlspecialchars($editProduct['price']) : '' ?>" />

  <label>رابط التنزيل:</label>
  <input type="text" name="download" required value="<?= $editProduct ? htmlspecialchars($editProduct['download']) : '' ?>" />

  <label>الوصف (إنجليزي):</label>
  <textarea name="desc_en"><?= $editProduct ? htmlspecialchars($editProduct['description']['en']) : '' ?></textarea>

  <label>الوصف (فرنسي):</label>
  <textarea name="desc_fr"><?= $editProduct ? htmlspecialchars($editProduct['description']['fr']) : '' ?></textarea>

  <label>الوصف (عربي):</label>
  <textarea name="desc_ar"><?= $editProduct ? htmlspecialchars($editProduct['description']['ar']) : '' ?></textarea>

  <label>صورة المنتج (رفع جديد):</label>
  <input type="file" name="image" accept="image/*" />

  <?php if ($editProduct && !empty($editProduct['image'])): ?>
    <p>الصورة الحالية:</p>
    <img src="uploads/<?= htmlspecialchars($editProduct['image']) ?>" alt="الصورة الحالية" style="max-width:150px;" />
  <?php endif; ?>

  <button type="submit"><?= $editProduct ? "حفظ التعديلات" : "إضافة المنتج" ?></button>
</form>

</body>
</html>
