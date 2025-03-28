<?php

 include 'connect.php';
 $post_id = $_POST['post_id']; // current video id
 
  $Sql_Query = "UPDATE user_post SET total_views = total_views + 1 WHERE id = '$post_id'";
     if(mysqli_query($con,$Sql_Query))
        {
              echo 'success';
            //   pushNotification();
        }
         else
             {
 
                    echo 'failed';
 
             }
             
             
             $userId = $_POST['user_id'];
             $device = $_POST['device'];
             $date = $_POST['date'];
             $time= $_POST['time'];
             $timeDate= $_POST['time_date'];
             $timezone = $_POST['timezone'];
             $ip = $_POST['ip'];
             $device_name = $_POST['device_name'];
             $country_name = $_POST['country_name'];
             
      //        if($userId != 1){
                    
                    
                      $sqlQuery = "INSERT INTO views (user_id , post_id , device , date , time, time_date , timezone , ip , device_name, country_name)VALUES ('$userId' , '$post_id' , '$device' , '$date' , '$time' , '$timeDate' , '$timezone' , '$ip' , '$device_name' , '$country_name')";
                       if(mysqli_query($con,$sqlQuery)){
                           // Success
                       }else{
                           // Failure
                       }
                    
               return;
          //     }
             
?>