<?php
session_start();
ob_start();

// إظهار الأخطاء فقط إذا كان المستخدم Admin
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: auth.php");
    exit;
}

$productsFile = sys_get_temp_dir() . "/products.json"; // للعمل على Render
$uploadDir = sys_get_temp_dir() . "/uploads/";

if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0775, true);
}

$products = file_exists($productsFile) ? json_decode(file_get_contents($productsFile), true) : [];
if (!is_array($products)) $products = [];

// حذف منتج
if (isset($_GET['delete'])) {
    $deleteId = intval($_GET['delete']);
    foreach ($products as $i => $p) {
        if ($p['id'] === $deleteId) {
            if (!empty($p['image']) && file_exists($uploadDir . $p['image'])) {
                unlink($uploadDir . $p['image']);
            }
            unset($products[$i]);
            break;
        }
    }
    $products = array_values($products);
    file_put_contents($productsFile, json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    header("Location: admin.php");
    exit;
}

// فلترة
$filter = isset($_GET['filter']) ? trim($_GET['filter']) : '';
$filteredProducts = array_filter($products, function($p) use ($filter) {
    return $filter === '' || stripos($p['name'], $filter) !== false;
});

// إضافة منتج جديد
if (isset($_POST['action']) && $_POST['action'] === 'add') {
    foreach ($products as $p) {
        if ($p['name'] === $_POST['name'] || $p['download'] === $_POST['download']) {
            die("اسم المنتج أو رابط التحميل موجود مسبقًا.");
        }
    }

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

    $products[] = [
        "id" => $newId,
        "name" => $_POST["name"],
        "description" => [
            "en" => $_POST["desc_en"],
            "fr" => $_POST["desc_fr"],
            "ar" => $_POST["desc_ar"]
        ],
        "price" => $_POST["price"],
        "download" => $_POST["download"],
        "image" => $imageName,
        "published" => true
    ];

    file_put_contents($productsFile, json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
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
    unset($product);

    file_put_contents($productsFile, json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    header("Location: admin.php");
    exit;
}

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
