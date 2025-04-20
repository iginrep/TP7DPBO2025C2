<?php
// Menampilkan halaman utama atau menu utama aplikasi
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liverpool FC - Aplikasi Statistik</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header class="bg-danger text-white p-4">
        <div class="container">
            <h1>Selamat datang di Aplikasi Liverpool FC</h1>
            <nav class="mt-3">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link bg-light text-danger" href="view/players.php">Daftar Pemain</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="nav-link bg-light text-danger" href="view/matches.php">Daftar Pertandingan</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container my-5">
        <section class="card p-4">
            <h2 class="card-title">Pengenalan Aplikasi</h2>
            <p class="card-text">Aplikasi ini digunakan untuk mengelola data pemain, pertandingan, dan statistik pemain dalam pertandingan Liverpool FC.</p>
        </section>
    </main>

    <footer class="bg-light p-3 text-center">
        <p>&copy; 2025 Liverpool FC - Semua Hak Dilindungi</p>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>