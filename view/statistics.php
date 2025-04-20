<?php
include_once '../config/db.php';
include_once '../class/PlayerMatch.php';
include_once '../class/Player.php';

$playerMatch = new PlayerMatch($pdo);
$playerClass = new Player($pdo);
$message = '';

// Pastikan id_player diambil dari URL
if (isset($_GET['id_player'])) {
    $id_player = $_GET['id_player'];
    // Mengambil data pemain
    $playerData = $playerClass->getPlayerById($id_player);
    if (!$playerData) {
        echo "Data pemain tidak ditemukan!";
        exit();
    }

    // Ambil statistik pemain berdasarkan ID pemain
    $playerStats = $playerMatch->getPlayerStats($id_player);

    // Ambil total statistik pemain
    $totalStats = $playerMatch->getPlayerTotalStats($id_player);

    // Ambil daftar pertandingan untuk form tambah statistik
    $availableMatches = $playerMatch->getAvailableMatches();
} else {
    echo "<script>alert('ID Pemain tidak ditemukan. Silakan pilih pemain.'); window.location.href='players.php';</script>";
    exit();
}

// Menghapus statistik pemain
if (isset($_GET['delete'])) {
    $id_player_match = $_GET['delete'];
    if ($playerMatch->deletePlayerStats($id_player_match)) {
        $message = '<div class="alert alert-success">Statistik pemain berhasil dihapus!</div>';
    } else {
        $message = '<div class="alert alert-danger">Gagal menghapus statistik pemain!</div>';
    }
    // Redirect untuk menghindari resubmission saat refresh
    header("Location: statistics.php?id_player=$id_player");
    exit();
}

// Mengedit statistik pemain
if (isset($_GET['edit'])) {
    $id_player_match = $_GET['edit'];
    // Ambil data statistik pemain berdasarkan ID
    $edit_stats = $playerMatch->getPlayerStatById($id_player_match);

    if (!$edit_stats) {
        $message = '<div class="alert alert-danger">Data statistik tidak ditemukan!</div>';
    }
}

// Memproses form edit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_stats'])) {
    $id_player_match = $_POST['id_player_match'];
    $goals = $_POST['goals'];
    $assists = $_POST['assists'];

    if ($playerMatch->updatePlayerStats($id_player_match, $goals, $assists)) {
        $message = '<div class="alert alert-success">Statistik pemain berhasil diupdate!</div>';
        // Redirect untuk menghindari resubmission saat refresh
        header("Location: statistics.php?id_player=$id_player");
        exit();
    } else {
        $message = '<div class="alert alert-danger">Gagal mengupdate statistik pemain!</div>';
    }
}

