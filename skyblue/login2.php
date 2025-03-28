<?php

 include 'connect.php';
 include 'functions.php';
 
 if(isset($_POST['mobile']) && isset($_POST['token'])){
       $mobile = $_POST['mobile'];
       $token =  $_POST['token'];
      
       $query    = "SELECT mobile_no_full FROM users WHERE mobile_no_full = ?";
       
       $data=array();
       
        if($stmt = $con->prepare($query)){
        $stmt->bind_param("s",$mobile);
        $stmt->execute();
        $stmt->bind_result($mobile);
        if($stmt->fetch()){
            
                    tokenRegister();
        }else{
                // Account not found!
                 $data = array();
                 array_push($data, array("message"=>"2" , "id"=>"0" , "name"=>"0" , "profile_url"=>"0" , "cover_url"=>"0" , "token"=>"0" , "gender"=>"0" , "dob"=>"0"));
                 header('Content-Type:Application/json');
                 print(json_encode(($data)));
        }
         $stmt->close();
    }
 }else{
                // Empty field
                 $data = array();
                 array_push($data, array("message"=>"3" , "id"=>"0" , "name"=>"0" , "profile_url"=>"0" , "cover_url"=>"0" , "token"=>"0" , "gender"=>"0" , "dob"=>"0"));
                 header('Content-Type:Application/json');
                 print(json_encode(($data)));
}

 
  function tokenRegister()
      {
          
          include 'connect.php'; 
          
              global $mobile;
              global $token;

             $Sql_Query = "UPDATE users SET users.token = '$token' WHERE mobile_no_full = '$mobile'";
 
                        if(mysqli_query($con,$Sql_Query))
                          {
                                 getUserData();
                          }
                                else
                                   {
                                         echo '0';
                                   } 
          
      }
      
      
      function getUserData()
      {
            include 'connect.php'; 
          
          global $mobile;


          $Sql_Query = "SELECT id , name , profile_url , cover_picture_url , token , gender , dob FROM users WHERE mobile_no_full = '$mobile' ";
          $result=mysqli_query($con,$Sql_Query);
       
          $data=array();

          while($row=mysqli_fetch_assoc($result))
            {
                
              array_push($data, array("message"=>"4" , "user_id"=>$row["id"] , "user_name"=>$row["name"] , "profile_url"=>$row["profile_url"] , "cover_url"=>$row["cover_picture_url"] , "token"=>$row["token"] , "gender"=>$row["gender"] , "dob"=>$row["dob"]));

            }
 
                     header('Content-Type:Application/json');
                     print(json_encode(($data)));
      }
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
//       <?php
 
//  $response = array();
//   include 'connect.php';
 
//  include 'functions.php';

//  //Get the input request parameters
//  $inputJSON = file_get_contents('php://input');
//  $input = json_decode($inputJSON, TRUE); //convert JSON into array

//  //Check for Mandatory parameters
//  if(isset($_POST['mobile']) && isset($_POST['password'])){
//     $mobile = $_POST['mobile'];
//     $token = $_POST['token'];
//     $password = $_POST['password'];
//     $query    = "SELECT name , password_hash , salt FROM users WHERE mobile = ?";

//     if($stmt = $con->prepare($query)){
//         $stmt->bind_param("s",$mobile);
//         $stmt->execute();
//         $stmt->bind_result($name,$passwordHashDB,$salt);
//         if($stmt->fetch()){
//             //Validate the password
//             if(password_verify(concatPasswordWithSalt($password,$salt),$passwordHashDB)){
                
//                 // $response["status"] = 0;
//                 // $response["message"] = "Login successful";
//                 // $response["mobile"] = $mobile;
                
//                 tokenRegister();
                
//             }
//             else{
//                  $data = array();
//                  array_push($data, array("message"=>"1" , "id"=>"0" , "name"=>"0" , "profile_url"=>"0" , "cover_url"=>"0" , "token"=>"0" , "gender"=>"0" , "dob"=>"0"));
                 
//                  header('Content-Type:Application/json');
//                  print(json_encode(array("status"=>"true" , "data" =>$data))); 
              
//                 //echo "1";
//                 // $response["status"] = 1;
//                 // $response["message"] = "Check Username And Password!";
//             }
//         }
//         else{
//                  $data = array();
//                  array_push($data, array("message"=>"2" , "id"=>"0" , "name"=>"0" , "profile_url"=>"0" , "cover_url"=>"0" , "token"=>"0" , "gender"=>"0" , "dob"=>"0"));

                 
//                  header('Content-Type:Application/json');
//                  print(json_encode(array("status"=>"true" , "data" =>$data))); 
//             // echo "2";
// //          $response["status"] = 1;
// //          $response["message"] = "Account not found!";
//         }
        
//         $stmt->close();
//     }
// }
// else{
//      $data = array();
//                  array_push($data, array("message"=>"3" , "id"=>"0" , "name"=>"0" , "profile_url"=>"0" , "cover_url"=>"0" , "token"=>"0" , "gender"=>"0" , "dob"=>"0"));
                 
//                  header('Content-Type:Application/json');
//                  print(json_encode(array("status"=>"true" , "data" =>$data))); 
//     // echo "3";
// //  $response["status"] = 2;
// //  $response["message"] = "Please Enter Empty Field";
// }


//   function tokenRegister()
//       {
          
//           include 'connect.php'; 
          
//               global $mobile;
//               global $token;

//              $Sql_Query = "UPDATE users SET users.token = '$token' WHERE mobile = '$mobile'";
 
//                         if(mysqli_query($con,$Sql_Query))
//                           {
 
//                                  //echo '1';
//                                  getUserData();
 
//                           }
//                                 else
//                                   {
 
//                                          echo '0';
 
//                                   } 
          
//       }
      
      
//       function getUserData()
//       {
//             include 'connect.php'; 
          
//           global $mobile;


//           $Sql_Query = "SELECT id , name , profile_url , cover_picture_url , token , gender , dob FROM users WHERE mobile = '$mobile' ";
//           $result=mysqli_query($con,$Sql_Query);
       
//           $data=array();

//           while($row=mysqli_fetch_assoc($result))
//             {
                
//               array_push($data, array("message"=>"4" , "id"=>$row["id"] , "name"=>$row["name"] , "profile_url"=>$row["profile_url"] , "cover_url"=>$row["cover_picture_url"] , "token"=>$row["token"] , "gender"=>$row["gender"] , "dob"=>$row["dob"]));

//             }
 
//       header('Content-Type:Application/json');

//       print(json_encode(array("status"=>"true" , "data" =>$data))); 
//       }


?>