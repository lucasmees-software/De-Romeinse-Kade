<?php
// Session
session_start();
require_once '../class/classMenu.php';

// Hardcoded for testing, replace with $_SESSION['klant_id'] when login is done
$customerId = 2;

// Get order items
$cart = new ShoppingCart();
$items = $cart->getOrderItems($customerId);

// Return as JSON
header('Content-Type: application/json');
echo json_encode($items);