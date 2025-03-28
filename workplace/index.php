<?php
session_start();

if (isset($_SESSION["email"])) {
    header("Location: dashboard/index.php");
}

?>
<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title>Skyblue Workplace | Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no, maximum-scale=1.0">
    <meta name="theme-color" content="#f4f4f4">
    <meta name="msapplication-navbutton-color" content="#f4f4f4">

    <link rel="apple-touch-icon" sizes="180x180" href="../assets/workplace/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/workplace/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/workplace/img/favicon/favicon-16x16.png">
    <link rel="icon" href="../assets/workplace/img/favicon/favicon.ico">
    <link rel="manifest" href="../assets/workplace/img/favicon/site.webmanifest">

    <link rel="stylesheet" href="../assets/workplace/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/workplace/css/login.css">
    <script src="../assets/workplace/js/vendor/jquery-1.12.4.min.js"></script>
    <link href='https://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Open Sans' rel='stylesheet'>
    <script src="https://skyblue.co.in/assets/workplace/js/vendor/jquery-1.12.4.min.js"></script>
</head>

<body style="background-color:rgb(248 250 252 / var(--tw-bg-opacity, 1));">

    <?php
include_once "../assets/workplace/header.php";
?>


    <div id="frm" class="form" style="margin-top:50px;">
        <h3 class="login" style="margin-top:25px;"><strong>Login Skyblue Workplace</strong></h3>

        <form class="form" name="form" autocomplete="new-password">
            <p>

                <input name="email" style="margin-top:0px;" id="email" type="text"
                    placeholder="Email" class="edit username" autocomplete="new-password">
            <p class="error-email" id="error-email">Enter valid email or mobile number.</p>
            </p>

            <p>
                <input style="margin-top:5px;" name="password" id="password" type="password" placeholder="Password"
                    class="edit password" autocomplete="new-password">
            <p class="error-password" id="error-password">Enter password.</p>
            </p>
            <div class="message" id="message" style="
            border-radius: 5px; 
            background-color: #ff0032; 
            color: white;
            padding: 6px;
            text-align: end;
            display: block;
            margin-bottom: -15px;
            display: none;">
            </div>
            <p>
            <div id="signUpButton" type="submit" class="btn-submit" onclick="showProgress()">
                <div id="text"> SIGN IN </div>
                <div id="progressCircle" class="hidden" style="margin-top: 30px; margin-left: 20px;"></div>
            </div>
            </p>
        </form>
        <p>
            <a href="registration/signup.html" style="text-decoration: none;"><button type="submit" id="btn-cr">Create an account</button></a>
        </p>
    </div>

    <script>
        var ipAddress;

        getIp();

        function getIp() {
            fetch('https://api.ipify.org?format=json')
                .then(response => response.json())
                .then(data => {
                    console.log(data.ip);
                    ipAddress = data.ip;
                })
                .catch(error => {
                    console.error('Error fetching the IP address:', error);
                    document.getElementById('ip').innerText = 'Could not fetch IP address.';
                });
            return ipAddress;
        }

        const button = document.getElementById('signUpButton');
        button.addEventListener('click', function () {
            var mEmail = document.getElementById('email').value;
            var mPassword = document.getElementById('password').value;

            if (mEmail.trim() == '') {
                var mEmail = document.getElementById('email');
                mEmail.focus();
                mEmail.scrollIntoView();
                document.getElementById('error-email').style.display = 'block';
                return;
            }
            if (mEmail.trim() == '') {
                var mEmail = document.getElementById('email');
                mEmail.focus();
                mEmail.scrollIntoView();
                document.getElementById('error-email').style.display = 'block';
                return;
            }

            if (!validateEmail(mEmail)) {
                const responseMessage = document.getElementById('message');
                responseMessage.textContent = "Invalid email address.";
                responseMessage.style.display = "block";
                return;
            }

            if (mPassword.trim() == '') {
                var mPassword = document.getElementById('password');
                mPassword.focus();
                mPassword.scrollIntoView();
                document.getElementById('error-password').style.display = 'block';
                return;
            }

            var textView = document.getElementById("text");
            textView.style.display = "none";
            progressCircle.classList.remove("hidden");
            postApi();

        });

        function postApi() {
            // TimeZone
            const timeZone = Intl.DateTimeFormat().resolvedOptions().timeZone;
            console.log(timeZone); // user time zone, e.g., "Asia/Kolkata"
            const acc = "wrk_sign";
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const ip = ipAddress;
            const date_time = getFormattedDateTime();

            const data = {
                acc: acc,
                email: email,
                password: password,
                timeZone: timeZone,
                ip: ip,
                date_time: date_time
            };

            // POST request to the API
            fetch('https://skyblue.co.in/skyblue_main.php', {
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
                        // window.open("https://skyblue.co.in/login/otp_verification.html?email="+email,"_self");
                        window.open("https://skyblue.co.in/workplace/dashboard/", "_self");
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
                // .catch((error) => {
                //     console.error('Error:', error);
                //     const responseMessage = document.getElementById('message');
                //     responseMessage.textContent = 'Error:' + error;
                //     responseMessage.style.display = "block";

                //     var textView = document.getElementById("text");
                //     textView.style.display = "block";
                //     progressCircle.classList.add("hidden");
                // });
        }

        function getFormattedDateTime() {
            const currentDateTime = new Date();

            const day = String(currentDateTime.getDate()).padStart(2, '0');
            const month = String(currentDateTime.getMonth() + 1).padStart(2, '0'); // Months are zero-based
            const year = currentDateTime.getFullYear();

            const hours = String(currentDateTime.getHours()).padStart(2, '0');
            const minutes = String(currentDateTime.getMinutes()).padStart(2, '0');
            const seconds = String(currentDateTime.getSeconds()).padStart(2, '0');

            return `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`;
        }

        const email = document.getElementById("email");
        const password = document.getElementById("password");

        email.addEventListener("input", function () {
            document.getElementById("email").className = document.getElementById("email").className.replace(" error", "");
            document.getElementById('error-email').style.display = 'none';

            const responseMessage = document.getElementById('message');
            responseMessage.style.display = "none";
        });

        password.addEventListener("input", function () {
            document.getElementById("password").className = document.getElementById("password").className.replace(" error", "");
            document.getElementById('error-password').style.display = 'none';
        });

        function validateEmail(email) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        }


    </script>

    <style>
        .btn-submit {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 40px;

            font-size: 16px;
            width: 100%;
            color: #fff;
            background: #337ab7;
            background-color: #0085d5;
            border: rgb(195, 216, 255) 1px;
            color: white;
            padding: 10px 32px;
            border-radius: 10px;
            margin-top: 25px;
            margin-bottom: 10px;
            box-shadow: 7px 6px 28px 1px rgba(0, 0, 0, 0.24);
            cursor: pointer;
            outline: none;
            transition: 0.2s all;
        }

        .error-email {
            color: #ff3500;
            font-size: 12px;
            margin-top: -15px;
            display: none;
        }

        .error-password {
            color: #ff3500;
            font-size: 12px;
            margin-top: -15px;
            display: none;
        }

        .hidden {
            display: none;
        }

        #progressCircle {
            width: 30px;
            height: 30px;
            border: 4px solid #ffffff;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            animation: spin 0.5s linear infinite;
            transform: translate(-50%, -50%);
        }

        @keyframes spin {
            0% {
                transform: translate(-50%, -50%) rotate(0deg);
            }

            100% {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }
    </style>

    <script src="../assets/workplace/js/login.js"></script>
</body>

</html>