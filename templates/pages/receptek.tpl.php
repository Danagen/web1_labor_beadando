<h2>Receptek</h2>

<?php
    // Visszajelzés a session-ből (pl. sikeres hozzáadás után)
    if (isset($_SESSION['recept_list_status'])) {
        $status = $_SESSION['recept_list_status'];
        $tipus = ($status['siker']) ? 'siker' : 'hiba';
        echo '<p class="uzenet-statusz ' . $tipus . '">' . htmlspecialchars($status['uzenet']) . '</p>';
        unset($_SESSION['recept_list_status']); // Üzenet törlése
    }
?>

<?php
// CSS a receptek egyszerű megjelenítéséhez (opcionális, lehet CSS fájlban is)
// ... (A CSS rész változatlan marad, mint előzőleg) ...
echo <<<HTML
<style>
/* ... CSS Stílusok ... */
.recept-lista {list-style: none; padding: 0;}
.recept-elem {border: 1px solid #ddd; margin-bottom: 15px; padding: 15px; background-color: #fff; display: flex; gap: 15px;}
.recept-kep img {max-width: 150px; height: auto; display: block;}
.recept-tartalom {flex-grow: 1;}
.recept-tartalom h3 {margin-top: 0; margin-bottom: 5px;}
.recept-meta {font-size: 0.9em; color: #555; margin-bottom: 10px;}
.recept-leiras-rovid {color: #333;}
/* Stílusok a visszajelzéshez */
.uzenet-statusz {padding: 10px; margin-bottom: 15px; border-radius: 4px;}
.uzenet-statusz.siker {background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;}
.uzenet-statusz.hiba { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;}
</style>
HTML;

// Link az új recept hozzáadásához (csak bejelentkezett felhasználóknak)
if (isset($_SESSION['user_id'])) {
    // Ellenőrizzük, hogy az 'ujrecept' oldal definiálva van-e a configban
    $ujrecept_url_kulcs = array_search('ujrecept', array_column($GLOBALS['oldalak'], 'fajl'));
    if ($ujrecept_url_kulcs !== false && isset($GLOBALS['oldalak'][$ujrecept_url_kulcs])) { // Ellenőrizzük a kulcs létezését is
         // Menü láthatóság ellenőrzése (biztonság kedvéért)
        $latszik_bejelentkezve = $GLOBALS['oldalak'][$ujrecept_url_kulcs]['menu_allapot'][0] == 1;
        if($latszik_bejelentkezve) {
            echo '<p><a href="?oldal=' . $ujrecept_url_kulcs . '">Új recept hozzáadása</a></p>';
        }
    }
}

// ... (A receptek lekérdezése és listázása rész változatlan marad) ...
try {
    // Receptek lekérdezése...
    $sql = "SELECT r.id, r.nev, r.leiras, r.hozzaadas_datum, r.kep_fajlnev, f.bejelentkezes as bekuldo_nev
            FROM receptek r
            JOIN felhasznalok f ON r.bekuldo_id = f.id
            ORDER BY r.hozzaadas_datum DESC";
    $stmt = $pdo->query($sql);

    if ($stmt->rowCount() > 0) {
        echo '<ul class="recept-lista">';
        while ($recept = $stmt->fetch(PDO::FETCH_ASSOC)) {
             echo '<li class="recept-elem">';
             // Kép
             echo '<div class="recept-kep">';
             if (!empty($recept['kep_fajlnev']) && isset($GLOBALS['kep_celmappa_rel'])) {
                 $kep_ut = $GLOBALS['kep_celmappa_rel'] . htmlspecialchars($recept['kep_fajlnev']);
                 // Ellenőrizzük, létezik-e a fájl fizikailag is
                 if (file_exists(PROJECT_ROOT . '/' . $GLOBALS['kep_celmappa_rel'] . htmlspecialchars($recept['kep_fajlnev']))) {
                    echo '<img src="' . $kep_ut . '" alt="' . htmlspecialchars($recept['nev']) . '">';
                 } else {
                     echo '<img src="images/placeholder.png" alt="Kép nem található" style="width:150px; height:100px; object-fit:cover; border:1px solid #eee;">';
                 }
             } else {
                 echo '<img src="images/placeholder.png" alt="Nincs kép" style="width:150px; height:100px; object-fit:cover; border:1px solid #eee;">';
             }
             echo '</div>';
             // Tartalom
             echo '<div class="recept-tartalom">';
             echo '<h3>' . htmlspecialchars($recept['nev']) . '</h3>';
             echo '<p class="recept-meta">Beküldte: ' . htmlspecialchars($recept['bekuldo_nev']) . ' | Dátum: ' . htmlspecialchars($recept['hozzaadas_datum']) . '</p>';
             $leiras_rovid = mb_substr(strip_tags($recept['leiras'] ?? ''), 0, 200, 'UTF-8');
             if (strlen($recept['leiras'] ?? '') > 200) { $leiras_rovid .= '...'; }
             echo '<p class="recept-leiras-rovid">' . nl2br(htmlspecialchars($leiras_rovid)) . '</p>';
             echo '</div>'; // recept-tartalom vége
             echo '</li>'; // recept-elem vége
        }
        echo '</ul>'; // recept-lista vége
    } else {
        echo "<p>Még nincsenek receptek az adatbázisban.</p>";
    }
} catch (PDOException $e) {
    error_log("Receptek lekérdezési hiba: " . $e->getMessage());
    echo "<p style='color: red;'>Hiba történt a receptek lekérdezése közben.</p>";
}
?>