<!-- index.php -->
<!DOCTYPE html>
<html>
<head>
    <title>PHP Click Event</title>
</head>
<body>

<form method="post" action="">
    <button type="submit" name="myButton">Click Me</button>
</form>

<?php
if (isset($_POST['myButton'])) {
    echo "Button was clicked!";
}
?>

</body>
</html>
