<?php
// Session
session_start();
require_once '../../../De-Romeinse-Kade/main/db/db.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$action = $data['action'] ?? '';

$dbClass = new Database();
$db = $dbClass->connection();

// Login
if ($action === 'login') {
    $email = $data['email'] ?? '';
    $wachtwoord = $data['wachtwoord'] ?? '';

    $stmt = $db->prepare("SELECT * FROM accounts WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $account = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($account && password_verify($wachtwoord, $account['wachtwoord'])) {
        // Save to session
        $_SESSION['klant_id'] = $account['ID'];
        $_SESSION['klant_naam'] = $account['naam'];
        $_SESSION['klant_rol'] = $account['rol'];
        echo json_encode(['success' => true, 'naam' => $account['naam']]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Verkeerd e-mailadres of wachtwoord.']);
    }
}

// Register
if ($action === 'register') {
    $naam = $data['naam'] ?? '';
    $email = $data['email'] ?? '';
    $wachtwoord = $data['wachtwoord'] ?? '';

    // Check if email already exists
    $stmt = $db->prepare("SELECT ID FROM accounts WHERE email = :email");
    $stmt->execute([':email' => $email]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'error' => 'E-mailadres is al in gebruik.']);
        exit;
    }

    // Hash password and insert
    $hash = password_hash($wachtwoord, PASSWORD_DEFAULT);
    $stmt = $db->prepare("
        INSERT INTO accounts (naam, email, wachtwoord, omzet, rol)
        VALUES (:naam, :email, :wachtwoord, 0, 'klant')
    ");
    $stmt->execute([
        ':naam' => $naam,
        ':email' => $email,
        ':wachtwoord' => $hash
    ]);

    $id = $db->lastInsertId();

    // Auto login after register
    $_SESSION['klant_id'] = $id;
    $_SESSION['klant_naam'] = $naam;
    $_SESSION['klant_rol'] = 'klant';

    echo json_encode(['success' => true, 'naam' => $naam]);
}

// Logout
if ($action === 'logout') {
    session_destroy();
    echo json_encode(['success' => true]);
}   