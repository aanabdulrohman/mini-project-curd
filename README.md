# 📦 DevOps Inventory System (Mini-Project CRUD)

Proyek ini adalah simulasi sistem manajemen inventaris barang sederhana yang dibangun dengan fokus pada implementasi **DevOps Best Practices**. Arsitektur ini dirancang untuk menunjukkan kemampuan dalam orkestrasi kontainer, otomatisasi CI/CD, dan ketahanan sistem (*system resilience*).

## 🚀 Fitur Utama Infrastruktur
* **Containerization**: Menggunakan Docker & Docker Compose untuk lingkungan yang terisolasi.
* **Health Orchestration**: PHP Service hanya akan berjalan setelah MySQL berstatus `healthy`.
* **Security Hardening**: 
    * Nginx dikonfigurasi untuk memblokir akses ke file sensitif (`.env`, `.git`).
    * Aplikasi PHP menggunakan *Prepared Statements* untuk mencegah SQL Injection.
* **Dynamic Configuration**: Manajemen kredensial menggunakan Environment Variables (`.env`).
* **CI Pipeline**: Otomatisasi deployment menggunakan GitHub Actions.
* **Resilience Logic**: Implementasi *connection retry logic* pada sisi aplikasi untuk menangani *startup delay* pada database.

---

## 🏗️ Arsitektur Sistem
Sistem ini terdiri dari tiga layanan utama yang saling terhubung dalam satu jaringan virtual Docker:
1.  **Nginx (Web Server)**: Sebagai pintu masuk utama (port 8080) dan pengelola aset statis.
2.  **PHP-FPM 8.2 (App Server)**: Memproses logika bisnis CRUD.
3.  **MySQL 8.0 (Database)**: Penyimpanan data persisten menggunakan Docker Volumes.



---

## 🛠️ Cara Instalasi (Lokal)

### 1. Persiapan
Pastikan Anda sudah menginstal **Docker** dan **Docker Compose** di mesin Anda.

### 2. Konfigurasi Environment
Buat file baru dan beri nama `.env`:
```env
DB_PASSWORD=your_secure_password
DB_NAME=crud_db