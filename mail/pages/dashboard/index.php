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
  <nav class="navbar navbar-light" style="width: 100%; position: fixed; box-shadow: 0 2px 5px 0 rgb(0 0 0 / 5%), 0 2px 10px 0 rgb(0 0 0 / 5%);">
    <a class="navbar-brand" href="#">
      <img src="/assets/mail/img/logo3.png" width="30" height="30" class="d-inline-block align-top" alt="">
      Skyblue Mail
    </a>

    <form class="form-inline my-2 my-lg-0">
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
            class="fa fas fa-chart-line fa-fw me-3"></i><span>Sent</span></a>
        <a href="#" class="list-group-item list-group-item-action py-2 ripple">
          <i class="fa fas fa-chart-pie fa-fw me-3"></i><span>Draft</span>
        </a>
        <a href="#" class="list-group-item list-group-item-action py-2 ripple"><i
            class="fa fas fa-chart-bar fa-fw me-3"></i><span>Important</span></a>
        <a href="#" class="list-group-item list-group-item-action py-2 ripple"><i
            class="fa fas fa-globe fa-fw me-3"></i><span>Spam</span></a>
        <a href="#" class="list-group-item list-group-item-action py-2 ripple"><i
            class=" fa  fas fa-building fa-fw me-3"></i><span>Trash</span></a>
        <a href="#" class="list-group-item list-group-item-action py-2 ripple"><i
            class="fa fas fa-calendar fa-fw me-3"></i><span>Calendar</span></a>
        <a href="#" class="list-group-item list-group-item-action py-2 ripple"><i
            class="fa fas fa-users fa-fw me-3"></i><span>Settings</span></a>
        <a href="#" class="list-group-item list-group-item-action py-2 ripple"><i
            class="fa fas fa-money-bill fa-fw me-3"></i><span>Logout</span></a>
      </div>
    </div>
  </nav>
  <!-- Sidebar -->

  <!-- Email lists -->

  <style>
 
  </style>
  <div id="home" class="view active" style="background-color: white; height: 100%;">
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
        background-color
      }
    </style>

  <div id="compose" class="view" style="background-color:red;">

        <div class="container-compose">

        </div>
  
  </div>

  <div id="message_view" class="view" style="background-color: white;">

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

  </script>


</body>

</html>