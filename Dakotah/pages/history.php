<?php
// Session
session_start();
require_once '../class/classMenu.php';

// Redirect to menu if not logged in
if (!isset($_SESSION['klant_naam'])) {
    header("Location: menu.php");
    exit;
}

$cart = new ShoppingCart();
$bestellingen = $cart->getOrderHistory($_SESSION['klant_naam']);
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bestelhistorie</title>

    <link rel="stylesheet" href="../../dakotah/styling/styling.css">
    <link rel="stylesheet" href="../../dakotah/styling/order-popup.css">
</head>

<body>
    <?php include('../../dakotah/prefabs/navbar.php'); ?>

    <div class="menu-container">
        <h2>Mijn bestellingen</h2>

        <?php if (empty($bestellingen)): ?>
            <p>Je hebt nog geen bestellingen geplaatst.</p>
        <?php else: ?>
            <?php foreach ($bestellingen as $bestelling): ?>
                <div class="menu-item">
                    <h3><?= htmlspecialchars($bestelling['datum']) ?></h3>
                    <p>Totaal: €<?= htmlspecialchars($bestelling['totaal_prijs']) ?></p>
                    <p>Aantal personen: <?= htmlspecialchars($bestelling['aantal_klanten']) ?></p>
                    <p>Status: <?= $bestelling['betaald_bool'] ? 'Betaald' : 'Niet betaald' ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</body>

</html>