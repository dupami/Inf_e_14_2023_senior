<?php
require("../inc/database.php");
session_start();

$page = file_get_contents("../tpl/ticket.tpl");
if (isset($_SESSION["user"])) {

    if (isset($_GET["archiv"]) && isset($_GET["id"])) {
        $table = "archive_jegy";
    } else {
        $table = "jegy";
    }
    $ticketData = DB::query("SELECT * FROM " . $table . " WHERE Id = :Id", array(":Id" => $_GET["id"]))[0];

    if (isset($_POST["save"])) {
        if ($_SESSION["user"]["Jogosultsag_Id"] == 1 && $_POST["statuszok"] != null && $_POST["nevek"] != null) {
            DB::query(
                "UPDATE jegy SET Statusz_Id = :Statusz_Id, Technikus_Id = :Technikus_Id WHERE Id = :Id",
                array(
                    ":Statusz_Id" => $_POST["statuszok"],
                    ":Technikus_Id" => $_POST["nevek"],
                    ":Id" => $_GET["id"]
                )
            );
        } else if ($_SESSION["user"]["Jogosultsag_Id"] == 3 && $_POST["techcomment"] != null && $_POST["csoportok"] != null) {
            $techcomment = substr($_POST["techcomment"], 0, 500);
            DB::query(
                "UPDATE jegy SET Technikus_Komment = :Technikus_Komment, Csoport_Id = :Csoport_Id WHERE Id = :Id",
                array(
                    ":Technikus_Komment" => $techcomment,
                    ":Csoport_Id" => $_POST["csoportok"],
                    ":Id" => $_GET["id"]
                )
            );
        } else if ($_SESSION["user"]["Jogosultsag_Id"] == 4 && $_POST["statuszok"] > 2) {
            $munkaora = null;
            $image_name = null;

            if ($_POST["munkaora"] != null && $_FILES["kep"] != null) {
                $image_file = $_FILES["kep"];
                if (filesize($image_file["tmp_name"]) <= 0)
                    die("A feltöltött kép 0 méretű!");

                $image_type = exif_imagetype($image_file["tmp_name"]);
                if (!$image_type)
                    die("A feltöltött kép nem valid kép kiterjesztésű!");

                $image_extension = image_type_to_extension($image_type, true);
                $image_name = bin2hex((random_bytes(16))) . $image_extension;
                move_uploaded_file($image_file["tmp_name"], "../img/" . $image_name);
                $munkaora = $_POST["munkaora"];
            }

            if ($_POST["statuszok"] != 5) {
                $sql = "UPDATE jegy SET Statusz_Id = :Statusz_Id, Osszes_Munkaora = :Osszes_Munkaora, Kep = :Kep WHERE Id = :Id";

                DB::query(
                    $sql,
                    array(
                        ":Statusz_Id" => $_POST["statuszok"],
                        ":Osszes_Munkaora" => $munkaora,
                        ":Kep" => $image_name,
                        ":Id" => $_GET["id"]
                    )
                );
            } else if ($_POST["statuszok"] == 5) {
                $sql = "UPDATE jegy SET Statusz_Id = :Statusz_Id, Osszes_Munkaora = :Osszes_Munkaora, Kep = :Kep, Befejezes_Datum = now() WHERE Id = :Id";
                DB::query(
                    $sql,
                    array(
                        ":Statusz_Id" => $_POST["statuszok"],
                        ":Osszes_Munkaora" => $munkaora,
                        ":Kep" => $image_name,
                        ":Id" => $_GET["id"]
                    )
                );
            }
        }
        header("Location: " . $_SERVER['PHP_SELF']);
    } else if (isset($_POST["archiv"])) {
        DB::query(
            "INSERT INTO archive_jegy SELECT * FROM jegy WHERE Id = :Id",
            array(":Id" => $_GET["id"])
        );
        DB::query(
            "DELETE FROM jegy WHERE Id = :Id",
            array(":Id" => $_GET["id"])
        );
        header("Location: profile.php");
    }
    if (isset($_GET["id"]) && $ticketData["Id"]) {
        $columnNames = array("Id", "Hiba_Leiras", "Cim", "Kontakt_Adatok", "Statusz_Id", "Technikus_Id", "Technikus_Komment", "Csoport_Id", "Osszes_Munkaora", "Kep", "Jegy_Datum", "Befejezes_Datum");
        $headers = array("Hibajegy száma", "Hiba leírása", "Hiba címe", "Kontakt adatok", "Státusz", "Technikus", "Technikusi komment", "Csoport", "Összes munkaóra", "Igazoló kép", "Hibajegy létrehozási dátuma", "Hibajegy befejezési dátuma");
        $page = preg_replace("/{{ticketName}}/", substr($ticketData[$columnNames[1]], 0, 25), $page);
        $i = 0;
        $content = "";
        $bossStatuses = DB::query("SELECT * FROM statusz", array());
        foreach ($ticketData as $value) {
            $content .= '<t><th>' . $headers[$i] . '</th>';
            if ($i == 4) {
                $content .= '<td><select class="form-select" name="statuszok" ';
                if ($_SESSION["user"]["Jogosultsag_Id"] != 1 && $_SESSION["user"]["Jogosultsag_Id"] != 4 || $ticketData["Befejezes_Datum"] != null) {
                    $content .= 'disabled>';
                } else {
                    $content .= '>';
                }
                $content .= '<option value="" disabled selected hidden>Üres</option>';
                for ($j = 0; $j < 5; $j++) {
                    $content .= '<option value="' . $bossStatuses[$j]["Id"] . '"';
                    if ($_SESSION["user"]["Jogosultsag_Id"] == 1 && $j >= 2) {
                        $content .= " hidden ";
                    } else if ($_SESSION["user"]["Jogosultsag_Id"] == 4 && $j < 2) {
                        $content .= " hidden ";
                    }
                    if ($bossStatuses[$j]["Id"] == $ticketData["Statusz_Id"]) {
                        $content .= " selected";
                    }
                    $content .= '>' . $bossStatuses[$j]["Statusz_Nev"] . '</option>';
                }
                $content .= '</select></td>';
            } else if ($i == 5) {
                $content .= '<td><select class="form-select" name="nevek"';
                if ($_SESSION["user"]["Jogosultsag_Id"] != 1 || $ticketData["Befejezes_Datum"] != null) {
                    $content .= 'disabled>';
                } else {
                    $content .= '>';
                }
                $content .= '<option value="" disabled selected hidden>Üres</option>';
                $techikusok = DB::query("SELECT Id, Nev FROM felhasznalok WHERE Jogosultsag_Id = :Jogosultsag_Id", array(":Jogosultsag_Id" => 3));
                foreach ($techikusok as $value) {
                    $content .= '<option value="' . $value["Id"] . '"';
                    if ($ticketData["Technikus_Id"] == $value["Id"]) {
                        $content .= " selected";
                    }
                    $content .= '>' . $value["Nev"] . '</option>';
                }
                $content .= "</select></td>";
            } else if ($i == 6) {
                $content .= "<td><textarea class='form-control' id='techcomment' name='techcomment' rows='5' cols='50' maxlenght='500'";
                if ($_SESSION["user"]["Jogosultsag_Id"] != 3 || $ticketData["Befejezes_Datum"] != null) {
                    $content .= "disabled>";
                } else {
                    $content .= ">";
                }
                $content .= $ticketData["Technikus_Komment"] . "</textarea></td>";
            } else if ($i == 7) {
                $content .= '<td><select class="form-select" name="csoportok"';
                if ($_SESSION["user"]["Jogosultsag_Id"] != 3 || $ticketData["Befejezes_Datum"] != null) {
                    $content .= ' disabled>';
                } else {
                    $content .= '>';
                }
                $content .= '<option value="" disabled selected hidden>Üres</option>';
                $csoportok = DB::query("SELECT * FROM csoport", array());
                foreach ($csoportok as $value) {
                    $content .= '<option value="' . $value["Id"] . '"';
                    if ($ticketData["Csoport_Id"] == $value["Id"]) {
                        $content .= " selected";
                    }
                    $content .= '>' . $value["Csoport_Nev"] . '</option>';
                }
                $content .= "</select></td>";
            } else if ($i == 8) {
                $content .= '<td><input class="form-control" type="number" name="munkaora" value="' . $ticketData["Osszes_Munkaora"] . '"';
                if ($_SESSION["user"]["Jogosultsag_Id"] != 4 || $ticketData["Befejezes_Datum"] != null) {
                    $content .= ' disabled>';
                } else {
                    $content .= '>';
                }
                $content .= "</td>";
            } else if ($i == 9) {
                $content .= '<td>';
                if ($ticketData["Kep"] != null) {
                    $content .= '<img src="../img/' . $ticketData["Kep"] . '" width="200" height="200"';
                } else {
                    $content .= '<input class="form-control" type="file" name="kep"';
                    if ($_SESSION["user"]["Jogosultsag_Id"] != 4) {
                        $content .= ' disabled>';
                    } else {
                        $content .= '>';
                    }
                }
                $content .= "</td>";
            } else {
                $content .= '<td><input type="text" disabled class="form-control" value="' . $ticketData[$columnNames[$i]] . '"></td>';
            }
            $content .= "</tr>";
            $i++;
        }
        if (!DB::query(
            "SELECT Id FROM archive_jegy WHERE Id = :Id",
            array(":Id" => $_GET["id"])
        )) {
            $content .= '<button type="submit" name="save" class="btn btn-success">Hibajegy mentése</button>';
        }
        if ($_SESSION["user"]["Jogosultsag_Id"] == 1 && $ticketData["Befejezes_Datum"] != null && !DB::query(
            "SELECT Id FROM archive_jegy WHERE Id = :Id",
            array(":Id" => $_GET["id"])
        )) {
            $content .= '<button type="submit" name="archiv" class="btn btn-primary">Hibajegy archiválása</button>';
        }
        $page = preg_replace("/{{content}}/", $content, $page);
        echo $page;
    } else {
        header('Location: profile.php');
    }
} else {
    header('Location: login.php');
}
