<?php

require_once  '../class/classMenu.php';

$ShoppingCart = new ShoppingCart();
/*
 * Tijdelijk hardcoded account ID.
 */
$customerId = 2;

if (isset($_POST['add'])) {

    $ShoppingCart->addToOrder(
        $customerId,
        (int)$_POST['item_id']
    );

    header("Location: menu.php");
    exit;
}

if (isset($_POST['delete'])) {
    $ShoppingCart->deleteItem(
        (int)$_POST['item_id']
    );

    header("Location: menu.php");
    exit;
}

$items = $ShoppingCart->readItems();
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>

    <link rel="stylesheet" href="../../Lucas/styling/styling.css">
    <link rel="stylesheet" href="../styling/order-popup.css">


</head>

<body>
    <?php include('../../Lucas/prefabs/navbar.php'); ?>

    <div class="menu-container">

        <?php foreach ($items as $item): ?>
            <div class="menu-item">
                <h3><?= htmlspecialchars($item['item']) ?></h3>
                <p><?= htmlspecialchars($item['omschrijving']) ?></p>
                <p>€<?= htmlspecialchars($item['prijs']) ?></p>
                <form method="post">
                    <input
                        type="hidden"
                        name="item_id"
                        value="<?= $item['ID'] ?>">
                    <button
                        type="submit"
                        name="add"
                        class="add">
                        Voeg toe
                    </button>
                    <button
                        type="submit"
                        name="delete"
                        class="delete">
                        Verwijder
                    </button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>


</body>

</html>