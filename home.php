<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header('Location: login.php');
    exit;
}




require_once "dbconnect.php";
$sql = "SELECT id, nazov, vytvorene, upravovane FROM poznamka WHERE vlastnik =" . $_SESSION['id'] . ' ORDER BY vytvorene;';
$result = $DB->query($sql);
$DB->close();
?>
<!doctype html>
<html lang="sk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="css/home.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav me-auto mb-2 mb-md-0">
                <li class="nav-item">
                    <a class="nav-link active" href="/home.php">Domov</a>
                </li>
                <li class="nav-item right">
                    <a class="nav-link" href="/password_reset.php">Zmena hesla</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/logout.php">Odhlásiť</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <div><button class="btn btn-success" onclick="location.href = 'create.php';">create</button></div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">názov</th>
                    <th scope="col">vytvorené</th>
                    <th scope="col">naposledy upravené</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                <?php
                if ($result || mysqli_num_rows($result) != 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td><strong>'. $row['nazov'] .'</strong></td>';
                        echo '<td><strong>'. $row['vytvorene'] .'</strong></td>';
                        echo '<td><strong>'. $row['upravovane'] .'</strong></td>';
                        echo '<td><strong>'. $row['upravovane'] .'</strong></td>';
                        echo '<td><button class="btn btn-primary" onclick="location.href = \'rename.php?id=' . $row['id'] . '\'">rename</button>
<button class="btn btn-danger" onclick="location.href = \'delete.php?id=' . $row['id'] . '\'">delete</button></td>';
                        echo '</tr>';
                    }
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://getbootstrap.com//docs/5.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>