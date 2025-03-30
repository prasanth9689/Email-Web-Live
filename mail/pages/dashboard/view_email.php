<?php
// Check if message_id is provided
if (!isset($_GET['message_id'])) {
    echo "No message selected.";
    exit;
}

session_start();

$message_id = $_GET['message_id'];

$username = $_SESSION["username"];
$password = $_SESSION["password"];

// Connect to the IMAP server
$mailbox = imap_open("{imap.skyblue.co.in:993/imap/ssl/novalidate-cert}INBOX", $username, $password);

if (!$mailbox) {
    echo "Failed to connect to IMAP server.";
    exit;
}

// Find the email by message_id
$email_number = imap_msgno($mailbox, $message_id);
if (!$email_number) {
    echo "Email not found.";
    exit;
}

// Fetch the full email body
$structure = imap_fetchstructure($mailbox, $email_number);
$body = imap_fetchbody($mailbox, $email_number, 1); // Fetch the first part (body)

// Decode the body if necessary (if encoded in base64 or quoted-printable)
if ($structure->encoding == 3) {
    $body = base64_decode($body);
} elseif ($structure->encoding == 4) {
    $body = quoted_printable_decode($body);
}

// Get sender's info
$header = imap_headerinfo($mailbox, $email_number);
$from = $header->fromaddress;
$subject = $header->subject;
$date = $header->date;

imap_close($mailbox);
?>

<!-- <h1>Email from <?php echo htmlspecialchars($from); ?></h1>
<p><strong>Subject:</strong> <?php echo htmlspecialchars($subject); ?></p>
<p><strong>Date:</strong> <?php echo htmlspecialchars($date); ?></p>

<div>
    <h3>Body:</h3>
    <pre><?php echo nl2br(htmlspecialchars($body)); ?></pre>
</div> -->

<div class="container2" id="backBtn" onclick="showView('home')">
             <div class="image">
                 <img src="https://skyblue.co.in/assets/mail/img/back.png" alt="Sample Image">
             </div>
          
             <div class="text">
                  <div class="dd">Back</div>
             </div>


             <div class="container3">

             </div>
      </div>

                     <div class="container4">
                    
                     <?php 
                     
                      $decoded_subject = imap_utf8($subject);
                     echo htmlspecialchars($decoded_subject);
                     
                     ?>
                     </div>

                     <div class="container5">
                     <div class="circle">

                     <?php
                  
                     echo strtoupper(substr($from, 0, 1));

                     ?>                     
                     </div>


                     <div class="container" style="margin:0px;">
  <div class="row">
    <div class="col">
    <div class="view-from-name">
                     <?php echo $from; ?> 
                    </div>
    </div>
    <div class="col">
    <div class="view-date">
                    <?php 
                    
                    // echo htmlspecialchars($date);
                    $mDate = new DateTime($date);
                    echo $mDate->format('F j, Y');
                    ?>
                    </div>
    </div>
  </div>

</div>

                     <!-- <div class="view-from-name">
                     <?php echo $from; ?> 
                    </div>

                    

                    <div class="view-date">
                    <?php echo htmlspecialchars($date); ?>
                    </div> -->


                     </div>

                     <div class="container6">
                     <!-- <pre><?php echo nl2br(htmlspecialchars($body)); ?></pre> -->

                     <?php

if (strpos($body, '<html') !== false) {
    echo "<strong>Message (HTML):</strong><br>" . $body . "<br><br>";
} else {
    echo "<strong>Message (Plain Text):</strong><br>" . nl2br(htmlspecialchars($body)) . "<br><br>";
}
                     ?>
                     </div>
