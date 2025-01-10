<?php
include '../config/database.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tingkat = $_POST['tingkat'];
    $nama_kelas = $_POST['nama_kelas'];
    $jurusan = $_POST['jurusan'];
    $id_guru = $_POST['id_guru'];
    $kelas = "Kelas " . $tingkat . ' ' . $jurusan . ' ' . $nama_kelas;

    $query = "UPDATE kelas SET nama = '$kelas', id_guru = $id_guru WHERE id = $id";
    
    if (mysqli_query($conn, $query)) {
        header("Location: index.php");
        exit();
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

// Ambil data kelas yang akan diedit
$query = "SELECT * FROM kelas WHERE id = $id";
$result = mysqli_query($conn, $query);
$kelas = mysqli_fetch_assoc($result);

// Ambil daftar guru
$query_guru = "SELECT * FROM guru";
$guru_result = mysqli_query($conn, $query_guru);

// Parse nama kelas untuk mendapatkan tingkat, jurusan dan nama
preg_match('/Kelas (\d+) (\w+) (\d+)/', $kelas['nama'], $matches);
$tingkat_kelas = $matches[1] ?? '';
$jurusan_kelas = $matches[2] ?? '';
$nama_kelas = $matches[3] ?? '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Edit Kelas</h2>
        <?php if (isset($error)) { ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php } ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Tingkat Kelas</label>
                <select class="form-control" name="tingkat" required>
                    <option value="10" <?php echo $tingkat_kelas == '10' ? 'selected' : ''; ?>>10</option>
                    <option value="11" <?php echo $tingkat_kelas == '11' ? 'selected' : ''; ?>>11</option>
                    <option value="12" <?php echo $tingkat_kelas == '12' ? 'selected' : ''; ?>>12</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Jurusan</label>
                <select class="form-control" name="jurusan" required>
                    <option value="IPA" <?php echo $jurusan_kelas == 'IPA' ? 'selected' : ''; ?>>IPA</option>
                    <option value="IPS" <?php echo $jurusan_kelas == 'IPS' ? 'selected' : ''; ?>>IPS</option>
                    <option value="BAHASA" <?php echo $jurusan_kelas == 'BAHASA' ? 'selected' : ''; ?>>BAHASA</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">No Kelas</label>
                <select class="form-control" name="nama_kelas" required>
                    <option value="1" <?php echo $nama_kelas == '1' ? 'selected' : ''; ?>>1</option>
                    <option value="2" <?php echo $nama_kelas == '2' ? 'selected' : ''; ?>>2</option>
                    <option value="3" <?php echo $nama_kelas == '3' ? 'selected' : ''; ?>>3</option>
                    <option value="4" <?php echo $nama_kelas == '4' ? 'selected' : ''; ?>>4</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Wali Kelas</label>
                <select class="form-control" name="id_guru" required>
                    <option value="">Pilih Wali Kelas</option>
                    <?php while ($guru = mysqli_fetch_assoc($guru_result)) { ?>
                        <option value="<?php echo $guru['id']; ?>" <?php echo $guru['id'] == $kelas['id_guru'] ? 'selected' : ''; ?>>
                            <?php echo $guru['nama']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="index.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</body>
</html> 