<?php
require("../inc/database.php");
session_start();

$page = file_get_contents("../tpl/login.tpl");
$errorClass = "d-none";


if (isset($_POST["submit"])) {
    $userData = DB::query('SELECT * FROM felhasznalok WHERE Nev = :Nev', array(":Nev" => $_POST["username"]));
    if ($userData != null && password_verify($_POST["password"], $userData[0]["Jelszo"])) {
        $_SESSION["user"] = $userData[0];
        header('Location: profile.php');
    } else {
        $errorClass = "";
    }
}

$page = preg_replace("/{{showError}}/", $errorClass, $page);
echo $page;
