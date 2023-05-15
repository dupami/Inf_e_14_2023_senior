<?php
require("../inc/database.php");
session_start();

$page = file_get_contents("../tpl/profile.tpl");

if (isset($_SESSION["user"])) {
    if (isset($_POST["submitTicket"])) {
        $cim = substr($_POST["cim"], 0, 50);
        $hibaleiras = substr($_POST["hibaleiras"], 0, 500);
        $kontakt = substr($_POST["kontakt"], 0, 150);

        DB::query(
            "INSERT INTO jegy (Hiba_Leiras, Cim, Kontakt_Adatok) VALUE (:Hiba_Leiras, :Cim, :Kontakt_Adatok)",
            array(":Hiba_Leiras" => $hibaleiras, ":Cim" => $cim, ":Kontakt_Adatok" => $kontakt)
        );

        header('Location: profile.php');
    }
} else {
    header('Location: login.php');
}
