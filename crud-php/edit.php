<?php
$id = "";
$name = "";
$elo = "";
$wins = "";
$matches = "";

$errorMessage = "";
$sucessMessage = "";

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ub";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  if (!isset($_GET["id"])) {
    header("location: ./index.php");
    exit;
  }

  $id = $_GET["id"];

  $sql = "SELECT * FROM subjects WHERE id=$id";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();

  if (!$row) {
    header("location: ./index.php");
    exit;
  }

  $id = $row["id"];
  $name = $row["name"];
  $elo = $row["elo"];
  $wins = $row["wins"];
  $matches = $row["matches"];
} else {
  $id = $_POST["id"];
  $name = $_POST["name"];
  $elo = $_POST["elo"];
  $wins = $_POST["wins"];
  $matches = $_POST["matches"];

  do {
    if (empty($id)) {
      $errorMessage = "All the field are required";
      break;
    }

    $sql = "UPDATE subjects " .
      "SET name = '$name', elo = '$elo', wins = '$wins', matches = '$wins' " . "WHERE id = $id";

    $result = $conn->query($sql);

    if (!$result) {
      $errorMessage = "Invalid query: " . $conn->error;
      break;
    }

    $sucessMessage = "Cliend added correctly";

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
    <h2>Editar Materia</h2>

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

      <input
        type="hidden"
        name="id"
        value="<?= htmlspecialchars($id) ?>">

      <!-- Name -->
      <div class="row mb-3">
        <label class="col-sm-3 col-form-label">
          Name
        </label>

        <div class="col-sm-6">
          <input
            type="text"
            class="form-control"
            name="name"
            value="<?= htmlspecialchars($name) ?>">
        </div>
      </div>

      <!-- Email -->
      <div class="row mb-3">
        <label class="col-sm-3 col-form-label">
          Elo
        </label>

        <div class="col-sm-6">
          <input
            type="text"
            class="form-control"
            name="elo"
            value="<?= htmlspecialchars($elo) ?>">
        </div>
      </div>

      <!-- Phone -->
      <div class="row mb-3">
        <label class="col-sm-3 col-form-label">
          Vitorias
        </label>

        <div class="col-sm-6">
          <input
            type="text"
            class="form-control"
            name="wins"
            value="<?= htmlspecialchars($wins) ?>">
        </div>
      </div>

      <!-- Address -->
      <div class="row mb-3">
        <label class="col-sm-3 col-form-label">
          Duelos
        </label>

        <div class="col-sm-6">
          <input
            type="text"
            class="form-control"
            name="matches"
            value="<?= htmlspecialchars($matches) ?>">
        </div>
      </div>

      <!-- Buttons -->
      <div class="row mb-3">

        <div class="offset-sm-3 col-sm-3 d-grid">
          <button
            type="submit"
            class="btn btn-primary">
            Atualizar
          </button>
        </div>

        <div class="col-sm-3 d-grid">
          <a
            href="./index.php"
            class="btn btn-outline-primary">
            Cancelar
          </a>
        </div>

      </div>

    </form>

  </div>
</body>

</html>
