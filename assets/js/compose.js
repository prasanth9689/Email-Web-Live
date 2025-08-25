function showView(viewId) {
    document.getElementById("emailDetails").innerHTML = "";
    document.querySelectorAll('.view').forEach(v => v.classList.remove('active'));
    document.getElementById(viewId).classList.add('active');
}

const message = document.getElementById('editor');

message.addEventListener('focus', function () {
    if (!message.classList.contains('active')) {
        message.textContent = '';
        message.classList.add('active');
    }

  
});

message.addEventListener('blur', function () {
    if (message.textContent.trim() === '') {
        message.textContent = 'Write your message here...';
        message.classList.remove('active');
    }
});


const sendMail = document.getElementById('sendMail');
sendMail.addEventListener('click', function () {
    var toAddress = document.getElementById("emailInput").value.trim();
    var subject = document.getElementById("subject").value;
    const message = document.getElementById("editor").innerHTML;
    const holder = "Write your message here...";

    if (toAddress.length === 0) {
        alert("Please add To email address. To address shouldn't be empty.");
        return;
    }

    if (!isValidEmail(toAddress)) {
        alert("Invalid email address");
        return;
      } 

      if (subject.length == "") {
        alert("Please type subject. Subject shouldn't be empty.");
        return;
    }

    if (message.trim() === holder) {
        alert("Please write something. Message shouldn't be empty.");
        return;
    }

    var textView2 = document.getElementById("text-sendMail");
    textView2.style.display = "none";

    var progressBar = document.getElementById("progressCircleGreen");
    progressBar.style.display = "block";

    var topProgressbar = document.getElementById("Loading");
    topProgressbar.style.display = "block";

    sendMessage(toAddress, subject, message);
});


function sendMessage(toAddress, subject, message) {
    const acc = "send_mail";
    
    const data = {
        acc: acc,
        to_address: toAddress,
        subject: subject,
        message: message
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
                // Success
                showMessage(`${data[0].message}`, 4000);

                var viewId = "home";
                document.getElementById("emailDetails").innerHTML = "";
                document.querySelectorAll(".view").forEach(v => v.classList.remove("active"));
                document.getElementById(viewId).classList.add("active");
            }

            if (status == 2) {
               // Failure
               alert(`${data[0].message}`);
            }

            var textView2 = document.getElementById("text-sendMail");
            var progressBarCircle = document.getElementById("progressCircleGreen");
            textView2.style.display = "block";
            progressBarCircle.classList.add("hidden");
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

function showMessage(text, duration = 3000) {
    const box = document.getElementById("messageBox");
    box.textContent = text;
    box.style.display = "block";
  
    setTimeout(() => {
      box.style.display = "none";
    }, duration);
  }

function isValidEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

function onClickCC() {
    var activateCC = document.getElementById("activateCC");
    var hideCcText = document.getElementById("ccText");
    activateCC.style.display = "block";
    hideCcText.style.display = "none";
  }

  function onClickBCC() {
    var activateBCC = document.getElementById("activateBCC");
    var hideBccText = document.getElementById("bccText");
    activateBCC.style.display = "block";
    hideBccText.style.display = "none";
  }


