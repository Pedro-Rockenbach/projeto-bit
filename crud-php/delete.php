<?php
if (isset($_GET["id"])) {
  $id = $_GET["id"];

  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "ub";
  $conn = new mysqli($servername, $username, $password, $dbname);

  $sql = "DELETE FROM subjects WHERE id='$id'";
  $conn->query($sql);
}

header("location: ./index.php");
exit;
?>
