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

    if (!validateEmail(email)) {
        const responseMessage = document.getElementById('message');
        responseMessage.textContent = "Invalid email address.";
        responseMessage.style.display = "block";
        return;
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
});

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