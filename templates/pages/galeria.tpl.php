<h2>Galéria</h2>

<?php
// CSS a galéria egyszerű megjelenítéséhez
echo <<<HTML
<style>
.galeria-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 15px; /* Térköz a képek között */
}
.galeria-elem {
    border: 1px solid #ccc;
    padding: 10px;
    text-align: center;
    background-color: #f9f9f9;
    width: calc(25% - 20px); /* Kb. 4 kép egy sorban, figyelembe véve a gap-et és paddingot */
    box-sizing: border-box; /* Padding és border beleszámít a szélességbe */
}
.galeria-elem img {
    max-width: 100%;
    height: auto;
    display: block;
    margin-bottom: 5px;
    min-height: 100px; /* Minimum magasság, hogy a törött kép ikon ne legyen túl kicsi */
    background-color: #eee; /* Háttér a kép helyén, ha nem töltődik be */
}
 .galeria-elem p {
    font-size: 0.8em;
    margin: 3px 0;
    word-wrap: break-word; /* Hosszú fájlnevek tördelése */
 }
 /* Reszponzivitás (példa) */
 @media (max-width: 992px) {
    .galeria-elem { width: calc(33.33% - 20px); } /* 3 kép/sor */
 }
  @media (max-width: 768px) {
    .galeria-elem { width: calc(50% - 20px); } /* 2 kép/sor */
 }
  @media (max-width: 576px) {
    .galeria-elem { width: calc(100% - 20px); } /* 1 kép/sor */
 }
</style>
HTML;

try {
    // Képek lekérdezése az adatbázisból, feltöltők nevével együtt
    $sql = "SELECT k.fajlnev, k.feltoltes_datum, f.bejelentkezes as feltolto_nev
            FROM kepek k
            JOIN felhasznalok f ON k.feltolto_id = f.id
            ORDER BY k.feltoltes_datum DESC";
    $stmt = $pdo->query($sql); // Feltételezzük, $pdo létezik a configból

    if ($stmt->rowCount() > 0) {
        echo '<div class="galeria-grid">';
        while ($kep = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Kép elérési útja a HELYES relatív változóval a configból
            $kep_eleresi_ut = $GLOBALS['kep_celmappa_rel'] . htmlspecialchars($kep['fajlnev']); // JAVÍTVA ITT

            echo '<div class="galeria-elem">';
            // Link a teljes képhez (opcionális)
            echo '<a href="' . $kep_eleresi_ut . '" target="_blank">';
            // Az alt attribútum fontos a kép leírásához
            echo '<img src="' . $kep_eleresi_ut . '" alt="Feltöltött kép: ' . htmlspecialchars($kep['fajlnev']) . '">';
            echo '</a>';
            echo '<p>Fájlnév: ' . htmlspecialchars($kep['fajlnev']) . '</p>';
            echo '<p>Feltöltő: ' . htmlspecialchars($kep['feltolto_nev']) . '</p>';
            // Dátum formázása (opcionális, olvashatóbban)
            $datum_obj = new DateTime($kep['feltoltes_datum']);
            echo '<p>Dátum: ' . $datum_obj->format('Y-m-d H:i') . '</p>';
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo "<p>Még nincsenek képek a galériában.</p>";
    }

} catch (PDOException $e) {
    error_log("Galéria lekérdezési hiba: " . $e->getMessage());
    echo "<p style='color: red;'>Hiba történt a galéria betöltése közben.</p>";
}
?>