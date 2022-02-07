<?php

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
        $sql = 'UPDATE pouzivatelia SET user_pass = "'. $param_password .'" WHERE user_id = ' . $param_id . ';';
        require_once 'forum/dbconnect.php';
        $result = $DB->query($sql);
        $DB->close();
        if ($result) {
            session_destroy();
            header("location: login.php");
            exit();
        }
    }
}
include 'head-foot/header.php';
?>
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

<?php
include 'head-foot/footer.php';
?>