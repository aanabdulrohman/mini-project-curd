<?php
$conn = new mysqli("db", "root", "password123", "crud_db");

if ($conn->connect_error) { die("Koneksi gagal"); }

$conn->query("CREATE TABLE IF NOT EXISTS barang (id INT AUTO_INCREMENT PRIMARY KEY, nama VARCHAR(100))");

// 1. CREATE & UPDATE logic
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

// 2. DELETE logic
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM barang WHERE id=$id");
    header("Location: index.php");
    exit();
}

// 3. EDIT logic (Ambil data untuk diletakkan di form)
$update_nama = "";
$update_id = "";
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $res = $conn->query("SELECT * FROM barang WHERE id=$id");
    $row = $res->fetch_assoc();
    $update_nama = $row['nama'];
    $update_id = $row['id'];
}

// 4. READ logic
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
        <h2>Inventory System</h2>
        
        <form method="POST" class="form-group">
            <input type="hidden" name="id" value="<?= $update_id ?>">
            <input type="text" name="nama" value="<?= $update_nama ?>" placeholder="Nama Barang..." required>
            <button type="submit" name="save"><?= $update_id ? 'Update' : 'Tambah' ?></button>
            <?php if($update_id): ?>
                <a href="index.php" style="padding:10px; text-decoration:none; color:grey;">Batal</a>
            <?php endif; ?>
        </form>

        <table>
            <tr>
                <th>ID</th>
                <th>Nama Barang</th>
                <th>Aksi</th>
            </tr>
            <?php while($row = $data->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['nama'] ?></td>
                <td>
                    <a href="?edit=<?= $row['id'] ?>" class="btn-edit">Edit</a>
                    <a href="?delete=<?= $row['id'] ?>" class="btn-delete" onclick="return confirm('Hapus data?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>