<?php
session_start();

// الإداري الثابت
$admin_user = "admin";
$admin_pass = "gnm123";

// تحميل المستخدمين المسجلين
$users = file_exists("users.json") ? json_decode(file_get_contents("users.json"), true) : [];

$redirect = isset($_GET["redirect"]) ? $_GET["redirect"] : "user_dashboard.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $role = $_POST["role"];

  if ($role === "admin" && $username === $admin_user && $password === $admin_pass) {
    $_SESSION["role"] = "admin";
    header("Location: admin.php");
    exit;
  } elseif ($role === "user") {
    foreach ($users as $u) {
      if ($u["username"] === $username && $u["password"] === $password) {
        $_SESSION["role"] = "user";
        $_SESSION["user"] = $username;
        header("Location: " . htmlspecialchars($redirect));
        exit;
      }
    }
    echo "❌ Invalid user login.";
  } else {
    echo "❌ Invalid credentials.";
  }
}
?>
