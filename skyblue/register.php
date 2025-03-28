<?php
$response = array();
include 'connect.php';
include 'functions.php';

     $mobile = $_POST['mobile'];
    
     $password = $_POST['password'];
	 $name = $_POST['name'];
	 $mobile_with_plus = $_POST['mobile_with_plus'];
	 $country = $_POST['country'];
	 $country_name_code = $_POST['country_name_code'];
	 $phone_code = $_POST['phone_code'];
	 $dob = $_POST['dob'];
	 
	 $gender = $_POST['gender'];
	 $gender_id = $_POST['gender_id'];
	 $date = $_POST['date'];
	 $time = $_POST['time'];
	 $time_zone = $_POST['time_zone'];
	 $date_time_zone = $_POST['date_time_zone'];
	 $token = $_POST['token'];
   
   //  $mobile = "+911234567890";
  //Get a unique Salt
	  $salt         = getSalt();
		
	  //Generate a unique password Hash
	  $passwordHash = password_hash(concatPasswordWithSalt($password,$salt),PASSWORD_DEFAULT);
 
 
  $Sql_Query = "INSERT INTO users (mobile, 
                                   name, 
                                   password_hash, 
                                   salt , 
                                   mobile_no_full , 
                                   country,
                                   country_name_code , 
                                   phone_code , 
                                   dob , 
                                   gender , 
                                   joined_date , 
                                   joined_time , 
                                   joined_time_zone , 
                                   joined_date_time_zone , 
                                   token)VALUES ('$mobile' , 
                                                 '$name' , 
                                                 '$passwordHash' , 
                                                 '$salt' , 
                                                 '$mobile_with_plus' , 
                                                 '$country', 
                                                 '$country_name_code' , 
                                                 '$phone_code' , 
                                                 '$dob' , 
                                                 '$gender' , 
                                                 '$date' , 
                                                 '$time' , 
                                                 '$time_zone' , 
                                                 '$date_time_zone' , 
                                                 '$token')";
 
 if(mysqli_query($con,$Sql_Query))
     {
 
       //   echo '1'; // success
       getUserId();
 
     }
         else
                 {
 
                    echo '0';
 
                 }
                 
                 
                 
     function getUserId()
      {
          
          include 'connect.php'; 
          
          global $mobile;
                 
          //$mobile = $_POST['mobile'];


          $Sql_Query = "SELECT id FROM users WHERE mobile = '$mobile' ";
          $result=mysqli_query($con,$Sql_Query);
       
          $data=array();

          while($row=mysqli_fetch_assoc($result))
            {
                
               array_push($data, array("user_id"=>$row["id"]));

            }
 
       header('Content-Type:Application/json');

    //   print(json_encode(array("status"=>"true" , "data" =>$data)));
    print(json_encode(($data)));
      }
?>