<?php
require_once '../class/classMenu.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&family=Oswald:wght@200..700&family=Outfit:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../../De-Romeinse-Kade/Dakotah/styling/styling.css">
    <title>Registration</title>
</head>

<body>
    <header>
        <?php include('../../../De-Romeinse-Kade/Lucas/prefabs/navbar.php') ?>
    </header>
    <main>
        <form action="registration.php" method="POST">
            <h1>Registration</h1>
            <label for="username">naam:</label>
            <input type="text" id="username" name="username" required>

            <label for="email">email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">wachtwoord:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" name="register">Register</button>
        </form>
    </main>
</body>

</html>

<?php
if (isset($_POST['username'])) {
    $username = $_POST['username'];
    echo "Username: " . $username . "<br>";
}
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    echo "registered: " . $username . "<br>";
    $account = new account();
    $account->register($_POST['username'], $_POST['email'], $_POST['password']);
}



?>