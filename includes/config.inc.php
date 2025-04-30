<?php
// Oldalak definiálása a routinghoz és menühöz
$oldalak = array(
    // Kulcs (URL) => array(fájlnév (kiterj. nélkül), menüszöveg)
    'cimlap' => array('fajl' => 'cimlap', 'szoveg' => 'Címlap', 'menu_allapot' => array(1,1)), // Mindig látszik
    'receptek' => array('fajl' => 'receptek', 'szoveg' => 'Receptek', 'menu_allapot' => array(1,1)), // Mindig látszik
    'kapcsolat' => array('fajl' => 'kapcsolat', 'szoveg' => 'Kapcsolat', 'menu_allapot' => array(1,1)), // Mindig látszik

    // Később ide jönnek a további oldalak:
    // 'ujrecept' => array('fajl' => 'ujrecept', 'szoveg' => 'Új Recept', 'menu_allapot' => array(1,0)), // Csak bejelentkezve
    // 'galeria' => array('fajl' => 'galeria', 'szoveg' => 'Galéria', 'menu_allapot' => array(1,1)),
    'belepes' => array('fajl' => 'belepes', 'szoveg' => 'Belépés', 'menu_allapot' => array(0,1)), // Csak kijelentkezve
    'kilepes' => array('fajl' => 'kilepes', 'szoveg' => 'Kilépés', 'menu_allapot' => array(1,0)), // Csak bejelentkezve
    'regisztracio' => array('fajl' => 'regisztracio', 'szoveg' => 'Regisztráció', 'menu_allapot' => array(0,1)), // Csak kijelentkezve

    // Feldolgozó "oldalak", amik nem jelennek meg a menüben (szoveg üres, menu_allapot (0,0))
    'belep_feldolgoz' => array('fajl' => 'belep_feldolgoz', 'szoveg' => '', 'menu_allapot' => array(0,0)),
    'regisztral_feldolgoz' => array('fajl' => 'regisztral_feldolgoz', 'szoveg' => '', 'menu_allapot' => array(0,0)),
    'uzenetkuldes' => array('fajl' => 'uzenetkuldes', 'szoveg' => '', 'menu_allapot' => array(0,0)),
    'feltolt' => array('fajl' => 'feltolt', 'szoveg' => '', 'menu_allapot' => array(0,0)),

    // Hibaoldal
    '404' => array('fajl' => '404', 'szoveg' => '', 'menu_allapot' => array(1,1)) // Mindig elérhető legyen technikailag
);

// Alapértelmezett oldal (ha nincs más megadva az URL-ben)
$alapertelmezett_oldal = 'cimlap';

// --- Adatbázis Kapcsolat Beállításai ---
$db_host = "localhost";         // Általában ez marad
$db_name = "recept_db";         // Az imént létrehozott adatbázis neve
$db_user = "root";              // XAMPP alapértelmezett felhasználó
$db_pass = "";                  // XAMPP alapértelmezett jelszó (üres)

// PDO kapcsolat létrehozása (próbálkozás)
try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass,
                   array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION)); // Hibakezelés beállítása
    $pdo->query('SET NAMES utf8mb4 COLLATE utf8mb4_hungarian_ci'); // Karakterkódolás beállítása
} catch (PDOException $e) {
    // Hiba esetén leállás és hibaüzenet kiírása
    die("Adatbázis kapcsolódási hiba: " . $e->getMessage());
}
// --- Adatbázis Kapcsolat Vége ---

?>