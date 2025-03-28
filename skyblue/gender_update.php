<?php
 
      include "connect.php";

     $user_id = $_POST['user_id'];
     $male_id = $_POST['male_id'];
     $female_id = $_POST['female_id'];
 
 
 
 $temp = "1";
 $male = "MALE";
  $female = "FEMALE";
 
 if($male_id==$temp)
 {
    //echo "Male"; 
    
  $Sql_Query = "  UPDATE users SET gender = '$male' , gender_id = '$male_id'  WHERE id = '$user_id' ";
  
    if(mysqli_query($con,$Sql_Query))
        {
 
              echo 'success';
 
       }
         else
                 {
 
                    echo 'Try agin';
 
                 }
      
 }else
 {
    // echo "Female";
    
     $Sql_Query = "  UPDATE users SET gender = '$female' , gender_id = '$female_id'  WHERE id = '$user_id' ";
 
    if(mysqli_query($con,$Sql_Query))
        {
 
              echo 'success';
 
       }
         else
                 {
 
                    echo 'Try agin';
 
                 }
      
 }
   




?>