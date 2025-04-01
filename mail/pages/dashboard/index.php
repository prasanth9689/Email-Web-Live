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

<!-- <script>
  		const back = document.getElementById('back');
		back.addEventListener('click', function () {
      alert("fuck");
    });

  </script> -->

  <!-- Image and text -->
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
  <!-- Sidebar -->

  <!-- Email lists -->

  <style>
 
  </style>
  <div id="home" class="view active" style="background-color: white; height: 100%; width: 100%;">
    <div class="content__email_list">


    <?php


//  $username = $_SESSION["username"];
//  $password = $_SESSION["password"];

// IMAP server details
$hostname = '{imap.skyblue.co.in:993/imap/ssl/novalidate-cert}INBOX'; // Gmail IMAP server, SSL encryption, port 993
// $username = 'test8';  // Your email address
// $password = 'test8';     // Your email password or app-specific password

$username = $_SESSION["username"];
$password = $_SESSION["password"];

// echo $username;
// echo $password;

// Open an IMAP connection
$inbox = imap_open($hostname, $username, $password) or die('Cannot connect to mailbox: ' . imap_last_error());

// Get the number of messages in the inbox
$numMessages = imap_num_msg($inbox);

// Display the email headers (Subject, From, Date)
// echo "<h1>Inbox - $numMessages messages</h1>";

// Get the list of email IDs
$email_ids = imap_search($inbox, 'ALL'); // You can modify the search criteria if needed

// If there are emails, sort them by ascending date
if ($email_ids) {
    // Sort email IDs by ascending date
    rsort($email_ids);
    

    // Fetch the emails
    foreach ($email_ids as $email_id) {
        $header = imap_headerinfo($inbox, $email_id);
        
        // Get the email details
        $subject = $header->subject;
        $from = $header->fromaddress;
        $date = $header->date;

    //    echo "<a href='#' style=' text-decoration: none; color: black;' onclick=showView('message_view')>";

    $decoded_subject0 = imap_utf8($subject);

  //   $data = [
  //     "view" => "message_view",
  //     "email_id" => $email_id , 
  //     "subject" => $decoded_subject0
  // ];

  $data = array("view"=> "message_view", "email_id"=>$email_id);

  $js_data = json_encode($data);


    echo "<a href='#' class='viewEmail' data-id='$email_id' style=' text-decoration: none; color: black;'>";




        // showView('message_view')
        // Display the email information
        // echo "<b>Subject:</b> $subject <br>";
        echo "<div class='email__start'></div>";
        echo "<p class='email__name'>";
        // echo "<b></b> $from <br>";
    
        $main = substr($from, 0, 20);
    
        echo "<b></b> $main <br>";
        
        echo "</p>";
    
        //   $decoded_subject = imap_utf8($subject);
        echo "<p class='email__content'>";

        $decoded_subject = imap_utf8($subject);
        echo "<b></b> $decoded_subject <br>";
        echo "</p>";
        // echo "<b>Date:</b> $date <br><br>";
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

                                                                                                <!-- <div class="btn-group" style="margin-left:5px;">
                                                                                          							<button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="fa fa-tags"></span> <span class="caret"></span></button>
					                                                                                                  		<ul class="dropdown-menu">
								                                                                                                         <li><a href="#">add label <span class="label label-danger"> Home</span></a></li>
								                                                                                                         <li><a href="#">add label <span class="label label-info">Job</span></a></li>
								                                                                                                         <li><a href="#">add label <span class="label label-success">Clients</span></a></li>
								                                                                                                         <li><a href="#">add label <span class="label label-warning">News</span></a></li>
							                                                                                                  </ul>
					                                                                                      </div> -->

                                                                                    </div>

                                                                                    <!-- // new  -->
                                                                                    <br>	

                                                                                            <!-- <div class="form-group">
					                                                                                            	<textarea class="form-control" id="message" name="body" rows="8" placeholder="Click here to reply"></textarea>
				                                                                                  	</div> -->

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
              <div id="emailDetails">
    <!-- Full email details will be shown here -->
                 </div>



  </div>



  <script>


document.querySelectorAll('.viewEmail').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();

            var viewId = "message_view";
        
            document.querySelectorAll('.view').forEach(v => v.classList.remove('active'));
            document.getElementById(viewId).classList.add('active');


            const messageId = this.getAttribute('data-id');

            
  // Fetch email details using AJAX (fetch API)
  fetch('view_email.php?message_id=' + messageId)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('emailDetails').innerHTML = data;
                })
                .catch(error => console.error('Error:', error));
        });
        //    alert(messageId);
     
    });


    function showView(viewId) {
      document.getElementById("emailDetails").innerHTML = "";
      document.querySelectorAll('.view').forEach(v => v.classList.remove('active'));
      document.getElementById(viewId).classList.add('active');
    }

    function showEmail(data){

        var t = JSON.parse(data);
        var viewId = t['view'];
        var emailId = t['email_id'];

      document.querySelectorAll('.view').forEach(v => v.classList.remove('active'));
      document.getElementById(viewId).classList.add('active');



   //   alert(emailId);
    }


  
		const back = document.getElementById('backBtn');
		back.addEventListener('click', function () {
      alert("fuck");
    });



    // Ensure the execCommand method is applied to the selected text or current cursor position
    document.getElementById('editor').addEventListener('input', function() {
      // This event listener ensures that input changes are immediately reflected
    });

     // Change text color based on the color picker value
     function changeTextColor() {
      const color = document.getElementById('colorPicker').value;
      document.execCommand('foreColor', false, color);
    }
  </script>


</body>

</html>