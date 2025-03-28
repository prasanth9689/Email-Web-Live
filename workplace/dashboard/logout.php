<?php
session_start();
unset($_SESSION["email"]);
header("Location: https://skyblue.co.in/login/");
?>