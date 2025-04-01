<?php
// Connect to the IMAP server
$hostname = '{imap.skyblue.co.in:993/imap/ssl/novalidate-cert}';  // Adjust for your email server
$username = 'prasanth';           // Your email address
$password = 'Prasanth968@@';            // Your email password or app password
$inbox = imap_open($hostname, $username, $password);

if (!$inbox) {
    echo "Connection failed: " . imap_last_error();
    exit;
}

// Fetch the email structure (example: email number 1)
$email_number = 15;  // Change based on the email you want to fetch
$structure = imap_fetchstructure($inbox, $email_number);

// Initialize variables to hold content
$plainText = '';
$htmlContent = '';
$attachments = [];

// Check if there are parts (multipart message)
if (isset($structure->parts)) {


        echo "<div style='color:blue;'> Structure </div>";
    var_dump($structure);

    // Loop through each part to find plain text, HTML, and inline images
    foreach ($structure->parts as $part_number => $part) {
        // Handle the RELATED part (subtype: "ALTERNATIVE")
        if (isset($part->subtype) && strtolower($part->subtype) == 'alternative') {
            if (isset($part->parts)) {
                // Loop through the nested parts (plain text or HTML)
                foreach ($part->parts as $sub_part_number => $sub_part) {
                    // Extract plain text part
                    if (isset($sub_part->subtype) && strtolower($sub_part->subtype) == 'plain') {
                        $body = imap_fetchbody($inbox, $email_number, $part_number + 1 . '.' . ($sub_part_number + 1));

                        // Decode the body if necessary (Base64 or quoted-printable)
                        if ($sub_part->encoding == 3) {  // Base64 encoding
                            $body = base64_decode($body);
                        } elseif ($sub_part->encoding == 4) {  // Quoted-printable encoding
                            $body = quoted_printable_decode($body);
                        }

                        // Store the plain text content
                        $plainText .= $body;
                    }
                    // Extract HTML part
                    elseif (isset($sub_part->subtype) && strtolower($sub_part->subtype) == 'html') {
                        $body = imap_fetchbody($inbox, $email_number, $part_number + 1 . '.' . ($sub_part_number + 1));

                        // Decode the body if necessary
                        if ($sub_part->encoding == 3) {  // Base64 encoding
                            $body = base64_decode($body);
                        } elseif ($sub_part->encoding == 4) {  // Quoted-printable encoding
                            $body = quoted_printable_decode($body);
                        }

                        // Store the HTML content
                        $htmlContent .= $body;
                    }
                }
            }
        }
        // Handle inline image (MIME type: image/png)
        elseif (isset($part->subtype) && strtolower($part->subtype) == 'png') {
            // Check if the part has a CID (Content-ID)
            if (isset($part->id)) {
                $cid = trim($part->id, '<>');  // Extract the CID (remove the angle brackets)
                $body = imap_fetchbody($inbox, $email_number, $part_number + 1);

                // Decode the image if necessary (Base64 encoding)
                if ($part->encoding == 3) {  // Base64 encoding
                    $body = base64_decode($body);
                } elseif ($part->encoding == 4) {  // Quoted-printable encoding
                    $body = quoted_printable_decode($body);
                }

                // Save the image to a temporary path or database
                $imagePath = '/var/www/skyblue.co.in/mail/data/images/' . uniqid('img_', true) . '.' . get_image_extension($part->subtype);
                file_put_contents($imagePath, $body);

                // Add the CID-image path to the attachments array
                $attachments[$cid] = $imagePath;
            }
        }
    }
}

// Function to get the image extension based on the MIME type
function get_image_extension($mime_type) {
    $ext = '';
    switch (strtolower($mime_type)) {
        case 'jpeg':
        case 'jpg':
            $ext = 'jpg';
            break;
        case 'png':
            $ext = 'png';
            break;
        case 'gif':
            $ext = 'gif';
            break;
        // Add other types as necessary
    }
    return $ext;
}

// Close the IMAP connection
imap_close($inbox);

// Display the plain text content
echo '<h3>Plain Text Content:</h3><pre>' . htmlspecialchars($plainText) . '</pre>';

// Display the HTML content
echo '<h3>HTML Content:</h3>';
echo $htmlContent;  // This will render the HTML as it is

// If there are inline images, replace CID references in HTML content
foreach ($attachments as $cid => $imagePath) {
    // Replace the CID in the HTML content with the image file path

    $fileName = basename($imagePath);
    $file = "https://skyblue.co.in/mail/data/images/".$fileName;
    echo $file;


    $htmlContent = str_replace('cid:' . $cid, $file, $htmlContent);
}

// Display HTML content with embedded images
echo '<h3>HTML Content with Images:</h3>' . $htmlContent;
?>
