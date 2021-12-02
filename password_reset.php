<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header('Location: login.php');
    exit;
}


$nove_heslo = $potvr_hesla = '';
$nove_heslo_chyba = $potvr_hesla_chyba = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty(trim($_POST['new_password']))) {
        $nove_heslo_chyba = 'Zadajte nové heslo.';
    } elseif (strlen(trim($_POST['new_password'])) < 6) {
        $nove_heslo_chyba = 'Heslo musí obsahovať aspoň 6 znakov.';
    } else {
        $nove_heslo = trim($_POST['new_password']);
    }
    if (empty(trim($_POST['confirm_password']))) {
        $potvr_hesla_chyba = 'Potvrdte heslo znovu.';
    } else {
        $potvr_hesla = trim($_POST['confirm_password']);
        if (empty($nove_heslo_chyba) && ($nove_heslo != $potvr_hesla)) {
            $potvr_hesla_chyba = 'Zadané heslo sa nezhoduje.';
        }
    }
    if (empty($nove_heslo_chyba) && empty($potvr_hesla_chyba)) {
        $param_password = password_hash($nove_heslo, PASSWORD_DEFAULT);
        $param_id = $_SESSION["id"];
        $sql = 'UPDATE pouzivatelia SET heslo = "'. $param_password .'" WHERE id = ' . $param_id . ';';
        require_once 'dbconnect.php';
        $result = $DB->query($sql);
        $DB->close();
        if ($result) {
            session_destroy();
            header("location: login.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Zmena hesla</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="css/home.css" rel="stylesheet">
    <link href="css/formular.css" rel="stylesheet">
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
                    <a class="nav-link" href="/home.php">Domov</a>
                </li>
                <li class="nav-item right">
                    <a class="nav-link active" href="/password_reset.php">Zmena hesla</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/logout.php">Odhlásiť</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="formular">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group <?php echo (!empty($nove_heslo_chyba)) ? 'has-error' : ''; ?>">
            <label>Nové heslo</label>
            <input type="password" name="new_password" class="form-control" value="<?php echo $nove_heslo; ?>">
            <span class="help-block"><?php echo $nove_heslo_chyba; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($potvr_hesla_chyba)) ? 'has-error' : ''; ?>">
            <label>Potvrdenie hesla</label>
            <input type="password" name="confirm_password" class="form-control">
            <span class="help-block"><?php echo $potvr_hesla_chyba; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn-primary btn-form" value="Odoslať">
        </div>
    </form>
</div>
<script src="https://getbootstrap.com//docs/5.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>