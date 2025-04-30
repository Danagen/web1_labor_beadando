<h2>Új Recept Hozzáadása</h2>

<?php
// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
if (!isset($_SESSION['user_id'])) {
    echo "<p>Új recept hozzáadásához be kell jelentkeznie!</p>";
} else {
    // Ha be van jelentkezve, megjelenítjük az űrlapot

    // Visszajelzés a session-ből
    if (isset($_SESSION['recept_status'])) {
        $status = $_SESSION['recept_status'];
        $tipus = ($status['siker']) ? 'siker' : 'hiba';
        echo '<p class="uzenet-statusz ' . $tipus . '">' . htmlspecialchars($status['uzenet']) . '</p>';
        unset($_SESSION['recept_status']); // Üzenet törlése
    }
?>
    <form action="?oldal=ujrecept_feldolgoz" method="post">
        <div>
            <label for="nev">Recept Neve:</label><br>
            <input type="text" id="nev" name="nev" size="50" required>
            <br><br>
        </div>
         <div>
            <label for="hozzavalok">Hozzávalók:</label><br>
            <textarea id="hozzavalok" name="hozzavalok" cols="70" rows="8"></textarea>
             <small>(Minden hozzávalót új sorba írj.)</small>
            <br><br>
        </div>
        <div>
            <label for="leiras">Elkészítés Leírása:</label><br>
            <textarea id="leiras" name="leiras" cols="70" rows="15"></textarea>
            <br><br>
        </div>
        <div>
            </div>
        <div>
            <input type="submit" value="Recept Mentése">
        </div>
    </form>

     <style>
    .uzenet-statusz {padding: 10px; margin-bottom: 15px; border-radius: 4px;}
    .uzenet-statusz.siker {background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;}
    .uzenet-statusz.hiba { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;}
    </style>

<?php
} // vége az if (isset($_SESSION['user_id'])) blokknak
?>