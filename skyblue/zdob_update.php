<?php
 
   include "connect.php";

     $user_id = $_POST['user_id'];
     $dob = $_POST['dob'];
 
 
 
 
 
  $Sql_Query = "  UPDATE users SET dob = '$dob'   WHERE id = '$user_id' ";
 
    if(mysqli_query($con,$Sql_Query))
        {
 
              echo 'success';
 
       }
         else
                 {
 
                    echo 'Try agin';
 
                 }

?>