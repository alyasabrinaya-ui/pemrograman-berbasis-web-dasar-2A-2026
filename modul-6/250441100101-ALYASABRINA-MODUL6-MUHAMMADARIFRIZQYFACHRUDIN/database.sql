CREATE DATABASE IF NOT EXISTS db_modul6_perpustakaan CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_modul6_perpustakaan;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','user') NOT NULL DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS buku (
  id INT AUTO_INCREMENT PRIMARY KEY,
  judul VARCHAR(150) NOT NULL,
  penulis VARCHAR(100) NOT NULL,
  kategori VARCHAR(50) NOT NULL,
  tahun_terbit YEAR NOT NULL,
  stok INT NOT NULL DEFAULT 0,
  harga DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  deskripsi TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (username, password, role) VALUES
('admin', '$2y$12$kN7Xkm2grSAZ0/RtgwTHA.mIE060MImn4FG5OdXrgDoBVeQvHLdmK', 'admin')
ON DUPLICATE KEY UPDATE username = username;

INSERT INTO buku (judul, penulis, kategori, tahun_terbit, stok, harga, deskripsi) VALUES
('Dasar Pemrograman Web', 'Hanifudin Sukri', 'Teknologi', 2024, 12, 85000.00, 'Buku pengantar HTML, PHP, dan database.'),
('Belajar MySQL Praktis', 'Imamah', 'Database', 2023, 8, 78000.00, 'Panduan dasar query dan pengelolaan data.'),
('Logika Pemrograman', 'Alya Sabrina', 'Pemrograman', 2022, 15, 65000.00, 'Latihan berpikir algoritmik untuk pemula.');
