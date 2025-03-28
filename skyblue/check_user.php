<?php 

 include 'connect.php';

 
 $mobile = $_POST['mobile'];


$query = ("SELECT ? FROM users WHERE mobile_no_full = '$mobile'");
	global $con;
	if($stmt = $con->prepare($query)){
		$stmt->bind_param("s",$mobile);
		$stmt->execute();
		$stmt->store_result();
		$stmt->fetch();
		 if($stmt->num_rows == 1)
              {
              	echo "0"; // mobile number already exits
              	$stmt->close();
              	return true;
              }
                 else
                     {
                     	echo "1"; // Newww
                     	 $stmt->close();
                 	     return false;
                     }
	}
 ?>