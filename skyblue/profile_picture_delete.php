<?php 

  include 'connect.php';

 
  $user_id = $_POST['user_id'];

  $Sql_Query = "UPDATE users SET users.profile_url = '', users.profile_picture = '' WHERE id = '$user_id'";
 
                        if(mysqli_query($con,$Sql_Query))
                          {
 
                                 echo '1';
 
                          }
                                else
                                   {
 
                                         echo '0';
 
                                   }    

 ?>