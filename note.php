<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header('Location: login.php');
    exit;
}


if (!isset($_GET['id'])) {
    header('Location: home.php');
    exit;
}

require_once "dbconnect.php";
$sql = "SELECT vlastnik,nazov FROM poznamka WHERE id=" . $_GET['id'] . ";";
$result = $DB->query($sql);
$r = $result->fetch_assoc();
$DB->close();


if ($r['vlastnik'] != $_SESSION['id']) {
    header('Location: home.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $r['nazov'] ?></title>
</head>
<body>
    <textarea><?php echo file_get_contents("notes/". $_GET['id'] .".txt",true); ?></textarea>
</body>
</html>
