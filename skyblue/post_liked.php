<?php


     include 'connect.php';
     
     $user_id = $_POST['user_id']; // current logged user id
     $post_id = $_POST['post_id']; // current video id
     $status = '1';
     
     // for testing
    //  echo $user_id;
    //  echo $post_id;
    
    // run and open logcat
     
     
          $query = ("SELECT ?,? FROM user_likes WHERE user_id = '$user_id' AND post_id = '$post_id'");
          global $con;
      if($stmt = $con->prepare($query))
          {
             $stmt->bind_param("ss",$user_id , $post_id);
             $stmt->execute();
             $stmt->store_result();
             $stmt->fetch();
             if($stmt->num_rows == 1)
                  {
                      //  echo "already exits"; // this current logged user already liked this video
                       // $status = array("status"=>false); 
                             $status = array("status"=>"false"); 
                              $json_pretty = json_encode($status, JSON_PRETTY_PRINT); 
                              echo $json_pretty; 
                  }
                 else{
                     
                        global $status;
                        global $user_id;
                        $Sql_Query = "INSERT INTO user_likes (user_id , post_id , status)VALUES ($user_id , $post_id , $status)"; // $status = 1 liked
                        if(mysqli_query($con,$Sql_Query))
                             {
                                 
                             // echo 'success';
                              $status = array("status"=>"true"); 
                              $json_pretty = json_encode($status, JSON_PRETTY_PRINT); 
                              echo $json_pretty; 
                           //    pushNotification();
                               
                             }
                              else{
 
                                  //    echo 'failed';
                                       $status = array("status"=>"false"); 
                              $json_pretty = json_encode($status, JSON_PRETTY_PRINT); 
                              echo $json_pretty; 
 
                                  }

                      $stmt->close();
                     }
  }
     
     
     
     
     


//      $user_id = $_POST['user_id'];
//      $user_profile = $_POST['user_profile'];
//      $user_name = $_POST['user_name'];
//      $post_id = $_POST['post_id'];
//      $post_user_id = $_POST['post_user_id'];
//      $status = '1';
     
//     //  echo "$user_id";
//     //  echo "$user_profile";
//     //  echo "$user_name";
//     //  echo "$post_id";
//     //  echo "$post_user_id";
    
//     $query = ("SELECT ?,? FROM user_likes WHERE user_id = '$user_id' AND post_id = '$post_id'"); // fst check user_likes table availe on dabase
//     global $con;
//       if($stmt = $con->prepare($query))
//           {
//              $stmt->bind_param("ss",$user_id , $post_id);
//              $stmt->execute();
//              $stmt->store_result();
//              $stmt->fetch();
//              if($stmt->num_rows == 1)
//                   {
//                         echo "already exits";
//                   }
//                  else{
                     
//                         global $status;
//                         global $user_id;
//                         $Sql_Query = "INSERT INTO user_likes (user_id , post_id , status)VALUES ($user_id , $post_id , $status)";
//                         if(mysqli_query($con,$Sql_Query))
//                              {
                                 
//                               echo 'success';
//                               pushNotification();
                               
//                              }
//                               else{
 
//                                       echo 'failed';
 
//                                   }

//                       $stmt->close();
//                      }
//   }
  
//   function pushNotification()
//          {
          
//     include 'connect.php';

//                 global $sender_id;
//                 global $post_user_id;

//               $sql = "SELECT users.token FROM users WHERE id = '$post_user_id'";
//               $sql_query = mysqli_query($con, $sql);
//               while ($row = mysqli_fetch_array($sql_query)) {
//               // echo $row["token"];
     
//               $tokenHolder = $row["token"];
 
//         } 
    
//                 global $sender_name;
//                 global $receiver_profile_url;
//                 global $user_profile;
//                 global $user_name;
                
//                  define('API_ACCESS_KEY','AAAAhBOV0V0:APA91bFolcSp7qzDrLJNtT4Qkd7x523ACjK-A3VRBzj5i0r4nyPQMuKRiKfcn6Mztcl2_Gl_j3y4dcOoKCA1QKIUHM_DUvtGgd4JfOemrqpx2GKnrqFEHS4nhKsduAZQPBPMORTFef8s');
//                   $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
//                  // $token='f-8gbxApS7WOEki0rNpQas:APA91bE1w9oDM1rWMSA6AlTc_BNn_ztpGflwtYYHCBg6_Ztcle2XUBZZ8FS8-lOtS-IZ3QK32Zv_oOOEiWc-qAwujZ6xccwO9O2SWBGRwi-_v4sS6mg5JSRknYBy6K2SaOR1bVE25cX5';
//                   $token = $tokenHolder;
//                   $skyblue = 'Skyblue';
//                   $body = 'Your post liked by..'.$user_name;
//                   $image = $receiver_profile_url;
                  
//                   // 'image' =>'https://cdn.britannica.com/31/149831-050-83A0E45B/Donald-J-Trump-2010.jpg', 
                     
//                       $extraNotificationData = [
//                                                   'title' =>$skyblue,
//                                                   'body' => $body,
//                                                   'image' =>$user_profile, 
//                                                   //  'sound' => 'mySound'
//                                               ];
                                               
//                               $notification = [
//                                                   "message" => $notification,"moredata" =>'dd'];

//                             $fcmNotification = [ 
//                                                   //'registration_ids' => $tokenList, //multple token array
//                                                  'to'        => $token, //single token
//                                                  'notification' => $notification,
//                                                  'data' => $extraNotificationData
//                                               ];

//                                     $headers = [
//                                                   'Authorization: key=' . API_ACCESS_KEY,
//                                                   'Content-Type: application/json'
//                                               ];


//                                                  $ch = curl_init();
//                                                  curl_setopt($ch, CURLOPT_URL,$fcmUrl);
//                                                  curl_setopt($ch, CURLOPT_POST, true);
//                                                  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//                                                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                                                  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//                                                  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
//                                                  $result = curl_exec($ch);
//                                                  curl_close($ch);


//                                                  //   echo $result;
        
             
//              echo '2';
//          }
  
?>