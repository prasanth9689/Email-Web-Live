<?php
session_start();

if (isset($_SESSION["username"])) {
    header("Location: https://skyblue.co.in/mail/pages/dashboard/index.php");
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Secure Business Email for your Organization | Skyblue Mail</title>
	<link rel="stylesheet" href="../assets/mail/css/styles.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
		integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
		<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Ubuntu" />
</head>
<body>
	
    <div class="Loading" id="Loading"></div>

	<div class="header2">

		<div class="leftSide">
			 <div class="left">
				   <a href="#" class="logo"><img class="logo" src="../assets/mail/img/logo4.png" alt="" style="height: 30px;"></a>
				   <a href="#" class="logo1"><img class="logo1" src="../assets/mail/img/skyblue1.png" alt="" style="height: 60px;"></a>
			 </div>
		</div>

		<a href="../mail/pages/registration/signup.php" target="_blank" rel="noopener noreferrer" style="text-decoration: none;">
			<div class="phone-support-text"  style="margin-top: 20px;">
				<button class="button-green" ta onclick="openPrice()" type="submit" id="btn-cr">Create an account</button>
			</div>
		</a>

	</div>

	<div class="container">

		<div class="row">

			<div class="col-sm left-side" style="height: 400px;">
				<img class="left-side-img" src="../assets/mail/img/img1.png" width="500px" height="500px" />
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
									class="edit username" autocomplete="new-password">
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


	<script>
		const button = document.getElementById('signUpButton');
        button.addEventListener('click', function () {

    var email = document.form.email.value;
    var password = document.form.password.value;

    if (email.length == "") {
        var email = document.getElementById("email");
        document.getElementById("email").className = document.getElementById("email").className + " error";  // this adds the error class
        email.focus();
        email.scrollIntoView();
        document.getElementById('error-email').style.display = 'block';
        return false;
    }



    if (password.length == "") {
        var password = document.getElementById("password");
        document.getElementById("password").className = document.getElementById("password").className + " error";  // this adds the error class
        password.focus();
        password.scrollIntoView();
        document.getElementById('error-password').style.display = 'block';
        return false;
    }

    var textView = document.getElementById("text");
    textView.style.display = "none";
    progressCircle.classList.remove("hidden");

    // Top progressbar enable
    var topProgressbar = document.getElementById("Loading");
    topProgressbar.style.display = "block";

	postApi();
});

function postApi(){

	        const acc = "user_login";
            const email = document.getElementById('email').value;
			const password = document.getElementById('password').value;

	const data = {
                acc: acc,
                email: email,
                password: password
            };

            // POST request to the API
            fetch('https://skyblue.co.in/mail/mail.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
                .then(response => response.json())
                .then(data => {

					var topProgressbar = document.getElementById("Loading");
                    topProgressbar.style.display = "none";


                    console.log(`Message: ${data.message}`);
                    const status = +`${data[0].status}`;
                    console.log("status : " + status)

                    if (parseInt(status) == 1) {
                        // window.open("https://skyblue.co.in/login/otp_verification.html?email="+email,"_self");
                        window.open("https://skyblue.co.in/mail/pages/dashboard/", "_self");
                    }

                    if (status == 2) {
                        const responseMessage = document.getElementById('message');
                        responseMessage.textContent = `${data[0].message}`;
                        responseMessage.style.display = "block";
                    }

                    var textView = document.getElementById("text");
                    textView.style.display = "block";
                    progressCircle.classList.add("hidden");
                })
				 .catch((error) => {
                    console.error('Error:', error);
                    const responseMessage = document.getElementById('message');
                    responseMessage.textContent = 'Error:' + error;
                    responseMessage.style.display = "block";

                    var textView = document.getElementById("text");
                    textView.style.display = "block";
                    progressCircle.classList.add("hidden");

					var topProgressbar = document.getElementById("Loading");
                    topProgressbar.style.display = "none";
                });
              
}

const emailElement = document.getElementById("email");
const passwordElement = document.getElementById("password");

emailElement.addEventListener("input", function () {
    document.getElementById("email").className = document.getElementById("email").className.replace(" error", "");
    document.getElementById('error-email').style.display = 'none';

    const responseMessage = document.getElementById('message');
    responseMessage.style.display = "none";
});

passwordElement.addEventListener("input", function () {
    document.getElementById("password").className = document.getElementById("password").className.replace(" error", "");
    document.getElementById('error-password').style.display = 'none';

    const responseMessage = document.getElementById('message');
    responseMessage.style.display = "none";
});

function validateEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}
</script>

</body>
</html>