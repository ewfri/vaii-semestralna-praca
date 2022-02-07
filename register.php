<?php
	require_once 'forum/dbconnect.php';
	$pouzivatel = $heslo = $potvr_hesla = "";
	$meno_chyba = $heslo_chyba = $potvr_hesla_chyba = "";

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if (empty(trim($_POST['username']))) {
			$meno_chyba = "Prosím zadajte meno.";
		} else {
            $parUzivatel = trim($_POST['username']);
			$sql = 'SELECT user_id FROM pouzivatelia WHERE user_name = "' . $parUzivatel .'";';
            $result = $DB->query($sql);
            if ($result && mysqli_num_rows($result) != 0) {
                $meno_chyba = 'Používateľské meno je už obsadené.';
                $DB->close();
            } else {
                $pouzivatel = trim($_POST['username']);
            }
		}
	    if(empty(trim($_POST["password"]))){
	        $heslo_chyba = "Zadajte heslo.";
	    } elseif(strlen(trim($_POST["password"])) < 6){
	        $heslo_chyba = "Heslo musí obsahovať aspoň 6 znakov";
	    } else{
	        $heslo = trim($_POST["password"]);
	    }
	    if(empty(trim($_POST["confirm_password"]))){
	        $potvr_hesla_chyba = "Prosím potvrdťe heslo.";
	    } else{
	        $potvr_hesla = trim($_POST["confirm_password"]);
	        if(empty($heslo_chyba) && ($heslo != $potvr_hesla)){
	            $potvr_hesla_chyba = "Heslá sa nezhodujú.";
	        }
	    }
	    if (empty($meno_chyba) && empty($heslo_chyba) && empty($confirm_err)) {
            $parUzivatel = $pouzivatel;
            $parHeslo = password_hash($heslo, PASSWORD_DEFAULT);

			$sql = 'INSERT INTO pouzivatelia (user_name, user_pass) VALUES ("'. trim($parUzivatel) . '", "' . trim($parHeslo) . '")';
			$result = $DB->query($sql);
			$DB->close();

            header('location: ./login.php');
	    }
	}

include 'head-foot/header.php';
?>
<div class="formular">
			<h2 class="display-4 pt-3">Sign Up</h2>
        	<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        		<div class="form-group <?php (!empty($meno_chyba))?'has_error':'';?>">
        			<label for="username">Username</label>
        			<input type="text" name="username" id="username" class="form-control" value="<?php echo $pouzivatel ?>">
        			<span class="help-block"><?php echo $meno_chyba;?></span>
        		</div>

        		<div class="form-group <?php (!empty($heslo_chyba))?'has_error':'';?>">
        			<label for="password">Password</label>
        			<input type="password" name="password" id="password" class="form-control" value="<?php echo $heslo ?>">
        			<span class="help-block"><?php echo $heslo_chyba; ?></span>
        		</div>

        		<div class="form-group <?php (!empty($potvr_hesla_chyba))?'has_error':'';?>">
        			<label for="confirm_password">Confirm Password</label>
        			<input type="password" name="confirm_password" id="confirm_password" class="form-control" value="<?php echo $potvr_hesla; ?>">
        			<span class="help-block"><?php echo $potvr_hesla_chyba;?></span>
        		</div>

        		<div class="form-group">
        			<input type="submit" class="btn-primary btn-form" value="Submit">
        		</div>
        		<p><a href="login.php">Already have an account?</a></p>
        	</form>
</div>
<?php
include 'head-foot/footer.php';
?>