<?php
   session_start();
   $user_Id = $_SESSION["user_id"];
   if (!(isset($_SESSION["username"]) && $_SESSION["password"] != "")) {
       header("Location: https://mail.skyblue.co.in/");
   }
   ?>
   
<html>
   <head>
      <title>E-Mail Dashboard</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
         integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
      <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Ubuntu" />
      <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
      <link rel="stylesheet" href="/assets/css/styles.css">
   </head>
   <body>
      <div class="Loading" id="Loading"></div>
      <div class="bottom-message-box" id="messageBox" style="position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%); background-color: #2971fc; color: white; padding: 15px 25px; border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); font-family: sans-serif; font-size: 14px; display: none; z-index: 1000;">
      </div>
      <nav class="navbar navbar-light navbar-custom" style="width: 100%; position: fixed; box-shadow: 0 2px 5px 0 rgb(0 0 0 / 5%), 0 2px 10px 0 rgb(0 0 0 / 5%);">
         <a class="navbar-brand" href="https://mail.skyblue.co.in">
         <img src="/assets/img/logo3.png" width="30" height="30" class="d-inline-block align-top" alt="">
         Skyblue Mail
         </a>
      
         <form class="form-inline my-2 my-lg-0 search">
            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-primary my-2 my-sm-0" type="submit">Search</button>
         </form>
      </nav>
      <nav id="sidebarMenu" class=" d-lg-block sidebar  ">
         <div class="position-sticky">
            <div class="list-group list-group-flush mx-3 mt-4">
               <a href="?action=COMPOSE"   class="list-group-item list-group-item-action py-2 ripple active">
               <i class="fa fas  fa-fw me-3"></i><span>Compose Mail</span>
               </a>
               <a href="?action=INBOX"  class="list-group-item list-group-item-action py-2 ripple"><i
                  class="fa fas fa-envelope fa-fw me-3"></i><span>Inbox</span></a>
               <a href="?action=SENT"  class="list-group-item list-group-item-action py-2 ripple"><i
                  class="fa fa-paper-plane fa-fw me-3"></i><span>Sent</span></a>
               <a href="?action=DRAFT" class="list-group-item list-group-item-action py-2 ripple">
               <i class="fa fas fa-file-alt fa-fw me-3"></i><span>Draft</span>
               </a>
               <a href="?action=IMPORTANT"  class="list-group-item list-group-item-action py-2 ripple"><i
                  class="fa fas fa-heart fa-fw me-3"></i><span>Important</span></a>
               <a href="?action=SPAM" class="list-group-item list-group-item-action py-2 ripple"><i
                  class="fa fas fa-exclamation-triangle fa-fw me-3"></i><span>Spam</span></a>
               <a href="?action=TRASH" class="list-group-item list-group-item-action py-2 ripple"><i
                  class=" fa  fas fa-trash fa-fw me-3"></i><span>Trash</span></a>
               <a  href="?action=CALENDAR" class="list-group-item list-group-item-action py-2 ripple"><i
                  class="fa fas fa-calendar fa-fw me-3"></i><span>Calendar</span></a>
               <a href="?action=SETTINGS" class="list-group-item list-group-item-action py-2 ripple"><i
                  class="fa fas fa-cog fa-fw me-3"></i><span>Settings</span></a>
               <a href="logout.php" class="list-group-item list-group-item-action py-2 ripple"><i
                  class="fa fa-sign-out-alt fa-fw me-3"></i><span>Logout</span></a>
            </div>
         </div>
      </nav>
      <div id="home" class="view active" style="background-color: white; height: 100%; width: 100%; overflow: scroll;">
         <div class="content__email_list">
            <div class="tools" id="tools">
               <div class="tools-container">
                  <div class="tools-left">
                     <label class="container-mark-total" style="margin-left:-4px;">
                     <input id="inboxCheckTotal" class="mark-box-total" type="checkbox" name="delete[]" value="'.$email_id.'" >
                     </label>
                     <a id="inboxDeleteMessage" class="tools-btn"  style="text-decoration: none; margin-left:10px; color: white;">
                     Delete
                     </a>
                     <a href="?action=move" style="text-decoration: none; margin-left:10px;">
                     Move
                     </a>
                  </div>
                  <a style="text-decoration: none;" id="toolsClose">
                     <div class="tools-right" >
                        X
                     </div>
                  </a>
               </div>
            </div>
            <div id="emailContainer">
            </div>
            <script>
               function fetchEmails() {
                   console.log("loaded");
                   fetch('load_emails.php')
                       .then(response => response.text())
                       .then(data => {
                           document.getElementById('emailContainer').innerHTML = data;
                       })
                       .catch(error => {
                           console.error("Error loading emails:", error);
                       });
               }
               
               // Run immediately on page load
               fetchEmails();
               
               // Run every 1 minutes
               setInterval(fetchEmails, 60000);
            </script>
            <script src="/assets/js/tools.js"></script>
            <script src="/assets/js/delete_message.js"></script>
         </div>
      </div>
      <div id="sent" class="view" style="background-color: white; height: 100%; width: 100%; overflow: scroll;">
         <div class="content__email_list">
            <?php
               $hostname = "{mail.skyblue.co.in:993/imap/ssl/novalidate-cert}Sent";
               $username = $_SESSION["username"];
               $password = $_SESSION["password"];
               ($inbox = imap_open($hostname, $username, $password)) or
                   die("Cannot connect to mailbox: " . imap_last_error());
               $numMessages = imap_num_msg($inbox);
               $email_ids = imap_search($inbox, "ALL");
               if ($email_ids) {
                   rsort($email_ids);
                   foreach ($email_ids as $email_id) {
                       $header = imap_headerinfo($inbox, $email_id);
                       $to = $header->toaddress;
                       $to_name = isset($header->to[0]->personal) ? $header->to[0]->personal : $to;
               
                       $subject = $header->subject;
                       $from = $header->fromaddress;
                       $date = $header->date;
                       $decoded_subject0 = imap_utf8($subject);
                       $data = ["view" => "message_view", "email_id" => $email_id];
                       $js_data = json_encode($data);
                       echo "<a href='?view=Sent&messageId=$email_id' class='viewEmail' data-id='$email_id' style='background-color: white; text-decoration: none; color: black;'>";
                       echo "<div class='email__start'>";
                       echo '<label class="container-mark">';
                       echo '<input class="mark-box" type="checkbox">';
                       echo '<span class="checkmark"></span>';
                       echo "</label>";
                       echo "</div>";
                       echo "<p class='email__name' >";
                      // $main = substr("To: ".$to_name, 0, 20);
                      $main = "To: ".extractNameOrUsername($to_name);
                       echo "<b></b> $main <br>";
                       echo "</p>";
                       echo "<p class='email__content' >";
                       $decoded_subject = imap_utf8($subject);
                       echo "<b></b> $decoded_subject <br>";
                       echo "</p>";
                       echo "<div class='text-right' style='margin-bottom:1rem; '> ";
                       $mDate = new DateTime($date);
                       echo $mDate->format("F j, Y");
                       echo "</div>";
                       echo "</a>";
                   }
               } else {
                   echo "No emails found.";
               }
               
               function extractNameOrUsername($toHeader) {
                   // Use regex to parse email and name
                   preg_match('/(?:"?([^"]*)"?\s)?<?([^<>]+)>?/', $toHeader, $matches);
                  $name = trim($matches[1] ?? '');
                  $email = $matches[2] ?? '';
                  
                  if (!empty($name)) {
                      return $name;
                  }
                  
                  return explode('@', $email)[0]; // fallback to email username
                  }
                  
                  imap_close($inbox);
                  ?>
         </div>
      </div>
      <div id="draft" class="view" style="background-color:white;">
         <!-- Draft layout -->
      </div>
      <div id="important" class="view" style="background-color:white;">
         <!-- Important layout -->
      </div>
      <div id="spam" class="view" style="background-color:white;">
         <!-- Spam layout -->
      </div>
      <div id="trash" class="view" style="background-color:white;">
         <!-- Trash layout -->
      </div>
      <div id="calendar" class="view" style="background-color:white;">
         <!-- Calendar layout -->
      </div>
      <div id="settings" class="view" style="background-color:white; bottom: 20px;">
         <!-- Settings layout -->
      </div>
     
        <div id="compose" class="view compose-view"> 
         <div class="container-compose">
            <div class="row inbox" style="height:100px;">
               <div  style="flex-basis: 100%; margin-right:5%;">
                  <div class="panel panel-default">
                     <div class="panel-body ">
                        <p class="text-center" style="display:none;">New Message</p>
                        <form class="form-horizontal" role="form">
                           <div class="form-group d-flex justify-content-start">
                              <label style="font-weight: 600;" for="to" class="col-sm-1 control-label">To</label>
                              <div class="col-sm-11">

                                 <!-- To address -->
                                 <div class="email-container" id="emailContainerTo" onclick="emailInput.focus()">
                                    <input type="text" class="EditText select2-offscreen" id="emailInput" value="" placeholder="Type email and press space or enter...">
                                 </div>


                                 <div class="container-bcc-cc" >
                                    <div class="textviewBCC-CC" id="ccText" onclick="onClickCC()" >CC</div>
                                    <div class="textviewBCC-CC" id="bccText" onclick="onClickBCC()">BCC</div>
                                 </div>
                                 <input type="hidden" name="emails_json" id="emailsInput">
                                 <!-- 
                                    Display the entered mail lists, don't erase below line 
                                    When production below lines will be removed
                                    Debug purpose
                                    -->
                                    <!-- Debug To Address -->
                                     To Address (User entered emails)
                                 <pre id="emailOutput"></pre>
                                    Debug draft response for "to address"
                                    <pre id="draftResponse"></pre>
   
                              </div>
                           </div>

                           <!-- CC address -->
                           <div id="activateCC" style="display: none;">
                              <div class="form-group d-flex justify-content-start" style="">
                                 <label style="font-weight: 600;" for="cc" class="col-sm-1 control-label">CC</label>
                                 <div class="col-sm-11">

                                    <div class="email-container-cc" id="emailContainerCC" onclick="emailInputCC.focus()">
                                    <input type="email" class="EditText select2-offscreen"  id="emailInputCC" placeholder="Type email" tabindex="-1">
                                    </div>
                                    <input type="hidden" name="emails_json_cc" id="emailsInputCC">
                                    CC Address (User entered emails)
                                    <pre id="emailOutputCC"></pre>
                                 </div>
                              </div>
                           </div>

                           <!-- BCC Address -->
                           <div id="activateBCC" style="display: none;">
                              <div class="form-group d-flex justify-content-start">
                                 <label style="font-weight: 600;" for="bcc" class="col-sm-1 control-label">BCC:</label>
                                 <div class="col-sm-11">
                                    <!-- <input type="email" class="EditText select2-offscreen"  id="bcc" placeholder="Type email" tabindex="-1"> -->
                                 
                                    <div class="email-container-bcc" id="emailContainerBCC" onclick="emailInputBCC.focus()">
                                    <input type="email" class="EditText select2-offscreen"  id="emailInputBCC" placeholder="Type email" tabindex="-1">
                                    </div>
                                    <input type="hidden" name="emails_json_bcc" id="emailsInputBCC">
                                    BCC Address (User entered emails)
                                    <pre id="emailOutputBCC"></pre>

                                 </div>
                              </div>
                           </div>

                           <div class="form-group d-flex justify-content-start">
                              <label style="font-weight: 600;" for="bcc" class="col-sm-1 control-label">Subject</label>
                              <div class="col-sm-11">
                                 <input type="text" class="EditText select2-offscreen" id="subject" placeholder="Subject" tabindex="-1">
                              </div>
                           </div>

                           <script>

