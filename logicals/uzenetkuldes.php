<?php
// Munkamenet indítása az üzenetekhez
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Konfig és adatbázis kapcsolat
include('../includes/config.inc.php');

// Alap átirányítási oldal
$redirect_page = '?oldal=kapcsolat';

// Csak POST kéréseket dolgozunk fel
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nev = $_POST['nev'] ?? '';
    $email = $_POST['email'] ?? '';
    $uzenet = $_POST['uzenet'] ?? '';
    $hiba = false;
    $hiba_uzenet = '';

    // --- Szerver oldali validáció ---

    // Név ellenőrzése
    if (empty($nev) || strlen(trim($nev)) < 5) {
        $hiba = true;
        $hiba_uzenet .= "A név megadása kötelező (minimum 5 karakter). ";
    }

    // E-mail ellenőrzése (filter_var a beépített, megbízhatóbb ellenőrző)
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
         $hiba = true;
         $hiba_uzenet .= "Érvénytelen e-mail cím formátum. ";
    }

    // Üzenet ellenőrzése
    if (empty($uzenet) || trim($uzenet) === '') {
         $hiba = true;
         $hiba_uzenet .= "Az üzenet kitöltése kötelező. ";
    }

    // --- Feldolgozás ---

    if (!$hiba) {
        // Ha nincs hiba, mentsük az adatbázisba
        try {
            $sql_insert = "INSERT INTO uzenetek (nev, email, uzenet) VALUES (:nev, :email, :uzenet)";
            $stmt = $pdo->prepare($sql_insert);
            $stmt->bindParam(':nev', $nev);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':uzenet', $uzenet);

            if ($stmt->execute()) {
                // Sikeres mentés
                $_SESSION['uzenet_status'] = ['siker' => true, 'uzenet' => 'Üzenetedet sikeresen elküldtük és rögzítettük!'];
                header("Location: " . $redirect_page);
                exit();
            } else {
                // Adatbázis hiba mentéskor
                $_SESSION['uzenet_status'] = ['siker' => false, 'uzenet' => 'Hiba történt az üzenet mentésekor (adatbázis).'];
                header("Location: " . $redirect_page);
                exit();
            }

        } catch (PDOException $e) {
            error_log("Kapcsolat űrlap mentési hiba: " . $e->getMessage()); // Hibanaplózás
            $_SESSION['uzenet_status'] = ['siker' => false, 'uzenet' => 'Adatbázis hiba történt az üzenet feldolgozása során.'];
            header("Location: " . $redirect_page);
            exit();
        }
    } else {
        // Ha validációs hiba volt
        $_SESSION['uzenet_status'] = ['siker' => false, 'uzenet' => trim($hiba_uzenet)];
        // Opcionális: Visszaadhatnánk a már beírt értékeket is a session-ben,
        // hogy az űrlap újratöltéskor ne vesszenek el, de ez bonyolítaná.
        header("Location: " . $redirect_page);
        exit();
    }

} else {
    // Ha nem POST kéréssel próbálják elérni
    header("Location: " . $redirect_page);
    exit();
}

?>