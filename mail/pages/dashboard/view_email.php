<?php

if (!isset($_GET['message_id'])) {
    echo "No message selected.";
    exit;
}

session_start();

$message_id = $_GET['message_id'];
$username = $_SESSION["username"];
$password = $_SESSION["password"];

// echo "<h1> $message_id </h1>";

$mailbox = imap_open("{imap.skyblue.co.in:993/imap/ssl/novalidate-cert}INBOX", $username, $password);

if (!$mailbox) {
    echo "Failed to connect to IMAP server.";
    exit;
}


// Get sender's info
$header = imap_headerinfo($mailbox, $message_id);
$from = $header->fromaddress;
$subject = $header->subject;
$date = $header->date;

// imap_close($mailbox);
// Previous code. view_email2.php
?>

<!-- container 2 -->
<div class="container2" id="backBtn" onclick="showView('home')">
      <div class="image">
           <img src="https://skyblue.co.in/assets/mail/img/back.png" alt="Sample Image">
      </div>

      <div class="text">
           <div class="dd">Back</div>
      </div> 

      <div class="container3">
      </div>
</div> <!-- container 2 over -->

<div class="container4">
     <?php 
         $decoded_subject = imap_utf8($subject);
         echo htmlspecialchars($decoded_subject);         
     ?>
</div> <!-- container 4 over -->

<div class="container5">
    <div class="circle">
          <?php echo strtoupper(substr($from, 0, 1)); ?>
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
                               <?php $mDate = new DateTime($date); echo $mDate->format('F j, Y'); ?>
                               </div>
                       </div>
              </div>
    </div>
</div> <!-- container 5 over -->

<div class="container6">
       <?php
           $structure = imap_fetchstructure($mailbox, $message_id);

           function getContentType($structure) {
               $primaryTypes = array("TEXT", "MULTIPART", "MESSAGE", "APPLICATION", "AUDIO", "IMAGE", "VIDEO", "OTHER");
               if (isset($primaryTypes[$structure->type])) {
                   return $primaryTypes[$structure->type] . '/' . $structure->subtype;
               }
               return "UNKNOWN";
           }
           
           $contentType = getContentType($structure);
           echo "Content-Type: " . $contentType;
           
           $structure = imap_fetchstructure($mailbox, $message_id);
           $body = imap_fetchbody($mailbox, $message_id, 1);

           switch($contentType){
            case "TEXT/HTML":
            //    echo "<h1> TEXT/HTML </h1> ";
            if ($structure->encoding == 3) {
                $body = base64_decode($body);
            } elseif ($structure->encoding == 4) {
                $body = quoted_printable_decode($body);
            }

            echo "$body";
            break;

            case "MULTIPART/RELATED":
              //  echo "<h1> MULTIPART/RELATED </h1> ";
              if (isset($structure->parts)) {
                      foreach ($structure->parts as $part_number => $part) {
                           // Handle the RELATED part (subtype: "ALTERNATIVE")
                           if (isset($part->subtype) && strtolower($part->subtype) == 'alternative') {
                                  if (isset($part->parts)) { 
                                          foreach ($part->parts as $sub_part_number => $sub_part) { 
                                            if (isset($sub_part->subtype) && strtolower($sub_part->subtype) == 'plain') {
                                                $body = imap_fetchbody($mailbox, $message_id, $part_number + 1 . '.' . ($sub_part_number + 1));

                                                if ($sub_part->encoding == 3) {
                                                    $body = base64_decode($body);
                                                   } elseif ($sub_part->encoding == 4) {
                                                    $body = quoted_printable_decode($body);
                                                   }
                                                   $plainText .= $body;
                                               }
                                               elseif (isset($sub_part->subtype) && strtolower($sub_part->subtype) == 'html') {
                                                     $body = imap_fetchbody($mailbox, $message_id, $part_number + 1 . '.' . ($sub_part_number + 1));

                                                     if ($sub_part->encoding == 3) {
                                                         $body = base64_decode($body);
                                                     } elseif ($sub_part->encoding == 4) {
                                                         $body = quoted_printable_decode($body);
                                                     }
                                                     $htmlContent .= $body;
                                               }
                                          }
                                  }
                           }
                           elseif (isset($part->subtype) && strtolower($part->subtype) == 'png') {
                            if (isset($part->id)) {
                                $cid = trim($part->id, '<>');
                                $body = imap_fetchbody($mailbox, $message_id, $part_number + 1);

                                if ($part->encoding == 3) { 
                                    $body = base64_decode($body);
                                } elseif ($part->encoding == 4) { 
                                    $body = quoted_printable_decode($body);
                                }

                                $imagePath = '/var/www/skyblue.co.in/mail/data/images/' . uniqid('img_', true) . '.' . get_image_extension($part->subtype);
                                file_put_contents($imagePath, $body);
                                $attachments[$cid] = $imagePath;
                              }
                           }
                      }
               }

               foreach ($attachments as $cid => $imagePath) {
                $fileName = basename($imagePath);
                $file = "https://skyblue.co.in/mail/data/images/".$fileName;
            
                $htmlContent = str_replace('cid:' . $cid, $file, $htmlContent);
            }
            echo $htmlContent;
            break;

            case "MULTIPART/ALTERNATIVE":
                echo "<h1> MULTIPART/ALTERNATIVE </h1> ";
            break;

            default:
                echo "<script type='text/javascript'>alert('default');</script>";
            break;
           }

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

           imap_close($mailbox);
       ?>
</div>



