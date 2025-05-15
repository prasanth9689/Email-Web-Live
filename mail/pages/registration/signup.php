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
	<title>Skyblue Mail Signup</title>
	<link rel="stylesheet" href="/assets/mail/css/styles.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
		integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Ubuntu" />
	<script src="https://www.gstatic.com/firebasejs/8.3.1/firebase-app.js"></script>
	<script src="https://www.gstatic.com/firebasejs/8.3.1/firebase-analytics.js"></script>
	<script src="https://www.gstatic.com/firebasejs/8.3.1/firebase-auth.js"></script>
	<script src="https://www.gstatic.com/firebasejs/8.3.1/firebase-firestore.js"></script>
	<script src="https://www.google.com/recaptcha/api.js"></script>

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
</head>

<body>
	<div class="Loading" id="Loading"></div>

	<div class="header2">
		<div class="leftSide">
			<div class="left">
				<a href="#" class="logo"><img class="logo" src="/assets/mail/img/logo4.png" alt=""
						style="height: 30px;"></a>
				<a href="#" class="logo1"><img class="logo1" src="/assets/mail/img/skyblue1.png" alt=""
						style="height: 60px;"></a>
			</div>
		</div>

		<a href="/index.html" target="_blank" rel="noopener noreferrer" style="text-decoration: none;">
			<div class="phone-support-text button-primary" style="margin-top: 20px;">
				<button class="button-blue" onclick="openPrice()" type="submit" id="btn-cr">Login</button>
			</div>
		</a>
	</div>

	<style>
		#otpLayout {
			display: none;
		}

		#layoutMobile {
			display: block;
		}

		#usernameLayout {
			position: fixed;
			display: none;
		}

		.error-field2 {
			color: #ff3500;
			font-size: 12px;
			margin-top: -13px;
			margin-bottom: 10px;
			display: block;
			background-color: rgb(255, 255, 255);
		}
	</style>

	<div class="container" id="layoutMobile">
		<div class="row">

			<div class="col-sm left-side" style="height: 400px;">
				<img class="left-side-img" src="/assets/mail/img/register1.png" style="margin-top: 20px;" width="400px"
					height="400px" />
			</div>

			<div class="col-sm right-side">
				<div class="container-2"
					style="background-color: azure; width: 100%; margin-right: 50%; padding-left: 100px;">

					<div id="frm">
						<div class="login">
							<h2><strong>Create an account now</strong></h2>
						</div>

						<form class="form1" name="form" autocomplete="new-password">
							<p>
								<input name="mobile" style="margin-top:5px; caret-color: #2971fc;" id="mobile"
									type="tel" maxlength="13" value="" placeholder="Enter mobile no"
									class="edit username" autocomplete="tel" autofocus>
							</p>
							<p class="error-field" id="error-field">Enter mobile number.</p>
							<div id="showCapcha"></div>
							<div id="recaptcha-container"></div>
							<p></p>
							<p></p>
							<div class="message" id="message">
							</div>
							<p>
							</p>
							<div id="button-phone" type="submit" class="btn-submit button-blue"
								onclick="showProgress()">
								<div id="text"> CONTINUE </div>
								<div id="progressCircle" class="hidden" style="margin-top: 30px; margin-left: 20px;">
								</div>
							</div>
					</div>
					<p></p>
					</form>
				</div>
			</div>
		</div>
	</div>

	<script>
		const button = document.getElementById('button-phone');
		button.addEventListener('click', function () {

			var mobile = document.form.mobile.value;

			if (mobile.length == "") {
				var mobile = document.getElementById("mobile");
				document.getElementById("mobile").className = document.getElementById("mobile").className + " error";  // this adds the error class
				mobile.focus();
				mobile.scrollIntoView();
				document.getElementById('error-field').style.display = 'block';
				return false;
			}

			// if (!/^[0-9]+$/.test(mobile)) {
			// 	var error_message = document.getElementById("error-field");
			// 	document.getElementById("mobile").className = document.getElementById("mobile").className + " error";
			// 	error_message.textContent = "Please enter mobile number only.";
			// 	error_message.style.display = 'block';
			// 	//alert("Please only enter numeric characters only for your Age! (Allowed input:0-9)")
			// 	return false;
			// }

			var textView = document.getElementById("text");
			textView.style.display = "none";
			progressCircle.classList.remove("hidden");

			// Top progressbar enable
			var topProgressbar = document.getElementById("Loading");
			topProgressbar.style.display = "block";

			checkUser();
		});


		function checkUser() {
			const acc = "mail_check_user";
			const mobile = document.getElementById('mobile').value;

			const data = {
				acc: acc,
				mobile: mobile
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
					console.log(`Message: ${data.message}`);
					const status = +`${data[0].status}`;
					console.log("status : " + status)

					if (parseInt(status) == 1) {
						phoneSendOTP();
					}

					if (status == 2) {
						const responseMessage = document.getElementById('message');
						responseMessage.textContent = `${data[0].message}`;
						responseMessage.style.display = "block";

						var topProgressbar = document.getElementById("Loading");
						topProgressbar.style.display = "none";

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

					var progressBar = document.getElementById("progressCircle");
					progressBar.style.display = "none";

					location.reload();
				});
		}

		const mMobileNo = document.getElementById("mobile");

		mMobileNo.addEventListener("input", function () {
			document.getElementById("mobile").className = document.getElementById("mobile").className.replace(" error", "");
			document.getElementById('error-field').style.display = 'none';

			const responseMessage = document.getElementById('message');
			responseMessage.style.display = "none";


		});

		// firebaseConfig is https://console.firebase.google.com/u/0/project/ -> Project setting  { IN TITLE : Your apps }
		const firebaseConfig = {
			apiKey: "AIzaSyBvXW2105hNadt0V9Jud6ViutKYAV7eTNo",
			authDomain: "skyblue-email.firebaseapp.com",
			projectId: "skyblue-email",
			storageBucket: "skyblue-email.firebasestorage.app",
			messagingSenderId: "968612558123",
			appId: "1:968612558123:web:633c1243b35a739c93816e",
			measurementId: "G-MJ6SRLBK36"
		};
		firebase.initializeApp(firebaseConfig);
		firebase.analytics();
		// firebase.auth().languageCode = 'th';
		window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container');

		function phoneSendOTP() {
			console.log("Otp send started");
			var topProgressbar = document.getElementById("Loading");
			topProgressbar.style.display = "none";
			//	alert("hello");

			const phoneNumber = document.getElementById("mobile").value;
			const appVerifier = window.recaptchaVerifier;
			firebase.auth().signInWithPhoneNumber(phoneNumber, appVerifier)
				.then((confirmationResult) => {

					console.log(confirmationResult)
					window.confirmationResult = confirmationResult;
					document.getElementById('recaptcha-container').style.display = 'none';

					const layoutMobile = document.getElementById("layoutMobile");
					layoutMobile.style.display = 'none';
					console.log("Otp send success");

					onResendOtp();

					const otpLayout = document.getElementById("otpLayout");
					otpLayout.style.display = 'block';

				}).catch((error) => {
					document.getElementById("message").innerHTML = "" + error.message
					document.getElementById("message").style.display = 'block';
					console.log("Error :" + error.message);

					document.getElementById('recaptcha-container').style.display = 'none';

					var topProgressbar = document.getElementById("Loading");
					topProgressbar.style.display = "none";

					var textView = document.getElementById("text");
					textView.style.display = "block";
					progressCircle.classList.add("hidden");


				});
		}


	</script>

	<!-- 
	
	lt 2 
	
	-->

	<style>
		.error-otp {
			color: #ff3500;
			font-size: 12px;
			margin-top: -15px;
			display: none;
		}

		#progressBar {
			width: 30px;
			height: 30px;
			border: 4px solid #ffffff;
			border-top: 4px solid var(--primary-blue);
			border-radius: 50%;
			animation: spin 0.5s linear infinite;
			transform: translate(-50%, -50%);
		}
	</style>

	<div class="container" id="otpLayout" style="background-color: white;">
		<div class="row">
			<div class="col-sm left-side" style="height: 400px;">
				<div style="margin-top: 20px; background-color: white;" width="400px" height="400px">
				</div>
			</div>

			<div class="col-sm right-side">

				<div class="container-2"
					style="background-color: azure; width: 100%; margin-right: 50%; padding-left: 100px;">

					<div id="frm">
						<div class="login">
							<h3><strong>Enter OTP for verification.</strong></h3>
							<h5>OTP sent to +91 8940570614</h5>
						</div>

						<div class="form1" name="form" autocomplete="new-password">
							<p>

								<input name="otp" style="margin-top:10px;" id="otp" type="text"
									placeholder="Enter 6 digit OTP" class="edit username" maxlength="6"
									autocomplete="new-password">
							</p>
							<p class="error-otp" id="error-otp">Enter OTP Code.</p>
							<p></p>
							<p></p>

							<style>
								.msg-otp {
									border-radius: 5px;
									background-color: #ff0032;
									color: white;
									padding: 6px;
									text-align: end;
									display: block;
									margin-bottom: -15px;
									display: none;
								}
							</style>
							<div class="msg-otp" id="msg-otp">
							</div>
							<p>
							<p style="font-size: 14px; text-align: end; display: none; cursor: pointer;"
								id="resend-otp-main" onclick="onResendOtp()">Resend OTP</p>

							<div style=" font-size: 14px; text-align: end;" id="timer-con">
								<p style="margin-right: 10px;" id="timer"></p>
							</div>

							</p>
							<div id="button-otp" type="submit" class="btn-submit button-blue" onclick="showProgress()">
								<div id="text-otp"> VERIFY </div>
								<div id="progressBar" class="hidden" style="margin-top: 30px; margin-left: 20px;">
								</div>
							</div>
						</div>
						<p></p>
					</div>
				</div>

			</div>
		</div>
	</div>

	<script>
		const buttonVerifyOtp = document.getElementById("button-otp");

		buttonVerifyOtp.addEventListener("click", function () {
			event.preventDefault();

			vertify_otp();
		});

		function vertify_otp() {
			const code = document.getElementById("otp").value;

			if (code.length == "") {
				var otp = document.getElementById("otp");
				document.getElementById("otp").className = document.getElementById("otp").className + " error";  // this adds the error class
				otp.focus();
				otp.scrollIntoView();
				document.getElementById('error-otp').style.display = 'block';
				return false;
			}



			var textView2 = document.getElementById("text-otp");
			textView2.style.display = "none";

			//	progressCircle22.classList.remove("hidden");

			var progressBar = document.getElementById("progressBar");
			progressBar.style.display = "block";

			var topProgressbar = document.getElementById("Loading");
			topProgressbar.style.display = "block";

			confirmationResult.confirm(code).then((result) => {
				console.log(result)
				const user = result.user;
				alert('OTP verification success.');
				document.getElementById('otpLayout').style.display = 'none';
				document.getElementById("message").innerHTML = "Verified";

				var topProgressbar = document.getElementById("Loading");
				topProgressbar.style.display = "none";

				document.getElementById('usernameLayout').style.display = 'block';
			}).catch((error) => {
				document.getElementById("msg-otp").innerHTML = "" + error.message;
				document.getElementById("msg-otp").style.display = "block";
				//	alert('error');

				var textView = document.getElementById("text-otp");
				textView.style.display = "block";

				var progressBar = document.getElementById("progressBar");
				progressBar.style.display = "none";

				var topProgressbar = document.getElementById("Loading");
				topProgressbar.style.display = "none";
			});
		}


		function runTimer() {
			var timeLeft = 30;
			var elem = document.getElementById('timer');

			var timerId = setInterval(countdown, 1000);

			function countdown() {
				if (timeLeft == -1) {
					clearTimeout(timerId);
					reSendOtpView();
				} else {
					elem.innerHTML = 'Re-send OTP  ' + timeLeft + ' Seconds';
					timeLeft--;
				}
			}
		}

		function reSendOtpView() {
			document.getElementById('timer-con').style.display = 'none';
			document.getElementById('resend-otp-main').style.display = 'block';
		}

		function onResendOtp() {
			document.getElementById('timer-con').style.display = 'block';
			document.getElementById('resend-otp-main').style.display = 'none';
			runTimer();

		}

	</script>

	<br><br>

	<div class="container" id="usernameLayout" style="background-color: white;">
		<div class="row">
			<div class="col-sm left-side" style="height: 400px;">
				<div style="margin-top: 20px; background-color: white;" width="400px" height="400px">
				</div>
			</div>





			<style>
				.error-name3 {
					color: #ff3500;
					font-size: 12px;
					margin-top: -13px;
					margin-bottom: 10px;
					display: none;
					background-color: rgb(255, 255, 255);
				}

				.error-email-address {
					color: #ff3500;
					font-size: 12px;
					margin-top: -13px;
					margin-bottom: 10px;
					display: none;
					background-color: rgb(255, 255, 255);
				}

				.error-dob {
					color: #ff3500;
					font-size: 12px;
					margin-top: -13px;
					margin-bottom: 10px;
					display: none;
					background-color: rgb(255, 255, 255);
				}

				.error-gender {
					color: #ff3500;
					font-size: 12px;
					margin-top: -13px;
					margin-bottom: 10px;
					display: none;
					background-color: rgb(255, 255, 255);
				}

				.error-username-pass {
					color: #ff3500;
					font-size: 12px;
					margin-top: -13px;
					margin-bottom: 10px;
					display: none;
					background-color: rgb(255, 255, 255);
				}

				.error-username-cpass {
					color: #ff3500;
					font-size: 12px;
					margin-top: -13px;
					margin-bottom: 10px;
					display: none;
					background-color: rgb(255, 255, 255);
				}
			</style>


			<div class="col-sm right-side">

				<div class="container-2"
					style="background-color: azure; width: 100%; margin-right: 50%; padding-left: 100px;">

					<div id="frm" style="padding-top: 10px; padding-bottom: 20px; width:68%;">

						<!-- Header -->
						<div class="login">
							<h3><strong style="font-weight: 700;">Signup</strong></h3>
						</div>

						<!-- Main form -->
						<form class="form1" name="form2" autocomplete="new-password">

							<!-- Name -->
							<p style="background-color: white;">
								<input name="name2" style="margin-top:0px;" id="name2" type="text"
									placeholder="Enter name" class="edit username" autocomplete="new-password">
							</p>

							<p class="error-name3" id="error-name3">Enter name.</p>

							<!-- Email address -->
							<p style="background-color: white;">
								<!-- <input name="email_address" style="margin-top:0px;" id="email_address" type="text"
									placeholder="Enter new email address" class="edit username"
									autocomplete="new-password"> -->

							<div class="input-group mb-3">
								<input type="text" name="email_address" id="email_address"
									class="form-control edit username" placeholder="Enter new username"
									aria-label="Enter new username" aria-describedby="basic-addon2">
								<div class="input-group-append">
									<span class="input-group-text" id="basic-addon2">@skyblue.co.in</span>
								</div>
							</div>
							</p>

							<p class="error-email-address" id="error-email-address">Enter email address.</p>

							<!-- Date  -->
							<p style="background-color: white;">
								<input name="dob" style="margin-top:0px;" id="dob" type="date" placeholder="DD/MM/YYYY"
									class="edit username" autocomplete="new-password">
							</p>

							<p class="error-dob" id="error-dob">Select date of birth</p>

							<!-- Gender -->
							<p>
							<!-- <div class="box" id="gender" style="margin-top: 0px;">
								<select id="select" class="required">
									<option value="">Select Gender</option>
									<option value="Male">Male</option>
									<option value="Female">Female</option>
									<option value="Transgender">Transgender</option>
								</select>
							</div> -->
							<div class="box" style="margin-top: 0px;">
							<select id="gender">
								<option value="">Select Gender</option>
									<option value="Male">Male</option>
									<option value="Female">Female</option>
									<option value="Transgender">Transgender</option>
							  </select>
							</div> 
							</p>

							<p class="error-gender" id="error-gender">Select gender.</p>

							<!-- Password -->
							<p style="background-color: white;">
								<input name="password" style="margin-top:0px;" id="password" type="text"
									placeholder="Enter password" class="edit username" autocomplete="new-password">
							</p>

							<p class="error-username-pass" id="error-username-pass">Enter password.</p>

							<!-- Confirm password -->
							<p style="background-color: white;">
								<input name="password2" style="margin-top:0px;" id="password2" type="text"
									placeholder="Enter confirm password" class="edit username"
									autocomplete="new-password">
							</p>

							<p class="error-username-cpass" id="error-username-cpass">Enter confirm password.</p>

							<p></p>
							<p></p>
							<style>
								.message-username {
									border-radius: 5px;
									background-color: #ff0032;
									color: white;
									padding: 6px;
									text-align: end;
									display: block;
									margin-bottom: -15px;
									display: none;
								}
							</style>
							<div class="message-username" id="message-username">
							</div>
							<p>
							</p>
							<div id="button-username" type="submit" class="btn-submit button-blue"
								onclick="showProgress()">
								<div id="text5"> Lets Start </div>

								<style>
									#progressCircle5 {
										width: 30px;
										height: 30px;
										border: 4px solid #ffffff;
										border-top: 4px solid var(--primary-blue);
										border-radius: 50%;
										animation: spin 0.5s linear infinite;
										transform: translate(-50%, -50%);
									}
								</style>

								<div id="progressCircle5" class="hidden" style="margin-top: 30px; margin-left: 20px;">
								</div>
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
		const buttonUsername = document.getElementById('button-username');
		buttonUsername.addEventListener('click', function () {

			// Top progressbar show
			var topProgressbar = document.getElementById("Loading");
			topProgressbar.style.display = "block";

			var name2 = document.getElementById("name2").value;
			var email_address = document.getElementById("email_address").value;
			var dob = document.getElementById("dob").value;
			var gender = document.getElementById("gender").value;
			var password = document.getElementById("password").value;
			var password2 = document.getElementById("password2").value;


			if (name2.length == "") {
				var name2 = document.getElementById("name2");
				document.getElementById("name2").className = document.getElementById("name2").className + " error";  // this adds the error class
				name2.focus();
				name2.scrollIntoView();
				document.getElementById('error-name3').style.display = 'block';

				// To stop top progressbar
				var topProgressbar = document.getElementById("Loading");
				topProgressbar.style.display = "none";
				return false;
			}

			if (email_address.length == "") {
				var email_address = document.getElementById("email_address");
				document.getElementById("email_address").className = document.getElementById("email_address").className + " error";  // this adds the error class
				email_address.focus();
				email_address.scrollIntoView();
				document.getElementById('error-email-address').style.display = 'block';

				// To stop top progressbar
				var topProgressbar = document.getElementById("Loading");
				topProgressbar.style.display = "none";
				return false;
			}

			if (dob.length == "") {
				var dob = document.getElementById("dob");
				document.getElementById("dob").className = document.getElementById("dob").className + " error";  // this adds the error class
				dob.focus();
				dob.scrollIntoView();
				document.getElementById('error-dob').style.display = 'block';

				// To stop top progressbar
				var topProgressbar = document.getElementById("Loading");
				topProgressbar.style.display = "none";
				return false;
			}

			if (gender.length == "") {
				var gender = document.getElementById("gender");
				document.getElementById("gender").className = document.getElementById("gender").className + " error";  // this adds the error class
				gender.focus();
				gender.scrollIntoView();
				document.getElementById('error-gender').style.display = 'block';

				// To stop top progressbar
				var topProgressbar = document.getElementById("Loading");
				topProgressbar.style.display = "none";
				return false;
			}

			if (password.length == "") {
				var password = document.getElementById("password");
				document.getElementById("password").className = document.getElementById("password").className + " error";  // this adds the error class
				password.focus();
				password.scrollIntoView();
				document.getElementById('error-username-pass').style.display = 'block';

				// To stop top progressbar
				var topProgressbar = document.getElementById("Loading");
				topProgressbar.style.display = "none";
				return false;
			}

			if (password2.length == "") {
				var password2 = document.getElementById("password2");
				document.getElementById("password2").className = document.getElementById("password2").className + " error";  // this adds the error class
				password2.focus();
				ppassword2assword.scrollIntoView();
				document.getElementById('error-username-cpass').style.display = 'block';

				// To stop top progressbar
				var topProgressbar = document.getElementById("Loading");
				topProgressbar.style.display = "none";
				return false;
			}


			var textView3 = document.getElementById("text5");
			textView3.style.display = "none";
			progressCircle5.classList.remove("hidden");


			createUser();
		});

		function createUser() {
			const acc = "create_user";
			const mobile = document.getElementById('mobile').value;
			const personName = document.getElementById('name2').value;
			const userName = document.getElementById('email_address').value; // prasanth or person name (it cover prasanth@skyblue.co.in)
			const dob = document.getElementById('dob').value;
			var selectElement = document.getElementById('gender');
            var selectedValue = selectElement.value;
			const password = document.getElementById('password').value;

			const data = {
				acc: acc,
				mobile: mobile,
				name: personName,
				dob: dob,
				gender: selectedValue,
				password: password,
				username: userName
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
					console.log(`Message: ${data.message}`);
					const status = +`${data[0].status}`;
					console.log("status : " + status)

					if (parseInt(status) == 1) {
						 window.open("https://skyblue.co.in/mail/pages/dashboard/", "_self");
						// const responseMessage = document.getElementById('message-username');
						// responseMessage.textContent = `${data[0].message}`;
						// responseMessage.style.display = "block";

				//		alert("create user");
					}

					if (status == 2) {
						const responseMessage = document.getElementById('message-username');
						responseMessage.textContent = `${data[0].message}`;
						responseMessage.style.display = "block";

						var textView3 = document.getElementById("text5");
						textView3.style.display = "block";

						var progressBar = document.getElementById("progressCircle5");
						progressBar.style.display = "none";

						var topProgressbar = document.getElementById("Loading");
						topProgressbar.style.display = "none";
					}


				})
				.catch((error) => {

				});
		}

		// Input box click listener
		const mName2 = document.getElementById("name2");
		const mEmailAddress2 = document.getElementById("email_address");
		const mDob = document.getElementById("dob"); gender
		const mGender = document.getElementById("gender");
		const mPassword = document.getElementById("password");
		const mPassword2 = document.getElementById("password2");

		mName2.addEventListener("input", function () {
			document.getElementById("name2").className = document.getElementById("name2").className.replace(" error", "");
			document.getElementById('error-name3').style.display = 'none';

			const responseMessage = document.getElementById('message');
			responseMessage.style.display = "none";
		});

		mEmailAddress2.addEventListener("input", function () {
			document.getElementById("email_address").className = document.getElementById("email_address").className.replace(" error", "");
			document.getElementById('error-email-address').style.display = 'none';

			const responseMessage = document.getElementById('message');
			responseMessage.style.display = "none";
		});

		mDob.addEventListener("input", function () {
			document.getElementById("dob").className = document.getElementById("dob").className.replace(" error", "");
			document.getElementById('error-dob').style.display = 'none';

			const responseMessage = document.getElementById('message');
			responseMessage.style.display = "none";
		});

		mGender.addEventListener("input", function () {
			document.getElementById("gender").className = document.getElementById("gender").className.replace(" error", "");
			document.getElementById('error-gender').style.display = 'none';

			const responseMessage = document.getElementById('message');
			responseMessage.style.display = "none";
		});

		mPassword.addEventListener("input", function () {
			document.getElementById("password").className = document.getElementById("password").className.replace(" error", "");
			document.getElementById('error-username-pass').style.display = 'none';

			const responseMessage = document.getElementById('message');
			responseMessage.style.display = "none";
		});

		mPassword2.addEventListener("input", function () {
			document.getElementById("password2").className = document.getElementById("password2").className.replace(" error", "");
			document.getElementById('error-username-cpass').style.display = 'none';

			const responseMessage = document.getElementById('message');
			responseMessage.style.display = "none";
		});
	</script>
</body>

</html>