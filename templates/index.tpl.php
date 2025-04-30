<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $akt_oldal['szoveg']; ?> - Receptgyűjtemény</title>
    <link rel="stylesheet" href="styles/stilus.css">
</head>
<body>

    <header>
        <h1>Receptgyűjtemény</h1>
        <nav>
            <ul>
                <?php foreach ($oldalak as $url => $oldal_info) { ?>
                    <?php // Csak azokat jelenítjük meg, amiknek van menüszövege ?>
                    <?php if (!empty($oldal_info['szoveg'])) { ?>
                        <li <?php echo ($url == $keresett_oldal_kulcs) ? 'class="active"' : ''; ?>>
                            <a href="?oldal=<?php echo $url; ?>"><?php echo $oldal_info['szoveg']; ?></a>
                        </li>
                    <?php } ?>
                <?php } ?>
            </ul>
        </nav>
    </header>

    <main>
        <?php
            // Az aktuális oldal sablonjának betöltése
            include("./templates/pages/{$keres}.tpl.php");
        ?>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Receptgyűjtemény</p>
    </footer>

</body>
</html>