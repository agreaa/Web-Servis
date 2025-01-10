<?php
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tingkat = $_POST['tingkat'];
    $nama_kelas = $_POST['nama_kelas'];
    $jurusan = $_POST['jurusan'];
    $id_guru = $_POST['id_guru'];
    $kelas = "Kelas " . $tingkat . ' ' . $jurusan . ' ' . $nama_kelas;

    $query = "INSERT INTO kelas (nama, id_guru) VALUES ('$kelas', $id_guru)";
    
    if (mysqli_query($conn, $query)) {
        header("Location: index.php");
        exit();
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

// Ambil daftar guru untuk dipilih sebagai wali kelas
$query_guru = "SELECT * FROM guru";
$guru_result = mysqli_query($conn, $query_guru);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Tambah Kelas Baru</h2>
        <?php if (isset($error)) { ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php } ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Tingkat Kelas</label>
                <select class="form-control" name="tingkat" required>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Jurusan</label>
                <select class="form-control" name="jurusan" required>
                    <option value="IPA">IPA</option>
                    <option value="IPS">IPS</option>
                    <option value="BAHASA">BAHASA</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Nama Kelas</label>
                <select class="form-control" name="nama_kelas" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Wali Kelas</label>
                <select class="form-control" name="id_guru" required>
                    <option value="">Pilih Wali Kelas</option>
                    <?php while ($guru = mysqli_fetch_assoc($guru_result)) { ?>
                        <option value="<?php echo $guru['id']; ?>"><?php echo $guru['nama']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="index.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</body>
</html> 