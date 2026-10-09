<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ub";
$conn = new mysqli($servername, $username, $password, $dbname);

$sql = "TRUNCATE TABLE subjects";
$conn->query($sql);

header("location: ./index.php");
exit;
?>
