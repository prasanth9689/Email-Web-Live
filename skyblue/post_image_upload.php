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
    
     $save_path = 'post_image/';
       $server_ip       = 'sh021.hostgator.tempwebhost.net/~skybl4b37w';   
       $file_upload_url = 'http://' . $server_ip . '/' . $save_path;
       
        if(isset($_FILES['file_image']))
         {
             $user_id = $_POST['user_id'];
             $media_type = $_POST['media_type'];
             $image_about = $_POST['image_about'];
             $image_name = $_POST['image_name'];
             $user_name = $_POST['user_name'];
             $time_date = $_POST['time_date'];
             $placeholder_url = $_POST['placeholder_url'];
             $ImageName = $save_path.generate_string($permitted_chars, 25).".PNG";
             $moveImage = "user/post_image/".generate_string($permitted_chars, 25).".PNG";
             $url_location_thumbnail = 'http://' . $server_ip . '/skyblue/' . $moveImage;

     	      try
	           {
	             	if(!move_uploaded_file($_FILES['file_image']['tmp_name'], $moveImage))
		             {
            		 //	echo "Success!";
		             }
                  
                        $Sql_Query = "INSERT INTO user_post ( user_id , url , image_about , image_name , user_name , time_date ,  placeholder_url , media_type) 
                                                   VALUES ( '$user_id' , '$url_location_thumbnail' , '$image_about' , '$ImageName' , '$user_name' , '$time_date' , '$url_placeholder' , '$media_type')";
                        if(mysqli_query($con,$Sql_Query))
                           {
                             // echo 'success';
                              // getUserData();
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
              
              
//     $save_path       = 'images/test/';
//     $server_ip       = 'imobiless.com'; 
//     $file_upload_url = 'http://' . $server_ip . '/' . $save_path;
//     //                  http://imobiless.com     /images/test/

//     if(isset($_FILES['file_image']) && isset($_FILES['file_image_placeholder'])){

//         $user_id = $_POST['user_id'];
//         $image_about = $_POST['image_about'];
//         $user_name = $_POST['user_name'];
//         $time_date = $_POST['time_date'];
//         $tag_text = $_POST['tag_text'];
//         $tag_id = $_POST['tag_id'];
        
//     $ImageName = $save_path.generate_string($permitted_chars, 25).".JPG";
//     $url_location_thumbnail = 'http://' . $server_ip . '/web/upload_post_image/' . $ImageName;
    
//     $save_placehoder_dir = 'images/placeholder/'.generate_string($permitted_chars, 25).".JPG";
//     $url_placeholder = 'http://' . $server_ip . '/web/upload_post_image/' . $save_placehoder_dir;

// 	try
// 	{
	    
//  	if(!move_uploaded_file($_FILES['file_image']['tmp_name'], $ImageName))
// 		{
// 			echo "Could not upload the file!";
// 		}
		
		
// 			if(!move_uploaded_file($_FILES['file_image_placeholder']['tmp_name'], $save_placehoder_dir))
// 		{
// 			echo "upload placehoder success";
// 		}
// 		//    echo 'upload placehoder fail';
		

//       $insertQuery  = "INSERT INTO upload_post_image(user_id,url,image_about,image_name,user_name,time_date,tag_text,tag_id, placeholder_url) VALUES (?,?,?,?,?,?,?,?,?)";
// 		if($stmt = $con->prepare($insertQuery)){
// 			$stmt->bind_param("sssssssss",$user_id,$url_location_thumbnail,$image_about,$ImageName,$user_name,$time_date,$tag_text,$tag_id,$url_placeholder);
// 			$stmt->execute();
// 		//	$response["status"] = 200;                                   
// 		//	$response["message"] = "upload success";                    
// 				echo "Success";
// 			$stmt->close();
// 		}

// 	} catch(Exception $e)
// 	{
// 			echo "Failure";
// 	}
// }
// else
// {
// 	echo "File is missing";
// }

// ?> <?php
 
//   include 'connect.php';

//   $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
 
//   function generate_string($input, $strength = 16) {
//     $input_length = strlen($input);
//     $random_string = '';
//     for($i = 0; $i < $strength; $i++) {
//         $random_character = $input[mt_rand(0, $input_length - 1)];
//         $random_string .= $random_character;
//     }
 
//     return $random_string;
// }

//     $response  = array();
    
    
//     $save_path       = 'images/test/';
//     $server_ip       = 'imobiless.com'; 
//     $file_upload_url = 'http://' . $server_ip . '/' . $save_path;
//     //                  http://imobiless.com     /images/test/

//     if(isset($_FILES['file_image']) && isset($_FILES['file_image_placeholder'])){

//         $user_id = $_POST['user_id'];
//         $image_about = $_POST['image_about'];
//         $user_name = $_POST['user_name'];
//         $time_date = $_POST['time_date'];
//         $tag_text = $_POST['tag_text'];
//         $tag_id = $_POST['tag_id'];
        
//     $ImageName = $save_path.generate_string($permitted_chars, 25).".JPG";
//     $url_location_thumbnail = 'http://' . $server_ip . '/web/upload_post_image/' . $ImageName;
    
//     $save_placehoder_dir = 'images/placeholder/'.generate_string($permitted_chars, 25).".JPG";
//     $url_placeholder = 'http://' . $server_ip . '/web/upload_post_image/' . $save_placehoder_dir;

// 	try
// 	{
	    
//  	if(!move_uploaded_file($_FILES['file_image']['tmp_name'], $ImageName))
// 		{
// 			echo "Could not upload the file!";
// 		}
		
		
// 			if(!move_uploaded_file($_FILES['file_image_placeholder']['tmp_name'], $save_placehoder_dir))
// 		{
// 			echo "upload placehoder success";
// 		}
// 		//    echo 'upload placehoder fail';
		

//       $insertQuery  = "INSERT INTO upload_post_image(user_id,url,image_about,image_name,user_name,time_date,tag_text,tag_id, placeholder_url) VALUES (?,?,?,?,?,?,?,?,?)";
// 		if($stmt = $con->prepare($insertQuery)){
// 			$stmt->bind_param("sssssssss",$user_id,$url_location_thumbnail,$image_about,$ImageName,$user_name,$time_date,$tag_text,$tag_id,$url_placeholder);
// 			$stmt->execute();
// 		//	$response["status"] = 200;                                   
// 		//	$response["message"] = "upload success";                    
// 				echo "Success";
// 			$stmt->close();
// 		}

// 	} catch(Exception $e)
// 	{
// 			echo "Failure";
// 	}
// }
// else
// {
// 	echo "File is missing";
// }

?>