// Memproses form tambah statistik
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_stats'])) {
    $player_id = $_POST['player_id'];
    $match_id = $_POST['match_id'];
    $goals = $_POST['goals'];
    $assists = $_POST['assists'];

    if ($playerMatch->addPlayerMatch($player_id, $match_id, $goals, $assists)) {
        $message = '<div class="alert alert-success">Statistik pemain berhasil ditambahkan!</div>';
        // Redirect untuk menghindari resubmission saat refresh
        header("Location: statistics.php?id_player=$id_player");
        exit();
    } else {
        $message = '<div class="alert alert-danger">Gagal menambahkan statistik pemain!</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik Pemain - Liverpool FC</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <div class="container mt-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../index.php">Beranda</a></li>
                <li class="breadcrumb-item"><a href="players.php">Daftar Pemain</a></li>
                <li class="breadcrumb-item active">Statistik Pemain</li>
            </ol>
        </nav>

        <h1 class="mb-4">Statistik Pemain: <?php echo htmlspecialchars($playerData['name']); ?></h1>

        <?php echo $message; ?>

        <!-- Detail Pemain -->
        <div class="card mb-4">
            <div class="card-header liverpool-red">
                <h5 class="card-title m-0">Detail Pemain</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nama:</strong> <?php echo htmlspecialchars($playerData['name']); ?></p>
                        <p><strong>Posisi:</strong> <?php echo htmlspecialchars($playerData['position']); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Tanggal Lahir:</strong> <?php echo date('d-m-Y', strtotime($playerData['dob'])); ?></p>
                        <p><strong>Kewarganegaraan:</strong> <?php echo htmlspecialchars($playerData['nationality']); ?></p>
                    </div>
                </div>
                <div class="card bg-light">
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <h4><?php echo $totalStats['total_matches'] ?? 0; ?></h4>
                                <p>Total Pertandingan</p>
                            </div>
                            <div class="col-md-4">
                                <h4><?php echo $totalStats['total_goals'] ?? 0; ?></h4>
                                <p>Total Gol</p>
                            </div>
                            <div class="col-md-4">
                                <h4><?php echo $totalStats['total_assists'] ?? 0; ?></h4>
                                <p>Total Assist</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Tambah Statistik Pemain -->
        <div class="card mb-4">
            <div class="card-header liverpool-red">
                <h5 class="card-title m-0">Tambah Statistik Pemain</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="player_id" value="<?php echo $id_player; ?>">
                    <input type="hidden" name="add_stats" value="1">

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="match_id" class="form-label">Pilih Pertandingan:</label>
                            <select class="form-select" id="match_id" name="match_id" required>
                                <option value="">-- Pilih Pertandingan --</option>
                                <?php foreach ($availableMatches as $match): ?>
                                    <option value="<?php echo $match['id_match']; ?>">
                                        <?php echo date('d-m-Y', strtotime($match['date'])) . ' - ' .
                                            htmlspecialchars($match['team_a']) . ' vs ' .
                                            htmlspecialchars($match['team_b']) . ' (' .
                                            $match['score_a'] . '-' . $match['score_b'] . ')'; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="goals" class="form-label">Gol:</label>
                            <input type="number" class="form-control" id="goals" name="goals" min="0" value="0" required>
                        </div>
                        <div class="col-md-6">
                            <label for="assists" class="form-label">Assist:</label>
                            <input type="number" class="form-control" id="assists" name="assists" min="0" value="0" required>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn liverpool-btn">Tambah Statistik</button>
                    </div>
                </form>
            </div>
        </div>

        <?php if (isset($edit_stats)): ?>
            <!-- Form Edit Statistik Pemain -->
            <div class="card mb-4">
                <div class="card-header liverpool-red">
                    <h5 class="card-title m-0">Edit Statistik Pemain</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="id_player_match" value="<?php echo $edit_stats['id_player_match']; ?>">
                        <input type="hidden" name="update_stats" value="1">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="edit_goals" class="form-label">Gol:</label>
                                <input type="number" class="form-control" id="edit_goals" name="goals" min="0"
                                    value="<?php echo $edit_stats['goals']; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_assists" class="form-label">Assist:</label>
                                <input type="number" class="form-control" id="edit_assists" name="assists" min="0"
                                    value="<?php echo $edit_stats['assists']; ?>" required>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="statistics.php?id_player=<?php echo $id_player; ?>" class="btn btn-secondary me-md-2">Batal</a>
                            <button type="submit" class="btn liverpool-btn">Update Statistik</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <!-- Daftar Statistik Pemain -->
        <div class="card">
            <div class="card-header liverpool-red">
                <h5 class="card-title m-0">Daftar Statistik Pemain</h5>
            </div>
            <div class="card-body">
                <?php if (count($playerStats) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Pertandingan</th>
                                    <th>Skor</th>
                                    <th>Gol</th>
                                    <th>Assist</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($playerStats as $stat): ?>
                                    <tr>
                                        <td><?php echo date('d-m-Y', strtotime($stat['date'])); ?></td>
                                        <td><?php echo htmlspecialchars($stat['team_a']) . ' vs ' . htmlspecialchars($stat['team_b']); ?></td>
                                        <td><?php echo $stat['score_a'] . ' - ' . $stat['score_b']; ?></td>
                                        <td><?php echo $stat['goals']; ?></td>
                                        <td><?php echo $stat['assists']; ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="statistics.php?id_player=<?php echo $id_player; ?>&edit=<?php echo $stat['id_player_match']; ?>"
                                                    class="btn btn-sm btn-warning">Edit</a>
                                                <a href="statistics.php?id_player=<?php echo $id_player; ?>&delete=<?php echo $stat['id_player_match']; ?>"
                                                    class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus statistik ini?')">Hapus</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">Tidak ada statistik untuk pemain ini.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>