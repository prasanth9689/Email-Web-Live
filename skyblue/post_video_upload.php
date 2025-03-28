 <?php
 
   include 'connect.php';

   $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
 
  function generate_string($input, $strength = 16) {
    $input_length = strlen($input);
    $random_string = '';
    for($i = 0; $i < $strength; $i++) {
        $random_character = $input[mt_rand(0, $input_length - 1)];
        $random_string .= $random_character;
    }
 
    return $random_string;
}

       $response  = array();
    
       $save_path = 'post_video/';
       $server_ip       = 'skyblue.co.in';   
       $file_upload_url = 'https://' . $server_ip . '/' . $save_path; 
       
        if(isset($_FILES['thumbnail']) && isset($_FILES['video']))
         {
             $user_id = $_POST['user_id'];
             $media_type = $_POST['media_type'];
             $video_name = $_POST['video_name'];
             $image_name = $_POST['image_name'];
             $user_name = $_POST['user_name'];
             $time_date = $_POST['time_date'];
             $placeholder_url = $_POST['placeholder_url'];
             $description  = $_POST['description'];
             $duration = $_POST['duration'];
             $channel_id = $_POST['channel_id'];
             $channel_name = $_POST['channel_name'];
             
             $fileName = generate_string($permitted_chars, 25);
             
             $ImageName = $save_path.$fileName.".PNG";
             $moveImage = "user/post_video/thumbnail/".$fileName.".JPG";
             $moveVideo = "user/post_video/".$fileName.".MP4";
             $thumbnail_url = 'https://' . $server_ip . '/skyblue/' . $moveImage;
             $video_url = 'https://' . $server_ip . '/skyblue/' . $moveVideo;

             $data = array();



              try
               {
                    if(!move_uploaded_file($_FILES['thumbnail']['tmp_name'], $moveImage))
                     {
                     // echo "Success!";
                     }
                     
                     if(!move_uploaded_file($_FILES['video']['tmp_name'], $moveVideo))
                     {
                     // echo "Success!";
                     }
                  
                  
                        $Sql_Query = "INSERT INTO user_post ( user_id , 
                                                              thumbnail_url , 
                                                              video_name , 
                                                              image_name , 
                                                              user_name , 
                                                              time_date ,  
                                                              placeholder_url , 
                                                              media_type , 
                                                              video_url , 
                                                              description, 
                                                              duration,
                                                              channel_id,
                                                              channel_name) 
                                                   VALUES ( '$user_id' , 
                                                   '$thumbnail_url' , 
                                                   '$video_name' , 
                                                   '$ImageName' , 
                                                   '$user_name' , 
                                                   '$time_date' , 
                                                   '$url_placeholder' , 
                                                   '$media_type' , 
                                                   '$video_url' , 
                                                   '$description' , 
                                                   '$duration',
                                                   '$channel_id',
                                                   '$channel_name')";
                        if(mysqli_query($con,$Sql_Query)) 
                           {
                              // echo 'success';
                             $name = array("message"=> 1);
                             $json_pretty = json_encode($name, JSON_PRETTY_PRINT);
                             
                             echo $json_pretty;
                                     
                           }
                              else{
                                     // Error
                                      array_push($data, array("message"=>"2"));
                                      header('Content-Type:Application/json');
                                      print(json_encode(($data))); 
                                  }
                              

              }  catch(Exception $e)
                   {
                       // Server error expectation
                        array_push($data, array("message"=>$e));
                        header('Content-Type:Application/json');
                        print(json_encode(($data))); 
                   }
        }
          else
              {
                // File is missing
                array_push($data, array("message"=>"2"));
                header('Content-Type:Application/json');
                print(json_encode(($data))); 
              }


        //  echo 'success';
        //  array_push($data, array("message"=>"1"));
        //  header('Content-Type:Application/json');
        //  print(json_encode(array("status"=>"true" , "data" =>$data))); 
              
?>