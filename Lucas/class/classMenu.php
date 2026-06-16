<?php
require_once '../../../De-Romeinse-Kade/main/db/db.php';

class ShoppingCart
{
    private PDO $db;

    public function __construct()
    {
        $dbClass = new Database();
        $this->db = $dbClass->connection();
    }

    // Read all menu items
    public function readItems(): array
    {
        $stmt = $this->db->prepare("SELECT * FROM items");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add a new menu item
    public function addItem(string $item, float $prijs, string $omschrijving): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO items (item, prijs, omschrijving)
            VALUES (:item, :prijs, :omschrijving)
        ");
        return $stmt->execute([
            ':item' => $item,
            ':prijs' => $prijs,
            ':omschrijving' => $omschrijving
        ]);
    }

    // Add item to order
    public function addToOrder(int $customerId, int $itemId): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO bestellingen (bestelling_ID, item)
            VALUES (:customer_id, :item)
        ");
        return $stmt->execute([
            ':customer_id' => $customerId,
            ':item' => $itemId
        ]);
    }

    // Delete one row of an item from order
    public function deleteItem(int $id): bool
    {
        if ($id <= 0) return false;

        $stmt = $this->db->prepare("
            DELETE FROM bestellingen
            WHERE item = :id
            LIMIT 1
        ");
        return $stmt->execute([':id' => $id]);
    }

    // Delete all rows of a specific item for a customer
    public function deleteAllOfItem(int $customerId, int $itemId): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM bestellingen
            WHERE bestelling_ID = :customer_id AND item = :item_id
        ");
        return $stmt->execute([
            ':customer_id' => $customerId,
            ':item_id' => $itemId
        ]);
    }

    // Get all order items for a customer with quantity
    public function getOrderItems(int $customerId): array
    {
        $stmt = $this->db->prepare("
            SELECT i.ID as item_id, i.item, i.prijs, COUNT(*) as aantal
            FROM bestellingen b
            JOIN items i ON b.item = i.ID
            WHERE b.bestelling_ID = :customer_id
            GROUP BY i.ID, i.item, i.prijs
        ");
        $stmt->execute([':customer_id' => $customerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update a menu item
    public function updateItem(int $id, string $item, float $prijs, string $omschrijving): bool
    {
        $stmt = $this->db->prepare("
            UPDATE items
            SET item = :item, prijs = :prijs, omschrijving = :omschrijving
            WHERE ID = :id
        ");
        return $stmt->execute([
            ':id' => $id,
            ':item' => $item,
            ':prijs' => $prijs,
            ':omschrijving' => $omschrijving
        ]);
    }
}