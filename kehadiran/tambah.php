<?php
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_siswa = $_POST['id_siswa'];
    $tanggal = $_POST['tanggal'];
    $status = $_POST['status'];

    $query = "INSERT INTO kehadiran (id_siswa, tanggal, status) 
              VALUES ($id_siswa, '$tanggal', '$status')";
    
    if (mysqli_query($conn, $query)) {
        header("Location: index.php");
        exit();
    }
}

$query_siswa = "SELECT * FROM siswa";
$siswa_result = mysqli_query($conn, $query_siswa);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kehadiran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Tambah Data Kehadiran</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Siswa</label>
                <select class="form-control" name="id_siswa" required>
                    <?php while ($siswa = mysqli_fetch_assoc($siswa_result)) { ?>
                        <option value="<?php echo $siswa['id']; ?>"><?php echo $siswa['nama']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Tanggal</label>
                <input type="date" class="form-control" name="tanggal" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select class="form-control" name="status" required>
                    <option value="hadir">Hadir</option>
                    <option value="tidak hadir">Tidak Hadir</option>
                    <option value="terlambat">Terlambat</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="index.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</body>
</html> 