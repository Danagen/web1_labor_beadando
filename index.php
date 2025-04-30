<?php
    // Munkamenet indítása MINDIG az elején!
    session_start();

    // Hibajelentés bekapcsolása fejlesztéshez (később kikapcsolható)
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Konfigurációs fájl betöltése
    include('includes/config.inc.php'); // Ez már tartalmazza a $pdo kapcsolatot is

    // Oldal meghatározása a GET paraméter alapján
    $keresett_oldal_kulcs = isset($_GET['oldal']) ? trim($_GET['oldal'], '/') : $alapertelmezett_oldal;

    // Ellenőrizzük, létezik-e az oldal a konfigurációban ÉS a hozzá tartozó fájl is
    $page_exists = isset($oldalak[$keresett_oldal_kulcs]);
    $file_to_include = $page_exists ? $oldalak[$keresett_oldal_kulcs]['fajl'] : $oldalak['404']['fajl'];
    $template_file_path = "./templates/pages/{$file_to_include}.tpl.php";
    $logical_file_path = "./logicals/{$file_to_include}.php"; // Feldolgozó fájl elérési útja

    // Ha létezik logikai fájl az oldalhoz, azt futtatjuk először
    if (file_exists($logical_file_path)) {
         include($logical_file_path);
         // A logikai fájlok (pl. regisztral_feldolgoz.php) általában átirányítanak,
         // így ide ritkán jut el a vezérlés utána, de nem zárható ki.
    }

    // Ha a kért oldalhoz nincs sablonfájl VAGY maga a kulcs sem létezett -> 404
    if (!file_exists($template_file_path) || !$page_exists) {
         $akt_oldal = $oldalak['404'];
         $keres = $akt_oldal['fajl'];
         header("HTTP/1.0 404 Not Found");
         $template_file_path = "./templates/pages/{$keres}.tpl.php"; // Biztosítjuk a 404 sablont
    } else {
         // Ha létezik sablon és a kulcs is, akkor azt használjuk
         $akt_oldal = $oldalak[$keresett_oldal_kulcs];
         $keres = $akt_oldal['fajl']; // Ezt használja a fő sablon (index.tpl.php)
    }


    // Fő sablon betöltése (ez fogja behívni a $keres alapján a megfelelő aloldal sablont)
    include('templates/index.tpl.php');
?>