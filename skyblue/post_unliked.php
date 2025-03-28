<?php

      include 'connect.php';
 
      $post_id = $_POST['post_id'];
      $user_id = $_POST['user_id'];

 
      $Sql_Query = "DELETE FROM user_likes WHERE user_id = '$user_id' AND post_id = '$post_id'";
      if(mysqli_query($con,$Sql_Query))
          {
              
                  // echo 'success';
                      $status = array("status"=>"true"); 
                              $json_pretty = json_encode($status, JSON_PRETTY_PRINT); 
                              echo $json_pretty; 
                   
          }
              else
                   {
 
                    //  echo 'Try Again';
                        $status = array("status"=>"false"); 
                              $json_pretty = json_encode($status, JSON_PRETTY_PRINT); 
                              echo $json_pretty; 
 
                   }
                       mysqli_close($con);
?>