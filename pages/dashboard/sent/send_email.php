<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $from = $_POST['from_email'];
    $to = $_POST['to_email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Boundary
    $boundary = md5(time());

    // Headers
    $headers = "From: $from\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\r\n";

    // Message Body
    $body = "--{$boundary}\r\n";
    $body .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $body .= "$message\r\n";

    // Attachments
    if (!empty($_FILES['attachments']['name'][0])) {
        foreach ($_FILES['attachments']['tmp_name'] as $key => $tmp_name) {
            $file_name = $_FILES['attachments']['name'][$key];
            $file_size = $_FILES['attachments']['size'][$key];
            $file_type = $_FILES['attachments']['type'][$key];
            $file_tmp = $_FILES['attachments']['tmp_name'][$key];

            if (is_uploaded_file($file_tmp)) {
                $file_content = chunk_split(base64_encode(file_get_contents($file_tmp)));

                $body .= "--{$boundary}\r\n";
                $body .= "Content-Type: {$file_type}; name=\"{$file_name}\"\r\n";
                $body .= "Content-Disposition: attachment; filename=\"{$file_name}\"\r\n";
                $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
                $body .= "$file_content\r\n";
            }
        }
    }

    $body .= "--{$boundary}--";

    // Send Email
    if (mail($to, $subject, $body, $headers)) {
        echo "Email sent successfully.";
    } else {
        echo "Failed to send email.";
    }
}
?>
