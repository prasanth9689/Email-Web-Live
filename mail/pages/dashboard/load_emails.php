<?php


session_start();
$hostname = "{mail.skyblue.co.in:993/imap/ssl/novalidate-cert}INBOX";
$username = $_SESSION["username"];
$password = $_SESSION["password"];

($inbox = imap_open($hostname, $username, $password)) or die("Cannot connect to mailbox: " . imap_last_error());

$numMessages = imap_num_msg($inbox);
$email_ids = imap_search($inbox, "ALL");

if ($email_ids) {
    rsort($email_ids); // Sort email IDs by descending date

    foreach ($email_ids as $email_id) {
        $header = imap_headerinfo($inbox, $email_id);
        $subject = $header->subject;
        $from = $header->fromaddress;
        $date = $header->date;
        $decoded_subject = imap_utf8($subject);
        $main = substr($from, 0, 20);
        $checkboxId = "inboxCheck_" . $email_id;
        ?>
        <a href='?view=INBOX&messageId=<?= $email_id ?>' class='viewEmail' data-id='<?= $email_id ?>' style='background-color: white; text-decoration: none; color: black;'>
            <div class='email__start'>
                <label class="container-mark">
                    <input id="<?= $checkboxId ?>" class="mark-box inboxCheck" type="checkbox" name="delete[]" value="<?= $email_id ?>">
                    <span class="checkmark"></span>
                </label>
            </div>
            <p class='email__name'><b></b> <?= $main ?> <br></p>
            <p class='email__content'><b></b> <?= $decoded_subject ?> <br></p>
            <div class='text-right' style='margin-bottom:1rem;'>
                <?= (new DateTime($date))->format("F j, Y") ?>
            </div>
        </a>
        <?php
    }
} else {
    ?>
    <div class="inbox-empty-container">
        <div class="inbox-empty-content">
            <i class="fa fas fa-envelope fa-fw me-3 inbox-empty-img"></i>
            <p>Emails not found</p>
        </div>
    </div>
    <?php
}
imap_close($inbox);
?>
