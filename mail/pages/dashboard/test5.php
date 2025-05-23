<?php
$to = "prasanth.jhon@yahoo.com";
$subject = "Multipart Email Example";
$from = "prasanth@skyblue.co.in";

// Create a boundary string. It must be unique.
$boundary = md5(uniqid(time()));

// Headers
$headers = "From: $from\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: multipart/alternative; boundary=\"$boundary\"\r\n";

// Plain text version
$plainText = "This is the plain text version of the message.";

// HTML version
$htmlContent = "
<html>
<head>
  <title>HTML Email</title>
</head>
<body>
  <h1>This is the HTML version of the message.</h1>
  <p>Supports <strong>bold</strong>, <em>italic</em>, and other HTML tags.</p>
</body>
</html>
";

// Message body with boundary parts
$body = "--$boundary\r\n";
$body .= "Content-Type: text/plain; charset=UTF-8\r\n";
$body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
$body .= "$plainText\r\n\r\n";

$body .= "--$boundary\r\n";
$body .= "Content-Type: text/html; charset=UTF-8\r\n";
$body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
$body .= "$htmlContent\r\n\r\n";

$body .= "--$boundary--";

// Send email
if (mail($to, $subject, $body, $headers)) {
    echo "Email sent successfully.";
} else {
    echo "Email sending failed.";
}
?>
