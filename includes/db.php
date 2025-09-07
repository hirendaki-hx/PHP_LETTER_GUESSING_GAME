<?php
$host = "your_host";
$db = "your_db";
$user = "your_user";
$pass = "your_pass";

$conn = new mysqli($host, $user, $pass, $db);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$conn->set_charset("utf8");
?>