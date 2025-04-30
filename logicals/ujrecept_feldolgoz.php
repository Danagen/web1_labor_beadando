<?php
// Munkamenet indítása
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Konfig és adatbázis kapcsolat
require_once(dirname(__DIR__) . '/includes/config.inc.php');

// Oldal, ahova visszairányítunk (lehetne a receptek listája is)
$redirect_page_form = '?oldal=ujrecept';
$redirect_page_list = '?oldal=receptek';

// --- Jogosultság Ellenőrzés ---
if (!isset($_SESSION['user_id'])) {
    $_SESSION['recept_status'] = ['siker' => false, 'uzenet' => 'Recept hozzáadásához bejelentkezés szükséges!'];
    header("Location: " . $redirect_page_form);
    exit();
}

// --- Kérés Feldolgozása ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nev = $_POST['nev'] ?? '';
    $hozzavalok = $_POST['hozzavalok'] ?? '';
    $leiras = $_POST['leiras'] ?? '';
    $bekuldo_id = $_SESSION['user_id']; // Bejelentkezett felhasználó ID-ja
    // $kep_fajlnev = $_POST['kep_fajlnev'] ?? null; // Ha implementálva lenne a képválasztás

    $hiba = false;
    $hiba_uzenet = '';

    // --- Szerver oldali validáció ---
    if (empty($nev) || trim($nev) === '') {
        $hiba = true;
        $hiba_uzenet .= "A recept nevének megadása kötelező. ";
    }
    // További validációk (pl. hossz) hozzáadhatók itt, ha szükséges

    // --- Feldolgozás ---
    if (!$hiba) {
        try {
            $sql_insert = "INSERT INTO receptek (nev, leiras, hozzavalok, bekuldo_id)
                           VALUES (:nev, :leiras, :hozzavalok, :bekuldo_id)";
                           // Ha lenne kép: ", kep_fajlnev" és ":kep_fajlnev"
            $stmt = $pdo->prepare($sql_insert);
            $stmt->bindParam(':nev', $nev);
            $stmt->bindParam(':leiras', $leiras);
            $stmt->bindParam(':hozzavalok', $hozzavalok);
            $stmt->bindParam(':bekuldo_id', $bekuldo_id);
            // if ($kep_fajlnev) { $stmt->bindParam(':kep_fajlnev', $kep_fajlnev); }

            if ($stmt->execute()) {
                // Sikeres mentés
                $_SESSION['recept_list_status'] = ['siker' => true, 'uzenet' => 'Az új recept sikeresen hozzáadva!']; // Üzenet a lista oldalra
                header("Location: " . $redirect_page_list);
                exit();
            } else {
                // Adatbázis hiba mentéskor
                $_SESSION['recept_status'] = ['siker' => false, 'uzenet' => 'Hiba történt a recept mentésekor (adatbázis).'];
                header("Location: " . $redirect_page_form);
                exit();
            }

        } catch (PDOException $e) {
            error_log("Új recept mentési hiba: " . $e->getMessage());
            $_SESSION['recept_status'] = ['siker' => false, 'uzenet' => 'Adatbázis hiba történt a recept mentése során.'];
            header("Location: " . $redirect_page_form);
            exit();
        }
    } else {
        // Ha validációs hiba volt
        $_SESSION['recept_status'] = ['siker' => false, 'uzenet' => trim($hiba_uzenet)];
        header("Location: " . $redirect_page_form);
        exit();
    }

} else {
    // Ha nem POST kéréssel próbálják elérni
    header("Location: " . $redirect_page_form);
    exit();
}
?>