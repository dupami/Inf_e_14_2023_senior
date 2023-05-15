<?php
require("../inc/database.php");
session_start();

if (isset($_SESSION["user"])) {
    if ($_SESSION["user"]["Jogosultsag_Id"] == 1) {
        $page = file_get_contents("../tpl/felhasznalokezeles.tpl");

        $jogok = DB::query("SELECT * FROM jogosultsag", array());

        $jogosultsagok = '<select class="form-select" name="jog">
<option value="" disabled selected hidden>Jogosultság</option>';
        foreach ($jogok as $value) {
            $jogosultsagok .= '<option value="' . $value["Id"] . '">' . $value["Jogosultsag_Nev"] . '</option>';
        }
        $jogosultsagok .= '</select>';

        $page = preg_replace("/{{jogosultsag}}/", $jogosultsagok, $page);
        echo $page;
    } else {
        header('Location: profile.php');
    }
} else {
    header('Location: login.php');
}
