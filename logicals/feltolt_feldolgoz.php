<?php
// Munkamenet indítása
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Konfigurációs fájl betöltése megbízhatóbb útvonallal
// Feltételezzük, hogy a PROJECT_ROOT konstans definiálva van a configban
// és a configban definiálva vannak: $pdo, $kep_celmappa_abs, $kep_engedelyezett_tipusok, $kep_max_meret
require_once(dirname(__DIR__) . '/includes/config.inc.php');

// Oldal, ahova visszairányítunk
$redirect_page = '?oldal=feltolt';

// --- Jogosultság Ellenőrzés ---
if (!isset($_SESSION['user_id'])) {
    $_SESSION['upload_status'] = ['siker' => false, 'uzenet' => 'A képfeltöltéshez bejelentkezés szükséges!'];
    header("Location: " . $redirect_page);
    exit();
}

// --- Kérés és Fájl Ellenőrzése ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['kepfajl'])) {

    $feltoltott_fajl = $_FILES['kepfajl'];
    $feltolto_id = $_SESSION['user_id'];

    // 1. Volt-e feltöltési hiba?
    if ($feltoltott_fajl['error'] !== UPLOAD_ERR_OK) {
        $php_hiba_uzenetek = [
            UPLOAD_ERR_INI_SIZE   => 'A fájl mérete túl nagy (szerver oldali limit).',
            UPLOAD_ERR_FORM_SIZE  => 'A fájl mérete túl nagy (űrlap oldali limit).',
            UPLOAD_ERR_PARTIAL    => 'A fájl csak részben töltődött fel.',
            UPLOAD_ERR_NO_FILE    => 'Nem lett fájl kiválasztva.',
            UPLOAD_ERR_NO_TMP_DIR => 'Hiányzik az ideiglenes mappa.',
            UPLOAD_ERR_CANT_WRITE => 'Nem sikerült a fájlt a lemezre írni.',
            UPLOAD_ERR_EXTENSION  => 'Egy PHP kiterjesztés megállította a feltöltést.',
        ];
        $hiba_uzenet = $php_hiba_uzenetek[$feltoltott_fajl['error']] ?? 'Ismeretlen feltöltési hiba.';
        $_SESSION['upload_status'] = ['siker' => false, 'uzenet' => $hiba_uzenet];
        header("Location: " . $redirect_page);
        exit();
    }

    // 2. Méret ellenőrzése
    if ($feltoltott_fajl['size'] > $kep_max_meret) {
        $_SESSION['upload_status'] = ['siker' => false, 'uzenet' => 'A fájl mérete meghaladja a megengedett (' . ($kep_max_meret / 1024 / 1024) . ' MB) limitet.'];
        header("Location: " . $redirect_page);
        exit();
    }

    // 3. Típus ellenőrzése (MIME)
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_tipus = finfo_file($finfo, $feltoltott_fajl['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime_tipus, $kep_engedelyezett_tipusok)) {
         $_SESSION['upload_status'] = ['siker' => false, 'uzenet' => 'Nem megengedett fájltípus. Engedélyezett típusok: JPG, PNG, GIF.'];
         header("Location: " . $redirect_page);
         exit();
    }

    // --- Fájl Feldolgozása és Mentése ---

    $eredeti_fajlnev = $feltoltott_fajl['name'];
    $kiterjesztes = strtolower(pathinfo($eredeti_fajlnev, PATHINFO_EXTENSION));
    $uj_fajlnev = uniqid('kep_', true) . '.' . $kiterjesztes;

    // Cél útvonal konstruálása az abszolút elérési úttal
    $cel_utvonal_teljes = $kep_celmappa_abs . $uj_fajlnev;
    $cel_mappa_teljes = $kep_celmappa_abs; // Mappa útvonala

    // 5. Fájl áthelyezése a végleges helyére
    // Ellenőrizzük és hozzuk létre a mappát, ha szükséges
    if (!is_dir($cel_mappa_teljes)) {
         if (!mkdir($cel_mappa_teljes, 0777, true)) {
             error_log("Nem sikerült létrehozni a mappát: " . $cel_mappa_teljes);
             $_SESSION['upload_status'] = ['siker' => false, 'uzenet' => 'Nem sikerült létrehozni a célmappát a feltöltéshez.'];
             header("Location: " . $redirect_page);
             exit();
         }
    }

    // Ellenőrizzük, írható-e a mappa
    if (!is_writable($cel_mappa_teljes)) {
         error_log("A célmappa nem írható: " . $cel_mappa_teljes);
         $_SESSION['upload_status'] = ['siker' => false, 'uzenet' => 'A célmappa nem írható. Ellenőrizd a jogosultságokat!'];
         header("Location: " . $redirect_page);
         exit();
    }

    if (move_uploaded_file($feltoltott_fajl['tmp_name'], $cel_utvonal_teljes)) {
        // Sikeres fájlmozgatás, rögzítsük az adatbázisba
        try {
            $sql_insert = "INSERT INTO kepek (fajlnev, feltolto_id) VALUES (:fajlnev, :feltolto_id)";
            $stmt = $pdo->prepare($sql_insert);
            $stmt->bindParam(':fajlnev', $uj_fajlnev);
            $stmt->bindParam(':feltolto_id', $feltolto_id);

            if ($stmt->execute()) {
                // Sikeres adatbázis mentés is
                $_SESSION['upload_status'] = ['siker' => true, 'uzenet' => 'Kép sikeresen feltöltve és rögzítve!'];
                header("Location: " . $redirect_page);
                exit();
            } else {
                // Adatbázis hiba mentéskor
                unlink($cel_utvonal_teljes);
                $_SESSION['upload_status'] = ['siker' => false, 'uzenet' => 'Hiba történt az adatbázisba mentés során. A feltöltött fájl törölve lett.'];
                header("Location: " . $redirect_page);
                exit();
            }
        } catch (PDOException $e) {
             error_log("Képfeltöltés adatbázis hiba: " . $e->getMessage());
             if (file_exists($cel_utvonal_teljes)) {
                 unlink($cel_utvonal_teljes);
             }
             $_SESSION['upload_status'] = ['siker' => false, 'uzenet' => 'Adatbázis hiba történt a kép rögzítése során. A feltöltött fájl törölve lett.'];
             header("Location: " . $redirect_page);
             exit();
        }

    } else {
        // Sikertelen fájlmozgatás
         error_log("move_uploaded_file sikertelen ide: " . $cel_utvonal_teljes . ". PHP Error: " . $feltoltott_fajl['error']);
         $_SESSION['upload_status'] = ['siker' => false, 'uzenet' => 'Hiba történt a fájl végleges helyre mozgatása során (move_uploaded_file).'];
         header("Location: " . $redirect_page);
         exit();
    }

} else {
    // Ha nem POST kérés vagy nincs fájl
    $_SESSION['upload_status'] = ['siker' => false, 'uzenet' => 'Érvénytelen kérés vagy hiányzó fájl.'];
    header("Location: " . $redirect_page);
    exit();
}
?>