var  draftId = "";

//===============================================================================
// To address
const emailInput = document.getElementById('emailInput');
const emailContainerTo = document.getElementById('emailContainerTo');
const emailOutput = document.getElementById('emailOutput');
const emailHiddenInput = document.getElementById('emailsInput');
const emailList = [];

function isValidEmail(email) {
        const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
         return pattern.test(email);
    }
                                                        
function addEmail(email) {
     email = email.trim();
    if (!isValidEmail(email)) {
            alert(`Invalid email: ${email}`);
            emailInput.value = '';
            return;
        }
                            
if (emailList.includes(email)) {
            alert(`Duplicate email: ${email}`);
            emailInput.value = '';
            return;
        }
                            
        emailList.push(email);
                            
        const tag = document.createElement('div');
        tag.className = 'email-tag';
        tag.textContent = email;
                            
        const remove = document.createElement('span');
        remove.textContent = '×';

remove.onclick = () => {
        emailContainerTo.removeChild(tag);
        const index = emailList.indexOf(email);
        if (index !== -1) emailList.splice(index, 1);
        updateemailOutput();
    };
                            
tag.appendChild(remove);
        emailContainerTo.insertBefore(tag, emailInput);
        emailInput.value = '';
        updateemailOutput();         
    }
                            
function updateemailOutput() {
        const mToAddressJson = JSON.stringify(emailList);
        emailOutput.textContent = mToAddressJson;
        console.log(mToAddressJson);
        emailHiddenInput.value = mToAddressJson;
        updateDraft(emailList, emailListCC , emailListBCC);
    }
                            
