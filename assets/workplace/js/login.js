// Automatically focus the input field when the page loads
var textbox = document.getElementById("username");
textbox.focus();

 const formEl = document.querySelector('.form');

 formEl.addEventListener('submit', event => {
     event.preventDefault();

     var id = document.form.username.value;
     var ps = document.form.password.value;

 
     if (id.length == "" && ps.length == "") {
           document.getElementById("username").className = document.getElementById("username").className + " error";  // this adds the error class
            
           
           var textbox = document.getElementById("username");
           textbox.focus();
           textbox.scrollIntoView();
           document.getElementById( 'error-username' ).style.display = 'block';
           return false; 
    }else {
             if (id.length == "") {
                     document.getElementById("username").className = document.getElementById("username").className + " error";  
                     return false;
             }
             if (ps.length == "") {
                     document.getElementById("password").className = document.getElementById("password").className + " error";  
                     document.getElementById( 'error-password' ).style.display = 'block';
                     var textbox = document.getElementById("password");
                     textbox.focus();
                     textbox.scrollIntoView();
                     return false;
 }
}

    

     const formData = new FormData(formEl);
     const data = Object.fromEntries(formData);

     console.log(data);
 });
 
 const inputElement = document.getElementById("username");
 const password = document.getElementById("password");

 inputElement.addEventListener("input", function() {
     document.getElementById("username").className = document.getElementById("username").className.replace(" error", "");
     document.getElementById( 'error-username' ).style.display = 'none';
 });

 password.addEventListener("input", function() {
     document.getElementById("password").className = document.getElementById("password").className.replace(" error", "");
     document.getElementById( 'error-password' ).style.display = 'none';
 });