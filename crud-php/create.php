<?php
$name = "";
/* $elo = ""; */
/* $wins = ""; */
/* $matches = ""; */

$errorMessage = "";
$sucessMessage = "";

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ub";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = $_POST["name"];

  do {
    if (empty($name)) {
      $errorMessage = "All the field are required";
      break;
    }


    $sql = "INSERT INTO subjects (name) " .
      "VALUES ('$name')";

    $result = $conn->query($sql);

    if (!$result) {
      $errorMessage = "Invalid query: " . $conn->error;
      break;
    }

    $name = "";

    header("location: ./index.php");
    exit;
  } while (false);
}
?>

<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>My shop</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
</head>

<body>
  <div class="container my-5">
    <h2>Nova Materia</h2>

    <?php
    if (!empty($sucessMessage)) {
      echo "
          <div class='alert alert-sucess alert-dismissible fade show' role='alert'>
            <strong>$errorMessage</strong>
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>
          ";
    } ?>

    <form method="post">
      <div class="row mb-3">
        <label class="col-sm-3 col-form-label">Name</label>
        <div class="col-sm-6">
          <input type="text" class"form-control" name="name" value="<?php echo $name; ?>">
        </div>
      </div>


      <?php
      if (!empty($sucessMessage)) {
        echo "
          <div class='row mb-3'>
          <div class='offset-sm-3 col-sm-3 d-grid'>
              <div class='alert alert-sucess alert-dismissible fade show' role='alert'>
                <strong>$sucessMessage</strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'>Enviar</button>
              </div>
          </div>
          </div>
          ";
      } ?>

      <div class="row mb-3">
        <div class="offset-sm-3 col-sm-3 d-grid">
          <button type="submit" class="btn btn-primary">Enviar</button>
        </div>
      </div>

      <div class="row mb-3">

        <div class="offset-sm-3 col-sm-3 d-grid">
          <a class="btn btn-outline-primary" href="./index.php" role="button">Cancelar</a>
        </div>
      </div>
    </form>
  </div>
</body>

</html>
