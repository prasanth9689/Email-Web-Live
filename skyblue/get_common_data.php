<?php

$handle = fopen("counter.txt", "r"); 
if(!$handle) { 
    echo "could not open the file"; 
} else { 
    $counter =(int )fread($handle,20);
        fclose($handle); 
        $counter++; 
     //   echo"Number of visitors to this page so far: ". $counter . "" ; 
    $handle = fopen("counter.txt", "w" ); 
    
    fwrite($handle,$counter);
    fclose ($handle); 
}

   include 'connect.php';
 
  $user_id = $_POST['user_id'];
 
$Sql_Query = "SELECT post_table.id , 
       post_table.thumbnail_url as thumbnail_url , 
       post_table.video_name as video_name, 
       post_table.placeholder_url as placeholder_url,
       post_table.user_id as user_id, 
        post_table.duration as duration, 
       post_table.user_name as user_name ,
       post_table.time_date as time_date ,
       post_table.total_views as views ,
       COUNT(DISTINCT comments_table.id) as comments ,
       COUNT(DISTINCT likes_table.id) as likes ,
       user_table.profile_url as profile_url ,
       user_table.cover_picture_url as user_cover ,
       user_table.name as user_name , 
       likes_table_status.status as status ,
       post_table.media_type as media_type ,
       post_table.video_url as video_url,
       post_table.channel_id as channel_id,
       post_table.channel_name as channel_name 

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
      user_likes likes_table_status ON  likes_table_status.user_id = '$user_id' AND likes_table_status.status = '1' AND likes_table_status.post_id =  post_table.id
      
     GROUP BY post_table.id  ORDER BY RAND() LIMIT 9";
 
 
$result=mysqli_query($con,$Sql_Query);
 
$data=array();

while($row=mysqli_fetch_assoc($result)){
    
array_push($data, array("user_name"=>$row["user_name"] , 
                        "thumbnail_url"=>$row["thumbnail_url"] , 
                        "video_name"=>$row["video_name"], 
                        "placeholder_url"=>$row["placeholder_url"] , 
                        "likes"=>$row["likes"], 
                        "comments"=>$row["comments"], 
                        "user_id"=>$row["user_id"] , 
                        "profile_url"=>$row["profile_url"] , 
                        "like_status"=>$row["status"] , 
                        "post_id"=>$row["id"]  , 
                        "time_date"=>$row["time_date"], 
                        "user_cover"=>$row["user_cover"], 
                        "media_type"=>$row["media_type"], 
                        "video_url"=>$row["video_url"] , 
                        "duration"=>$row["duration"],
                        "channel_id"=>$row["channel_id"],
                        "total_views"=>$row["views"],
                        "channel_name"=>$row["channel_name"]));

}
 
    header('Content-Type:Application/json');
    print(json_encode(($data)));


 
?>

 