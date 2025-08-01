//====================================================================================================
// Login started 
//====================================================================================================

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
//====================================================================================================
// Login end / delete start
//====================================================================================================

const form = document.querySelector("form"),
fileInput = document.querySelector(".file-input"),
progressArea = document.querySelector(".progress-area"),
uploadedArea = document.querySelector(".uploaded-area");

form.addEventListener("click", () =>{
  fileInput.click();
});

let totalAttachments = [];

fileInput.onchange = ({ target }) => {
  const newFiles = Array.from(target.files);

    // Merge with previously attached files
    const allFiles = totalAttachments.concat(newFiles);


  // Calculate total size
  let totalSize = allFiles.reduce((acc, file) => acc + file.size, 0);

  const maxSize = 25 * 1024 * 1024; 

  if (totalSize > maxSize) {
    alert("Total file size exceeds 25MB limit.");
    fileInput.value = ""; // Allow reselection
    return;
  }

  // Accept new files
  totalAttachments = allFiles;

  newFiles.forEach(file => {
    uploadFile(file, file.name);
    console.log("Uploading:", file.name);
    // uploadFile(file);
  });
};


function uploadFile(file, name){
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "upload.php");

  xhr.upload.addEventListener("progress", ({ loaded, total }) => {
    let fileLoaded = Math.floor((loaded / total) * 100);
    let fileTotal = Math.floor(total / 1000);
    let fileSize = (fileTotal < 1024)
      ? fileTotal + " KB"
      : (loaded / (1024 * 1024)).toFixed(2) + " MB";

    let progressHTML = `<li class="row">
                          <i class="fas fa-file-alt"></i>
                          <div class="content">
                            <div class="details">
                              <span class="name">${name} • Uploading</span>
                              <span class="percent">${fileLoaded}%</span>
                            </div>
                            <div class="progress-bar">
                              <div class="progress" style="width: ${fileLoaded}%"></div>
                            </div>
                          </div>
                        </li>`;
    uploadedArea.classList.add("onprogress");
    progressArea.innerHTML = progressHTML;

    if (loaded === total) {
      progressArea.innerHTML = "";
      let uploadedHTML = `<li class="row">
                            <div class="content upload">
                              <i class="fas fa-file-alt"></i>
                              <div class="details">
                                <span class="name">${name} • Uploaded</span>
                                <span class="size">${fileSize}</span>
                              </div>
                            </div>
                            <i class="fas fa-check"></i>
                          </li>`;
      uploadedArea.classList.remove("onprogress");
      uploadedArea.insertAdjacentHTML("afterbegin", uploadedHTML);
    }
  });

  let formData = new FormData();
  formData.append("file", file); // attach the actual file

  xhr.send(formData);
}
