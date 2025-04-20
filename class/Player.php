<?php
class Player
{
    private $pdo;   // Database connection

    // Konstruktor untuk menginisialisasi koneksi database
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Fungsi untuk mendapatkan semua pemain
    public function getPlayers()
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM players ORDER BY name ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error getting players: ' . $e->getMessage());
            return [];
        }
    }

    // Fungsi untuk mendapatkan pemain berdasarkan ID
    public function getPlayerById($id_player)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM players WHERE id_player = :id_player");
            $stmt->execute([':id_player' => $id_player]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error getting player by ID: ' . $e->getMessage());
            return false;
        }
    }

    // Fungsi untuk menambah pemain
    public function addPlayer($name, $position, $dob, $nationality)
    {
        try {
            $sql = "INSERT INTO players (name, position, dob, nationality) VALUES (:name, :position, :dob, :nationality)";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([
                ':name' => $name,
                ':position' => $position,
                ':dob' => $dob,
                ':nationality' => $nationality
            ]);
            return $result ? $this->pdo->lastInsertId() : false;
        } catch (PDOException $e) {
            error_log('Error adding player: ' . $e->getMessage());
            return false;
        }
    }

    // Fungsi untuk mengedit data pemain
    public function updatePlayer($id_player, $name, $position, $dob, $nationality)
    {
        try {
            $sql = "UPDATE players SET name = :name, position = :position, dob = :dob, nationality = :nationality WHERE id_player = :id_player";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([
                ':id_player' => $id_player,
                ':name' => $name,
                ':position' => $position,
                ':dob' => $dob,
                ':nationality' => $nationality
            ]);
            return $result;
        } catch (PDOException $e) {
            error_log('Error updating player: ' . $e->getMessage());
            return false;
        }
    }

    // Fungsi untuk menghapus pemain
    public function deletePlayer($id_player)
    {
        try {
            $this->pdo->beginTransaction();

            // First delete related records from player_match
            $sql = "DELETE FROM player_match WHERE id_player = :id_player";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id_player' => $id_player]);

            // Then delete the player
            $sql = "DELETE FROM players WHERE id_player = :id_player";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([':id_player' => $id_player]);

            $this->pdo->commit();
            return $result;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log('Error deleting player: ' . $e->getMessage());
            return false;
        }
    }

    // Fungsi untuk mencari pemain berdasarkan nama
    public function searchPlayer($name)
    {
        try {
            $sql = "SELECT * FROM players WHERE name LIKE :name ORDER BY name ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':name' => '%' . $name . '%']);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error searching player: ' . $e->getMessage());
            return [];
        }
    }
}
