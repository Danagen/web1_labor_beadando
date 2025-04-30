<h2>Képfeltöltés a Galériába</h2>

<?php
// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
if (!isset($_SESSION['user_id'])) {
    echo "<p>A képfeltöltéshez be kell jelentkeznie!</p>";
} else {
    // Ha be van jelentkezve, megjelenítjük az űrlapot

    // Visszajelzés a session-ből (a feltöltés feldolgozója állítja be)
    if (isset($_SESSION['upload_status'])) {
        $status = $_SESSION['upload_status'];
        $tipus = ($status['siker']) ? 'siker' : 'hiba';
        echo '<p class="uzenet-statusz ' . $tipus . '">' . htmlspecialchars($status['uzenet']) . '</p>';
        unset($_SESSION['upload_status']); // Üzenet törlése a megjelenítés után
    }
?>
    <p>Válassz ki egy képet (JPG, PNG vagy GIF, max. <?php echo $GLOBALS['kep_max_meret'] / 1024 / 1024; ?> MB):</p>

    <form action="?oldal=feltolt_feldolgoz" method="post" enctype="multipart/form-data">
        <div>
            <label for="kepfajl">Feltöltendő kép:</label><br>
            <input type="file" id="kepfajl" name="kepfajl" accept="image/jpeg, image/png, image/gif" required>
            <br><br>
        </div>
        <div>
            <input type="submit" value="Kép feltöltése">
        </div>
    </form>

    <style>
    .uzenet-statusz {
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 4px;
    }
    .uzenet-statusz.siker {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
     .uzenet-statusz.hiba {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    </style>

<?php
} // vége az if (isset($_SESSION['user_id'])) blokknak
?>