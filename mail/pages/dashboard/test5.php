<style>
        .flex-container {
            display: inline-flex;
            overflow-x: auto;
            gap: 20px;
            padding-left:10px;
            padding-right:10px;
            padding-top:10px;
            padding-bottom:30px;
            height:180px;
            width:210px;
        }

        .flex-item {
            min-width: 200px;
            height: 150px;
            
            display: flex;
            justify-content: center;
           
            text-align: center;
            border-radius: 8px;
            position: relative;
        }

        .image-thumbnail {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
            transition: transform 0.3s ease; 
        }

        .image-thumbnail:hover {
            transform: scale(1.1); 
        }

        .zoomed-image-container {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .zoomed-image {
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
            border-radius: 8px;
        }

        .close-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            color: #fff;
            font-size: 36px;
            cursor: pointer;
            font-weight: bold;
        }

        .file-name {
            color:black;
            
        }

        .line {
            border-top: 1px solid #c5c9d0; 
            width: 100%;               
            margin-top: 20px;         
        }

        .image-text {
            position: absolute;
            bottom: 0px; 
            left: 0;
            right: 0;
            background-color: rgba(0, 0, 0, 0.6); 
            color: white;
            padding: 5px;
            text-align: center;
            font-size: 14px;
            border-radius: 0 0 8px 8px;
            cursor: pointer;
        }
    </style>
<?php

$hostname = '{imap.skyblue.co.in:993/imap/ssl/novalidate-cert}'; 
$username = 'prasanth';          
$password = 'Prasanth968@@';          
$mailbox = imap_open($hostname, $username, $password);

if (!$mailbox) {
    echo "Connection failed: " . imap_last_error();
    exit;
}

$message_id = 21;  
$structure = imap_fetchstructure($mailbox, $message_id);

