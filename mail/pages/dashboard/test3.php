<?php
// Connection details
$hostname = '{imap.skyblue.co.in:993/imap/ssl/novalidate-cert}INBOX'; // Gmail IMAP server, SSL encryption, port 993
$username = 'prasanth';  // Your email address
$password = 'Prasanth';     // Your email password or app-specific password

// Open the IMAP stream
$inbox = imap_open($hostname, $username, $password);

if (!$inbox) {
    echo "Connection failed: " . imap_last_error();
    exit;
}

// Get emails from the inbox
$emails = imap_search($inbox, 'ALL');  // Search for all emails, you can adjust this query

if ($emails) {
    // Sort emails in reverse order (newest first)
    rsort($emails);

    echo "<h2>Email List</h2>";

    // Display the list of emails with links to open them
    foreach ($emails as $email_number) {
        // Fetch email overview (header info like subject, from, date)
        $overview = imap_fetch_overview($inbox, $email_number, 0);
        $subject = htmlspecialchars($overview[0]->subject);
        $from = htmlspecialchars($overview[0]->from);
        $date = htmlspecialchars($overview[0]->date);

        // Generate a link to open the email in a new window
        echo "<p><a href='#' onclick='openEmailWindow($email_number)'>Subject: $subject</a> (From: $from, Date: $date)</p>";
    }
} else {
    echo "No emails found.";
}

// Close the IMAP connection
imap_close($inbox);
?>

<script type="text/javascript">
    function openEmailWindow(emailNumber) {
        // Open a new window to display the selected email
        window.open('view_email1.php?email_id=' + emailNumber, '_blank', 'width=800,height=600');
    }
</script>