emailInput.addEventListener('keydown', e => {
        const value = emailInput.value.trim();
        if ((e.key === 'Enter' || e.key === ' ') && value) {
        addEmail(value);
        e.preventDefault();
    }
});
                            
emailInput.addEventListener('blur', () => {
        const value = emailInput.value.trim();
        if (value) {
            addEmail(value);
        }
});


// CC address 
const emailInputCC = document.getElementById('emailInputCC');
const emailContainerCC = document.getElementById('emailContainerCC');
const emailOutputCC = document.getElementById('emailOutputCC')
const emailHiddenInputCC = document.getElementById('emailsInputCC');
const emailListCC = [];

function addEmailCC(email) {
     email = email.trim();
    if (!isValidEmail(email)) {
            alert(`Invalid email: ${email}`);
            emailInputCC.value = '';
            return;
        }
                            
if (emailListCC.includes(email)) {
            alert(`Duplicate email: ${email}`);
            emailInputCC.value = '';
            return;
        }
                            
        emailListCC.push(email);
                            
        const tag = document.createElement('div');
        tag.className = 'email-tag';
        tag.textContent = email;
                            
        const remove = document.createElement('span');
        remove.textContent = '×';

remove.onclick = () => {
        emailContainerCC.removeChild(tag);
        const index = emailListCC.indexOf(email);
        if (index !== -1) emailListCC.splice(index, 1);
        updateemailOutputCC();
    };
                            
tag.appendChild(remove);
        emailContainerCC.insertBefore(tag, emailInputCC);
        emailInputCC.value = '';
        updateemailOutputCC();         
    }

    function updateemailOutputCC() {
        const mCCAddressJson = JSON.stringify(emailListCC);
        emailOutputCC.textContent = mCCAddressJson;
        console.log(mCCAddressJson);
        emailHiddenInputCC.value = mCCAddressJson;
     //   updateDraft(emailList);
     updateDraft(emailList, emailListCC , emailListBCC);

    }

emailInputCC.addEventListener('keydown', e => {
        const value = emailInputCC.value.trim();
        if ((e.key === 'Enter' || e.key === ' ') && value) {
        addEmailCC(value);
        e.preventDefault();
    }
});

// BCC address 
const emailInputBCC = document.getElementById('emailInputBCC');
const emailContainerBCC = document.getElementById('emailContainerBCC');
const emailOutputBCC = document.getElementById('emailOutputBCC')
const emailHiddenInputBCC = document.getElementById('emailsInputBCC');
const emailListBCC = [];

function addEmailBCC(email) {
     email = email.trim();
    if (!isValidEmail(email)) {
            alert(`Invalid email: ${email}`);
            emailInputBCC.value = '';
            return;
        }
                            
if (emailListBCC.includes(email)) {
            alert(`Duplicate email: ${email}`);
            emailInputBCC.value = '';
            return;
        }
                            
        emailListBCC.push(email);
                            
        const tag = document.createElement('div');
        tag.className = 'email-tag';
        tag.textContent = email;
                            
        const remove = document.createElement('span');
        remove.textContent = '×';

remove.onclick = () => {
        emailContainerBCC.removeChild(tag);
        const index = emailListBCC.indexOf(email);
        if (index !== -1) emailListBCC.splice(index, 1);
        updateemailOutputBCC();
    };
                            
tag.appendChild(remove);
        emailContainerBCC.insertBefore(tag, emailInputBCC);
        emailInputBCC.value = '';
        updateemailOutputBCC();         
    }

    function updateemailOutputBCC() {
        const mBCCAddressJson = JSON.stringify(emailListBCC);
        emailOutputBCC.textContent = mBCCAddressJson;
        console.log(mBCCAddressJson);
        emailHiddenInputBCC.value = mBCCAddressJson;
     //   updateDraft(emailList);
     updateDraft(emailList, emailListCC , emailListBCC);

    }

