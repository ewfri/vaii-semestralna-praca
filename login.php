<?php
session_start();
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: index.php");
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
        require_once "forum/dbconnect.php";
        $sql = 'SELECT user_id, user_name, user_pass FROM pouzivatelia WHERE user_name = "' . $meno . '";';
        $result = $DB->query($sql);
        if ($result && mysqli_num_rows($result) != 0) {
            $r = $result->fetch_assoc();
            if (password_verify($heslo, $r['user_pass'])) {
                session_start();
                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = $r['user_id'];
                $_SESSION['user_name'] = $meno;
                $DB->close();
                header('location: index.php');
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
include 'head-foot/header.php';
?>

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

<?php
include 'head-foot/footer.php';
?>