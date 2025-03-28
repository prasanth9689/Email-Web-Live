<?php

   include 'connect.php';
   $post_id = $_POST['post_id'];
   
   $Sql_Query="SELECT user_comments.`id`, user_comments.`sender_id` , user_comments.`post_id` , user_comments.`comments` , users.`profile_url` , users.`name`
     FROM user_comments , users WHERE post_id = '$post_id' AND user_comments.`sender_id` = users.`id` ORDER BY `id` DESC";
     
   $result=mysqli_query($con,$Sql_Query);
   $data=array();

              if($result->num_rows > 0)
                 {
                 	// Assign $results to array() function
                     $results=array();
                     // Query started Mysql table num_rows
                     while($row=$result->fetch_array())
                       {
                       	    // SELECT statement all data store to " $results " array()
                           array_push($results, array("id"=>$row['id'],"sender_id"=>$row['sender_id'],"comments"=>$row['comments'],"post_id"=>$row['post_id'],"profile_url"=>$row['profile_url'],"name"=>$row['name']));
                       }
                               print(json_encode(array_reverse($results)));
                 } 
                    else
                      {
                      }
              
  


?>