emailInputBCC.addEventListener('keydown', e => {
        const value = emailInputBCC.value.trim();
        if ((e.key === 'Enter' || e.key === ' ') && value) {
        addEmailBCC(value);
        e.preventDefault();
    }
});

                            
function updateDraft(mToAddressJson, mCcAddress, mBccAddress) {
var  acc = "updateDraft";                  
if (draftId !== "") {        
     // draftId is not empty
     console.log("Valid draftId:", draftId);
} else {
     console.log("draftId is empty");
}                          
                            
var  ccAddressJson = "";
var  bccAddressJson = "";
                            
const data = {
        acc: acc,
        draft_id: draftId,
        to_address: mToAddressJson,
        cc_address: mCcAddress,
        bcc_address: mBccAddress,
        client_date: getFormattedDateTime(),
        user_id: <?php echo json_encode($user_Id); ?>
    };

    // POST request to the API
    fetch('https://mail.skyblue.co.in/mail.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(data => {

            if (data.status === "success") {
                    const responseMessage = document.getElementById('draftResponse');
                    responseMessage.textContent = 'status: ' + data.status + '\n' + 'message: ' + data.message  + '\n' + 'draft_id: ' + data.draft_id;
                    draftId = data.draft_id;
                 } else {
                        alert("Error: " + response.message);
                 }
        })
        .catch((error) => {
            console.error("Request failed:", error);
            alert("Network or server error occurred.");
        });
}

function getFormattedDateTime() {
const now = new Date();
const year = now.getFullYear();
const month = String(now.getMonth() + 1).padStart(2, '0'); // Month is 0-based
const day = String(now.getDate()).padStart(2, '0');
const hours = String(now.getHours()).padStart(2, '0');
const minutes = String(now.getMinutes()).padStart(2, '0');
const seconds = String(now.getSeconds()).padStart(2, '0');
return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
}

