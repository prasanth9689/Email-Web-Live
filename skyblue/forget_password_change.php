<?php
$response = array();
include 'connect.php';
include 'functions.php';

 //$con = mysqli_connect($HostName,$HostUser,$HostPassword,$DatabaseName);
 
 
     $mobile = $_POST['mobile'];
    
     $password = $_POST['password'];
	 $mobile_number_only = $_POST['mobile_number_only'];
 
 
 
   //  $mobile = "+911234567890";
  //Get a unique Salt
	  $salt         = getSalt();
		
	  //Generate a unique password Hash
	  $passwordHash = password_hash(concatPasswordWithSalt($password,$salt),PASSWORD_DEFAULT);
 
 
 
  $Sql_Query = "UPDATE users SET password_hash = '$passwordHash' , salt = '$salt' WHERE mobile_number_only='$mobile_number_only'";
 
 if(mysqli_query($con,$Sql_Query))
     {
 
          echo '1';
 
     }
         else
                 {
 
                    echo '0';
 
                 }


?>