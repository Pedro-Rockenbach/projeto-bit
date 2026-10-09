<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>UniobatleCrud</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
</head>

<body>
  <div class="container my-5">
    <h2>Materias do Uniobattle</h2>
    <a class="btn btn-primary" href="./create.php" role="button">Adcionar Materia</a>
    <a class="btn btn-primary" href="./truncate.php" role="button">Excluir Dados</a>
    <br>
    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Elo</th>
          <th>Vitorias</th>
          <th>Partidas</th>
          <th>Ação</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "andromeda";
        $dbname = "ub";
        $conn = mysqli_connect($servername, $username, $password, $dbname);

        // Check connection
        if (!$conn) {
          die("Connection failed: " . mysqli_connect_error());
        }
        echo "Connected successfully";

        $sql = "SELECT * FROM subjects";
        $result = $conn->query($sql);

        if (!$result) {
        die("Invalid query: " . $conn->error);
        }

        while ($row = $result->fetch_assoc()) {
        echo "
        <tr>
          <td>$row[id]</td>
          <td>$row[name]</td>
          <td>$row[elo]</td>
          <td>$row[wins]</td>
          <td>$row[matches]</td>
          <td>
            <a class='btn btn-primary btn-sm' href='./edit.php?id=$row[id]'>📝</a>
            <a class='btn btn-danger btn-sm' href='./delete.php?id=$row[id]>'>❌</a>
          </td>
        </tr>
        ";
        }

        ?>

      </tbody>
    </table>
  </div>
</body>

</html>
