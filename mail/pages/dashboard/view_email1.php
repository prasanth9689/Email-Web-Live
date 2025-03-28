<?php
// Connection details
$hostname = '{imap.skyblue.co.in:993/imap/ssl/novalidate-cert}INBOX'; // Gmail IMAP server, SSL encryption, port 993
$username = 'prasanth';  // Your email address
$password = 'Prasanth';     // Your email password or app-specific password

// Get the email ID passed via the query string
$email_id = isset($_GET['email_id']) ? (int)$_GET['email_id'] : 0;

// Open the IMAP stream
$inbox = imap_open($hostname, $username, $password);

if (!$inbox) {
    echo "Connection failed: " . imap_last_error();
    exit;
}

// Fetch email overview (header info like subject, from, date)
$overview = imap_fetch_overview($inbox, $email_id, 0);
$subject = htmlspecialchars($overview[0]->subject);
$from = htmlspecialchars($overview[0]->from);
$date = htmlspecialchars($overview[0]->date);

// Fetch the email structure to know the parts (text or HTML)
$structure = imap_fetchstructure($inbox, $email_id);

// Initialize a variable to hold the message body
$message = '';

// Check if the email is multipart
if (isset($structure->parts) && count($structure->parts)) {
    // Loop through all parts of the email
    foreach ($structure->parts as $part_number => $part) {
        $part_type = $part->subtype;
        $encoding = $part->encoding;

        // Fetch the part body
        $part_body = imap_fetchbody($inbox, $email_id, $part_number + 1); // Parts are indexed starting from 1

        // Decode the body based on encoding
        if ($encoding == 3) { // base64
            $part_body = base64_decode($part_body);
        } elseif ($encoding == 4) { // quoted-printable
            $part_body = quoted_printable_decode($part_body);
        }

        // Append the body based on content type
        if ($part_type == 'PLAIN') {
            $message .= $part_body;  // Plain text part
        } elseif ($part_type == 'HTML') {
            $message .= $part_body;  // HTML part
        }
    }
} else {
    // Fetch the single part of the email (non-multipart)
    $message = imap_fetchbody($inbox, $email_id, 1);
    if ($structure->encoding == 3) { // base64
        $message = base64_decode($message);
    } elseif ($structure->encoding == 4) { // quoted-printable
        $message = quoted_printable_decode($message);
    }
}

// Display email content
echo "<h2>Subject: $subject</h2>";
echo "<p><strong>From:</strong> $from</p>";
echo "<p><strong>Date:</strong> $date</p>";

if (strpos($message, '<html') !== false) {
    echo "<strong>Message (HTML):</strong><br>" . $message . "<br><br>";
} else {
    echo "<strong>Message (Plain Text):</strong><br>" . nl2br(htmlspecialchars($message)) . "<br><br>";
}

// Close the IMAP connection
imap_close($inbox);
?>
