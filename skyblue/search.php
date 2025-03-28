<?php

   include 'connect.php';
 
  $user_id = $_POST['user_id'];
  $query_format = $_POST['query_format'];
 
  $Sql_Query = "SELECT * FROM users WHERE name LIKE '$query_format' ORDER BY RAND() LIMIT 15 ";
 
 
  $result=mysqli_query($con,$Sql_Query);
 
  $data=array();

  while($row=mysqli_fetch_assoc($result)){
    
  array_push($data, array( "user_id"=>$row["id"] , "user_name"=>$row["name"] , "user_profile"=>$row["profile_url"]));

 }
 
    header('Content-Type:Application/json');
    print(json_encode(array("data" =>$data)));
    
?>

 