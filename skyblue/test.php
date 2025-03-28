 <?php
 
 $parent_dir = dirname(__DIR__);
 
 echo $parent_dir;
 
//   $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
 
                  
//     function generate_string($input, $strength = 16) {
//     $input_length = strlen($input);
//     $random_string = '';
//     for($i = 0; $i < $strength; $i++) {
//         $random_character = $input[mt_rand(0, $input_length - 1)];
//         $random_string .= $random_character;
//     }
 
//     return $random_string;
// }

//       $save_path = 'test/';
//       $server_ip       = 'imadras.com/prasanth';   
//       $file_upload_url = 'https://' . $server_ip . '/' . $save_path;
       
//          if(isset($_FILES['video']))
//          {
             
//               $fileName = generate_string($permitted_chars, 25);
//               $moveVideo = "user/test/".$fileName.".MP4";
             
//                 try
//               {
//                   if(!move_uploaded_file($_FILES['video']['tmp_name'], $moveVideo))
//                      {
//                         // return false itself (video upload failure)
//                      }else{
//                           $message = array("message"=> 1);
//                           $json_pretty = json_encode($message, JSON_PRETTY_PRINT);
//                           echo $json_pretty;
//                      }
                 
//               } catch(Exception $e)
//                   {
//                       // Server error expectation
//                       $message = array("message"=> 2);
//                       $json_pretty = json_encode($message, JSON_PRETTY_PRINT);
//                       echo $json_pretty;
//                   }
             
//          } else
//               {
//                 // File is missing
//                  $message = array("message"=> 3);
//                  $json_pretty = json_encode($message, JSON_PRETTY_PRINT);
//                  echo $json_pretty;
//               }
              
?>