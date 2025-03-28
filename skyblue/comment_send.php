<?php
 
     include 'connect.php';

     $logged_user_id = $_POST['logged_user_id'];
     $logged_user_name = $_POST['logged_user_name'];
     $logged_user_comment = $_POST['logged_user_comment'];
     $logged_user_profile = $_POST['user_profile'];
     
     $post_user_id = $_POST['post_user_id'];
     $post_id = $_POST['post_id'];
     
     $status = "1";
     
     if(empty($logged_user_comment)) { 
    echo "Empty parameter";
    return;
      } 
 
     $Sql_Query = "INSERT INTO user_comments (sender_id , post_id , comments)VALUES ('$logged_user_id' , '$post_id' , '$logged_user_comment' ) ";
     if(mysqli_query($con,$Sql_Query))
        {
              echo 'success';
            //   pushNotification();
        }
         else
             {
 
                    echo 'failed';
 
             }
             
function pushNotification()
         {
                  
     include 'connect.php';

                global $user_id;
                global $post_user_id;

               $sql = "SELECT users.token FROM users WHERE id = '$post_user_id'";
               $sql_query = mysqli_query($con, $sql);
               while ($row = mysqli_fetch_array($sql_query)) {
               // echo $row["token"];
     
               $tokenHolder = $row["token"];
 
        } 
    
                global $sender_name;
                global $receiver_profile_url;
                global $user_profile;
                global $user_name;
                
                 define('API_ACCESS_KEY','AAAAhBOV0V0:APA91bFolcSp7qzDrLJNtT4Qkd7x523ACjK-A3VsuccessRBzj5i0r4nyPQMuKRiKfcn6Mztcl2_Gl_j3y4dcOoKCA1QKIUHM_DUvtGgd4JfOemrqpx2GKnrqFEHS4nhKsduAZQPBPMORTFef8s');
                  $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
                 // $token='f-8gbxApS7WOEki0rNpQas:APA91bE1w9oDM1rWMSA6AlTc_BNn_ztpGflwtYYHCBg6_Ztcle2XUBZZ8FS8-lOtS-IZ3QK32Zv_oOOEiWc-qAwujZ6xccwO9O2SWBGRwi-_v4sS6mg5JSRknYBy6K2SaOR1bVE25cX5';
                  $token = $tokenHolder;
                  $skyblue = 'Skyblue';
                  $body = 'Your post commented by..'.$user_name;
                  $image = $receiver_profile_url;
                  
                  // 'image' =>'https://cdn.britannica.com/31/149831-050-83A0E45B/Donald-J-Trump-2010.jpg', 
                     
                      $extraNotificationData = [
                                                  'title' =>$skyblue,
                                                  'body' => $body,
                                                  'image' =>$user_profile, 
                                                   //  'sound' => 'mySound'
                                               ];
                                               
                               $notification = [
                                                  "message" => $notification,"moredata" =>'dd'];

                            $fcmNotification = [ 
                                                  //'registration_ids' => $tokenList, //multple token array
                                                 'to'        => $token, //single token
                                                 'notification' => $notification,
                                                 'data' => $extraNotificationData
                                              ];

                                    $headers = [
                                                  'Authorization: key=' . API_ACCESS_KEY,
                                                  'Content-Type: application/json'
                                               ];


                                                 $ch = curl_init();
                                                 curl_setopt($ch, CURLOPT_URL,$fcmUrl);
                                                 curl_setopt($ch, CURLOPT_POST, true);
                                                 curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                                                 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                                 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                                 curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
                                                 $result = curl_exec($ch);
                                                 curl_close($ch);


                                                 //   echo $result;
        
             
             echo '2';
         }


?>