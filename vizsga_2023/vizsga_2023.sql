-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2023. Máj 15. 22:54
-- Kiszolgáló verziója: 10.4.24-MariaDB
-- PHP verzió: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `vizsga_2023`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `archive_jegy`
--

CREATE TABLE `archive_jegy` (
  `Id` int(10) NOT NULL,
  `Hiba_Leiras` varchar(500) NOT NULL,
  `Cim` varchar(50) NOT NULL,
  `Kontakt_Adatok` varchar(150) NOT NULL,
  `Statusz_Id` int(10) DEFAULT NULL,
  `Technikus_Id` int(10) DEFAULT NULL,
  `Technikus_Komment` varchar(500) DEFAULT NULL,
  `Csoport_Id` int(10) DEFAULT NULL,
  `Osszes_Munkaora` int(10) DEFAULT NULL,
  `Kep` varchar(50) DEFAULT NULL,
  `Jegy_Datum` datetime NOT NULL DEFAULT current_timestamp(),
  `Befejezes_Datum` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- A tábla adatainak kiíratása `archive_jegy`
--

INSERT INTO `archive_jegy` (`Id`, `Hiba_Leiras`, `Cim`, `Kontakt_Adatok`, `Statusz_Id`, `Technikus_Id`, `Technikus_Komment`, `Csoport_Id`, `Osszes_Munkaora`, `Kep`, `Jegy_Datum`, `Befejezes_Datum`) VALUES
(10, 'A mosdónál a villanykapcsoló szétesett.', '2310 Szigetszentmiklós Dália utca 3', 'Kozma Tamás - 06301565053 - Hétköznap 9 és 15 óra között elérhető.', 5, 15, 'Nem lesz szükség eszközökre, csak a műanyag keret ugrott szét.', 3, 2, '68f2e245c58e8076f1d8fca00e26ecfd.jpeg', '2023-05-10 00:00:00', '2023-05-10');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `csoport`
--

CREATE TABLE `csoport` (
  `Id` int(10) NOT NULL,
  `Csoport_Nev` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- A tábla adatainak kiíratása `csoport`
--

INSERT INTO `csoport` (`Id`, `Csoport_Nev`) VALUES
(1, 'Vízszerelő'),
(2, 'Lakatos'),
(3, 'Villanyszerelő'),
(4, 'Festő'),
(5, 'Felvonószerelő'),
(6, 'Automatikai technikus');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `felhasznalok`
--

CREATE TABLE `felhasznalok` (
  `Id` int(10) NOT NULL,
  `Nev` varchar(50) NOT NULL,
  `Jelszo` varchar(200) NOT NULL,
  `Jogosultsag_Id` int(10) NOT NULL,
  `Csoport_Id` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- A tábla adatainak kiíratása `felhasznalok`
--

INSERT INTO `felhasznalok` (`Id`, `Nev`, `Jelszo`, `Jogosultsag_Id`, `Csoport_Id`) VALUES
(1, 'Admin', '$2y$10$LZZknMnFmPD.vP/UGeG9CuoP10mZNXpKeb1qTVNQvOxPpAWgl9yt.', 1, NULL),
(9, 'Szépe Roland', '$2y$10$96T7jlwmni6yW5KuK2gA9.cTiO6B8C3P.h/zckaYT0uAaqUyHjgDi', 1, NULL),
(10, 'Diszpécser', '$2y$10$Ka02QX49SHewL.TafYmFSOd3EytsX0td0gc1MqZfvOxyIo.O5enmO', 2, NULL),
(11, 'Technikus Tamás', '$2y$10$NsTZbMrBfR09YTmue/6s9e7/5JZzuHfl.3FEBovOovrUIhIyFsqKS', 3, NULL),
(12, 'Strasser Gergő', '$2y$10$prraNaiBRp4kE8B8TJYhk.wPPWfXrfwuArNk3vxRcdD.I/28T750S', 4, NULL);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `jegy`
--

CREATE TABLE `jegy` (
  `Id` int(10) NOT NULL,
  `Hiba_Leiras` varchar(500) NOT NULL,
  `Cim` varchar(50) NOT NULL,
  `Kontakt_Adatok` varchar(150) NOT NULL,
  `Statusz_Id` int(10) DEFAULT NULL,
  `Technikus_Id` int(10) DEFAULT NULL,
  `Technikus_Komment` varchar(500) DEFAULT NULL,
  `Csoport_Id` int(10) DEFAULT NULL,
  `Osszes_Munkaora` int(10) DEFAULT NULL,
  `Kep` varchar(50) DEFAULT NULL,
  `Jegy_Datum` date NOT NULL DEFAULT current_timestamp(),
  `Befejezes_Datum` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- A tábla adatainak kiíratása `jegy`
--

INSERT INTO `jegy` (`Id`, `Hiba_Leiras`, `Cim`, `Kontakt_Adatok`, `Statusz_Id`, `Technikus_Id`, `Technikus_Komment`, `Csoport_Id`, `Osszes_Munkaora`, `Kep`, `Jegy_Datum`, `Befejezes_Datum`) VALUES
(1, 'Centrica irodában izzócserét kérünk', '1061 Podmaniczky utca 111.', 'Centrica Géza\r\n06 30 629 0702', 1, 11, NULL, NULL, NULL, NULL, '2023-05-15', NULL),
(2, '37-es portán az automata ajtó 11-es hibakóddal hibára kiállt', '1061 Podmaniczky utca 111.', 'porta őrség  \r\n\r\n1157-es belső mellék', 1, 11, '11-es hibakód: készenléti akkumulátor tartófeszültsége elégtelen\r\n\r\nvigyetek 12V-os 25x50x100-on belüli új akkumulátort\r\n\r\náramaláhelyezéskor automatikusan újra feláll a rendszer', 6, NULL, NULL, '2023-05-15', NULL),
(3, '1. emeleti Gubacsi út felőli férfi wc piszoár eldugult', '1097 Budapest, Timót u. 3.', 'Gondnok néni Irénke  \r\n+36-1-505-4300\r\n\r\n', 1, 11, NULL, NULL, NULL, NULL, '2023-05-15', NULL),
(4, 'Felvonó megállt a földszinten', '1119 Bikszádi utca 53-55', 'Dudás Pál  \r\n06 70 7019863', 1, 11, '- újraindítás\r\n- küszöbök ellenőrzése\r\n- hibakód kiolvasása', 5, NULL, NULL, '2023-05-15', NULL),
(5, '2. emelet 215 kórterem világítása hibásan működik ', '1068 MÁV Kórház, II.Kh', 'Szépnővérke Anita  \r\n06 70 364 65 91', 1, 11, '- kondenzátort vigyetek fénycsőhöz\r\n- kapcsoló kontakthiba keresés\r\n- fénycsőcsere', 3, NULL, NULL, '2023-05-15', NULL),
(6, 'Bejárati ajtó kulcsa elforog, kilincs működik, de nem kulcsrazárható az ajtó', '1123 Széki Ernő utca 34.', 'Kedves Gizi néni\r\n06 1 2569658', NULL, NULL, NULL, NULL, NULL, NULL, '2023-05-15', NULL);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `jogosultsag`
--

CREATE TABLE `jogosultsag` (
  `Id` int(10) NOT NULL,
  `Jogosultsag_Nev` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- A tábla adatainak kiíratása `jogosultsag`
--

INSERT INTO `jogosultsag` (`Id`, `Jogosultsag_Nev`) VALUES
(1, 'Főnök'),
(2, 'Diszpécser'),
(3, 'Területi képviselő'),
(4, 'Csoportvezető'),
(5, 'Szerelő');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `statusz`
--

CREATE TABLE `statusz` (
  `Id` int(10) NOT NULL,
  `Statusz_Nev` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- A tábla adatainak kiíratása `statusz`
--

INSERT INTO `statusz` (`Id`, `Statusz_Nev`) VALUES
(1, 'Elfogadva'),
(2, 'Elutasítva'),
(3, 'Beszerzés alatt'),
(4, 'Munka elkezdve'),
(5, 'Készre jelentve');

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `csoport`
--
ALTER TABLE `csoport`
  ADD PRIMARY KEY (`Id`);

--
-- A tábla indexei `felhasznalok`
--
ALTER TABLE `felhasznalok`
  ADD PRIMARY KEY (`Id`);

--
-- A tábla indexei `jegy`
--
ALTER TABLE `jegy`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Statusz_Id` (`Statusz_Id`,`Technikus_Id`,`Csoport_Id`);

--
-- A tábla indexei `jogosultsag`
--
ALTER TABLE `jogosultsag`
  ADD PRIMARY KEY (`Id`);

--
-- A tábla indexei `statusz`
--
ALTER TABLE `statusz`
  ADD PRIMARY KEY (`Id`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `csoport`
--
ALTER TABLE `csoport`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT a táblához `felhasznalok`
--
ALTER TABLE `felhasznalok`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT a táblához `jegy`
--
ALTER TABLE `jegy`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT a táblához `jogosultsag`
--
ALTER TABLE `jogosultsag`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT a táblához `statusz`
--
ALTER TABLE `statusz`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
