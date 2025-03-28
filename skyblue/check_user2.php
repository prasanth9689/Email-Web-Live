<?php 

 include 'connect.php';

 
 $email = $_POST['email'];


$query = ("SELECT ? FROM users WHERE email = '$email'");
	global $con;
	if($stmt = $con->prepare($query)){
		$stmt->bind_param("s",$email_person_id);
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