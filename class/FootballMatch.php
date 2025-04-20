<?php
class FootballMatch
{
    private $pdo;   // Database connection

    // konstruktor untuk menginisialisasi koneksi database
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Fungsi untuk mendapatkan semua pertandingan
    public function getMatches()
    {
        $stmt = $this->pdo->query("SELECT * FROM matches ORDER BY date DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fungsi untuk mendapatkan pertandingan berdasarkan ID
    public function getMatchById($id_match)
    {
        $sql = "SELECT * FROM matches WHERE id_match = :id_match";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_match' => $id_match]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Fungsi untuk mencari pertandingan berdasarkan tim
    public function searchMatch($team)
    {
        $sql = "SELECT * FROM matches WHERE team_a LIKE :team OR team_b LIKE :team ORDER BY date DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':team' => '%' . $team . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fungsi untuk menambah pertandingan
    public function addMatch($date, $team_a, $team_b, $score_a, $score_b)
    {
        try {
            $sql = "INSERT INTO matches (date, team_a, team_b, score_a, score_b) 
                    VALUES (:date, :team_a, :team_b, :score_a, :score_b)";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([
                ':date' => $date,
                ':team_a' => $team_a,
                ':team_b' => $team_b,
                ':score_a' => $score_a,
                ':score_b' => $score_b
            ]);
            return $result;
        } catch (PDOException $e) {
            error_log('Error adding match: ' . $e->getMessage());
            return false;
        }
    }

    // Fungsi untuk mengupdate pertandingan
    public function updateMatch($id_match, $date, $team_a, $team_b, $score_a, $score_b)
    {
        try {
            $sql = "UPDATE matches SET date = :date, team_a = :team_a, team_b = :team_b, 
                   score_a = :score_a, score_b = :score_b WHERE id_match = :id_match";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([
                ':id_match' => $id_match,
                ':date' => $date,
                ':team_a' => $team_a,
                ':team_b' => $team_b,
                ':score_a' => $score_a,
                ':score_b' => $score_b
            ]);
            return $result;
        } catch (PDOException $e) {
            error_log('Error updating match: ' . $e->getMessage());
            return false;
        }
    }

    // Fungsi untuk menghapus pertandingan
    public function deleteMatch($id_match)
    {
        try {
            // First check and delete any related records in player_match
            $sql = "DELETE FROM player_match WHERE id_match = :id_match";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id_match' => $id_match]);

            // Then delete the match
            $sql = "DELETE FROM matches WHERE id_match = :id_match";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([':id_match' => $id_match]);
            return $result;
        } catch (PDOException $e) {
            error_log('Error deleting match: ' . $e->getMessage());
            return false;
        }
    }
}
