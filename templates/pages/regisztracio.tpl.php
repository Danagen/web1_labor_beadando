<h2>Regisztráció</h2>

<?php
    // Ha van hibaüzenet a session-ben (pl. a feldolgozó szkript állította be)
    if (isset($_SESSION['reg_error'])) {
        echo '<p style="color: red;">' . $_SESSION['reg_error'] . '</p>';
        unset($_SESSION['reg_error']); // Üzenet törlése a megjelenítés után
    }
    // Ha van sikerüzenet
    if (isset($_SESSION['reg_success'])) {
        echo '<p style="color: green;">' . $_SESSION['reg_success'] . '</p>';
        unset($_SESSION['reg_success']); // Üzenet törlése
    }
?>

<form action="?oldal=regisztral_feldolgoz" method="post">
    <label for="csaladi_nev">Családi név:</label><br>
    <input type="text" id="csaladi_nev" name="csaladi_nev" required><br><br>

    <label for="uto_nev">Utónév:</label><br>
    <input type="text" id="uto_nev" name="uto_nev" required><br><br>

    <label for="bejelentkezes">Felhasználónév:</label><br>
    <input type="text" id="bejelentkezes" name="bejelentkezes" required maxlength="12"><br><br>

    <label for="jelszo">Jelszó:</label><br>
    <input type="password" id="jelszo" name="jelszo" required><br><br>

    <label for="jelszo_ujra">Jelszó újra:</label><br>
    <input type="password" id="jelszo_ujra" name="jelszo_ujra" required><br><br>

    <input type="submit" value="Regisztráció">
</form>