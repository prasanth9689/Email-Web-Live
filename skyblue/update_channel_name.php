<?php

 include 'connect.php';
   
 $user_id = $_POST['user_id'];
 $channel_id = $_POST['channel_id'];
 $channel_name = $_POST['channel_name'];
// $data=array();

 $Sql_Query = "  UPDATE channels SET channel_name = '$channel_name'   WHERE id = '$channel_id' ";
 
    if(mysqli_query($con,$Sql_Query))
        {
 
              // success
              //   array_push($data, array("message"=>"success"));
              $data = array("message"=>"success"); 
                 header('Content-Type:Application/json');
                 print(json_encode(($data)));
 
       }
         else
                 {
 
                    // failure
                     array_push($data, array("message"=>"failure"));
                     header('Content-Type:Application/json');
                     print(json_encode(($data)));
 
                 }

?>