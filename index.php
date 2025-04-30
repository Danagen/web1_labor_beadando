<?php
    // Hibajelentés bekapcsolása fejlesztéshez (később kikapcsolható)
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Konfigurációs fájl betöltése
    include('includes/config.inc.php');

    // Oldal meghatározása a GET paraméter alapján
    $keresett_oldal_kulcs = isset($_GET['oldal']) ? trim($_GET['oldal'], '/') : $alapertelmezett_oldal;

    // Ellenőrizzük, létezik-e az oldal a konfigurációban
    if (isset($oldalak[$keresett_oldal_kulcs]) && file_exists("./templates/pages/{$oldalak[$keresett_oldal_kulcs]['fajl']}.tpl.php")) {
        $akt_oldal = $oldalak[$keresett_oldal_kulcs];
        $keres = $akt_oldal['fajl']; // A betöltendő sablonfájl neve (kiterjesztés nélkül)
    } else {
        // Ha az oldal nem létezik, a 404-es oldalt használjuk
        $akt_oldal = $oldalak['404'];
        $keres = $akt_oldal['fajl'];
        // Opcionális: HTTP 404 fejléc küldése
        header("HTTP/1.0 404 Not Found");
    }

    // Fő sablon betöltése (ez fogja behívni a $keres alapján a megfelelő aloldal sablont)
    include('templates/index.tpl.php');
?>