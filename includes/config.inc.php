<?php
// Projekt gyökérkönyvtárának meghatározása
if (!defined('PROJECT_ROOT')) {
    define('PROJECT_ROOT', dirname(__DIR__));
}

// Oldalak definiálása
$oldalak = array(
    'cimlap' => array('fajl' => 'cimlap', 'szoveg' => 'Címlap', 'menu_allapot' => array(1,1)),
    'receptek' => array('fajl' => 'receptek', 'szoveg' => 'Receptek', 'menu_allapot' => array(1,1)),
    'ujrecept' => array('fajl' => 'ujrecept', 'szoveg' => 'Új Recept', 'menu_allapot' => array(1,0)), // << ÚJ (csak belépve)
    'galeria' => array('fajl' => 'galeria', 'szoveg' => 'Galéria', 'menu_allapot' => array(1,1)),
    'feltolt' => array('fajl' => 'feltolt', 'szoveg' => 'Képfeltöltés', 'menu_allapot' => array(1,0)),
    'kapcsolat' => array('fajl' => 'kapcsolat', 'szoveg' => 'Kapcsolat', 'menu_allapot' => array(1,1)),
    'uzenetek' => array('fajl' => 'uzenetek', 'szoveg' => 'Üzenetek', 'menu_allapot' => array(1,0)),
    'belepes' => array('fajl' => 'belepes', 'szoveg' => 'Belépés', 'menu_allapot' => array(0,1)),
    'kilepes' => array('fajl' => 'kilepes', 'szoveg' => 'Kilépés', 'menu_allapot' => array(1,0)),
    'regisztracio' => array('fajl' => 'regisztracio', 'szoveg' => 'Regisztráció', 'menu_allapot' => array(0,1)),

    // Feldolgozó "oldalak"
    'belep_feldolgoz' => array('fajl' => 'belep_feldolgoz', 'szoveg' => '', 'menu_allapot' => array(0,0)),
    'regisztral_feldolgoz' => array('fajl' => 'regisztral_feldolgoz', 'szoveg' => '', 'menu_allapot' => array(0,0)),
    'ujrecept_feldolgoz' => array('fajl' => 'ujrecept_feldolgoz', 'szoveg' => '', 'menu_allapot' => array(0,0)), // << ÚJ Feldolgozó
    'uzenetkuldes' => array('fajl' => 'uzenetkuldes', 'szoveg' => '', 'menu_allapot' => array(0,0)),
    'feltolt_feldolgoz' => array('fajl' => 'feltolt_feldolgoz', 'szoveg' => '', 'menu_allapot' => array(0,0)),

    // Hibaoldal
    '404' => array('fajl' => '404', 'szoveg' => '', 'menu_allapot' => array(1,1))
);

// Alapértelmezett oldal
$alapertelmezett_oldal = 'cimlap';

// --- Adatbázis Kapcsolat ---
$db_host = "localhost";
$db_name = "recept_db";
$db_user = "root";
$db_pass = "";

// --- Képfeltöltés Beállításai ---
$kep_celmappa_rel = 'images/uploads/';
$kep_celmappa_abs = PROJECT_ROOT . '/' . trim($kep_celmappa_rel, '/') . '/';
$kep_engedelyezett_tipusok = array('image/jpeg', 'image/png', 'image/gif');
$kep_max_meret = 2 * 1024 * 1024; // 2 MB

// PDO kapcsolat
try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass,
                   array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
    $pdo->query('SET NAMES utf8mb4 COLLATE utf8mb4_hungarian_ci');
} catch (PDOException $e) {
    error_log("Adatbázis kapcsolódási hiba config.inc.php-ben: " . $e->getMessage());
    die("Súlyos hiba: Az adatbázis nem elérhető. Próbálja meg később.");
}
// --- Adatbázis Kapcsolat Vége ---
?>