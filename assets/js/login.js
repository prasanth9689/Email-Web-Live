    const passwordInput = document.getElementById('password');

      passwordInput.addEventListener('keydown', function(event) {
      if (event.key === 'Enter' && passwordInput.value.trim() !== '') {
        event.preventDefault(); 
      
         var textView = document.getElementById("text");
         textView.style.display = "none";
         progressCircle.classList.remove("hidden");

         var topProgressbar = document.getElementById("Loading");
         topProgressbar.style.display = "block";

	     postApi();
      }
    });

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
            fetch('https://mail.skyblue.co.in/mail.php', {
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
                        window.open("https://mail.skyblue.co.in/pages/dashboard/", "_self");
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