<?php
require("../inc/database.php");
session_start();


if (isset($_POST["letrehoz"]) && isset($_POST["username"]) && isset($_POST["password"]) && $_POST["username"] != "" && $_POST["password"] != "") {
    DB::query(
        "INSERT INTO felhasznalok (Nev, Jelszo, Jogosultsag_Id) VALUES (:Nev, :Jelszo, :Jogosultsag_Id)",
        array(
            ":Nev" => $_POST["username"],
            ":Jelszo" => password_hash($_POST["password"], PASSWORD_BCRYPT),
            ":Jogosultsag_Id" => $_POST["jog"]
        )
    );
    header('Location: felhasznalokezeles.php');
}
