
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $messages = json_decode(file_get_contents("messages.json"), true);
  $messages[] = [
    "name" => $_POST["name"],
    "email" => $_POST["email"],
    "lang" => $_POST["lang"],
    "message" => $_POST["message"]
  ];
  file_put_contents("messages.json", json_encode($messages, JSON_PRETTY_PRINT));
  echo "<h3>✅ Your message has been sent!</h3>";
}
?>
