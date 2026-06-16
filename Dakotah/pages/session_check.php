<?php
// Session
session_start();

header('Content-Type: application/json');

// Check if logged in
if (isset($_SESSION['klant_id'])) {
    echo json_encode([
        'loggedin' => true,
        'naam' => $_SESSION['klant_naam'],
        'rol' => $_SESSION['klant_rol']
    ]);
} else {
    echo json_encode(['loggedin' => false]);
}