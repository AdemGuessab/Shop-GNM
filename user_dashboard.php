<?php
session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "user") {
  header("Location: login.html");
  exit;
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>User Dashboard</title></head>
<body>
<h1>👤 Welcome, User</h1>
<p><a href='index.php'>Go to Shop</a></p>
<p><a href='logout.php'>Logout</a></p>
</body>
</html>
