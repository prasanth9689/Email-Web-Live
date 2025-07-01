document.addEventListener('change', function (e) {
    if (e.target.classList.contains('inboxCheck')) {
        e.stopPropagation(); // prevents the click from triggering the parent <a>
        document.getElementById('tools').style.display = 'block';
    }
});

document.getElementById('toolsClose').addEventListener('click', function () {
    document.querySelectorAll('.inboxCheck').forEach(cb => {
        cb.checked = false;
    });
    document.getElementById('tools').style.display = 'none';
});

document.getElementById('inboxCheckTotal').addEventListener('change', function () {
    const checkboxes = document.querySelectorAll('.inboxCheck');
    checkboxes.forEach(cb => cb.checked = this.checked);

    // Show/hide tools bar based on selection
    const tools = document.getElementById('tools');
    if (this.checked) {
        tools.style.display = 'block';
    } else {
        // Hide tools if none checked
        const anyChecked = Array.from(checkboxes).some(box => box.checked);
        tools.style.display = anyChecked ? 'block' : 'none';
    }
});

 // Show/hide tools bar when individual checkbox is clicked
 document.addEventListener('change', function (e) {
    if (e.target.classList.contains('inboxCheck')) {
        e.stopPropagation();

        const anyChecked = Array.from(document.querySelectorAll('.inboxCheck')).some(box => box.checked);
        document.getElementById('tools').style.display = anyChecked ? 'block' : 'none';

        // Uncheck the "Select All" if any box is manually unchecked
        if (!this.checked) {
            document.getElementById('inboxCheckTotal').checked = false;
        }
    }
});