<?php
// Oldalak definiálása a routinghoz és menühöz
$oldalak = array(
    // Kulcs (URL) => array(fájlnév (kiterj. nélkül), menüszöveg)
    'cimlap' => array('fajl' => 'cimlap', 'szoveg' => 'Címlap'),
    'receptek' => array('fajl' => 'receptek', 'szoveg' => 'Receptek'),
    'kapcsolat' => array('fajl' => 'kapcsolat', 'szoveg' => 'Kapcsolat'),

    // Később ide jönnek a további oldalak:
    // 'ujrecept' => array('fajl' => 'ujrecept', 'szoveg' => 'Új Recept'),
    // 'galeria' => array('fajl' => 'galeria', 'szoveg' => 'Galéria'),
    // 'belepes' => array('fajl' => 'belepes', 'szoveg' => 'Belépés'),
    // 'kilepes' => array('fajl' => 'kilepes', 'szoveg' => 'Kilépés'),
    // 'regisztracio' => array('fajl' => 'regisztracio', 'szoveg' => 'Regisztráció'),

    // Feldolgozó oldalak, amik nem jelennek meg a menüben (szoveg üres)
    'uzenetkuldes' => array('fajl' => 'uzenetkuldes', 'szoveg' => ''), // Kapcsolati űrlap feldolgozója
    'feltolt' => array('fajl' => 'feltolt', 'szoveg' => ''), // Képfeltöltés feldolgozója
    // ... stb. a bejelentkezés/regisztráció logikájához

    // Hibaoldal
    '404' => array('fajl' => '404', 'szoveg' => '')
);

// Alapértelmezett oldal (ha nincs más megadva az URL-ben)
$alapertelmezett_oldal = 'cimlap';

// Adatbázis kapcsolódási adatok (egyelőre üresen, vagy a helyi XAMPP alap adataival)
// $db_host = "localhost";
// $db_name = "beadando_db"; // Ezt majd létre kell hozni phpMyAdminban
// $db_user = "root";
// $db_pass = "";

?>