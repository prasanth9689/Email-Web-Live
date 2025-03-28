<?php

  include 'connect.php';

    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    function generate_string($input, $strength = 16) 
    {
        $input_length = strlen($input);
        $random_string = '';
        for($i = 0; $i < $strength; $i++) 
          {
              $random_character = $input[mt_rand(0, $input_length - 1)];
              $random_string .= $random_character;
          }
                return $random_string;
    }

       $response  = array();
       $save_path = 'profile/';
       $server_ip       = 'sh021.hostgator.tempwebhost.net/~skybl4b37w';   
       $file_upload_url = 'http://' . $server_ip . '/' . $save_path;

       if(isset($_FILES['file_image']))
         {
             $user_id = $_POST['user_id'];
             $ImageName = $save_path.generate_string($permitted_chars, 25).".PNG";
             $moveImage = "user/profile/".generate_string($permitted_chars, 25).".PNG";
             $url_location_thumbnail = 'http://' . $server_ip . '/skyblue/' . $moveImage;

     	      try
	           {
	             	if(!move_uploaded_file($_FILES['file_image']['tmp_name'], $moveImage))
		             {
            		 //	echo "Success!";
		             }
                  
                        $Sql_Query = "UPDATE users SET profile_picture = '$ImageName' , profile_url = '$url_location_thumbnail' WHERE id = '$user_id' ";
                        if(mysqli_query($con,$Sql_Query))
                           {
                             // echo 'success';
                               getUserData();
                           }
                              else{
                                   //  echo 'Try Again';
                                      array_push($data, array("message"=>"1"));
                                      header('Content-Type:Application/json');
                                      print(json_encode(array("status"=>"true" , "data" =>$data))); 
		                          }

	          }  catch(Exception $e)
	               {
	                   // echo expectation
	               }
        }
          else
              {
        	    //  echo "File is missing";
        	                          array_push($data, array("message"=>"2"));
                                      header('Content-Type:Application/json');
                                      print(json_encode(array("status"=>"true" , "data" =>$data))); 
              }
              
              
                function getUserData()
      {
            include 'connect.php'; 
          
          global $user_id;


          $Sql_Query = "SELECT profile_url FROM users WHERE id='$user_id' ";
          $result=mysqli_query($con,$Sql_Query);
       
          $data=array();

          while($row=mysqli_fetch_assoc($result))
            {
                
               array_push($data, array("message"=>"3" , "profile_url"=>$row["profile_url"] ));

            }
 
       header('Content-Type:Application/json');

       print(json_encode(array("status"=>"true" , "data" =>$data))); 
      }
?>