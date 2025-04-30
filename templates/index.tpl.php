<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($akt_oldal['szoveg'] ?: 'Receptgyűjtemény'); ?> - Receptgyűjtemény</title>
    <link rel="stylesheet" href="styles/stilus.css">
</head>
<body>

    <header>
        <h1>Receptgyűjtemény</h1>

        <?php
            // Üdvözlő üzenet és felhasználói infó kijelzése, ha be van lépve
            if (isset($_SESSION['user_id'])) {
                echo '<div class="user-info">';
                echo 'Bejelentkezve: <strong>' . htmlspecialchars($_SESSION['user_csaladi_nev'] ?? '') . ' ' . htmlspecialchars($_SESSION['user_uto_nev'] ?? '') . '</strong>';
                echo ' (' . htmlspecialchars($_SESSION['user_bejelentkezes'] ?? '') . ')';
                echo '</div>';
            }
        ?>

        <nav>
            <ul>
                <?php
                    // Meghatározzuk, hogy a felhasználó be van-e jelentkezve
                    $bejelentkezve = isset($_SESSION['user_id']);

                    foreach ($oldalak as $url => $oldal_info) {
                        // Megnézzük a menu_allapot tömböt:
                        // 1: Megjelenik bejelentkezve, 0: Nem jelenik meg bejelentkezve
                        // 1: Megjelenik kijelentkezve, 0: Nem jelenik meg kijelentkezve
                        $latszik_bejelentkezve = $oldal_info['menu_allapot'][0] == 1;
                        $latszik_kijelentkezve = $oldal_info['menu_allapot'][1] == 1;

                        // Csak akkor jelenítjük meg a menüpontot, ha:
                        // - Van szövege ÉS
                        // - (A felhasználó be van jelentkezve ÉS az adott menüpont látható bejelentkezve) VAGY
                        // - (A felhasználó nincs bejelentkezve ÉS az adott menüpont látható kijelentkezve)
                        if (!empty($oldal_info['szoveg']) &&
                           (($bejelentkezve && $latszik_bejelentkezve) || (!$bejelentkezve && $latszik_kijelentkezve)))
                        {
                ?>
                            <li <?php echo ($url == $keresett_oldal_kulcs) ? 'class="active"' : ''; ?>>
                                <a href="?oldal=<?php echo $url; ?>"><?php echo htmlspecialchars($oldal_info['szoveg']); ?></a>
                            </li>
                <?php
                        } // end if látható a menüpont
                    } // end foreach
                ?>
            </ul>
        </nav>
    </header>

    <main>
        <?php
            // Az aktuális oldal sablonjának betöltése
            // Feltételezzük, hogy az $akt_oldal és $keres változók léteznek az index.php-ból
            if (isset($keres) && file_exists("./templates/pages/{$keres}.tpl.php")) {
                include("./templates/pages/{$keres}.tpl.php");
            } else {
                // Ha valamilyen hiba folytán mégsem létezne, a 404-et töltjük be
                include("./templates/pages/404.tpl.php");
            }
        ?>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Receptgyűjtemény</p>
    </footer>

</body>
</html>