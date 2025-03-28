<!DOCTYPE html>
<html>


<style>

  .main-text{
  color:blue;
  }

</style>




<body>

<?php

global $line;

$fh = fopen('https://skyblue.co.in/skyblue/counter.txt','r');
while ($line = fgets($fh)) { 

 echo "<h1 class='main-text'>".($line)."<h1/>";



}

fclose($fh);

?>


</body>
</html>
