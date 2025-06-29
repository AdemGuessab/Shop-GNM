<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = htmlspecialchars($_POST["name"]);
  $email = htmlspecialchars($_POST["email"]);
  $message = htmlspecialchars($_POST["message"]);
  $lang = $_POST["lang"];

  $body = "🆕 رسالة دعم جديدة من CyberGNM:\\n\\n";
  $body .= "👤 الاسم: $name\\n📧 البريد: $email\\n🌐 اللغة: $lang\\n📝 الرسالة:\\n$message";

  $subject = "New Support Message from CyberGNM";
  $to = "your-email@example.com"; // ← غيّره إلى بريدك الحقيقي
  $headers = "From: $email\\r\\nReply-To: $email";

  if (mail($to, $subject, $body, $headers)) {
    echo "<h3>✅ تم الإرسال بنجاح! شكرًا لتواصلك معنا.</h3>";
  } else {
    echo "<h3>❌ فشل في إرسال الرسالة. حاول مجددًا.</h3>";
  }
}
?>
