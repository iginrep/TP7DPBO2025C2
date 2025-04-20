<?php
class PlayerMatch
{
    private $pdo;   // Database connection

    // Konstruktor untuk menginisialisasi koneksi database
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Fungsi untuk mendapatkan statistik pemain dalam pertandingan
    public function getPlayerStats($player_id)
    {
        try {
            // Query untuk mengambil statistik pemain berdasarkan ID pemain dengan detail pertandingan
            $sql = "SELECT pm.*, m.date, m.team_a, m.team_b, m.score_a, m.score_b 
                    FROM player_match pm
                    INNER JOIN matches m ON pm.id_match = m.id_match
                    WHERE pm.id_player = :player_id
                    ORDER BY m.date DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':player_id' => $player_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error getting player stats: ' . $e->getMessage());
            return [];
        }
    }

    // Fungsi untuk mendapatkan statistik spesifik pemain dalam pertandingan
    public function getPlayerStatById($id_player_match)
    {
        try {
            $sql = "SELECT * FROM player_match WHERE id_player_match = :id_player_match";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id_player_match' => $id_player_match]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error getting player stat by ID: ' . $e->getMessage());
            return false;
        }
    }

    // Fungsi untuk menambah statistik pemain dalam pertandingan
    public function addPlayerMatch($player_id, $match_id, $goals, $assists)
    {
        try {
            // Verify player and match exist
            $playerCheck = $this->pdo->prepare("SELECT * FROM players WHERE id_player = :player_id");
            $playerCheck->execute([':player_id' => $player_id]);
            if (!$playerCheck->fetch()) {
                return false;
            }

            $matchCheck = $this->pdo->prepare("SELECT * FROM matches WHERE id_match = :match_id");
            $matchCheck->execute([':match_id' => $match_id]);
            if (!$matchCheck->fetch()) {
                return false;
            }

            // Check if record already exists
            $checkExisting = $this->pdo->prepare("SELECT * FROM player_match WHERE id_player = :player_id AND id_match = :match_id");
            $checkExisting->execute([
                ':player_id' => $player_id,
                ':match_id' => $match_id
            ]);

            if ($checkExisting->fetch()) {
                // Update existing record
                $sql = "UPDATE player_match SET goals = :goals, assists = :assists 
                        WHERE id_player = :player_id AND id_match = :match_id";
            } else {
                // Insert new record
                $sql = "INSERT INTO player_match (id_player, id_match, goals, assists) 
                        VALUES (:player_id, :match_id, :goals, :assists)";
            }

            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([
                ':player_id' => $player_id,
                ':match_id' => $match_id,
                ':goals' => $goals,
                ':assists' => $assists
            ]);

            return $result;
        } catch (PDOException $e) {
            error_log('Error adding player match: ' . $e->getMessage());
            return false;
        }
    }

    // Fungsi untuk mengedit statistik pemain dalam pertandingan
    public function updatePlayerStats($id_player_match, $goals, $assists)
    {
        try {
            $sql = "UPDATE player_match SET goals = :goals, assists = :assists WHERE id_player_match = :id_player_match";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([
                ':id_player_match' => $id_player_match,
                ':goals' => $goals,
                ':assists' => $assists
            ]);
            return $result;
        } catch (PDOException $e) {
            error_log('Error updating player stats: ' . $e->getMessage());
            return false;
        }
    }

    // Fungsi untuk menghapus statistik pemain dalam pertandingan
    public function deletePlayerStats($id_player_match)
    {
        try {
            $sql = "DELETE FROM player_match WHERE id_player_match = :id_player_match";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([':id_player_match' => $id_player_match]);
            return $result;
        } catch (PDOException $e) {
            error_log('Error deleting player stats: ' . $e->getMessage());
            return false;
        }
    }

    // Fungsi untuk mendapatkan semua pertandingan yang tersedia untuk statistik
    public function getAvailableMatches()
    {
        try {
            $sql = "SELECT * FROM matches ORDER BY date DESC";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error getting available matches: ' . $e->getMessage());
            return [];
        }
    }

    // Fungsi untuk mendapatkan total statistik pemain
    public function getPlayerTotalStats($player_id)
    {
        try {
            $sql = "SELECT SUM(goals) as total_goals, SUM(assists) as total_assists, 
                    COUNT(id_match) as total_matches
                    FROM player_match 
                    WHERE id_player = :player_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':player_id' => $player_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error getting player total stats: ' . $e->getMessage());
            return false;
        }
    }
}
