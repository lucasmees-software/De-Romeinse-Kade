<?php
// Session
session_start();
require_once '../class/classMenu.php';

$customerId = $_SESSION['klant_id'] ?? 0;

// Get order items
$cart = new ShoppingCart();
$items = $cart->getOrderItems($customerId);

// Return as JSON
header('Content-Type: application/json');
echo json_encode($items);