</script>
                        </form>
                        <div class="col-sm-11 col-sm-offset-1" style="max-width:100%; ">
                           <br>	
                           <div id="editor" contenteditable="true" spellcheck="false" class="editor">
                              Write your message here...
                              <section class="progress-area"></section>
                              <section class="uploaded-area"></section>
                           </div>
                           <br>
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
                              <button type="button" class="btn btn-default" style="margin-left:5px;" onclick="document.getElementById('fileInput').click();">
                              <span class="fa fa-paperclip"></span>
                              </button>
                              <input type="file" class="file-input" id="fileInput" name="files[]" multiple style="display:none;">
                              <!-- <section class="progress-area"></section>
                                 <section class="uploaded-area"></section> -->
                              <script src="/assets/js/script.js"></script>
                              <button class="btn btn-default" style="margin-left:5px;"><input type="color" id="colorPicker" value="#000000" onchange="changeTextColor()"></span></button>
                           </div>
                           <div class="form-group msg-button-con">
                              <div id="sendMail" style="width: 150px;" type="submit" class="btn-submit button-green" onclick="showProgress()">
                                 <div id="text-sendMail"> Send </div>
                                 <div id="progressCircleGreen" class="hidden" style="margin-top: 30px; margin-left: 20px;">
                                 </div>
                              </div>
                           </div>
                           <script src="/assets/js/compose.js"></script>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div id="message_view" class="view" style="background-color: white;">
         <div id="emailDetails" style="background-color: white;">
            <?php
               if (isset($_GET["view"])) {
                   if (isset($_GET["messageId"])) {
                       $messageId = $_GET["messageId"];
                       $view = $_GET["view"];
                       loadMessageView($view, $messageId);
                   }
               }
               switch ($_GET["action"]) {
                   case "COMPOSE":
                       echo ' <script>
                        var viewId = "compose";
                         document.getElementById("emailDetails").innerHTML = "";
                         document.querySelectorAll(".view").forEach(v => v.classList.remove("active"));
                         document.getElementById(viewId).classList.add("active");
                        </script>';
                   break;
               
                   case "INBOX":
                       echo ' <script>
                           var viewId = "home";
                            document.getElementById("emailDetails").innerHTML = "";
                            document.querySelectorAll(".view").forEach(v => v.classList.remove("active"));
                            document.getElementById(viewId).classList.add("active");
                           </script>';
               
                   break;
               
                   case "SENT":
                       echo ' <script>
                              var viewId = "sent";
                               document.getElementById("emailDetails").innerHTML = "";
                               document.querySelectorAll(".view").forEach(v => v.classList.remove("active"));
                               document.getElementById(viewId).classList.add("active");
                              </script>';
                   break;
               
                   case "DRAFT":
                       echo ' <script>
                                 var viewId = "draft";
                                  document.getElementById("emailDetails").innerHTML = "";
                                  document.querySelectorAll(".view").forEach(v => v.classList.remove("active"));
                                  document.getElementById(viewId).classList.add("active");
                                 </script>';
                   break;
               
                   case "IMPORTANT":
                       echo ' <script>
                                    var viewId = "important";
                                     document.getElementById("emailDetails").innerHTML = "";
                                     document.querySelectorAll(".view").forEach(v => v.classList.remove("active"));
                                     document.getElementById(viewId).classList.add("active");
                                    </script>';
                   break;
               
                   case "SPAM":
                       echo ' <script>
                                       var viewId = "spam";
                                        document.getElementById("emailDetails").innerHTML = "";
                                        document.querySelectorAll(".view").forEach(v => v.classList.remove("active"));
                                        document.getElementById(viewId).classList.add("active");
                                       </script>';
                   break;
               
                   case "TRASH":
                       echo ' <script>
                                          var viewId = "trash";
                                           document.getElementById("emailDetails").innerHTML = "";
                                           document.querySelectorAll(".view").forEach(v => v.classList.remove("active"));
                                           document.getElementById(viewId).classList.add("active");
                                          </script>';
                   break;
               
                   case "CALENDAR":
                       echo ' <script>
                                             var viewId = "calendar";
                                              document.getElementById("emailDetails").innerHTML = "";
                                              document.querySelectorAll(".view").forEach(v => v.classList.remove("active"));
                                              document.getElementById(viewId).classList.add("active");
                                             </script>';
                   break;
               
                   case "SETTINGS":
                       echo ' <script>
                                                var viewId = "settings";
                                                 document.getElementById("emailDetails").innerHTML = "";
                                                 document.querySelectorAll(".view").forEach(v => v.classList.remove("active"));
                                                 document.getElementById(viewId).classList.add("active");
                                                </script>';
                       break;
               
                   case "ToolsViewClose":
                       echo "<script type='text/javascript'>
                                                   document.getElementById('tools').style.display = 'none';
                                                   alert('Clicked');
                                                   </script>";
                   break;
               }
               function loadMessageView($view, $messageId)
               {
                   echo ' <script>
                                var viewId = "message_view";
                    document.querySelectorAll(".view").forEach(v => v.classList.remove("active"));
                    document.getElementById(viewId).classList.add("active");
                    </script>';
                   $username = $_SESSION["username"];
                   $password = $_SESSION["password"];
                   global $mailbox;
                   $mailbox = imap_open(
                       "{mail.skyblue.co.in:993/imap/ssl/novalidate-cert}$view",
                       $username,
                       $password
                   );
                   if (!$mailbox) {
                       echo "Failed to connect to IMAP server.";
                       exit();
                   }
                   global $subject, $from, $subject, $date, $to;
                   $header = imap_headerinfo($mailbox, $messageId);
                   $from = $header->fromaddress;
                   $to = $header->toaddress;
                   $subject = $header->subject;
                   $date = $header->date;
               }
               ?>
            <a>
               <div class="container2" id="backBtn" onclick="goBack('home')">
                  <div >
                     <img class="back-image" src="https://mail.skyblue.co.in/assets/img/back.png" alt="Back Image">
                  </div>
            <a class="back-text">
            <div class="dd">Back</div>
            </a>
            <div class="container3">
            </div>
            <div class="three-dot"> 
            <div class="icon-container">
            <div class="icon tooltip" id="mViewReply"><i class="fas fa-reply"></i><span class="tooltiptext">Reply</span></div>
            <div class="icon tooltip" id="mViewForward"><i class="fas fa-share"></i><span class="tooltiptext">Forward</span></div>
            <div class="icon tooltip" id="mViewUnread"><i class="fas fa-envelope"></i><span class="tooltiptext">Email</span></div>
            <div class="icon tooltip" id="mViewSpam"><i class="fas fa-exclamation-triangle"></i><span class="tooltiptext">Report Spam</span></div>
            <div class="icon tooltip" id="mViewDelete"><i class="fas fa-trash"></i><span class="tooltiptext">Delete</span></div>
            <div class="icon tooltip" id="moreIcon"><i class="fas fa-ellipsis-v"></i><span class="tooltiptext">More</span></div>
            <div class="dropdown-card" id="dropdownCard">
            <div class="dropdown-item" onclick="alert('Raw message shown')">View Raw Message</div>
            <div class="dropdown-item" onclick="window.open('https://example.com', '_blank')">Open in New Tab</div>
            </div>
            </div>
            </div>
            <script>
               const moreIcon = document.getElementById("moreIcon");
               const dropdown = document.getElementById("dropdownCard");
               const mViewDeleteMessage = document.getElementById("mViewDelete");
               const mViewMarkSpam = document.getElementById("mViewSpam");
               const mViewMarkUnread = document.getElementById("mViewUnread");
               
               const mViewForward = document.getElementById("mViewForward");
               const mViewReply = document.getElementById("mViewReply");
               
               moreIcon.addEventListener("click", (e) => {
                 e.stopPropagation();
                 dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
               });
               
               document.body.addEventListener("click", () => {
                 dropdown.style.display = "none";
               });
               
               mViewDeleteMessage.addEventListener("click", (e) => {
                 e.stopPropagation();
                alert("clicked del");
               });
               
               mViewMarkSpam.addEventListener("click", (e) => {
                 e.stopPropagation(); 
                alert("clicked mark spam");
               });
               
               mViewMarkUnread.addEventListener("click", (e) => {
                 e.stopPropagation(); 
                alert("clicked unread");
               });
               
               mViewForward.addEventListener("click", (e) => {
                 e.stopPropagation(); 
                alert("clicked forward");
               });
               
               mViewReply.addEventListener("click", (e) => {
                 e.stopPropagation(); 
                alert("clicked reply");
               });
            </script>
            </div>
            <div class="container4">
               <?php
                  $decoded_subject = imap_utf8($subject);
                  echo htmlspecialchars($decoded_subject);
                  ?>
            </div>
            <div class="container5">
               <div class="circle" style="width: 50px; height: 50px;">
                  <?php echo strtoupper(substr($from, 0, 1)); ?>
               </div>
               <div class="container" style="margin:0px;">
                  <div class="row">
                     <div class="col">
                        <div class="view-from-name">
                           <?php echo $from; ?> 
                        </div>
                        <?php
                           preg_match("/<(.+)>/", $from, $matches);
                           $fromEmail = $matches[1] ?? $fromFull;
                           if ($view === "INBOX") {
                               echo "From: " . $fromEmail;
                           }
                           if ($view === "Sent") {
                               echo "To: " . $to;
                           }
                           ?> 
                     </div>
                     <div class="col">
                        <div class="view-date"> 
                           <?php
                              $mDate = new DateTime($date);
                              echo $mDate->format("F j, Y");
                              ?>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="container6">
               <?php
                  $structure = imap_fetchstructure($mailbox, $messageId);
                  function getContentType($structure)
                  {
                      $primaryTypes = [
                          "TEXT",
                          "MULTIPART",
                          "MESSAGE",
                          "APPLICATION",
                          "AUDIO",
                          "IMAGE",
                          "VIDEO",
                          "OTHER",
                      ];
                      if (isset($primaryTypes[$structure->type])) {
                          return $primaryTypes[$structure->type] .
                              "/" .
                              $structure->subtype;
                      }
                      return "UNKNOWN";
                  }
                  $contentType = getContentType($structure);
                  $structure = imap_fetchstructure($mailbox, $messageId);
                  $body = imap_fetchbody($mailbox, $messageId, 1);
                  switch ($contentType) {
                      case "TEXT/PLAIN":
                          $structure = imap_fetchstructure($mailbox, $messageId);
                          function get_part(
                              $mailbox,
                              $messageId,
                              $structure,
                              $part_number
                          ) {
                              $data = imap_fetchbody(
                                  $mailbox,
                                  $messageId,
                                  $part_number
                              );
                              switch ($structure->encoding) {
                                  case 0:
                                      return $data;
                                  // 7BIT
                                  case 1:
                                      return imap_utf8($data);
                                  // 8BIT
                                  case 3:
                                      return base64_decode($data);
                                  case 4:
                                      return quoted_printable_decode($data);
                                  default:
                                      return $data;
                              }
                          }
                          $plain_text = "";
                          if ($structure->type == TYPEMULTIPART) {
                              foreach ($structure->parts as $part_num => $part) {
                                  if ($part->subtype == "PLAIN") {
                                      $plain_text = get_part(
                                          $mailbox,
                                          $messageId,
                                          $part,
                                          $part_num + 1
                                      );
                                      break;
                                  }
                              }
                          } elseif ($structure->subtype == "PLAIN") {
                              $plain_text = get_part(
                                  $mailbox,
                                  $messageId,
                                  $structure,
                                  1
                              );
                          }
                          echo "<pre>" . htmlspecialchars($plain_text) . "</pre>";
                  
                          break;
                      case "TEXT/HTML":
                          if ($structure->encoding == 3) {
                              $body = base64_decode($body);
                          } elseif ($structure->encoding == 4) {
                              $body = quoted_printable_decode($body);
                          }
                          echo "$body";
                          break;
                      case "MULTIPART/RELATED":
                          if (isset($structure->parts)) {
                              foreach (
                                  $structure->parts
                                  as $part_number => $part
                              ) {
                                  if (
                                      isset($part->subtype) &&
                                      strtolower($part->subtype) == "alternative"
                                  ) {
                                      if (isset($part->parts)) {
                                          foreach (
                                              $part->parts
                                              as $sub_part_number => $sub_part
                                          ) {
                                              if (
                                                  isset($sub_part->subtype) &&
                                                  strtolower($sub_part->subtype) ==
                                                      "plain"
                                              ) {
                                                  $body = imap_fetchbody(
                                                      $mailbox,
                                                      $messageId,
                                                      $part_number +
                                                          1 .
                                                          "." .
                                                          ($sub_part_number + 1)
                                                  );
                                                  if ($sub_part->encoding == 3) {
                                                      $body = base64_decode($body);
                                                  } elseif (
                                                      $sub_part->encoding == 4
                                                  ) {
                                                      $body = quoted_printable_decode(
                                                          $body
                                                      );
                                                  }
                                                  $plainText .= $body;
                                              } elseif (
                                                  isset($sub_part->subtype) &&
                                                  strtolower($sub_part->subtype) ==
                                                      "html"
                                              ) {
                                                  $body = imap_fetchbody(
                                                      $mailbox,
                                                      $messageId,
                                                      $part_number +
                                                          1 .
                                                          "." .
                                                          ($sub_part_number + 1)
                                                  );
                                                  if ($sub_part->encoding == 3) {
                                                      $body = base64_decode($body);
                                                  } elseif (
                                                      $sub_part->encoding == 4
                                                  ) {
                                                      $body = quoted_printable_decode(
                                                          $body
                                                      );
                                                  }
                                                  $htmlContent .= $body;
                                              }
                                          }
                                      }
                                  } elseif (
                                      isset($part->subtype) &&
                                      strtolower($part->subtype) == "png"
                                  ) {
                                      if (isset($part->id)) {
                                          $cid = trim($part->id, "<>");
                                          $body = imap_fetchbody(
                                              $mailbox,
                  
                                              $messageId,
                                              $part_number + 1
                                          );
                                          if ($part->encoding == 3) {
                                              $body = base64_decode($body);
                                          } elseif ($part->encoding == 4) {
                                              $body = quoted_printable_decode(
                                                  $body
                                              );
                                          }
                                          $imagePath =
                                              "/var/www/skyblue.co.in/data/images/" .
                                              uniqid("img_", true) .
                                              "." .
                                              get_image_extension($part->subtype);
                                          file_put_contents($imagePath, $body);
                                          $attachments[$cid] = $imagePath;
                                      }
                                  }
                              }
                          }
                          foreach ($attachments as $cid => $imagePath) {
                              $fileName = basename($imagePath);
                              $file =
                                  "https://mail.skyblue.co.in/data/images/" .
                                  $fileName;
                              $htmlContent = str_replace(
                                  "cid:" . $cid,
                                  $file,
                                  $htmlContent
                              );
                          }
                          echo $htmlContent;
                          break;
                      case "MULTIPART/MIXED":
                          ini_set("xdebug.var_display_max_children", "-1");
                          ini_set("xdebug.var_display_max_data", "-1");
                          ini_set("xdebug.var_display_max_depth", "-1");
                          if (isset($structure->parts)) {
                              foreach (
                                  $structure->parts
                                  as $part_number => $part
                              ) {
                                  if (
                                      isset($part->subtype) &&
                                      strtolower($part->subtype) == "alternative"
                                  ) {
                                      if (isset($part->parts)) {
                                          foreach (
                                              $part->parts
                                              as $sub_part_number => $sub_part
                                          ) {
                                              $body = imap_fetchbody(
                                                  $mailbox,
                                                  $messageId,
                                                  $part_number +
                                                      1 .
                                                      "." .
                                                      ($sub_part_number + 1)
                                              ); // Check plain text and html available.
                                              if (
                                                  isset($sub_part->subtype) &&
                                                  strtolower($sub_part->subtype) ==
                                                      "plain"
                                              ) {
                                                  if ($sub_part->encoding == 3) {
                                                      $body = base64_decode($body);
                                                  } elseif (
                                                      $sub_part->encoding == 4
                                                  ) {
                                                      $body = quoted_printable_decode(
                                                          $body
                                                      );
                                                  }
                                                  $plainText .= $body;
                                              } elseif (
                                                  isset($sub_part->subtype) &&
                                                  strtolower($sub_part->subtype) ==
                                                      "html"
                                              ) {
                                                  $body = imap_fetchbody(
                                                      $mailbox,
                                                      $messageId,
                                                      $part_number +
                                                          1 .
                                                          "." .
                                                          ($sub_part_number + 1)
                                                  );
                                                  if ($sub_part->encoding == 3) {
                                                      $body = base64_decode($body);
                                                  } elseif (
                                                      $sub_part->encoding == 4
                                                  ) {
                                                      $body = quoted_printable_decode(
                                                          $body
                                                      );
                                                  }
                                               //    $htmlContent .= $body;
                  
                                               /*
                  
                                               Solution 1 : Working body color issue
                  
                                               $dom = new DOMDocument();
                                               @$dom->loadHTML($body); // suppress warnings
                                               $bodyContent = '';
                  
                                               foreach ($dom->getElementsByTagName('body')->item(0)->childNodes as $node) {
                                                            $bodyContent .= $dom->saveHTML($node);
                                               }
                  
                                               $htmlContent .= $bodyContent;
                                               */
                  
                                               // Solution 1 : Working body color issue
                                               $body = preg_replace('/<body[^>]*>/', '', $body);    // remove opening <body>
                                               $body = str_replace('</body>', '', $body);   // remove closing </body>
                                               $htmlContent .= $body;
                                               //-----------
                  
                                                  echo $htmlContent . "";
                                                  echo '<div class="line"></div>';
                                                  echo '<div style="color: black; padding-top:10px;">';
                                                  echo "<strong>";
                                                  echo "Attachments";
                                                  echo "</strong>";
                                                  echo "</div>";
                                              }
                                          }
                                      }
                                  }
                              }
                          }
                          $attachments = getAttachments(
                              $mailbox,
                              $messageId,
                              $structure
                          );
                          foreach ($attachments as $file) {
                              // Option 2: Display inline if image
                              $filename = mb_decode_mimeheader($file["filename"]);
                              file_put_contents(
                                  "/var/www/skyblue.co.in/data/images/" .
                                      $filename,
                                  $file["data"]
                              );
                              $ext = pathinfo(
                                  $file["filename"],
                                  PATHINFO_EXTENSION
                              );
                              $base64 = base64_encode($file["data"]);
                              echo '<div class="flex-container">';
                              echo '<div class="flex-item">';
                              echo "<div>";
                              $filename = mb_decode_mimeheader($file["filename"]);
                              $dotPosition = strrpos($filename, ".");
                              $extension = substr($filename, $dotPosition + 1); // if (in_array(strtolower($ext), ['png', 'jpg', 'jpeg', 'gif', 'pdf'])) {
                              if (
                                  ($extension == "jpg") |
                                  ($extension == "jpeg") |
                                  ($extension == "png")
                              ) {
                                  echo "<img src='data:image/{$ext};base64,{$base64}' class='image-thumbnail'>";
                                  $prasanth = $filename;
                                  echo "<a href='#' class='downloadFile' data-id='$prasanth' style=' text-decoration: none; color: black;'>";
                                  echo "<div class='image-text' >Download </div>";
                                  echo "</a>";
                              }
                              if ($extension == "pdf") {
                                  echo "<img src='/assets/img/pdf1.png' class='image-thumbnail'>";
                                  $prasanth = $filename;
                                  echo "<a href='#' class='downloadFile' data-id='$prasanth' style=' text-decoration: none; color: black;'>";
                                  echo "<div class='image-text' >Download </div>";
                                  echo "</a>";
                              }
                              echo "<p class='file-name'> $filename</p>";
                              echo "</div>";
                              echo "</div>";
                              echo "</div>";
                          }
                          break;
                      case "MULTIPART/ALTERNATIVE":
                          $structure = imap_fetchstructure($mailbox, $messageId);
                          function get_part(
                              $mailbox,
                              $messageId,
                              $structure,
                              $part_number
                          ) {
                              $data = imap_fetchbody(
                                  $mailbox,
                                  $messageId,
                                  $part_number
                              );
                              switch ($structure->encoding) {
                                  case 0:
                                      return $data;
                                  // 7BIT
                                  case 1:
                                      return imap_utf8($data);
                                  // 8BIT
                                  case 3:
                                      return base64_decode($data);
                                  case 4:
                                      return quoted_printable_decode($data);
                                  default:
                                      return $data;
                              }
                          }
                          if ($structure->type == TYPEMULTIPART) {
                              foreach ($structure->parts as $part_num => $part) {
                                  if ($part->subtype == "HTML") {
                                      $html = get_part(
                                          $mailbox,
                                          $messageId,
                                          $part,
                                          $part_num + 1
                                      );
                                      echo "\n$html\n";
                                  }
                              }
                          }
                          break;
                      case "MULTIPART/REPORT":
                          $structure = imap_fetchstructure($mailbox, $messageId);
                          function get_part(
                              $mailbox,
                              $messageId,
                              $part,
                              $part_number
                          ) {
                              $body = imap_fetchbody(
                                  $mailbox,
                                  $messageId,
                                  $part_number
                              );
                              switch ($part->encoding) {
                                  case 0:
                                      return $body;
                                  // 7BIT
                                  case 1:
                                      return imap_utf8($body);
                                  // 8BIT
                                  case 3:
                                      return base64_decode($body);
                                  case 4:
                                      return quoted_printable_decode($body);
                                  default:
                                      return $body;
                              }
                          }
                          $plain_text = "";
                          if (
                              $structure->type == TYPEMULTIPART &&
                              strtolower($structure->subtype) === "report"
                          ) {
                              foreach ($structure->parts as $index => $part) {
                                  if (
                                      $part->type == TYPETEXT &&
                                      strtolower($part->subtype) === "plain"
                                  ) {
                                      $plain_text = get_part(
                                          $mailbox,
                                          $messageId,
                                          $part,
                                          $index + 1
                                      );
                                      break;
                                  }
                              }
                          }
                          echo "<pre>" . htmlspecialchars($plain_text) . "</pre>";
                          break;
                      default:
                          echo "<script type='text/javascript'>alert('default');</script>";
                          break;
                  }
                  function get_image_extension($mime_type)
                  {
                      $ext = "";
                      switch (strtolower($mime_type)) {
                          case "jpeg":
                          case "jpg":
                              $ext = "jpg";
                              break;
                          case "png":
                              $ext = "png";
                              break;
                          case "gif":
                              $ext = "gif";
                              break;
                      }
                      return $ext;
                  }
                  function decodeAttachment(
                      $stream,
                      $msgNumber,
                      $part,
                      $partNumber
                  ) {
                      $data = imap_fetchbody($stream, $msgNumber, $partNumber);
                      switch ($part->encoding) {
                          case 0:
                              return $data;
                          case 1:
                              return imap_8bit($data);
                          case 2:
                              return imap_binary($data);
                          case 3:
                              return base64_decode($data);
                          case 4:
                              return quoted_printable_decode($data);
                          default:
                              return $data;
                      }
                  }
                  function getAttachments(
                      $stream,
                      $msgNumber,
                      $structure,
                      $prefix = ""
                  ) {
                      $attachments = [];
                      if (isset($structure->parts)) {
                          foreach ($structure->parts as $index => $part) {
                              $partNumber =
                                  $prefix === ""
                                      ? $index + 1
                                      : "$prefix." . ($index + 1); // If it's multipart itself, go deeper
                              if ($part->type == 1 && isset($part->parts)) {
                                  $attachments = array_merge(
                                      $attachments,
                                      getAttachments(
                                          $stream,
                                          $msgNumber,
                                          $part,
                                          $partNumber
                                      )
                                  );
                              } // If it's a file (attachment or inline file)
                              if (
                                  $part->type == 5 ||
                                  ($part->ifdisposition &&
                                      in_array(strtolower($part->disposition), [
                                          "attachment",
                                          "inline",
                                      ]))
                              ) {
                                  $filename = null;
                                  if ($part->ifdparameters) {
                                      foreach ($part->dparameters as $param) {
                                          if (
                                              strtolower($param->attribute) ==
                                              "filename"
                                          ) {
                                              $filename = $param->value;
                                              break;
                                          }
                                      }
                                  }
                                  if (!$filename && $part->ifparameters) {
                                      foreach ($part->parameters as $param) {
                                          if (
                                              strtolower($param->attribute) ==
                                              "name"
                                          ) {
                                              $filename = $param->value;
                                              break;
                                          }
                                      }
                                  }
                                  $filename =
                                      $filename ?: "unknown_" . $partNumber;
                                  $content = decodeAttachment(
                                      $stream,
                                      $msgNumber,
                                      $part,
                                      $partNumber
                                  );
                                  $attachments[] = [
                                      "filename" => $filename,
                                      "data" => $content,
                                      "mime" => "application/octet-stream", // Could improve based on subtype
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
         
         function goBack(viewId) {
            document.getElementById("emailDetails").innerHTML = "";
            document.querySelectorAll('.view').forEach(v => v.classList.remove('active'));
            document.getElementById(viewId).classList.add('active');
         
            history.replaceState(null, '', '/pages/dashboard/index.php?action=INBOX');
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
               link.href = "https://mail.skyblue.co.in/data/images/" + downloadFileName;
               document.body.appendChild(link);
               link.click();
               document.body.removeChild(link);
            });
         });
         
         
      </script>
   </body>
</html>
