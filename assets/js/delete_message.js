document.addEventListener('click', function (e) {
    if (e.target && e.target.id === 'inboxDeleteMessage') {
        const selectedCheckboxes = document.querySelectorAll('.inboxCheck:checked');
        const selectedIds = Array.from(selectedCheckboxes).map(cb => cb.value);

        if (selectedIds.length === 0) {
            alert("No emails selected.");
            return;
        }

        if (!confirm("Are you sure you want to delete selected emails?")) return;
        const acc = "inbox_message_delete";

        // Send AJAX request
        fetch('https://mail.skyblue.co.in/mail.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                acc: acc,
                delete: selectedIds
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the deleted emails from the DOM
                selectedCheckboxes.forEach(cb => {
                    const emailElement = cb.closest('.viewEmail');
                    if (emailElement) {
                        emailElement.remove();
                    }
                });

                showMessage("Message has been deleted", 4000);
                document.getElementById('tools').style.display = 'none';
            } else {
                alert("Error deleting emails.");
            }
        })
        .catch(err => {
            console.error("Request failed:", err);
            alert("Something went wrong.");
        });
    }
});


// document.getElementById('inboxDeleteMessage').addEventListener('click', function () {
//     const selectedCheckboxes = document.querySelectorAll('.inboxCheck:checked');
//     const selectedIds = Array.from(selectedCheckboxes).map(cb => cb.value);

//     if (selectedIds.length === 0) {
//         alert("No emails selected.");
//         return;
//     }

//     if (!confirm("Are you sure you want to delete selected emails?")) return;
//     const acc = "inbox_message_delete";
//     // Send AJAX request
//     fetch('https://mail.skyblue.co.in/mail/mail.php', {
//         method: 'POST',
//         headers: {
//             'Content-Type': 'application/json'
//         },
//         body: JSON.stringify({
//             acc: acc,
//             delete: selectedIds
//         })
//     })
//     .then(response => response.json())
//     .then(data => {
//         if (data.success) {
            
//             // Remove the deleted emails from the DOM
//             selectedCheckboxes.forEach(cb => {
//                 const emailElement = cb.closest('.viewEmail');
//                 if (emailElement) {
//                     emailElement.remove();
//                 }
//             });

//             showMessage("Message has been. deleted", 4000);


//             document.getElementById('tools').style.display = 'none';
//         } else {
//             alert("Error deleting emails.");
//         }
//     })
//     .catch(err => {
//         console.error("Request failed:", err);
//         alert("Something went wrong.");
//         alert({ "acc":"inbox_delete_msg", delete: selectedIds }) 
//     });
// });

function showMessage(text, duration = 3000) {
    const box = document.getElementById("messageBox");
    box.textContent = text;
    box.style.display = "block";
  
    setTimeout(() => {
      box.style.display = "none";
    }, duration);
  }

