<?php
//$url = "https://skyblue.co.in/";


for ($x = 0; $x <= 10; $x++) {
    $url = "https://skyblue.co.in/";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    } else {
        echo $response;
    }
    
    curl_close($ch);
  }

  

?>
