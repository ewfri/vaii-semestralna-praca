<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header('Location: login.php');
    exit;
}
$nazov_chyba = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    if (empty(trim($_POST['name']))) {
        $nazov_chyba = 'Zadajte názov';
    } elseif(strlen(trim($_POST["name"])) > 50){
        $nazov_chyba = "Názov môže obsahovať max 50 znakov";
    }
    require_once "dbconnect.php";
    $sql = 'SELECT nazov FROM poznamka WHERE vlastnik = ' . $_SESSION['id'] .' AND nazov = "' . $_POST['name'] . '";';
    $result = $DB->query($sql);
    if ($result && mysqli_num_rows($result) != 0) {
        $nazov_chyba = 'Poznámka s rovnakým názvom už existuje.';
        $DB->close();
    }

    if (empty($nazov_chyba)) {

        $sql = "INSERT INTO poznamka (vlastnik,nazov) VALUES (" . $_SESSION['id'] . ',"' . $_POST['name'] . '");';
        $result = $DB->query($sql);
        $DB->close();
        header('Location: home.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="css/formular.css" rel="stylesheet">
</head>
<body>
<div class="formular">
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        <div class="form-group <?php (!empty($nazov_chyba)) ? 'has_error' : ''; ?>">
            <label for="nazov">Zadajte názov</label>
            <input type="text" name="name" id="name" class="form-control" value="">
            <span class="help-block"><?php echo $nazov_chyba; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn-success btn-form" value="Vytvorit">
        </div>
    </form>
</div>
</body>
</html>
