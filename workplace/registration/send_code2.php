<?php
$to = "prasanth.jhon@gmail.com";
$subject = "Email Verification Code";
$verification_code = "123456";  // This would typically be dynamically generated

// HTML content for the email
$message = "
<html>
<head>
    <title>Email Verification</title>
</head>
<body>
    <h2>Welcome to Our Service!</h2>
    <p>Thank you for signing up. Please verify your email address by entering the code below:</p>
    <h3><strong>Your Verification Code:</strong></h3>
    <p style='font-size: 24px; font-weight: bold; color: #4CAF50;'>$verification_code</p>
    <p>If you didn't request this, please ignore this email.</p>
    <p>Thank you!</p>
</body>
</html>
";

// To send HTML mail, the Content-type header must be set
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
$headers .= "From: saradha@skyblue.co.in" . "\r\n";

// Send email
if(mail($to, $subject, $message, $headers)) {
    echo "Verification email sent successfully.";
} else {
    echo "Failed to send verification email.";
}
?>
