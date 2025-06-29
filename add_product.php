<?php
session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
  die("Unauthorized");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $new = [
    "name" => $_POST["name"],
    "description" => $_POST["description"],
    "price" => $_POST["price"],
    "download" => $_POST["download"]
  ];
  $data = json_decode(file_get_contents("products.json"), true);
  $data[] = $new;
  file_put_contents("products.json", json_encode($data, JSON_PRETTY_PRINT));
  header("Location: admin.php");
}
?>
