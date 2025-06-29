<?php
session_start();

// ملف المستخدمين
$usersFile = "users.json";
$users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: auth.php");
    exit;
}

$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($action === 'register') {
        if (isset($users[$username])) {
            $errors[] = "Username already taken.";
        } elseif (!$username || !$password) {
            $errors[] = "Please enter username and password.";
        } else {
            $users[$username] = ['password' => $password, 'role' => 'user'];
            file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
            $success = "Registration successful. You can now login.";
        }
    } elseif ($action === 'login') {
        if (!isset($users[$username]) || $users[$username]['password'] !== $password) {
            $errors[] = "Invalid username or password.";
        } else {
            $_SESSION['user'] = $username;
            $_SESSION['role'] = $users[$username]['role'];
            $redirect = $_GET['redirect'] ?? 'index.php';
            header("Location: $redirect");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Login / Register - CyberGNM</title>
<style>
    body { font-family: sans-serif; background: #f0f0f0; margin: 40px; }
    form { background: white; padding: 20px; margin-bottom: 20px; border-radius: 8px; max-width: 400px; }
    input { width: 100%; padding: 8px; margin: 6px 0; }
    button { padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 4px; }
    .error { color: red; }
    .success { color: green; }
</style>
</head>
<body>

<?php if (isset($_SESSION['user'])): ?>
    <p>Welcome, <?= htmlspecialchars($_SESSION['user']) ?>! (<a href="?logout=1">Logout</a>)</p>
    <p><a href="index.php">Go to Store</a></p>
<?php else: ?>

<h2>Login</h2>
<?php if ($errors && $_POST['action'] === 'login'): ?>
    <div class="error"><?= implode("<br>", $errors) ?></div>
<?php endif; ?>
<form method="POST">
    <input type="hidden" name="action" value="login" />
    <input type="text" name="username" placeholder="Username" required />
    <input type="password" name="password" placeholder="Password" required />
    <button type="submit">Login</button>
</form>

<h2>Register</h2>
<?php if ($errors && $_POST['action'] === 'register'): ?>
    <div class="error"><?= implode("<br>", $errors) ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="success"><?= $success ?></div>
<?php endif; ?>
<form method="POST">
    <input type="hidden" name="action" value="register" />
    <input type="text" name="username" placeholder="Choose a username" required />
    <input type="password" name="password" placeholder="Choose a password" required />
    <button type="submit">Register</button>
</form>

<?php endif; ?>

</body>
</html>
