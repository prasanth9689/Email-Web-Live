<?php
$host = 'localhost';
$db = 'skyblue_mail';
$user = 'root';
$pass = 'prasanth'; // use your actual password

try {
  $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Database connection failed: " . $e->getMessage());
}
?>
