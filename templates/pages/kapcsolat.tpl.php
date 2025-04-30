<h2>Kapcsolat</h2>
<p>Kérdésed van? Írj nekünk az alábbi űrlapon keresztül!</p>

<?php
    // Visszajelzés a session-ből (a feldolgozó szkript állítja be)
    if (isset($_SESSION['uzenet_status'])) {
        $status = $_SESSION['uzenet_status'];
        $tipus = ($status['siker']) ? 'siker' : 'hiba';
        echo '<p class="uzenet-statusz ' . $tipus . '">' . htmlspecialchars($status['uzenet']) . '</p>';
        unset($_SESSION['uzenet_status']); // Üzenet törlése a megjelenítés után
    }
?>

<form name="kapcsolaturlap" action="?oldal=uzenetkuldes" method="post" onsubmit="return ellenorizKapcsolat();" novalidate>
    <div>
        <label for="nev">Név (minimum 5 karakter):</label><br>
        <input type="text" id="nev" name="nev" size="30" maxlength="100">
        <span class="hiba-uzenet" id="nev-hiba"></span> <br><br>
    </div>
    <div>
        <label for="email">E-mail (valós cím):</label><br>
        <input type="email" id="email" name="email" size="30" maxlength="255">
        <span class="hiba-uzenet" id="email-hiba"></span> <br><br>
    </div>
    <div>
        <label for="uzenet">Üzenet (kötelező):</label><br>
        <textarea id="uzenet" name="uzenet" cols="50" rows="10"></textarea>
        <span class="hiba-uzenet" id="uzenet-hiba"></span> <br><br>
    </div>
    <div>
        <input type="submit" value="Üzenet küldése">
    </div>
</form>

<style>
    .hiba-uzenet {
        color: red;
        font-size: 0.9em;
        margin-left: 10px;
    }
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
    input.invalid, textarea.invalid {
        border: 1px solid red;
        background-color: #fdd;
    }
     input.valid, textarea.valid {
        border: 1px solid green;
        background-color: #dfd;
    }
</style>