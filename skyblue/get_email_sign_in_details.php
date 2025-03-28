<?php

 include 'connect.php';
 
 $email = $_POST['email'];
 
   $Sql_Query = "SELECT id , mobile_no_full, name , profile_url , cover_picture_url , token , gender , dob FROM users WHERE email = '$email' ";
          $result=mysqli_query($con,$Sql_Query);
       
          $data=array();

          while($row=mysqli_fetch_assoc($result))
            {
                
              array_push($data, array("message"=>"4" , "user_id"=>$row["id"] , "mobile_no_full"=>$row["mobile_no_full"] , "user_name"=>$row["name"] , "profile_url"=>$row["profile_url"] , "cover_url"=>$row["cover_picture_url"] , "token"=>$row["token"] , "gender"=>$row["gender"] , "dob"=>$row["dob"]));

            }
 
                     header('Content-Type:Application/json');
                     print(json_encode(($data)));
 
 ?>