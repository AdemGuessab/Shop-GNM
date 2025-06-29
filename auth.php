<?php
session_start();

// حساب افتراضي
$users = [
    'admin' => 'admin123',
    'user' => 'user123'
];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (isset($users[$username]) && $users[$username] === $password) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $username === 'admin' ? 'admin' : 'user';
        header("Location: index.php"); // توجيه تلقائي
        exit;
    } else {
        $error = "بيانات الدخول غير صحيحة.";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>تسجيل الدخول</title>
  <style>
    body { font-family: sans-serif; text-align: center; margin-top: 80px; }
    form { max-width: 400px; margin: auto; background: #f4f4f4; padding: 20px; border-radius: 10px; }
    input { padding: 10px; margin-bottom: 15px; width: 100%; }
    button { padding: 10px 25px; background: #007bff; color: white; border: none; border-radius: 5px; }
  </style>
</head>
<body>

<h2>تسجيل الدخول</h2>

<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

<form method="POST">
  <input type="text" name="username" placeholder="اسم المستخدم" required>
  <input type="password" name="password" placeholder="كلمة المرور" required>
  <button type="submit">دخول</button>
</form>

</body>
</html>
