<?php
// Munkamenet indítása az üzenetek tárolásához
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Konfigurációs fájl betöltése az adatbázis kapcsolathoz ($pdo)
include('../includes/config.inc.php');

// Alapértelmezett átirányítási oldal (hiba vagy siker esetén)
$redirect_page = '?oldal=regisztracio';

// Ellenőrizzük, hogy POST kérés érkezett-e (űrlap elküldve)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Adatok fogadása az űrlapról
    $csaladi_nev = $_POST['csaladi_nev'] ?? '';
    $uto_nev = $_POST['uto_nev'] ?? '';
    $bejelentkezes = $_POST['bejelentkezes'] ?? '';
    $jelszo = $_POST['jelszo'] ?? '';
    $jelszo_ujra = $_POST['jelszo_ujra'] ?? '';

    // --- Alapvető Szerver Oldali Validáció ---

    // 1. Kötelező mezők ellenőrzése
    if (empty($csaladi_nev) || empty($uto_nev) || empty($bejelentkezes) || empty($jelszo) || empty($jelszo_ujra)) {
        $_SESSION['reg_error'] = "Minden mező kitöltése kötelező!";
        header("Location: " . $redirect_page);
        exit();
    }

    // 2. Jelszavak egyezésének ellenőrzése
    if ($jelszo !== $jelszo_ujra) {
        $_SESSION['reg_error'] = "A két jelszó nem egyezik!";
        header("Location: " . $redirect_page);
        exit();
    }

    // 3. Felhasználónév hosszának ellenőrzése (opcionális, de hasznos)
    if (strlen($bejelentkezes) > 12) {
        $_SESSION['reg_error'] = "A felhasználónév maximum 12 karakter hosszú lehet!";
        header("Location: " . $redirect_page);
        exit();
    }

    // --- Adatbázis Műveletek ---
    try {
        // 4. Létezik-e már ilyen felhasználónév? (Prepared Statement)
        $sql_check = "SELECT id FROM felhasznalok WHERE bejelentkezes = :bejelentkezes";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->bindParam(':bejelentkezes', $bejelentkezes);
        $stmt_check->execute();

        if ($stmt_check->rowCount() > 0) {
            // Már létezik ilyen felhasználó
            $_SESSION['reg_error'] = "Ez a felhasználónév már foglalt!";
            header("Location: " . $redirect_page);
            exit();
        } else {
            // 5. Jelszó hashelése (Biztonságosabb módszer!)
            $hashed_password = password_hash($jelszo, PASSWORD_DEFAULT);

            // 6. Új felhasználó beszúrása az adatbázisba (Prepared Statement)
            $sql_insert = "INSERT INTO felhasznalok (csaladi_nev, uto_nev, bejelentkezes, jelszo)
                           VALUES (:csaladi_nev, :uto_nev, :bejelentkezes, :jelszo)";
            $stmt_insert = $pdo->prepare($sql_insert);
            $stmt_insert->bindParam(':csaladi_nev', $csaladi_nev);
            $stmt_insert->bindParam(':uto_nev', $uto_nev);
            $stmt_insert->bindParam(':bejelentkezes', $bejelentkezes);
            $stmt_insert->bindParam(':jelszo', $hashed_password);

            if ($stmt_insert->execute()) {
                // Sikeres regisztráció
                $_SESSION['reg_success'] = "Sikeres regisztráció! Most már bejelentkezhetsz.";
                header("Location: " . $redirect_page); // Visszairányítás ugyanoda, de sikerüzenettel
                exit();
            } else {
                // Adatbázis hiba beszúráskor
                $_SESSION['reg_error'] = "Hiba történt a regisztráció során (adatbázis hiba).";
                header("Location: " . $redirect_page);
                exit();
            }
        }

    } catch (PDOException $e) {
        // Adatbázis kapcsolódási vagy lekérdezési hiba
         error_log("Regisztrációs hiba: " . $e->getMessage()); // Hibanaplózás (opcionális)
        $_SESSION['reg_error'] = "Adatbázis hiba történt.";
        header("Location: " . $redirect_page);
        exit();
    }

} else {
    // Ha nem POST kéréssel próbálják elérni a fájlt, visszairányítjuk
    header("Location: " . $redirect_page);
    exit();
}
?>