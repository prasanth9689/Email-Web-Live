<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Full Email</title>
</head>
<body>

<h1>Email List</h1>
<ul id="emailList">
    <!-- List of email subjects will go here -->
</ul>

<div id="emailDetails">
    <!-- Full email details will be shown here -->
</div>

<script>
    // Example list of email subjects
    const emails = [
        { from: 'John Doe', subject: 'Meeting Invitation', message_id: '1' },
        { from: 'Jane Smith', subject: 'Project Update', message_id: '2' }
    ];

    // Display emails
    const emailList = document.getElementById('emailList');
    emails.forEach(email => {
        const li = document.createElement('li');
        li.innerHTML = `<a href="#" class="viewEmail" data-id="${email.message_id}">${email.from} - ${email.subject}</a>`;
        emailList.appendChild(li);
    });

    // Attach click event to view email
    document.querySelectorAll('.viewEmail').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const messageId = this.getAttribute('data-id');

            // Fetch email details using AJAX (fetch API)
            fetch('view_email.php?message_id=' + messageId)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('emailDetails').innerHTML = data;
                })
                .catch(error => console.error('Error:', error));
        });
    });
</script>

</body>
</html>
