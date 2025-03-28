<?php
session_start();


// include "s_connect.php";
$con = mysqli_connect('localhost' , 'root', 'prasanth' , 'skyblue_workplace');
include "functions.php";

// Get the raw POST data 
$json = file_get_contents('php://input');

// Decode the JSON data into a PHP array
$data = json_decode($json, true);

if (json_last_error() === JSON_ERROR_NONE) {
    $access = htmlspecialchars($data['acc']);
}

switch ($access) {


    case "mail_check_user":
        array_push($data, array("access auth"=>"true" , "status"=>"2" , "message"=>"OTP Send Failure. Try again"));
        header("Content-Type:Application/json");
        print json_encode($data);
        break;

    case "cr_master_signup":
        
        $name = htmlspecialchars($data['name']);
        $email = htmlspecialchars($data['email']);
        $mobile = htmlspecialchars($data['mobile']);
        $password = htmlspecialchars($data['password']);
        $userTimeZone = htmlspecialchars($data['timeZone']);
        $clientIp = htmlspecialchars($data['ip']);
        $dateTime = htmlspecialchars($data['date_time']);
        $data = [];
        
        // Check user already exists
        $sql = "SELECT * FROM master_users WHERE email = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $email); // "s" means the database expects a string
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if any rows were returned
        if ($result->num_rows > 0) {
            array_push($data, array("access auth"=>"true" , "status"=>"2" , "message"=>"User already exists!. Please login" ));
            header("Content-Type:Application/json");
            print json_encode($data);
           return;
        } 

        // OTP 
        if(sendOTP($email, $userTimeZone, $clientIp, $dateTime, $name, $password)){
            // success. otp send
            array_push($data, array("access auth"=>"true" , "status"=>"1" , "message"=>"OTP Send Success"));
            header("Content-Type:Application/json");
            print json_encode($data);
        }else{
            // failure. otp send 
            array_push($data, array("access auth"=>"true" , "status"=>"2" , "message"=>"OTP Send Failure. Try again"));
            header("Content-Type:Application/json");
            print json_encode($data);
        }
        /*

        $response = [];
        
        //Get a unique Salt
        $salt = getSalt();
        
        //Generate a unique password Hash
        $passwordHash = password_hash(concatPasswordWithSalt($mPassword, $salt),PASSWORD_DEFAULT);
                
        $Query = "INSERT INTO master_users (name, email, phone_no , password_hash, salt, user_timezone) 
                             VALUES ('$name' , '$email', '$mobile' , '$passwordHash' , '$salt' , '$userTimeZone')";
        if (mysqli_query($con, $Query)) {
           
            //  Success. Get newely created user id and details
            
             $Sql_Query = "SELECT id FROM master_users WHERE phone_no = '$mobile' ";
             $result = mysqli_query($con, $Sql_Query);
             
              while ($row = mysqli_fetch_assoc($result)) {
                  array_push($data, array("access auth"=>"true" , "status"=>"1" , "message"=>"Success" ,  "user_id" => $row["id"]));
              }
              header("Content-Type:Application/json");
              print json_encode($data);
              
        }else{
            // Failed
             array_push($data, ["status" => false]);
             header("Content-Type:Application/json");
             print json_encode($data);
        }
             */
        
        break;

        case "verify_otp":
            // include "s_connect.php";
            $con = mysqli_connect('localhost' , 'root', 'prasanth' , 'skyblue_workplace');

            $email = htmlspecialchars($data['email']);
            $otp = htmlspecialchars($data['otp']);

            $sql = "SELECT email, otp FROM otp_main WHERE email = ? AND otp = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("ss", $email, $otp); // "s" means the database expects a string
            $stmt->execute();
           $result = $stmt->get_result();
    if ($result->num_rows > 0) {


        // save user registration data to master_user table.
        $Query = "INSERT INTO master_users (name, email, password_hash, registration_ip, time_zone, server_date_time, device_date_time) SELECT name, email, password, client_ip, time_zone, server_date_time, device_date_time FROM otp_history WHERE email = '$email' AND otp = '$otp'" ;
        if (mysqli_query($con, $Query)) {
            // success
            array_push($data, array("access auth"=>"true" , "status"=>"1" , "message"=>"OTP verification Success"));
            header("Content-Type:Application/json");
            print json_encode($data);
        }else{
            // failure
            array_push($data, array("access auth"=>"true" , "status"=>"3" , "message"=>"Unable to create account. try again!"));
            header("Content-Type:Application/json");
            print json_encode($data);
        }
        //------------------------------------------------------
       
    }else{
        array_push($data, array("access auth"=>"true" , "status"=>"2" , "message"=>"Wrong OTP!".$otp.$email));
        header("Content-Type:Application/json");
        print json_encode($data);
    }
         
            break;

            case 'wrk_sign':
                // array_push($data, array("access auth"=>"true" , "status"=>"2" , "message"=>"Work progress. try again later!" ));
                // header("Content-Type:Application/json");
                // print json_encode($data);

            //    include "s_connect.php";
            $con = mysqli_connect('localhost' , 'root', 'prasanth' , 'skyblue_workplace');

                $email = htmlspecialchars($data['email']);
                $password = htmlspecialchars($data['password']);

                $sql = "SELECT email FROM master_users WHERE email = ?";
                $stmt = $con->prepare($sql);
                $stmt->bind_param("s", $email); // "s" means the database expects a string
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                  // Account found Check email and password
                  $sql = "SELECT email, password_hash FROM master_users WHERE email = ? AND password_hash = ?";
                  $stmt = $con->prepare($sql);
                  $stmt->bind_param("ss", $email, $password); // "s" means the database expects a string
                  $stmt->execute();
                 $result = $stmt->get_result();
          if ($result->num_rows > 0) {
            // $_SESSION['email'] = $email;

            // if (isset($_SESSION["email"])) {
            //     header("Location: terms_conditions.html");
            //     exit();
            // }

            $_SESSION["email"] = $email;

          //  echo $email;;

              array_push($data, array("access auth"=>"true" , "status"=>"1" , "message"=>"Success" ));
              header("Content-Type:Application/json");
              print json_encode($data);

 
          }else{
              array_push($data, array("access auth"=>"true" , "status"=>"2" , "message"=>"Check email and password" ));
              header("Content-Type:Application/json");
              print json_encode($data);
          }

                }else{
                    array_push($data, array("access auth"=>"true" , "status"=>"2" , "message"=>"Account not found!" ));
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

function sendOTP($email, $userTimeZone, $clientIp, $dateTime, $name, $password){
    $to = $email;
    $subject = "Email Verification Code";

    $OTP_CODE = generate4digitOTP();
    $verification_code = $OTP_CODE; // This would typically be dynamically generated

    // HTML content for the email
    $message = "
<html>
<head>
    <title>Email Verification</title>
</head>
<body>
    <h2>Welcome to Our Service!</h2>
    <p>Thank you for signing up. Please verify your email address by entering the code below:</p>
    <h3><strong>Your Verification Code:</strong></h3>
    <p style='font-size: 24px; font-weight: bold; color: #4CAF50;'>$verification_code</p>
    <p>If you didn't request this, please ignore this email.</p>
    <p>Thank you!</p>
</body>
</html>
";

    // To send HTML mail, the Content-type header must be set
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
    $headers .= "From: skyblue-verification@skyblue.co.in" . "\r\n";

    // Send email
    if(mail($to, $subject, $message, $headers)) {
        $type = "1"; // 1 for signup verification
        $type_name = "Signup verification";
        saveSendOtpHistory($email, $verification_code, $type, $type_name, $userTimeZone, $clientIp, $dateTime, $name, $password);
        
        updateMainOtp($email, $verification_code, $dateTime);
        
        return true; // Verification email sent successfully.
    } else { 
          return false; // Failed to send verification email.
         
    }

}

function updateMainOtp($email, $verification_code, $dateTime){
    // include "s_connect.php";
    $con = mysqli_connect('localhost' , 'root', 'prasanth' , 'skyblue_workplace');

    $sql = "SELECT * FROM otp_main WHERE email = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $email); // "s" means the database expects a string
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // email already exists just update otp and time onlye

        $Query = "UPDATE otp_main SET otp = '$verification_code' WHERE email = '$email'";
        if (mysqli_query($con, $Query)) {
            // Update success. 
         }else{
            // Update failed. 
         }
    }else{

        // email new fresh insert
        $Query = "INSERT INTO otp_main (email, otp) 
        VALUES ('$email', '$verification_code')";
    if (mysqli_query($con, $Query)) {
           // Insert success. 
        }else{
           // Insert failed. 
        }
    }
}

function saveSendOtpHistory($email, $verification_code, $type, $type_name, $userTimeZone, $clientIp, $dateTime, $name, $password){
    // include "s_connect.php";
    $con = mysqli_connect('localhost' , 'root', 'prasanth' , 'skyblue_workplace');
    $serverDateTime = date("d-m-Y H:i:s");
    $Query = "INSERT INTO otp_history (email, otp, type, type_name, time_zone, client_ip, device_date_time, server_date_time, name, password) 
                             VALUES ('$email', '$verification_code', '$type', '$type_name' , '$userTimeZone', '$clientIp', '$dateTime' , '$serverDateTime' , '$name' , '$password')";
        if (mysqli_query($con, $Query)) {
            // Insert success. otp history
                             }else{
                                // Insert failed. otp history
                             }
}

function generate4digitOTP() { 
    return rand(1000, 9999);
 }

?>
