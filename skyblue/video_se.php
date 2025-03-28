<?php



   include 'connect.php';
   
         $access = $_POST['access'];
         $user_id = $_POST['user_id'];
         $query_base64 = $_POST['query_base64'];
         $time_date = $_POST['time_date'];

        
$Sql_Query = "SELECT * FROM `user_post` WHERE FROM_BASE64(`video_name`) LIKE '%$query_base64%'  LIMIT 9";
 
 
$result=mysqli_query($con,$Sql_Query);
 
$data=array();

while($row=mysqli_fetch_assoc($result)){
    
//array_push($data, array("product_id"=>$row["id"] , "product_name"=>$row["product_name"] , "title"=>$row["title"] , "thumbnail"=>$row["thumbnail"], "sale_price"=>$row["sale_price"] , "discount_price"=>$row["discount_price"], "rating"=>$row["rating"], "feature_1"=>$row["feature_1"] , "feature_2"=>$row["feature_2"] , "feature_3"=>$row["feature_3"] , "feature_5"=>$row["feature_5"] ));
array_push($data, array("request_access"=>$access,
                        "request_user_id"=>$user_id,
                        "request_query_base64"=>$query_base64,
                        "request_time_date"=>$time_date,
                        "user_id"=>$row["user_id"] , 
                        "channel_id"=>$row["channel_id"] , 
                        "channel_name"=>$row["channel_name"] , 
                        "thumbnail_url"=>$row["thumbnail_url"],
                        "video_url"=>$row["video_url"],
                        "video_name"=>$row["video_name"],
                        "description"=>$row["description"],
                        "upload_date"=>$row["time_date"]));

}
 
    header('Content-Type:Application/json');
    print(json_encode(array("data" =>$data)));












//   include 'connect.php';
   
//          $access = $_POST['access'];
//          $user_id = $_POST['user_id'];
//          $query_base64 = $_POST['query_base64'];
//          $time_date = $_POST['time_date'];
        
// $Sql_Query = "SELECT * FROM user_post WHERE channel_name LIKE '%Prasanth%' LIMIT 9";
 
 
// $result=mysqli_query($con,$Sql_Query);
 
// $data=array();

// while($row=mysqli_fetch_assoc($result)){
    
// //array_push($data, array("product_id"=>$row["id"] , "product_name"=>$row["product_name"] , "title"=>$row["title"] , "thumbnail"=>$row["thumbnail"], "sale_price"=>$row["sale_price"] , "discount_price"=>$row["discount_price"], "rating"=>$row["rating"], "feature_1"=>$row["feature_1"] , "feature_2"=>$row["feature_2"] , "feature_3"=>$row["feature_3"] , "feature_5"=>$row["feature_5"] ));
// array_push($data, array("id"=>$row["id"] ,
//                         "user_id"=>$row["user_id"] , 
//                         "channel_id"=>$row["channel_id"] , 
//                         "channel_name"=>$row["channel_name"] , 
//                         "thumbnail_url"=>$row["thumbnail_url"],
//                         "video_url"=>$row["video_url"],
//                         "video_name"=>$row["video_name"],
//                         "description"=>$row["description"],
//                         "upload_date"=>$row["time_date"]));

// }
 
//     header('Content-Type:Application/json');
//     print(json_encode(array("data" =>$data)));


?>