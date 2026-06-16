<?php
require_once ('../../../De-Romeinse-Kade/main/db/db.php');
class AccountManagement
{
    public function register($username, $email, $password)
    {
        $dbClass = new Database();
        $db = $dbClass->connection();

        $stmt = $db->prepare("INSERT INTO accounts (naam, email, wachtwoord) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
    }

    public function login($email, $password)
    {
        
    }
    
    public function logout()
    {
    }
}
?>