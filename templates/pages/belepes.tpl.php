<h2>Belépés</h2>

<?php
    // Ha van hibaüzenet a session-ben (pl. a feldolgozó szkript állította be)
    if (isset($_SESSION['login_error'])) {
        echo '<p style="color: red;">' . $_SESSION['login_error'] . '</p>';
        unset($_SESSION['login_error']); // Üzenet törlése a megjelenítés után
    }
?>

<form action="?oldal=belep_feldolgoz" method="post">
    <label for="bejelentkezes">Felhasználónév:</label><br>
    <input type="text" id="bejelentkezes" name="bejelentkezes" required><br><br>

    <label for="jelszo">Jelszó:</label><br>
    <input type="password" id="jelszo" name="jelszo" required><br><br>

    <input type="submit" value="Belépés">
</form>