<?php
include_once '../config/db.php';
include_once '../class/Player.php';

$player = new Player($pdo);
$message = '';

// Menambah pemain
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['id_player'])) {
    $name = $_POST['name'];
    $position = $_POST['position'];
    $dob = $_POST['dob'];
    $nationality = $_POST['nationality'];

    if ($player->addPlayer($name, $position, $dob, $nationality)) {
        $message = '<div class="alert alert-success">Pemain berhasil ditambahkan!</div>';
    } else {
        $message = '<div class="alert alert-danger">Gagal menambahkan pemain!</div>';
    }
}

// Mengedit pemain
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_player'])) {
    $id_player = $_POST['id_player'];
    $name = $_POST['name'];
    $position = $_POST['position'];
    $dob = $_POST['dob'];
    $nationality = $_POST['nationality'];

    if ($player->updatePlayer($id_player, $name, $position, $dob, $nationality)) {
        $message = '<div class="alert alert-success">Data pemain berhasil diupdate!</div>';
    } else {
        $message = '<div class="alert alert-danger">Gagal mengupdate data pemain!</div>';
    }
}

// Mengambil data untuk edit
$edit_player = null;
if (isset($_GET['edit'])) {
    $id_player = $_GET['edit'];
    $edit_player = $player->getPlayerById($id_player);
}

// Menghapus pemain
if (isset($_GET['delete'])) {
    $id_player = $_GET['delete'];

    if ($player->deletePlayer($id_player)) {
        $message = '<div class="alert alert-success">Pemain berhasil dihapus!</div>';
    } else {
        $message = '<div class="alert alert-danger">Gagal menghapus pemain!</div>';
    }
}

// Menangani pencarian pemain
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $_GET['search'];
    $players = $player->searchPlayer($search);
} else {
    // Ambil semua pemain
    $players = $player->getPlayers();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pemain - Liverpool FC</title>
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
                <li class="breadcrumb-item active">Daftar Pemain</li>
            </ol>
        </nav>

        <h1 class="mb-4">Daftar Pemain Liverpool FC</h1>

        <?php echo $message; ?>

        <!-- Form Pencarian -->
        <div class="card mb-4">
            <div class="card-header liverpool-red">
                <h5 class="card-title m-0">Cari Pemain</h5>
            </div>
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-8">
                        <label for="search" class="form-label">Cari Berdasarkan Nama:</label>
                        <input type="text" class="form-control" name="search" id="search"
                            placeholder="Cari pemain..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn liverpool-btn">Cari</button>
                        <?php if (isset($_GET['search'])): ?>
                            <a href="players.php" class="btn btn-secondary ms-2">Reset</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- Form Tambah/Edit Pemain -->
        <div class="card mb-4">
            <div class="card-header liverpool-red">
                <h5 class="card-title m-0"><?php echo $edit_player ? 'Edit Pemain' : 'Tambah Pemain'; ?></h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <?php if ($edit_player): ?>
                        <input type="hidden" name="id_player" value="<?php echo $edit_player['id_player']; ?>">
                    <?php endif; ?>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nama Pemain:</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="<?php echo $edit_player ? htmlspecialchars($edit_player['name']) : ''; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="position" class="form-label">Posisi:</label>
                            <input type="text" class="form-control" id="position" name="position"
                                value="<?php echo $edit_player ? htmlspecialchars($edit_player['position']) : ''; ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="dob" class="form-label">Tanggal Lahir:</label>
                            <input type="date" class="form-control" id="dob" name="dob"
                                value="<?php echo $edit_player ? $edit_player['dob'] : ''; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="nationality" class="form-label">Kewarganegaraan:</label>
                            <input type="text" class="form-control" id="nationality" name="nationality"
                                value="<?php echo $edit_player ? htmlspecialchars($edit_player['nationality']) : ''; ?>" required>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <?php if ($edit_player): ?>
                            <a href="players.php" class="btn btn-secondary me-md-2">Batal</a>
                        <?php endif; ?>
                        <button type="submit" class="btn liverpool-btn">
                            <?php echo $edit_player ? 'Update Pemain' : 'Tambah Pemain'; ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Daftar Pemain -->
        <div class="card">
            <div class="card-header liverpool-red">
                <h5 class="card-title m-0">Daftar Pemain</h5>
            </div>
            <div class="card-body">
                <?php if (count($players) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Posisi</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Kewarganegaraan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($players as $player_item): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($player_item['name']); ?></td>
                                        <td><?php echo htmlspecialchars($player_item['position']); ?></td>
                                        <td><?php echo date('d-m-Y', strtotime($player_item['dob'])); ?></td>
                                        <td><?php echo htmlspecialchars($player_item['nationality']); ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="statistics.php?id_player=<?php echo $player_item['id_player']; ?>"
                                                    class="btn btn-sm btn-primary">Statistik</a>
                                                <a href="players.php?edit=<?php echo $player_item['id_player']; ?>"
                                                    class="btn btn-sm btn-warning">Edit</a>
                                                <a href="players.php?delete=<?php echo $player_item['id_player']; ?>"
                                                    class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus pemain ini?')">Hapus</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">Tidak ada pemain yang ditemukan.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>