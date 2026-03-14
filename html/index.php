<?php
$host = "db";
$user = "root";
$pass = getenv('DB_PASS') ?: 'passworddefault';
$db   = getenv('DB_NAME') ?: 'crud_db';

// 1. Koneksi dengan Retry Logic
mysqli_report(MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ERROR);
$conn = null;
for ($i = 0; $i < 10; $i++) {
    try {
        $conn = new mysqli($host, $user, $pass, $db);
        break;
    } catch (Exception $e) {
        sleep(3);
    }
}
if (!$conn) die("Gagal koneksi ke database.");

// 2. Auto-create Table
$conn->query("CREATE TABLE IF NOT EXISTS barang (id INT AUTO_INCREMENT PRIMARY KEY, nama VARCHAR(100))");

// 3. Logic CRUD (Secure)
if (isset($_POST['save'])) {
    $nama = $_POST['nama'];
    $id = $_POST['id'];
    if ($id == "") {
        $stmt = $conn->prepare("INSERT INTO barang (nama) VALUES (?)");
        $stmt->bind_param("s", $nama);
    } else {
        $stmt = $conn->prepare("UPDATE barang SET nama = ? WHERE id = ?");
        $stmt->bind_param("si", $nama, $id);
    }
    $stmt->execute();
    header("Location: index.php");
    exit();
}

if (isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM barang WHERE id = ?");
    $stmt->bind_param("i", $_GET['delete']);
    $stmt->execute();
    header("Location: index.php");
    exit();
}

$update_nama = ""; $update_id = "";
if (isset($_GET['edit'])) {
    $stmt = $conn->prepare("SELECT * FROM barang WHERE id = ?");
    $stmt->bind_param("i", $_GET['edit']);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $update_nama = $res['nama']; $update_id = $res['id'];
}

$data = $conn->query("SELECT * FROM barang ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>DevOps CRUD</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>📦 Inventory System</h2>
        <form method="POST" class="form-group">
            <input type="hidden" name="id" value="<?= $update_id ?>">
            <input type="text" name="nama" value="<?= $update_nama ?>" placeholder="Nama Barang..." required>
            <button type="submit" name="save"><?= $update_id ? 'Update' : 'Tambah' ?></button>
        </form>
        <table>
            <tr><th>ID</th><th>Nama</th><th>Aksi</th></tr>
            <?php while($row = $data->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['nama'] ?></td>
                <td>
                    <a href="?edit=<?= $row['id'] ?>">Edit</a> | 
                    <a href="?delete=<?= $row['id'] ?>" style="color:red">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>