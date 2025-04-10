<?php
   session_start();
   
   if (!(isset($_SESSION['username']) && $_SESSION['password'] != '')) {
       header ("Location: https://skyblue.co.in/");
       }
   ?>
<html>
   <head>
      <title>Skyblue Business E-mail Dashboard</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="/assets/mail/css/styles.css">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
         integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
      <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Ubuntu" />
      <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
      <!-- access id : #100 -->
   </head>
   <body>
      <nav class="navbar navbar-light navbar-custom" style="width: 100%; position: fixed; box-shadow: 0 2px 5px 0 rgb(0 0 0 / 5%), 0 2px 10px 0 rgb(0 0 0 / 5%);">
         <a class="navbar-brand" href="#">
         <img src="/assets/mail/img/logo3.png" width="30" height="30" class="d-inline-block align-top" alt="">
         Skyblue Mail
         </a>
         <style>
            @media only screen and (max-width: 400px) {
            .form-inline {
            display: none;
            }
            }
         </style>
         <form class="form-inline my-2 my-lg-0 search">
            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-primary my-2 my-sm-0" type="submit">Search</button>
         </form>
      </nav>
      <!-- Sidebar -->
      <nav id="sidebarMenu" class="collapse d-lg-block sidebar collapse ">
         <div class="position-sticky">
            <div class="list-group list-group-flush mx-3 mt-4">
               <a href="#" onclick="showView('compose')" class="list-group-item list-group-item-action py-2 ripple active">
               <i class="fa fas  fa-fw me-3"></i><span>Compose Mail</span>
               </a>
               <a href="#" onclick="showView('home')" class="list-group-item list-group-item-action py-2 ripple"><i
                  class="fa fas fa-envelope fa-fw me-3"></i><span>Inbox</span></a>
               <a href="#" onclick="showView('contact')" class="list-group-item list-group-item-action py-2 ripple"><i
                  class="fa fa-paper-plane fa-fw me-3"></i><span>Sent</span></a>
               <a href="#" class="list-group-item list-group-item-action py-2 ripple">
               <i class="fa fas fa-file-alt fa-fw me-3"></i><span>Draft</span>
               </a>
               <a href="#" class="list-group-item list-group-item-action py-2 ripple"><i
                  class="fa fas fa-heart fa-fw me-3"></i><span>Important</span></a>
               <a href="#" class="list-group-item list-group-item-action py-2 ripple"><i
                  class="fa fas fa-exclamation-triangle fa-fw me-3"></i><span>Spam</span></a>
               <a href="#" class="list-group-item list-group-item-action py-2 ripple"><i
                  class=" fa  fas fa-trash fa-fw me-3"></i><span>Trash</span></a>
               <a href="#" class="list-group-item list-group-item-action py-2 ripple"><i
                  class="fa fas fa-calendar fa-fw me-3"></i><span>Calendar</span></a>
               <a href="#" class="list-group-item list-group-item-action py-2 ripple"><i
                  class="fa fas fa-cog fa-fw me-3"></i><span>Settings</span></a>
               <a href="logout.php" class="list-group-item list-group-item-action py-2 ripple"><i
                  class="fa fa-sign-out-alt fa-fw me-3"></i><span>Logout</span></a>
            </div>
         </div>
      </nav>
      <div id="home" class="view active" style="background-color: white; height: 100%; width: 100%;">
         <div class="content__email_list">
            <?php
               $hostname = '{imap.skyblue.co.in:993/imap/ssl/novalidate-cert}INBOX';
               $username = $_SESSION["username"];
               $password = $_SESSION["password"];
               
               $inbox = imap_open($hostname, $username, $password) or die('Cannot connect to mailbox: ' . imap_last_error());
               
               $numMessages = imap_num_msg($inbox);
               $email_ids = imap_search($inbox, 'ALL'); 
               
               // sort them by ascending date
               if ($email_ids) {
                   // Sort email IDs by ascending date
                   rsort($email_ids);
                 
                   foreach ($email_ids as $email_id) {
                       $header = imap_headerinfo($inbox, $email_id);
                       
                       $subject = $header->subject;
                       $from = $header->fromaddress;
                       $date = $header->date;
               
                   $decoded_subject0 = imap_utf8($subject);
               
                 $data = array("view"=> "message_view", "email_id"=>$email_id);
               
                 $js_data = json_encode($data);
               
                       echo "<a href='?action=execute&messageId=$email_id' class='viewEmail' data-id='$email_id' style=' text-decoration: none; color: black;'>";
                       echo "<div class='email__start'></div>";
                       echo "<p class='email__name'>";
                       $main = substr($from, 0, 20);
                       echo "<b></b> $main <br>";
                       echo "</p>";
                       echo "<p class='email__content'>";
                       $decoded_subject = imap_utf8($subject);
                       echo "<b></b> $decoded_subject <br>";
                       echo "</p>";
                       echo "</a>";
                   }
               } else {
                   echo "No emails found.";
               }
               imap_close($inbox);
               ?>
         </div>
      </div>
      <div id="contact" class="view" style="background-color:white;">
         <h1>Send email list view</h1>
         <p>Development under progress.</p>
      </div>
      <style>
         .container-compose {
         margin:20px;
         height: 100%;
         width: 100%;
         }
         .btn-default {
         background-color: white;
         color: black;
         border-color:#adadad
         }
         .btn-default:hover,
         .btn-default:focus,
         .btn-default.focus,
         .btn-default:active,
         .btn-default.active,
         .open>.dropdown-toggle.btn-default {
         color:#333;
         background-color:#e6e6e6;
         border-color:#adadad
         }
         .editor {
         border: 1px solid #ccc;
         padding: 10px;
         min-height: 200px;
         width: 100%;
         margin-top: 10px;
         font-size: 16px;
         background-color: white;
         }
      </style>
      <div id="compose" class="view">
         <div class="container-compose">
            <div class="row inbox" style="height:100px;">
               <div class="col-md-9">
                  <div class="panel panel-default">
                     <div class="panel-body ">
                        <p class="text-center" style="display:none;">New Message</p>
                        <form class="form-horizontal" role="form">
                           <div class="form-group d-flex justify-content-start">
                              <label for="to" class="col-sm-1 control-label">To:</label>
                              <div class="col-sm-11">
                                 <input type="email" class="form-control select2-offscreen" id="to" placeholder="Type email" tabindex="-1">
                              </div>
                           </div>
                           <div class="form-group d-flex justify-content-start">
                              <label for="cc" class="col-sm-1 control-label">CC:</label>
                              <div class="col-sm-11">
                                 <input type="email" class="form-control select2-offscreen" id="cc" placeholder="Type email" tabindex="-1">
                              </div>
                           </div>
                           <div class="form-group d-flex justify-content-start">
                              <label for="bcc" class="col-sm-1 control-label">BCC:</label>
                              <div class="col-sm-11">
                                 <input type="email" class="form-control select2-offscreen" id="bcc" placeholder="Type email" tabindex="-1">
                              </div>
                           </div>
                        </form>
                        <div class="col-sm-11 col-sm-offset-1">
                           <div class="btn-toolbar" role="toolbar">
                              <div class="btn-group">
                                 <button onclick="document.execCommand('bold')" class="btn btn-default"><span class="fa fa-bold"></span></button>
                                 <button onclick="document.execCommand('italic')" class="btn btn-default"><span class="fa fa-italic"></span></button>
                                 <button onclick="document.execCommand('underline')" class="btn btn-default"><span class="fa fa-underline"></span></button>
                              </div>
                              <div class="btn-group" style="margin-left:5px;">
                                 <button onclick="document.execCommand('justifyLeft')" class="btn btn-default"><span class="fa fa-align-left"></span></button>
                                 <button onclick="document.execCommand('justifyRight')" class="btn btn-default"><span class="fa fa-align-right"></span></button>
                                 <button onclick="document.execCommand('justifyCenter')" class="btn btn-default"><span class="fa fa-align-center"></span></button>
                                 <button onclick="document.execCommand('justifyLeft')" class="btn btn-default"><span class="fa fa-align-justify"></span></button>
                              </div>
                              <div class="btn-group" style="margin-left:5px;">
                                 <button onclick="document.execCommand('indent')" class="btn btn-default"><span class="fa fa-indent"></span></button>
                                 <button onclick="document.execCommand('outdent')" class="btn btn-default"><span class="fa fa-outdent"></span></button>
                              </div>
                              <div class="btn-group" style="margin-left:5px;">
                                 <button onclick="document.execCommand('insertUnorderedList', false, null)" class="btn btn-default"><span class="fa fa-list-ul"></span></button>
                                 <button onclick="document.execCommand('insertOrderedList', false, null)" class="btn btn-default"><span class="fa fa-list-ol"></span></button>
                              </div>
                              <button class="btn btn-default" style="margin-left:5px;"><span class="fa fa-paperclip"></span></button>
                              <button class="btn btn-default" style="margin-left:5px;"><input type="color" id="colorPicker" value="#000000" onchange="changeTextColor()"></span></button>
                           </div>
                           <!-- // new  -->
                           <br>	
                           <div id="editor" contenteditable="true" spellcheck="false" class="editor">
                              <p>Start typing here...</p>
                           </div>
                           <!-- new stopped -->
                           <!-- bottom btn started -->
                           <div class="form-group">	
                              <button type="submit" class="btn btn-success">Send</button>
                              <button type="submit" class="btn btn-default">Draft</button>
                              <button type="submit" class="btn btn-danger">Discard</button>
                           </div>
                           <!-- bottom button stopped -->
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div id="message_view" class="view" style="background-color: white;">
         <style>
            #emailDetails {
            overflow:scroll;
            height:100%;
            margin:auto;
            }
         </style>
         <div id="emailDetails" style="background-color: red;">
            <?php
               if (isset($_GET['action']) && $_GET['action'] === 'execute') {
               
               if (isset($_GET['messageId'])) {
                   $messageId = $_GET['messageId'];
                   loadMessageView($messageId); 
                   }
               }
               
               function loadMessageView($messageId){
                 echo ' <script>
                             var viewId = "message_view";
                 document.querySelectorAll(".view").forEach(v => v.classList.remove("active"));
                 document.getElementById(viewId).classList.add("active");
                 </script>';
               
                 $username = $_SESSION["username"];
                 $password = $_SESSION["password"];
                 global $mailbox;
                 $mailbox = imap_open("{imap.skyblue.co.in:993/imap/ssl/novalidate-cert}INBOX", $username, $password);
               
                 if (!$mailbox) {
                   echo "Failed to connect to IMAP server.";
                   exit;
               }
               
               global $subject, $from, $subject, $date;
               $header = imap_headerinfo($mailbox, $messageId);
               $from = $header->fromaddress;
               $subject = $header->subject;
               $date = $header->date;          
               }
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
            </div>
            <!-- container 2 over -->
            <div class="container4">
               <?php 
                  $decoded_subject = imap_utf8($subject);
                  echo htmlspecialchars($decoded_subject);  
                         
                  ?>
            </div>
            <!-- container 4 over -->
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
            </div>
            <!-- container 5 over -->
            <div class="container6">
               <?php
                  $structure = imap_fetchstructure($mailbox, $messageId);
                  
                  function getContentType($structure) {
                      $primaryTypes = array("TEXT", "MULTIPART", "MESSAGE", "APPLICATION", "AUDIO", "IMAGE", "VIDEO", "OTHER");
                      if (isset($primaryTypes[$structure->type])) {
                          return $primaryTypes[$structure->type] . '/' . $structure->subtype;
                      }
                      return "UNKNOWN";
                  }
                  
                  $contentType = getContentType($structure);
                  echo "Content-Type: " . $contentType;
                  
                  $structure = imap_fetchstructure($mailbox, $messageId);
                  $body = imap_fetchbody($mailbox, $messageId, 1);
                  
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
                                                       $body = imap_fetchbody($mailbox, $messageId, $part_number + 1 . '.' . ($sub_part_number + 1));
                  
                                                       if ($sub_part->encoding == 3) {
                                                           $body = base64_decode($body);
                                                          } elseif ($sub_part->encoding == 4) {
                                                           $body = quoted_printable_decode($body);
                                                          }
                                                          $plainText .= $body;
                                                      }
                                                      elseif (isset($sub_part->subtype) && strtolower($sub_part->subtype) == 'html') {
                                                            $body = imap_fetchbody($mailbox, $messageId, $part_number + 1 . '.' . ($sub_part_number + 1));
                  
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
                                       $body = imap_fetchbody($mailbox, $messageId, $part_number + 1);
                  
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
                  
                   case "MULTIPART/MIXED":
                  //     $structure = imap_fetchstructure($mailbox, $message_id);
                  
                       ini_set("xdebug.var_display_max_children", '-1');
                       ini_set("xdebug.var_display_max_data", '-1');
                       ini_set("xdebug.var_display_max_depth", '-1');
                  
                    if (isset($structure->parts)) {
                        foreach ($structure->parts as $part_number => $part) { 
                            if (isset($part->subtype) && strtolower($part->subtype) == 'alternative') { 
                                if (isset($part->parts)) { 
                                    foreach ($part->parts as $sub_part_number => $sub_part) { 
                                    
                                     $body = imap_fetchbody($mailbox, $messageId, $part_number + 1 . '.' . ($sub_part_number + 1));
                  
                                     // Check plain text and html available.
                                     if (isset($sub_part->subtype) && strtolower($sub_part->subtype) == 'plain') {
                                        if ($sub_part->encoding == 3) {
                                            $body = base64_decode($body);
                                           } elseif ($sub_part->encoding == 4) {
                                            $body = quoted_printable_decode($body);
                                           }
                                           $plainText .= $body;
                  
                                     }elseif (isset($sub_part->subtype) && strtolower($sub_part->subtype) == 'html') {
                                        $body = imap_fetchbody($mailbox, $messageId, $part_number + 1 . '.' . ($sub_part_number + 1));
                  
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
                  
                    $attachments = getAttachments($mailbox, $messageId, $structure);
                  
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
                  
                       break;
                  
                   case "MULTIPART/ALTERNATIVE":
                     //  echo "<h1> MULTIPART/ALTERNATIVE </h1> ";
                       
                  
                  
                       $structure = imap_fetchstructure($mailbox, $messageId);
                  
                       function get_part($mailbox, $messageId, $structure, $part_number) {
                         $data = imap_fetchbody($mailbox, $messageId, $part_number);
                     
                         switch ($structure->encoding) {
                             case 0: return $data; // 7BIT
                             case 1: return imap_utf8($data); // 8BIT
                             case 3: return base64_decode($data);
                             case 4: return quoted_printable_decode($data);
                             default: return $data;
                         }
                     }
                  
                     if ($structure->type == TYPEMULTIPART) {
                       foreach ($structure->parts as $part_num => $part) {
                         // if ($part->subtype == 'PLAIN') {
                         //   $text = get_part($mailbox, $messageId, $part, $part_num + 1);
                         //   echo "\n$text\n";
                         //  // var_dump($part->subtype);
                         // }  
                         
                         if ($part->subtype == 'HTML') {
                           $html = get_part($mailbox, $messageId, $part, $part_num + 1);
                           echo "\n$html\n";
                         }
                         
                       }
                     }
                     
                  
                       
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
                  
                  imap_close($mailbox);
                  ?>
            </div>
         </div>
      </div>
      <div class="zoomed-image-container" id="zoomedImageContainer">
         <span class="close-btn" id="closeZoomedImage">&times;</span>
         <img class="zoomed-image" id="zoomedImage" src="" alt="">
      </div>
      <script>
         function showView(viewId) {
            document.getElementById("emailDetails").innerHTML = "";
            document.querySelectorAll('.view').forEach(v => v.classList.remove('active'));
            document.getElementById(viewId).classList.add('active');
         }
         
         function showEmail(data) {
            var t = JSON.parse(data);
            var viewId = t['view'];
            var emailId = t['email_id'];
            document.querySelectorAll('.view').forEach(v => v.classList.remove('active'));
            document.getElementById(viewId).classList.add('active');
         
         }
         
         document.getElementById('editor').addEventListener('input', function () {
         
         });
         
         function changeTextColor() {
            const color = document.getElementById('colorPicker').value;
            document.execCommand('foreColor', false, color);
         }
         
         const images = document.querySelectorAll(".image-thumbnail");
         
         const zoomedImageContainer = document.getElementById("zoomedImageContainer");
         const zoomedImage = document.getElementById("zoomedImage");
         const closeZoomedImage = document.getElementById("closeZoomedImage");
         
         images.forEach(image => {
            image.addEventListener("click", function () {
               zoomedImage.src = this.src;
               zoomedImageContainer.style.display = "flex";
            });
         });
         
         closeZoomedImage.addEventListener("click", function () {
            zoomedImageContainer.style.display = "none";
         });
         
         zoomedImageContainer.addEventListener("click", function (event) {
            if (event.target === zoomedImageContainer) {
               zoomedImageContainer.style.display = "none";
            }
         });
         
         document.querySelectorAll('.downloadFile').forEach(link => {
            link.addEventListener('click', function (e) {
               e.preventDefault();
         
               const downloadFileName = this.getAttribute('data-id');
         
               const link = document.createElement("a");
               link.href = "https://skyblue.co.in/mail/data/images/" + downloadFileName;
               document.body.appendChild(link);
               link.click();
               document.body.removeChild(link);
            });
         });
           
      </script>
   </body>
</html>
