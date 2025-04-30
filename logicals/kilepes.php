<?php
// Munkamenet indítása, hogy hozzáférjünk
session_start();

// Session változók törlése
session_unset();

// Munkamenet megszüntetése
session_destroy();

// Átirányítás a főoldalra
header("Location: ?oldal=cimlap");
exit();
?>