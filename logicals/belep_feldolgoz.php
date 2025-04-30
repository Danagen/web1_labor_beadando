<?php
// Munkamenet indítása
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Konfigurációs fájl betöltése az adatbázis kapcsolathoz ($pdo)
include('../includes/config.inc.php');

// Alapértelmezett átirányítási oldal (hiba esetén)
$redirect_page_error = '?oldal=belepes';
// Sikeres belépés utáni átirányítási oldal
$redirect_page_success = '?oldal=cimlap'; // Vagy ahova szeretnéd

// Ellenőrizzük, hogy POST kérés érkezett-e
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $bejelentkezes = $_POST['bejelentkezes'] ?? '';
    $jelszo = $_POST['jelszo'] ?? '';

    // Kötelező mezők ellenőrzése
    if (empty($bejelentkezes) || empty($jelszo)) {
        $_SESSION['login_error'] = "Felhasználónév és jelszó megadása kötelező!";
        header("Location: " . $redirect_page_error);
        exit();
    }

    try {
        // Felhasználó keresése a felhasználónév alapján (Prepared Statement)
        $sql = "SELECT id, csaladi_nev, uto_nev, bejelentkezes, jelszo
                FROM felhasznalok
                WHERE bejelentkezes = :bejelentkezes";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':bejelentkezes', $bejelentkezes);
        $stmt->execute();

        // Létezik a felhasználó?
        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Jelszó ellenőrzése a password_verify() függvénnyel
            if (password_verify($jelszo, $user['jelszo'])) {
                // Sikeres bejelentkezés!
                // Session változók beállítása
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_csaladi_nev'] = $user['csaladi_nev'];
                $_SESSION['user_uto_nev'] = $user['uto_nev'];
                $_SESSION['user_bejelentkezes'] = $user['bejelentkezes'];

                // Hibaüzenet törlése (ha korábban volt)
                unset($_SESSION['login_error']);

                // Átirányítás a sikeres oldalra
                header("Location: " . $redirect_page_success);
                exit();

            } else {
                // Hibás jelszó
                $_SESSION['login_error'] = "Hibás felhasználónév vagy jelszó!";
                header("Location: " . $redirect_page_error);
                exit();
            }
        } else {
            // Felhasználónév nem található
            $_SESSION['login_error'] = "Hibás felhasználónév vagy jelszó!";
            header("Location: " . $redirect_page_error);
            exit();
        }

    } catch (PDOException $e) {
        // Adatbázis hiba
        error_log("Bejelentkezési hiba: " . $e->getMessage()); // Hibanaplózás
        $_SESSION['login_error'] = "Adatbázis hiba történt.";
        header("Location: " . $redirect_page_error);
        exit();
    }

} else {
    // Ha nem POST kéréssel próbálják elérni
    header("Location: " . $redirect_page_error);
    exit();
}
?>