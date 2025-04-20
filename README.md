# TP7DPBO2025C2

# JANJI
Saya Muhammad Igin Adigholib dengan NIM 2301125 mengerjakan Tugas Praktikum 7 dalam mata kuliah Desain dan Pemrograman Berorientasi Objek untuk keberkahanNya maka saya tidak melakukan kecurangan seperti yang telah dispesifikasikan. Aamiin.

# Desain Program
![Desain Diagram TP7](https://github.com/user-attachments/assets/7ef9e703-4ac9-4685-81b5-0d1fcc162d65)

# Alur Program
Aplikasi Liverpool FC berfungsi untuk mengelola data pemain, pertandingan, dan statistik pemain dalam pertandingan. Pada dasarnya, aplikasi ini terdiri dari tiga komponen utama yang saling berinteraksi: Player, FootballMatch, dan PlayerMatch.

Kelas Player mengelola data pemain, yang mencakup informasi pribadi seperti nama, posisi, tanggal lahir, dan kewarganegaraan. Pemain dapat ditambahkan, diperbarui, atau dihapus melalui aplikasi ini. Fitur pencarian memungkinkan pengguna mencari pemain berdasarkan nama. Data pemain ini terhubung dengan statistik pemain melalui PlayerMatch, di mana setiap pemain dapat memiliki statistik yang mencatat gol dan assist yang dicetak dalam pertandingan tertentu.

Kelas FootballMatch bertanggung jawab untuk menyimpan informasi mengenai pertandingan yang telah dilaksanakan. Setiap pertandingan memiliki tanggal, tim yang bertanding (Tim A dan Tim B), serta skor yang tercatat. FootballMatch juga menyediakan fitur untuk menambahkan pertandingan baru, mengedit pertandingan yang ada, serta mencari pertandingan berdasarkan tim yang terlibat. Setiap pertandingan ini berhubungan dengan statistik pemain melalui PlayerMatch, yang mencatat performa individu dalam pertandingan tersebut.

Kelas PlayerMatch mengelola statistik pemain dalam pertandingan. Statistik ini mencakup gol dan assist yang dicetak oleh pemain dalam pertandingan tertentu. PlayerMatch memiliki fungsi untuk menambah, mengedit, dan menghapus statistik pemain, serta memungkinkan statistik pemain untuk ditampilkan berdasarkan ID pemain. Statistik ini sangat penting untuk menganalisis performa pemain selama musim pertandingan.

Alur kerja aplikasi dimulai dengan pengguna yang dapat memilih untuk melihat Daftar Pemain atau Daftar Pertandingan. Dalam Daftar Pemain, pengguna dapat melihat daftar semua pemain, menambah pemain baru, mengedit informasi pemain, atau menghapus pemain. Setiap pemain juga dapat dilihat statistik performanya dalam Daftar Statistik Pemain, yang menampilkan performa pemain dalam setiap pertandingan. Pengguna dapat menambah, mengedit, atau menghapus statistik pemain melalui halaman ini. Selain itu, pengguna juga dapat melihat Daftar Pertandingan, yang menampilkan semua pertandingan yang telah dimainkan, lengkap dengan hasil skor, tim yang bertanding, serta informasi lainnya. Pengguna dapat mencari pertandingan berdasarkan nama tim, menambah pertandingan baru, atau mengedit informasi pertandingan yang ada.

Melalui aplikasi ini, pengelolaan data pemain, pertandingan, dan statistik menjadi lebih terstruktur dan mudah diakses. Interaksi antara Player, FootballMatch, dan PlayerMatch memastikan bahwa setiap data pemain, pertandingan, dan statistik dapat saling berhubungan dengan baik. Dengan demikian, aplikasi ini memberikan kemudahan bagi pengguna dalam melakukan manajemen data terkait klub sepak bola Liverpool FC.

# Dokumentasi
![20250420-1534-11 5922657](https://github.com/user-attachments/assets/db32c3d9-97df-4e70-be42-215a1eda0c33)
