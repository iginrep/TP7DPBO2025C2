<?php
include_once '../config/db.php';
include_once '../class/FootballMatch.php';

$footballMatch = new FootballMatch($pdo);
$message = '';

// Menangani penghapusan pertandingan
if (isset($_GET['delete'])) {
    $id_match = $_GET['delete'];
    if ($footballMatch->deleteMatch($id_match)) {
        $message = '<div class="alert alert-success">Pertandingan berhasil dihapus!</div>';
    } else {
        $message = '<div class="alert alert-danger">Gagal menghapus pertandingan!</div>';
    }
}

// Menangani penambahan atau update pertandingan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['date'];
    $team_a = $_POST['team_a'];
    $team_b = $_POST['team_b'];
    $score_a = $_POST['score_a'];
    $score_b = $_POST['score_b'];

    if (isset($_POST['id_match'])) {
        // Update pertandingan
        $id_match = $_POST['id_match'];
        if ($footballMatch->updateMatch($id_match, $date, $team_a, $team_b, $score_a, $score_b)) {
            $message = '<div class="alert alert-success">Pertandingan berhasil diupdate!</div>';
        } else {
            $message = '<div class="alert alert-danger">Gagal mengupdate pertandingan!</div>';
        }
    } else {
        // Tambah pertandingan baru
        if ($footballMatch->addMatch($date, $team_a, $team_b, $score_a, $score_b)) {
            $message = '<div class="alert alert-success">Pertandingan berhasil ditambahkan!</div>';
        } else {
            $message = '<div class="alert alert-danger">Gagal menambahkan pertandingan!</div>';
        }
    }
}

// Mengambil data untuk edit
$edit_match = null;
if (isset($_GET['edit'])) {
    $id_match = $_GET['edit'];
    $edit_match = $footballMatch->getMatchById($id_match);
}

// Menangani pencarian berdasarkan nama tim
if (isset($_GET['search_team']) && !empty($_GET['search_team'])) {
    $search_team = $_GET['search_team'];
    // Filter pertandingan berdasarkan nama tim A atau tim B
    $matches = $footballMatch->searchMatch($search_team);
} else {
    // Ambil semua pertandingan jika tidak ada pencarian
    $matches = $footballMatch->getMatches();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pertandingan - Liverpool FC</title>
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
                <li class="breadcrumb-item active">Daftar Pertandingan</li>
            </ol>
        </nav>

        <h1 class="mb-4">Daftar Pertandingan Liverpool FC</h1>

        <?php echo $message; ?>

        <!-- Form Pencarian -->
        <div class="card mb-4">
            <div class="card-header liverpool-red">
                <h5 class="card-title m-0">Cari Pertandingan</h5>
            </div>
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-8">
                        <label for="search_team" class="form-label">Cari Berdasarkan Tim:</label>
                        <input type="text" class="form-control" name="search_team" id="search_team"
                            placeholder="Cari tim..." value="<?php echo isset($_GET['search_team']) ? $_GET['search_team'] : ''; ?>">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn liverpool-btn">Cari</button>
                        <?php if (isset($_GET['search_team'])): ?>
                            <a href="matches.php" class="btn btn-secondary ms-2">Reset</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- Form Tambah/Edit Pertandingan -->
        <div class="card mb-4">
            <div class="card-header liverpool-red">
                <h5 class="card-title m-0"><?php echo $edit_match ? 'Edit Pertandingan' : 'Tambah Pertandingan'; ?></h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <?php if ($edit_match): ?>
                        <input type="hidden" name="id_match" value="<?php echo $edit_match['id_match']; ?>">
                    <?php endif; ?>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="date" class="form-label">Tanggal Pertandingan:</label>
                            <input type="date" class="form-control" id="date" name="date"
                                value="<?php echo $edit_match ? $edit_match['date'] : ''; ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-5">
                            <label for="team_a" class="form-label">Tim A:</label>
                            <input type="text" class="form-control" id="team_a" name="team_a"
                                value="<?php echo $edit_match ? $edit_match['team_a'] : ''; ?>" required>
                        </div>
                        <div class="col-md-2 text-center d-flex align-items-end justify-content-center">
                            <span class="fw-bold">VS</span>
                        </div>
                        <div class="col-md-5">
                            <label for="team_b" class="form-label">Tim B:</label>
                            <input type="text" class="form-control" id="team_b" name="team_b"
                                value="<?php echo $edit_match ? $edit_match['team_b'] : ''; ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-5">
                            <label for="score_a" class="form-label">Skor Tim A:</label>
                            <input type="number" class="form-control" id="score_a" name="score_a" min="0"
                                value="<?php echo $edit_match ? $edit_match['score_a'] : '0'; ?>" required>
                        </div>
                        <div class="col-md-2"></div>
                        <div class="col-md-5">
                            <label for="score_b" class="form-label">Skor Tim B:</label>
                            <input type="number" class="form-control" id="score_b" name="score_b" min="0"
                                value="<?php echo $edit_match ? $edit_match['score_b'] : '0'; ?>" required>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <?php if ($edit_match): ?>
                            <a href="matches.php" class="btn btn-secondary me-md-2">Batal</a>
                        <?php endif; ?>
                        <button type="submit" class="btn liverpool-btn">
                            <?php echo $edit_match ? 'Update Pertandingan' : 'Tambah Pertandingan'; ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Daftar Pertandingan -->
        <div class="card">
            <div class="card-header liverpool-red">
                <h5 class="card-title m-0">Daftar Pertandingan</h5>
            </div>
            <div class="card-body">
                <?php if (count($matches) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Tim A</th>
                                    <th>Tim B</th>
                                    <th>Skor</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($matches as $match): ?>
                                    <tr>
                                        <td><?php echo date('d-m-Y', strtotime($match['date'])); ?></td>
                                        <td><?php echo htmlspecialchars($match['team_a']); ?></td>
                                        <td><?php echo htmlspecialchars($match['team_b']); ?></td>
                                        <td><?php echo $match['score_a'] . ' - ' . $match['score_b']; ?></td>
                                        <td>
                                            <a href="matches.php?edit=<?php echo $match['id_match']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                            <a href="matches.php?delete=<?php echo $match['id_match']; ?>"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus pertandingan ini?')">Hapus</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">Tidak ada pertandingan yang ditemukan.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>