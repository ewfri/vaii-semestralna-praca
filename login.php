<?php
session_start();
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: home.php");
    exit;
}

$meno = $heslo = $meno_chyba = $heslo_chyba = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (empty(trim($_POST['username']))) {
        $meno_chyba = 'Zadajte meno';
    } else {
        $meno = trim($_POST['username']);
    }
    if (empty(trim($_POST['password']))) {
        $heslo_chyba = 'Zadajte heslo.';
    } else {
        $heslo = trim($_POST['password']);
    }
    if (empty($meno_chyba) && empty($heslo_chyba)) {
        require_once "dbconnect.php";
        $sql = 'SELECT id, meno, heslo FROM pouzivatelia WHERE meno = "' . $meno . '";';
        $result = $DB->query($sql);
        if ($result && mysqli_num_rows($result) != 0) {
            $r = $result->fetch_assoc();
            if (password_verify($heslo, $r['heslo'])) {
                session_start();
                $_SESSION['loggedin'] = true;
                $_SESSION['id'] = $r['id'];
                $_SESSION['username'] = $meno;
                $DB->close();
                header('location: home.php');
                exit;
            } else {
                $heslo_chyba = 'Nesprávne heslo';
                echo 'Nesprávne heslo';
            }
            $DB->close();
        } else {
            $meno_chyba = 'Používateľ neexistuje';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="css/formular.css" rel="stylesheet">
</head>
<body>
<div class="formular">
    <h2 class="display-4 pt-3">Login</h2>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        <div class="form-group <?php (!empty($meno_chyba)) ? 'has_error' : ''; ?>">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" class="form-control" value="<?php echo $meno; ?>">
            <span class="help-block"><?php echo $meno_chyba; ?></span>
        </div>

        <div class="form-group <?php (!empty($heslo_chyba)) ? 'has_error' : ''; ?>">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" value="<?php echo $heslo; ?>">
            <span class="help-block"><?php echo $heslo_chyba; ?></span>
        </div>

        <div class="form-group">
            <input type="submit" class="btn-primary btn-form" value="login">
        </div>
        <p><a href="register.php">Don't have an account?</a></p>
    </form>
</div>
</body>
</html>
