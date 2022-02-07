<?php
include 'dbconnect.php';
session_start();

if(isset($_SESSION['loggedin']) == false)
{
    die(json_encode(array('error' => 'Niesi prihlásený')));

}

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (!isset($_POST['cat_name']) || empty($_POST['cat_name']) || !isset($_POST['cat_description']) ||  empty($_POST['cat_description'])) {
        die(json_encode(array('error' => 'Nesprávne vstupy')));
    }

    /*$sql = $DB->prepare("
        INSERT INTO kategorie(cat_name, cat_description)
       VALUES(:cat_name, :cat_description);
    ");

    $insert = $sql->execute([
        ':cat_name' => $_POST['cat_name'],
        ':cat_description' => $_POST['cat_description']
    ]);*/

    $sql = "INSERT INTO kategorie(cat_name, cat_description)
       VALUES('" . mysqli_real_escape_string($DB, $_POST['cat_name']) . "',
             '" . mysqli_real_escape_string($DB, $_POST['cat_description']). "');";
    $result = mysqli_query($DB, $sql);

    if(!$result)
    {
        die(json_encode(array('error' => 'Nepodarilo sa vytvoriť kategóriu')));
    }
    else
    {
        echo 'Kategória bola úspešne pridaná.';
    }
}