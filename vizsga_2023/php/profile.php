<?php
require("../inc/database.php");
session_start();

$page = file_get_contents("../tpl/profile.tpl");

if (isset($_SESSION["user"])) {
    $page = preg_replace("/{{username}}/", $_SESSION["user"]["Nev"], $page);

    $content = file_get_contents("../tpl/lista.tpl");
    $reszletek = "";
    if ($_SESSION["user"]["Jogosultsag_Id"] == 3) {
        $tickets = DB::query(
            "SELECT Id, Hiba_Leiras, Cim, Kontakt_Adatok, Jegy_Datum FROM jegy WHERE Technikus_Id = :Technikus_Id",
            array(":Technikus_Id" => $_SESSION["user"]["Id"])
        );
    } else if ($_SESSION["user"]["Jogosultsag_Id"] == 4) {
        $tickets = DB::query(
            "SELECT Id, Hiba_Leiras, Cim, Kontakt_Adatok, Jegy_Datum FROM jegy WHERE Csoport_Id IS NOT NULL",
            array()
        );
    } else if (isset($_POST["archiv"])) {
        $tickets = DB::query("SELECT Id, Hiba_Leiras, Cim, Kontakt_Adatok, Jegy_Datum FROM archive_jegy", array());
        $reszletek = "archiv=true&";
    } else {
        $tickets = DB::query("SELECT Id, Hiba_Leiras, Cim, Kontakt_Adatok, Jegy_Datum FROM jegy", array());
    }
    $headers = array("Id", "Hiba_Leiras", "Cim", "Kontakt_Adatok", "Jegy_Datum");
    $menugombok = "";

    if ($_SESSION["user"]["Jogosultsag_Id"] == 2) {
        $menugombok = '<button type="submit" name="jegyletrehoz" class="btn btn-primary">Jegy létrehozás</button>';
    } else if ($_SESSION["user"]["Jogosultsag_Id"] == 1) {
        $menugombok = '<button type="submit" name="archiv" class="btn btn-primary">Archívum</button>
        <a href="felhasznalokezeles.php" class="btn btn-primary">Felhasználó kezelés</a>';
    }

    $tableContent = "<tr>";
    for ($i = 0; $i < count($headers); $i++) {
        $tableContent .= "<th>" . $headers[$i] . "</th>";
    }
    $tableContent .= "<th>Részletek</th></tr>";

    for ($i = 0; $i < count($tickets); $i++) {
        $tableContent .= "<tr>";
        for ($j = 0; $j < count($headers); $j++) {
            if ($j != 1) {
                $row = $tickets[$i][$headers[$j]];
            } else {
                $row = substr($tickets[$i][$headers[$j]], 0, 50);
            }
            $tableContent .= "<td>" . $row . "</td>";
        }
        $tableContent .= '<td><a href="ticket.php?' . $reszletek . 'id=' . $tickets[$i]["Id"] . '" class="btn btn-info">Részletek</a></td></tr>';
    }

    $content = preg_replace("/{{tableColums}}/", $tableContent, $content);
    if (isset($_POST["jegyletrehoz"])) {
        $content = file_get_contents("../tpl/hibajegyform.tpl");
    }

    if (isset($_POST["logout"])) {
        session_destroy();
        header('Location: login.php');
    }
    $page = preg_replace("/{{jegyletrehoz}}/", $menugombok, $page);
    $page = preg_replace("/{{content}}/", $content, $page);
    echo $page;
} else {
    header('Location: login.php');
}
