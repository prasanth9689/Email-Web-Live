<?php

if (!isset($_GET['message_id'])) {
    echo "No message selected.";
    exit;
}

session_start();

$message_id = $_GET['message_id'];
$username = $_SESSION["username"];
$password = $_SESSION["password"];

echo "<h1> $message_id </h1>";

$mailbox = imap_open("{imap.skyblue.co.in:993/imap/ssl/novalidate-cert}INBOX", $username, $password);

if (!$mailbox) {
    echo "Failed to connect to IMAP server.";
    exit;
}

// $email_number = imap_msgno($mailbox, $message_id);

// var_dump($email_number);
// if (!$email_number) {
//     echo "Email not found.";
//     exit;
// }

$structure = imap_fetchstructure($mailbox, $message_id);
$body = imap_fetchbody($mailbox, $message_id, 1);

if ($structure->encoding == 3) {
    $body = base64_decode($body);
} elseif ($structure->encoding == 4) {
    $body = quoted_printable_decode($body);
}


if ($structure->type == 0) {
    // 0: TEXT (plain text or html)
    echo "0: TEXT (plain text or html)\n";
} elseif ($structure->type == 1) {
    // 1: MULTIPART (message with multiple parts, like a combination of text and attachments)
    echo "1: MULTIPART (message with multiple parts, like a combination of text and attachments)\n";
    
    if (isset($structure->parts)) {
        echo "<h1> Debug </h1>";

        $data1 = $structure->parts;
        /*

         $data1

         Array ( [0] => stdClass Object ( [type] => 1 [encoding] => 0 [ifsubtype] => 1 [subtype] => ALTERNATIVE [ifdescription] => 0 
         [ifid] => 0 [ifdisposition] => 0 [ifdparameters] => 0 [ifparameters] => 1 [parameters] => Array ( [0] => stdClass Object
          ( [attribute] => boundary [value] => 000000000000f6a078063196423b ) ) [parts] => Array ( [0] => stdClass Object ( [type] =>
            0 [encoding] => 0 [ifsubtype] => 1 [subtype] => PLAIN [ifdescription] => 0 [ifid] => 0 [lines] => 1 [bytes] => 29
             [ifdisposition] => 0 [ifdparameters] => 0 [ifparameters] => 1 [parameters] => Array ( [0] => stdClass Object
              ( [attribute] => charset [value] => UTF-8 ) ) ) [1] => stdClass Object ( [type] => 0 [encoding] => 0 [ifsubtype] => 
               1 [subtype] => HTML [ifdescription] => 0 [ifid] => 0 [lines] => 1 [bytes] => 105 [ifdisposition] => 0 [ifdparameters] =>
                0 [ifparameters] => 1 [parameters] => Array ( [0] => stdClass Object ( [attribute] => charset [value] => UTF-8 ) ) ) )             ) [1] => stdClass Object ( [type] => 5 [encoding] => 3 [ifsubtype] => 1 [subtype] => PNG [ifdescription] => 0 [ifid]
           => 1 [id] => [bytes] => 52198 [ifdisposition] => 1 [disposition] => inline [ifdparameters] => 1 [dparameters] => 
            Array ( [0] => stdClass Object ( [attribute] => filename [value] => wp_logo.png ) ) [ifparameters] => 1 [parameters] => 
            Array ( [0] => stdClass Object ( [attribute] => name [value] => wp_logo.png ) ) ) )

        */
        print_r($data1);

  //      $body = '';
        $attachments = [];

        foreach ($structure->parts as $part_number => $part) {
            echo "<h1> Debug 2</h1>";

             // Handle plain text or HTML content
             if ($part->type == 0 && $part->subtype == 'PLAIN') {
                echo "<h1> Debug 3</h1>";
             } elseif ($part->type == 0 && $part->subtype == 'HTML'){
                echo "<h1> Debug 4</h1>";
             }
             echo $part->disposition;

             /*
               Output
               inline
             */

                // Handle inline images (attachments with Content-ID)
                if ($part->disposition == 'inline' && isset($part->parameters)) {
                    echo "<h1> Debug 5</h1>";


                    // wrk
                    $body = imap_fetchbody($mailbox, $message_id, $part_number );

               var_dump($body);

                // Decode the body if necessary
                $body = quoted_printable_decode($body);

                $htmlContent .= $body;

                    echo "<h1> Debug 5.1 \n $htmlContent</h1>";
                    
                   
                    //
               

                    foreach ($part->parameters as $param) {
                        echo  "<h1> Debug 6</h1>";
                        var_dump($param);

                        /*
                        var_dump($param);
                        
                        object(stdClass)[12]
                        public 'attribute' => string 'name' (length=4)
                        public 'value' => string 'wp_logo.png' (length=11)

  */


                        if ($param->attribute == 'name') { // content-id
                            echo  "<h1> Debug 7</h1>";

                            $filename = $param->value;
                            echo  "<h1> File name : $filename</h1>";

                            /*
                             $filename

                             wp_logo.png
                            */

                            $image_data = imap_fetchbody($mailbox, $message_id, $part_number + 1);
                            
                        //    echo  "<p> Image  data : $image_data</p>";

                            /*
                            Image data : iVBORw0KGgoAAAANSUhEUgAAAgAAAAIACAYAAAD0eNT6AAAgAElEQVR4XuydBZgUxxKA6wTu0CBJ 
                            cJfg7u4n2GGHuwQITh5O0ODBHQ73u8Pd3SG4O0FCcNfb173C7e7t7siO9OzUvC8vge2prv5bqrqn u9oL8NEOgeKr4k
                            BC74zg450SoqKSgZfXj0T5nwAM5B/jfycBL4hP/h2X/F088nfk3/S/jf/ggwSQ ABIQQuA9SUz+MZB/vN4Z/9sAb8m/
                            n5O/e0r+7j/y3+Qf8m8veALfoh7C66hbcCT0g5BMMK16BLzU yxpzdkig6sbEYPiUGwyGnKRTZQCDV2aSLh3pcOmJwU
                            +C1JAAEkACTBMwGIiD4HUHvAx3iJ43yT+3 yJ8vgZffedhU7QXTuutMOXQA1Kpwaui/fsgFPl45iZHPQdSgBj8n+Xcyt
                            VTCfJEAEkAC8hIwPCbj 3SWSx0Uy3l2EKK+L8PXredgZ+krefFG6IwLoACjVLoJXJQfwLUpm9kXIbL4oybYQmdH/oFT2mA8S
                             QAJIgEkCBsMrMhaeIJ8XjpN/joHXtyOwJZR+XsBHZgLoAMgJODiSLt2HkEZdnWRThjTyWHJmh7KR ABJAAponYDB8IWPlPj
                             JurierBOthc527mi8TowVAB0DSijF4Q+CaouAdVcNo9L3I8j4+SAAJIAEk IJ6AwUA+F3itNzoEW2ofI/sJDOKF4ZvWBNABkK
                             I9VFmbBry/NibeaiPSUHNLIRJlIAEkgASQgB0B g+E8gPdS+Oa9DLaH3Ec+7hFAB0AsP9Nu/VDyeiPimZYmxh9ZimWJ7y
                             EBJIAEhBAwkFHXCw6Q/18K 3n7heLpACLzotGi0hHCrtyo2vPOtAhDVlDS8GmS27y/kdUyLBJAAEkACEhMwGD6SzwLr
                             IAoWQ8Jv OyA89LPEOXisOHQA+FRtUGRJspmvCUkaimfx+QDDNEgACSABFQgYYxDAKvA2LIZN9Q6roIGmskQH wFV1BUXkJ
                             57lQLLnpCb5t7emahaVRQJIAAnoloAhihR9LVmpHQ5b6v6tWwwcBUcHwBGgoHCyzO/d m3xjqoANBwkgASSABDRMwGDYReZvo2

                             etc........... */

                             if ($part->encoding == 3) {
                                $image_data = base64_decode($image_data);
                                echo  "<h1> Debug 8</h1>";
                            } elseif ($part->encoding == 4) {
                                $image_data = quoted_printable_decode($image_data);
                                echo  "<h1> Debug 9</h1>";
                            }

                             // Save the image to a temporary directory
                             $image_path = '/var/www/skyblue.co.in/mail/data/images/' . uniqid('img_', true) . '.' . get_image_extension($part->subtype);
                             file_put_contents($image_path, $image_data);
                             $attachments[] = [
                                 'cid' => $filename,
                                 'path' => $image_path
                             ];

//                             echo  $image_path;

                             $fileName = basename($image_path);
                             $file = "https://skyblue.co.in/mail/data/images/".$fileName;
                             echo $file;
                            
                             echo "<img src='$file' alt='Image' />";
                        }
                    }
                }
        }
    }


} elseif ($structure->type == 2) {
    // 2: MESSAGE (message)
    echo "2: MESSAGE (message)\n";
} elseif ($structure->type == 3) {
    // 3: RICH TEXT (could be HTML or similar)
    echo " 3: RICH TEXT (could be HTML or similar)\n";
} else {
    echo "Message $msgNum has an unknown type.\n";
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



// Retrieve the plain text part of the message
function getBody($mailbox, $messageNumber, $structure, $partNumber = 1) {
    if ($structure->type == 0 && $structure->subtype == 'PLAIN') {
        // Plain text part
        return imap_fetchbody($mailbox, $messageNumber, $partNumber);
    } elseif (!empty($structure->parts)) {
        foreach ($structure->parts as $index => $subPart) {
            $subPartNumber = ($partNumber ? $partNumber . '.' : '') . ($index + 1);
            $result = getBody($mailbox, $messageNumber, $subPart, $subPartNumber);
            if ($result) {
                return $result;
            }
        }
    }
    return null;
}








// Get sender's info
$header = imap_headerinfo($mailbox, $message_id);
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
