<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header('Location: login.php');
    exit;
} else {
    if (isset($_GET['id'])) {
        $deleteID = $_GET['id'];
        require_once "dbconnect.php";
        $sql = "SELECT vlastnik FROM poznamka WHERE id=" . $deleteID . ";";
        $result = $DB->query($sql);
        if ($result && mysqli_num_rows($result) != 0) {
            $r = $result->fetch_assoc();
            $owner = $r['vlastnik'];
        } else {
            header('Location: home.php');
            exit;
        }
        if ($_SESSION['id'] != $owner) {
            header('Location: home.php');
            exit;
        }

        $sql = "DELETE FROM poznamka WHERE id = " . $deleteID . ";";
        $DB->query($sql);
        if ($result && mysqli_num_rows($result) != 0) {
            unlink("notes/" . $deleteID . ".txt");
        }
        $DB->close();
        header('Location: home.php');
        exit;
    }
}
?>