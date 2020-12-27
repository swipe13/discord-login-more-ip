<?php
session_start();
require_once 'db.php';

$username = $_GET['username'];
$email = $_GET['email'];
$avatar = $_GET['avatar'];
$discordid = $_GET['discordid'];
$ip = $_SERVER['REMOTE_ADDR'];

$sql = "SELECT * FROM verify WHERE email = '$email'";
$result = mysqli_query($con, $sql);

if (mysqli_num_rows($result) > 0) {
  $sql = "UPDATE verify SET username='$username', user_img='$avatar' WHERE email='$email'";
  mysqli_query($con, $sql);
} else {
  $sql = "INSERT INTO verify VALUES (NULL, '$username', '$email', '$avatar', '$ip', '$discordid')";
  mysqli_query($con, $sql);

  
}
$_SESSION["email_discord"] = $email;
header("Location: verificado.php");
?>