<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header('Location: login.php');
    exit;
}


$heslo = $potvr_hesla = '';
$heslo_chyba = $potvr_hesla_chyba = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty(trim($_POST['password']))) {
        $heslo_chyba = 'Zadajte heslo.';
    } else {
        $heslo = trim($_POST['password']);
    }
    if (empty($heslo_chyba)) {
        require_once "forum/dbconnect.php";
        $sql = 'SELECT user_pass FROM pouzivatelia WHERE user_id = "' . $_SESSION['user_id'] . '";';
        $result = $DB->query($sql);
        if ($result && mysqli_num_rows($result) != 0) {
            $r = $result->fetch_assoc();
            if (password_verify($heslo, $r['user_pass'])) {

                $sql = 'DELETE FROM pouzivatelia WHERE user_id = "' . $_SESSION['user_id'] . '";';
                $DB->query($sql);
                session_destroy();
                $DB->close();
                header('location: index.php');
                exit;
            } else {
                $heslo_chyba = 'Nesprávne heslo';
                echo 'Nesprávne heslo';
            }
            $DB->close();
        }
    }

}
include 'head-foot/header.php';
?>
    <div class="formular">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($heslo_chyba)) ? 'has-error' : ''; ?>">
                <label>Nové heslo</label>
                <input type="password" name="password" class="form-control" value="<?php echo $heslo; ?>">
                <span class="help-block"><?php echo $heslo_chyba; ?></span>
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