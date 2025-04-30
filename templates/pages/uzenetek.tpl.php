<h2>Beérkezett Üzenetek</h2>

<?php
// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
if (!isset($_SESSION['user_id'])) {
    // Ha nincs bejelentkezve, ne jelenítsük meg az üzeneteket
    echo "<p>Az üzenetek megtekintéséhez be kell jelentkeznie!</p>";
} else {
    // Ha be van jelentkezve, lekérdezzük és megjelenítjük az üzeneteket

    try {
        // Üzenetek lekérdezése az adatbázisból, fordított időrendben
        // A $pdo változó a config.inc.php-ból érkezik, amit az index.php már betöltött
        $sql = "SELECT nev, email, uzenet, DATE_FORMAT(datum, '%Y-%m-%d %H:%i:%s') as formatalt_datum
                FROM uzenetek
                ORDER BY datum DESC";
        $stmt = $pdo->query($sql); // Egyszerű lekérdezés, nincs felhasználói input

        // Ellenőrizzük, hogy vannak-e üzenetek
        if ($stmt->rowCount() > 0) {
?>
            <p>Az eddig beérkezett üzenetek, legfrissebb elöl:</p>
            <table border="1" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="padding: 8px; text-align: left;">Dátum</th>
                        <th style="padding: 8px; text-align: left;">Név</th>
                        <th style="padding: 8px; text-align: left;">E-mail</th>
                        <th style="padding: 8px; text-align: left;">Üzenet</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($uzenet = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                        <tr>
                            <td style="padding: 8px; vertical-align: top;"><?php echo htmlspecialchars($uzenet['formatalt_datum']); ?></td>
                            <td style="padding: 8px; vertical-align: top;"><?php echo htmlspecialchars($uzenet['nev']); ?></td>
                            <td style="padding: 8px; vertical-align: top;"><?php echo htmlspecialchars($uzenet['email']); ?></td>
                            <td style="padding: 8px; vertical-align: top;"><?php echo nl2br(htmlspecialchars($uzenet['uzenet'])); // nl2br az új sorokhoz ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
<?php
        } else {
            // Ha nincsenek üzenetek
            echo "<p>Még nem érkezett üzenet.</p>";
        }

    } catch (PDOException $e) {
        // Adatbázis hiba esetén
        error_log("Üzenetek lekérdezési hiba: " . $e->getMessage());
        echo "<p style='color: red;'>Hiba történt az üzenetek lekérdezése közben.</p>";
    }
} // vége az if (isset($_SESSION['user_id'])) blokknak
?>