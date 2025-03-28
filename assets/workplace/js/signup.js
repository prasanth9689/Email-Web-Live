var ipAddress;

getIp();

function getIp(){
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

    const responseMessage = document.getElementById('message');
    responseMessage.style.display = "none";
    var mUserName = document.getElementById('name').value;
    var mEmail = document.getElementById('email').value;
    var mPassword = document.getElementById('password').value;

    if (mUserName.trim() == '') {
        var mUserName = document.getElementById('name');
        mUserName.focus();
        mUserName.scrollIntoView();
        document.getElementById('error-username').style.display = 'block';
        return;
    }

    if (mUserName.length < 5) {
        alert("Username must be at least 3 characters long!.");
        return;
    }
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

    var button = document.getElementById("actionButton");
    var textView = document.getElementById("text");
    textView.style.display = "none";
    progressCircle.classList.remove("hidden");
    postApi();
});


const userName = document.getElementById("name");
const email = document.getElementById("email");
const password = document.getElementById("password");

userName.addEventListener("input", function () {
    document.getElementById("name").className = document.getElementById("name").className.replace(" error", "");
    document.getElementById('error-username').style.display = 'none';
});

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

function postApi() {
    // TimeZone
    const timeZone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    console.log(timeZone); // user time zone, e.g., "Asia/Kolkata"
    const acc = "cr_master_signup";
    const mobile = "NULL";
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const ip = ipAddress;
    const date_time = getFormattedDateTime();

    const data = {
        acc: acc,
        mobile: mobile,
        name: name,
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
               window.open("https://skyblue.co.in/workplace/registration/otp_verification.html?email="+email,"_self");
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
        .catch((error) => {b 
            console.error('Error:', error);
            const responseMessage = document.getElementById('message');
            responseMessage.textContent = 'Error:' + error;
            responseMessage.style.display = "block";

            var textView = document.getElementById("text");
            textView.style.display = "block";
            progressCircle.classList.add("hidden");
        });
}

function getFormattedDate() {
    const currentDate = new Date();
    const day = String(currentDate.getDate()).padStart(2, '0');
    const month = String(currentDate.getMonth() + 1).padStart(2, '0'); // Months are zero-based
    const year = currentDate.getFullYear();
    
    return `${day}-${month}-${year}`;
}

function getFormattedTime() {
    const currentTime = new Date();
    const hours = String(currentTime.getHours()).padStart(2, '0');
    const minutes = String(currentTime.getMinutes()).padStart(2, '0');
    const seconds = String(currentTime.getSeconds()).padStart(2, '0');
    return `${hours}-${minutes}-${seconds}`;
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

function validateEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}