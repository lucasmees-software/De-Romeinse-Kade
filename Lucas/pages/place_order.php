<?php
// Session
session_start();
require_once '../class/classMenu.php';

header('Content-Type: application/json');

// Check if logged in
if (!isset($_SESSION['klant_id'])) {
    echo json_encode(['success' => false, 'error' => 'Niet ingelogd.']);
    exit;
}

// Get data from request
$data = json_decode(file_get_contents('php://input'), true);
$aantalKlanten = (int)($data['aantal_klanten'] ?? 1);

if ($aantalKlanten < 1) {
    $aantalKlanten = 1;
}

$customerId = (int)$_SESSION['klant_id'];
$klantNaam = $_SESSION['klant_naam'];

// Place the order
$cart = new ShoppingCart();
$success = $cart->placeOrder($customerId, $klantNaam, $aantalKlanten);

if ($success) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Winkelwagen is leeg.']);
}   