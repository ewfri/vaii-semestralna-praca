<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header('Location: login.php');
    exit;
} else {
    if(!isset($_GET['id'])) {
        header('Location: home.php');
        exit;
    }
    $nazov_chyba = "";

    require_once "dbconnect.php";
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (empty(trim($_POST['name']))) {
            $nazov_chyba = 'Zadajte názov';
        } elseif(strlen(trim($_POST["name"])) > 50){
            $nazov_chyba = "Názov môže obsahovať max 50 znakov";
        }
        $sql = 'SELECT nazov FROM poznamka WHERE vlastnik = ' . $_SESSION['id'] .' AND nazov = "' . $_POST['name'] . '";';
        $result = $DB->query($sql);
        if ($result && mysqli_num_rows($result) != 0) {
            $nazov_chyba = 'Poznámka s rovnakým názvom už existuje.';
        }
        if (empty(trim($nazov_chyba))) {
            if (isset($_POST['name'], $_GET['id'])) {
                $editID = $_GET['id'];

                $sql = "SELECT vlastnik FROM poznamka WHERE id=" . $editID . ";";
                $result = $DB->query($sql);
                if ($result && mysqli_num_rows($result) != 0) {
                    $r = $result->fetch_assoc();
                    $owner = $r['vlastnik'];
                } else {
                    $DB->close();
                    header('Location: home.php');
                    echo "error";
                    exit;
                }
                if ($_SESSION['id'] != $owner) {
                    header('Location: home.php');
                    exit;
                }
                $update = trim($_POST['name']);
                $sql = 'UPDATE poznamka SET nazov="' . $update . '" WHERE id = ' . $editID . ';';
                $result = $DB->query($sql);
                $DB->close();
                header('Location: home.php');
                exit;
            }
        }
    }
    $sql = "SELECT nazov FROM poznamka WHERE id=" . $_GET['id'] . ";";
    $result = $DB->query($sql);
    $DB->close();
    if ($result && mysqli_num_rows($result) != 0) {
        $r = $result->fetch_assoc();
        $title = $r['nazov'];
    } else {
        header('Location: home.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="css/formular.css" rel="stylesheet">
</head>
<body>
<div class="formular">
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . "?id=" . $_GET['id']; ?>" method="POST">
        <div class="form-group <?php (!empty($nazov_chyba)) ? 'has_error' : ''; ?>">
            <label for="nazov">Zadajte nový názov</label>
            <input type="text" name="name" id="name" class="form-control" value="<?php echo $title; ?>">
            <span class="help-block"><?php echo $nazov_chyba; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn-primary btn-form" value="Premenovať">
        </div>
    </form>
</div>
</body>
</html>
