<?php

   include 'connect.php';
 
  $user_id = $_POST['user_id'];
  $media_type = $_POST['media_type'];
 
$Sql_Query = "SELECT post_table.id , 
       post_table.url as url , 
       post_table.image_about as image_about, 
       post_table.placeholder_url as placeholder_url,
       post_table.user_id as user_id, 
       post_table.user_name as user_name ,
       post_table.time_date as time_date ,
       COUNT(DISTINCT comments_table.id) as comments ,
       COUNT(DISTINCT likes_table.id) as likes ,
       user_table.profile_url as profile_url ,
       user_table.cover_picture_url as user_cover ,
       user_table.name as user_name , 
       likes_table_status.status as status ,
       post_table.media_type as media_type ,
       post_table.video_url as video_url 

    FROM user_post post_table 
    
    LEFT JOIN   
   user_comments comments_table
     ON post_table.id = comments_table.post_id 
     
     LEFT JOIN
      user_likes likes_table
     ON post_table.id = likes_table.post_id  
        
    LEFT JOIN 
      users user_table ON post_table.user_id = user_table.id
    
     LEFT JOIN 
      user_likes likes_table_status ON  likes_table_status.user_id = '1' AND likes_table_status.status = '1' AND likes_table_status.post_id =  post_table.id WHERE post_table.user_id = '$user_id'
      
     GROUP BY post_table.id  ORDER BY RAND() LIMIT 50";
 
 
$result=mysqli_query($con,$Sql_Query);
 
$data=array();

while($row=mysqli_fetch_assoc($result)){
    
array_push($data, array("user_name"=>$row["user_name"] , "url"=>$row["url"] , "image_about"=>$row["image_about"], "placeholder_url"=>$row["placeholder_url"] , "likes"=>$row["likes"], "comments"=>$row["comments"], "user_id"=>$row["user_id"] , "profile_url"=>$row["profile_url"] , "status"=>$row["status"] , "id"=>$row["id"]  , "time_date"=>$row["time_date"], "user_cover"=>$row["user_cover"], "media_type"=>$row["media_type"], "video_url"=>$row["video_url"]));

}
 
    header('Content-Type:Application/json');
    print(json_encode(array("data" =>$data)));


 
?>

 