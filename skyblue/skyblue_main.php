<?php

include "connect.php";

$access = $_POST["acc"];

switch ($access) {
    case "login":
        echo "Not implemented";
        break;

    case "register2":
        register2();
        break;

        case "create_channel":
            channelCreation();
            break;

    case "get_common_data":
        echo "Not implemented";
        break;

    case "get_video_details":
        $post_id = $_POST["post_id"];
        $user_id = $_POST["user_id"];

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
      
     WHERE post_table.id = '$post_id' ";

        $result = mysqli_query($con, $Sql_Query);

        $data = [];

        while ($row = mysqli_fetch_assoc($result)) {
            array_push($data, [
                "user_name" => $row["user_name"],
                "thumbnail_url" => $row["thumbnail_url"],
                "video_name" => $row["video_name"],
                "placeholder_url" => $row["placeholder_url"],
                "likes" => $row["likes"],
                "comments" => $row["comments"],
                "user_id" => $row["user_id"],
                "profile_url" => $row["profile_url"],
                "like_status" => $row["status"],
                "post_id" => $row["id"],
                "time_date" => $row["time_date"],
                "user_cover" => $row["user_cover"],
                "media_type" => $row["media_type"],
                "video_url" => $row["video_url"],
                "duration" => $row["duration"],
                "channel_id" => $row["channel_id"],
                "total_views" => $row["views"],
                "channel_name" => $row["channel_name"],
            ]);
        }

        header("Content-Type:Application/json");
        print json_encode($data);

        break;

        case 'get_channels':
       //    include "connect.php";
    $user_id = $_POST["user_id"];
    $data = [];
    $SQL_QUERY = "SELECT * FROM channels WHERE user_id = '$user_id'";
    $result = mysqli_query($con, $SQL_QUERY);
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($data, [
            "message" => "success",
            "channel_id" => $row["id"],
            "channel_name" => $row["channel_name"],
            "created_date" => $row["created_date"],
        ]);
    }

    header("Content-Type:Application/json");
    print json_encode(["status" => "true", "data" => $data]);
          break;

          case 'delete_user_ac':
            array_push($data, array("access auth"=>"true" , "status"=>"1" , "message"=>"Account delete request success."));
            header("Content-Type:Application/json");
            print json_encode($data);
            break;

    default:
        echo "Wrong access key";
        break;
}


function register2(){
    include 'connect.php';

    $email_person_id = $_POST['email_person_id'];
    $person_name = $_POST['person_name'];
    $country_name = $_POST['country_name'];
    $phone_code = $_POST['phone_code'];
    $email = $_POST['email'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $time_zone = $_POST['time_zone'];
    $date_time_zone = $_POST['date_time_zone'];
    $token = $_POST['token'];


    $Sql_Query = "INSERT INTO users (email_person_id ,
                                     name ,
                                     country , 
                                     phone_code ,
                                     email ,
                                     joined_date , 
                                     joined_time , 
                                     joined_time_zone , 
                                     joined_date_time_zone , 
                                     token
                                  )VALUES ('$email_person_id' , 
                                           '$person_name' ,
                                            '$country_name' , 
                                            '$phone_code' ,
                                            '$email' , 
                                            '$date' , 
                                            '$time' , 
                                            '$time_zone' , 
                                            '$date_time_zone' , 
                                            '$token')";

             if(mysqli_query($con,$Sql_Query))
                 {

                      //  echo '1'; // success
                      getUserId();

                 }
                   else
                     {
                         echo '0';
                     }
       
                
                
    function getUserId()
     {
         
         include 'connect.php'; 
         
         global $email_person_id;
                
         //$mobile = $_POST['mobile'];


         $Sql_Query = "SELECT id FROM users WHERE email_person_id = '$email_person_id' ";
         $result=mysqli_query($con,$Sql_Query);
      
         $data=array();

         while($row=mysqli_fetch_assoc($result))
           {
               
              array_push($data, array("user_id"=>$row["id"]));

           }

      header('Content-Type:Application/json');

   //   print(json_encode(array("status"=>"true" , "data" =>$data)));
   print(json_encode(($data)));
     }
}

function searchHome()
{
    include "connect.php";
    $access = $_POST["access"];
    $user_id = $_POST["user_id"];
    $query_base64 = $_POST["query_base64"];
    $time_date = $_POST["time_date"];
    $sata = [];

    $Sql_Query = "SELECT * FROM user_post ";
    $result = mysqli_query($con, $Sql_Query);
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($data, ["video_name" => $row["video_name"]]);
    }

    header("Content-Type:Application/json");
    print json_encode(["data" => $data]);
}

function channelCreation()
{
    include "connect.php";
    $channel_name = $_POST["channel_name"];
    $access = $_POST["access"];
    $user_id = $_POST["user_id"];
    $time_date = $_POST["time_date"];
    $data = [];

    $Sql_Query = "INSERT INTO channels ( channel_name , user_id, created_date) VALUES ( '$channel_name', '$user_id', '$time_date')";
    if (mysqli_query($con, $Sql_Query)) {
        // success and get channel_id

        $SQL_QUERY = "SELECT * FROM channels WHERE user_id = '$user_id'";
        $result = mysqli_query($con, $SQL_QUERY);
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($data, [
                "message" => "success",
                "channel_id" => $row["id"],
                "channel_name" => $row["channel_name"],
                "created_date" => $row["created_date"],
            ]);
        }

        header("Content-Type:Application/json");
        print json_encode(["status" => "true", "data" => $data]);
    } else {
        // failure
        echo "failure";
    }
}

function getChannels()
{
    include "connect.php";
    $user_id = $_POST["user_id"];
    $data = [];
    $SQL_QUERY = "SELECT * FROM channels WHERE user_id = '$user_id'";
    $result = mysqli_query($con, $SQL_QUERY);
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($data, [
            "message" => "success",
            "channel_id" => $row["id"],
            "channel_name" => $row["channel_name"],
            "created_date" => $row["created_date"],
        ]);
    }

    header("Content-Type:Application/json");
    print json_encode(["status" => "true", "data" => $data]);
}

?>
