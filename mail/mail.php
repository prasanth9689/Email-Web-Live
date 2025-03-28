<?php

$con = mysqli_connect('localhost' , 'root', 'prasanth' , 'skyblue_mail');


// include "functions.php";

// Get the raw POST data 
$json = file_get_contents('php://input');

// Decode the JSON data into a PHP array
$data = json_decode($json, true);

if (json_last_error() === JSON_ERROR_NONE) {
    $access = htmlspecialchars($data['acc']);
}

switch ($access) {

    case "mail_check_user":
        $mMobile = htmlspecialchars($data['mobile']);

        $sql = "SELECT mobile FROM users WHERE mobile = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $mMobile); // "s" means the database expects a string
        $stmt->execute();
        $result = $stmt->get_result();
        // Check if any rows were returned
        if ($result->num_rows > 0) {
            array_push($data, array("access auth"=>"true" , "status"=>"2" , "message"=>"User already exists!. Please login" ));
            header("Content-Type:Application/json");
            print json_encode($data);
           return;
        } 
    
        array_push($data, array("access auth"=>"true" , "status"=>"1" , "message"=> "New user"));
        header("Content-Type:Application/json");
        print json_encode($data);
        break;

        case "user_login":
            $mUsername = htmlspecialchars($data['email']);
            $mPassword = htmlspecialchars($data['password']);

            $query    = "SELECT username, password FROM users WHERE username = ? AND password = ?";
            if($stmt = $con->prepare($query)){
                $stmt->bind_param("ss",$mUsername , $mPassword);
                $stmt->execute();
                $stmt->bind_result($mUsername, $mPassword);
                if($stmt->fetch()){
                    array_push($data, array("access auth"=>"true" , "status"=>"1" , "message"=>"Account found" ));
                    header("Content-Type:Application/json");
                    print json_encode($data);
                }else{
                        // Account not found!
                        array_push($data, array("access auth"=>"true" , "status"=>"2" , "message"=>"Check username and password!" ));
                        header("Content-Type:Application/json");
                        print json_encode($data);
                }
                 $stmt->close();
            }

            break;

        case "user_login_imap":

            // Get the raw POST data 
$json = file_get_contents('php://input');

// Decode the JSON data into a PHP array
$data = json_decode($json, true);

            $hostname = '{imap.skyblue.co.in:993/imap/ssl/novalidate-cert}INBOX';
            $username = htmlspecialchars($data['email']);
            $password = htmlspecialchars($data['password']);
        
            // Try to connect to the IMAP server
            $inbox = @imap_open($hostname, $username, $password);
        
            if ($inbox) {
          //      echo "Login successful!";
                array_push($data, array("access auth"=>"true" , "status"=>"1" , "message"=>"Login successful!" ));
                header("Content-Type:Application/json");
                print json_encode($data);
                imap_close($inbox);
            } else {
           //     echo "Login failed: " . imap_last_error();
                array_push($data, array("access auth"=>"true" , "status"=>"2" , "message"=>"Check email and password!" ));
                header("Content-Type:Application/json");
                print json_encode($data);
            }
            break;
    

    default:
        array_push($data, array("access auth"=>"true" , "status"=>"2" , "message"=>"Wrong access key" ));
        header("Content-Type:Application/json");
        print json_encode($data);
        break;     
}
?>
