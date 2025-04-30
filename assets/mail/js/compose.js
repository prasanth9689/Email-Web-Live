function showView(viewId) {
    document.getElementById("emailDetails").innerHTML = "";
    document.querySelectorAll('.view').forEach(v => v.classList.remove('active'));
    document.getElementById(viewId).classList.add('active');
}

const editor = document.getElementById('editor');

editor.addEventListener('focus', function () {
    if (!editor.classList.contains('active')) {
        editor.textContent = '';
        editor.classList.add('active');
    }
});

editor.addEventListener('blur', function () {
    if (editor.textContent.trim() === '') {
        editor.textContent = 'Write your message here...';
        editor.classList.remove('active');
    }
});


const sendMail = document.getElementById('sendMail');
sendMail.addEventListener('click', function () {
    const editor = document.getElementById("editor").innerHTML;
    const holder = "Write your message here...";
    //  console.log("log : " + editor);
    //  alert(editor.trim() + "");


    if (editor.trim() === holder) {
        alert("write something");
        return;
    }

    var textView2 = document.getElementById("text-sendMail");
    textView2.style.display = "none";

    var progressBar = document.getElementById("progressCircleGreen");
    progressBar.style.display = "block";

    var topProgressbar = document.getElementById("Loading");
    topProgressbar.style.display = "block";

 alert("hello");
    sendMessage();
});

function sendMessage() {
    alert("hello");
}
