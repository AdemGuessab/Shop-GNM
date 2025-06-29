<?php
session_start();

$admin_user = "admin";
$admin_pass = "gnm123";

// تحميل المستخدمين
$users = file_exists("users.json") ? json_decode(file_get_contents("users.json"), true) : [];

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
        header("Location: user_dashboard.php");
        exit;
      }
    }
    echo "❌ Invalid user login.";
  } else {
    echo "❌ Invalid credentials.";
  }
}
?>
