<?php
include 'dbconnect.php';
session_start();

if(isset($_SESSION['loggedin']) == false)
{
    die(json_encode(array('error' => 'Niesi prihlásený')));

}

if($_SERVER['REQUEST_METHOD'] != 'POST')
{
    if (!isset($_GET['id']) ||  empty($_GET['id'])) {
        die(json_encode(array('error' => 'Nesprávne vstupy')));
    }
    else
    {
        $sql = "SELECT post_by FROM prispevky WHERE post_id = " . $_GET['id'] . ";";
        $result = mysqli_query($DB, $sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row['post_by'] == $_SESSION['user_id']) {
                echo "deleted";
                $sql = "DELETE FROM prispevky WHERE post_id = " . $_GET['id'] .";";
                $result = mysqli_query($DB, $sql);

            } else {
                header("location: ../index.php");
                die(json_encode(array('error' => 'Príspevok neexistuje')));
            }
            header("location: ../index.php");
        } else {
            header("location: ../index.php");
            die(json_encode(array('error' => 'Nepodarilo sa pridať príspevok')));
        }
    }
}