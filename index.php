<?php

function getUserIP(): string {
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        return $_SERVER['HTTP_CF_CONNECTING_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // Can be a comma-separated list
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($ips[0]);
    } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
        return $_SERVER['REMOTE_ADDR'];
    } else {
        return 'UNKNOWN';
    }
}


$filePath = "views_.txt";
$count = file_exists($filePath) ? (int)file_get_contents($filePath) : 0;

$count++;

file_put_contents($filePath, $count);

file_put_contents("log_.txt", date("Y-m-d H:i:s") . ' IP: ' . getUserIP(). " - Viewed\n", FILE_APPEND);
?>

<?php
session_start();
if (isset($_SESSION["username"])) {
    header("Location: https://mail.skyblue.co.in/pages/dashboard/index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Secure Business Email for your Organization | Skyblue Mail</title>
	<link rel="stylesheet" href="../assets/css/styles.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
		integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
		<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Ubuntu" />
</head>
<body>
	
    <div class="Loading" id="Loading"></div>

	<div class="header2">

		<div class="leftSide">
			 <div class="left">
				   <a href="#" class="logo"><img class="logo" src="../assets/img/logo4.png" alt="" style="height: 30px;"></a>
				   <a href="#" class="logo1"><img class="logo1" src="../assets/img/skyblue1.png" alt="" style="height: 60px;"></a>
			 </div>
		</div>

		<a href="../pages/registration/signup.php" target="_blank" rel="noopener noreferrer" style="text-decoration: none;">
			<div class="phone-support-text"  style="margin-top: 20px;">
				<button class="button-green" ta onclick="openPrice()" type="submit" id="btn-cr">Create an account</button>
			</div>
		</a>

	</div>

	<div class="container">

		<div class="row">

			<div class="col-sm left-side" style="height: 400px;">
				<img class="left-side-img" src="../assets/img/img1.png" width="500px" height="500px" />
			</div>
            
			<div class="col-sm right-side">
				<div class="container-2"
					style="background-color: azure; width: 100%; margin-right: 50%; padding-left: 100px;">

					<div id="frm">
						<div class="login">
						<h1><strong>Login</strong></h1>	
						</div>

						<form class="form1" name="form" autocomplete="new-password">
							<p>

								<input name="email" style="margin-top:20px;" id="email" type="text" placeholder="Email"
									class="edit username">
							</p>
							<p class="error-email" id="error-email">Enter valid email or mobile number.</p>
							<p></p>

							<p>
								<input style="margin-top:5px;" name="password" id="password" type="password"
									placeholder="Password" class="edit password" autocomplete="new-password">
							</p>
							<p class="error-password" id="error-password">Enter password.</p>
							<p></p>
							<div class="message" id="message">
							</div>
							<p>
							</p>
							<div id="signUpButton" type="submit" class="btn-submit button-blue" onclick="showProgress()">
								<div id="text"> CONTINUE </div>
								<div id="progressCircle" class="hidden" style="margin-top: 30px; margin-left: 20px;">
								</div>
							</div>

							<div id="signUpButton" type="submit" class="btn-forgot" onclick="showProgress()">
								<div id="text"> Forgot password? </div>
							</div>
					</div>
					<p></p>
					</form>
				</div>
			</div>
		</div>
	</div>
	</div>

    <div class="fixed-bottom">
    <?php

                  global $line;

                  $fh = fopen('views_.txt','r');
                  while ($line = fgets($fh)) { 
                      echo ""."Total visitors : ".($line)."";
                  }

                fclose($fh);
                 ?>
  </div>
  <script src="../assets/js/login.js"></script>
</body>
</html>
