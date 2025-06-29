<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  $users = file_exists("users.json") ? json_decode(file_get_contents("users.json"), true) : [];

  foreach ($users as $u) {
    if ($u["username"] === $username) {
      die("❌ Username already exists.");
    }
  }

  $users[] = [
    "username" => $username,
    "password" => $password
  ];
  file_put_contents("users.json", json_encode($users, JSON_PRETTY_PRINT));
  echo "✅ Registered successfully. <a href='login.html'>Login now</a>";
}
?>
