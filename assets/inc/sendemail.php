<?php
// ==============================================
// Professional Contact Form Handler (MAQASAAD PORTFOLIO HTML)
// Author: MAQASAAD (for educational / personal use)
// ==============================================

// إعدادات البريد
$to = "maqasaad@hotmail.com"; // 🔹 غيّر هذا إلى بريدك الحقيقي
$subject = "New Contact Form Message";

// التحقق من أن الطلب جاء عبر POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
    exit;
}

// جلب وإزالة المسافات
$name    = trim($_POST["name"] ?? '');
$email   = trim($_POST["email"] ?? '');
$phone   = trim($_POST["phone"] ?? '');
$message = trim($_POST["message"] ?? '');

// التحقق من الحقول الأساسية
if (empty($name) || empty($email) || empty($message)) {
    echo json_encode(["status" => "error", "message" => "Please fill in all required fields."]);
    exit;
}

// التحقق من صحة البريد الإلكتروني
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["status" => "error", "message" => "Invalid email address."]);
    exit;
}

// بناء محتوى الرسالة
$emailBody = "
<html>
<head>
  <title>Contact Form Submission</title>
  <style>
    body { font-family: Arial, sans-serif; color: #333; }
    .content { background: #f9f9f9; padding: 15px; border-radius: 6px; }
  </style>
</head>
<body>
  <h2>New Message from Maru Website</h2>
  <div class='content'>
    <p><strong>Name:</strong> {$name}</p>
    <p><strong>Email:</strong> {$email}</p>
    <p><strong>Phone:</strong> {$phone}</p>
    <p><strong>Message:</strong><br>" . nl2br(htmlspecialchars($message)) . "</p>
  </div>
</body>
</html>
";

// رؤوس البريد
$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=UTF-8\r\n";
$headers .= "From: {$name} <{$email}>\r\n";
$headers .= "Reply-To: {$email}\r\n";

// إرسال البريد
$mailSent = mail($to, $subject, $emailBody, $headers);

// الرد بصيغة JSON للـ Ajax
if ($mailSent) {
    echo json_encode(["status" => "success", "message" => "Your message has been sent successfully."]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to send message. Please try again later."]);
}
?>
