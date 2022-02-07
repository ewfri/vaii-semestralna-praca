<?php
include 'dbconnect.php';
session_start();

if(isset($_SESSION['loggedin']) == false)
{
    die(json_encode(array('error' => 'Niesi prihlásený')));

}

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (!isset($_POST['reply-content']) || empty($_POST['reply-content']) || !isset($_GET['id']) ||  empty($_GET['id'])) {
        die(json_encode(array('error' => 'Nesprávne vstupy')));
    }
    else
    {
        //a real user posted a real reply
        $sql = "INSERT INTO
                    prispevky(post_content,
                          post_date,
                          post_topic,
                          post_by)
                VALUES ('" . $_POST['reply-content'] . "',
                        NOW(),
                        " . mysqli_real_escape_string($DB, $_GET['id']) . ",
                        " . $_SESSION['user_id'] . ")";

        $result = mysqli_query($DB, $sql);
        header("location: ../index.php");
    }
}