$plainText = '';
$htmlContent = '';
$attachments = [];


  $structure = imap_fetchstructure($mailbox, $message_id);

           function getContentType($structure) {
               $primaryTypes = array("TEXT", "MULTIPART", "MESSAGE", "APPLICATION", "AUDIO", "IMAGE", "VIDEO", "OTHER");
               if (isset($primaryTypes[$structure->type])) {
                   return $primaryTypes[$structure->type] . '/' . $structure->subtype;
               }
               return "UNKNOWN";
           }
           
           $contentType = getContentType($structure);
    
           ini_set("xdebug.var_display_max_children", '-1');
           ini_set("xdebug.var_display_max_data", '-1');
           ini_set("xdebug.var_display_max_depth", '-1');

        if (isset($structure->parts)) {
            foreach ($structure->parts as $part_number => $part) { 
                if (isset($part->subtype) && strtolower($part->subtype) == 'alternative') { 
                    if (isset($part->parts)) { 
                        foreach ($part->parts as $sub_part_number => $sub_part) { 
                        
                         $body = imap_fetchbody($mailbox, $message_id, $part_number + 1 . '.' . ($sub_part_number + 1));

                         // Check plain text and html available.
                         if (isset($sub_part->subtype) && strtolower($sub_part->subtype) == 'plain') {
                            if ($sub_part->encoding == 3) {
                                $body = base64_decode($body);
                               } elseif ($sub_part->encoding == 4) {
                                $body = quoted_printable_decode($body);
                               }
                               $plainText .= $body;

                         }elseif (isset($sub_part->subtype) && strtolower($sub_part->subtype) == 'html') {
                            $body = imap_fetchbody($mailbox, $message_id, $part_number + 1 . '.' . ($sub_part_number + 1));

                            if ($sub_part->encoding == 3) {
                                $body = base64_decode($body);
                            } elseif ($sub_part->encoding == 4) {
                                $body = quoted_printable_decode($body);
                            }
                            $htmlContent .= $body;

                            echo $htmlContent."";
                            echo '<div class="line"></div>';

                            echo '<div style="color: black; padding-top:10px;">';
                            echo '<strong>';
                            echo 'Attachments';
                            echo '</strong>';
                            echo '</div>';
                         }
                        }
                    }
                }
            }
        }

        $attachments = getAttachments($mailbox, $message_id, $structure);

        foreach ($attachments as $file) {
               // Option 2: Display inline if image
               file_put_contents('/var/www/skyblue.co.in/mail/data/images/' . $file['filename'], $file['data']);
          
               $ext = pathinfo($file['filename'], PATHINFO_EXTENSION);

        if (in_array(strtolower($ext), ['png', 'jpg', 'jpeg', 'gif', 'pdf'])) {
            $base64 = base64_encode($file['data']);

        echo '<div class="flex-container">';
            echo '<div class="flex-item">';
                echo '<div>';

                      $dotPosition = strrpos($file['filename'], '.');
                      $extension = substr($file['filename'], $dotPosition + 1);
                      //  echo "File Extension: " . $extension;

                      if($extension == 'jpg' | $extension == 'jpeg' | $extension == 'png'){
                             
                             echo "<img src='data:image/{$ext};base64,{$base64}' class='image-thumbnail'>";

                             $prasanth = $file['filename'];
                             echo "<a href='#' class='downloadFile' data-id='$prasanth' style=' text-decoration: none; color: black;'>";
                             echo "<div class='image-text' >Download </div>";
                             echo "</a>";
                        }

                      if($extension == 'pdf'){
                                 echo "<img src='/assets/mail/img/pdf1.png' class='image-thumbnail'>";
                                 $prasanth = $file['filename'];
                                 echo "<a href='#' class='downloadFile' data-id='$prasanth' style=' text-decoration: none; color: black;'>";
                                 echo "<div class='image-text' >Download </div>";
                                 echo "</a>";
                         }
                         echo "<p class='file-name'> {$file['filename']}</p>";


                echo '</div>';
            echo '</div>';
        echo '</div>';
        }
    }


        function decodeAttachment($stream, $msgNumber, $part, $partNumber) {
            $data = imap_fetchbody($stream, $msgNumber, $partNumber);
            switch ($part->encoding) {
                case 0: return $data;
                case 1: return imap_8bit($data);
                case 2: return imap_binary($data);
                case 3: return base64_decode($data);
                case 4: return quoted_printable_decode($data);
                default: return $data;
            }
        }
        

        function getAttachments($stream, $msgNumber, $structure, $prefix = '') {
            $attachments = [];
        
            if (isset($structure->parts)) {
                foreach ($structure->parts as $index => $part) {
                    $partNumber = $prefix === '' ? ($index + 1) : "$prefix." . ($index + 1);
        
                    // If it's multipart itself, go deeper
                    if ($part->type == 1 && isset($part->parts)) {
                        $attachments = array_merge($attachments, getAttachments($stream, $msgNumber, $part, $partNumber));
                    }
        
                    // If it's a file (attachment or inline file)
                    if ($part->type == 5 || ($part->ifdisposition && in_array(strtolower($part->disposition), ['attachment', 'inline']))) {
                        $filename = null;
        
                        // Try to get the filename
                        if ($part->ifdparameters) {
                            foreach ($part->dparameters as $param) {
                                if (strtolower($param->attribute) == 'filename') {
                                    $filename = $param->value;
                                    break;
                                }
                            }
                        }
        
                        if (!$filename && $part->ifparameters) {
                            foreach ($part->parameters as $param) {
                                if (strtolower($param->attribute) == 'name') {
                                    $filename = $param->value;
                                    break;
                                }
                            }
                        }
        
                        $filename = $filename ?: "unknown_" . $partNumber;
        
                        $content = decodeAttachment($stream, $msgNumber, $part, $partNumber);

                    //    file_put_contents('/var/www/skyblue.co.in/mail/data/images/' . $filename, $content);

                    

                        $attachments[] = [
                            'filename' => $filename,
                            'data' => $content,
                            'mime' => "application/octet-stream", // Could improve based on subtype
                        ];
                    }
                }
            }
        
            return $attachments;
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
imap_close($mailbox);
?>
<div class="zoomed-image-container" id="zoomedImageContainer">
    <span class="close-btn" id="closeZoomedImage">&times;</span>
    <img class="zoomed-image" id="zoomedImage" src="" alt="">
</div>

<script>
    // Get all the image thumbnails
    const images = document.querySelectorAll(".image-thumbnail");

    // Get the zoomed image container, the zoomed image itself, and the close button
    const zoomedImageContainer = document.getElementById("zoomedImageContainer");
    const zoomedImage = document.getElementById("zoomedImage");
    const closeZoomedImage = document.getElementById("closeZoomedImage");

    // Loop through all images and add click event listeners
    images.forEach(image => {
        image.addEventListener("click", function() {
            // Set the source of the zoomed image to the clicked image's source
            zoomedImage.src = this.src;
            
            // Show the zoomed image container
            zoomedImageContainer.style.display = "flex";
        });
    });

    // When the user clicks the close button, hide the zoomed image container
    closeZoomedImage.addEventListener("click", function() {
        zoomedImageContainer.style.display = "none";
    });

    // When the user clicks outside the image, hide the zoomed image container
    zoomedImageContainer.addEventListener("click", function(event) {
        if (event.target === zoomedImageContainer) {
            zoomedImageContainer.style.display = "none";
        }
    });

    document.querySelectorAll('.downloadFile').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();

            const downloadFileName = this.getAttribute('data-id');
          //  alert(downloadFileName);

          const link = document.createElement("a");
    link.href = "https://skyblue.co.in/mail/data/images/" + downloadFileName; // Path to your file
    link.download = downloadFileName;   // Suggested filename
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
        });
    });

</script>