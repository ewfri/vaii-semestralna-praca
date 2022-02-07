<?php
include 'dbconnect.php';
session_start();

if(isset($_SESSION['loggedin']) == false)
{
    echo 'Sorry, you have to be <a href="signin.php">signed in</a> to create a topic.';
}


if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['reply-content']) || empty($_POST['reply-content']) || !isset($_GET['id']) || empty($_GET['id'])) {
        die(json_encode(array('error' => 'Nesprávne vstupy')));
    }

    $sql = "UPDATE prispevky SET post_content = '" . $_POST['reply-content'] . "' WHERE  post_id=" . $_GET['id'] . ";";
    $result = mysqli_query($DB, $sql);
    if(!$result)
    {
        die(json_encode(array('error' => 'Nepodarilo sa pridať príspevok')));
    }
    else
    {
        echo 'Príspevok bol editovaný';
    }

}
