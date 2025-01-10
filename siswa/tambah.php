<?php
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    
    // Cek apakah menggunakan kelas yang sudah ada atau membuat baru
    if ($_POST['pilihan_kelas'] == 'existing') {
        $id_kelas = $_POST['id_kelas'];
    } else {
        // Buat kelas baru
        $tingkat = $_POST['tingkat'];
        $jurusan = $_POST['jurusan'];
        $nama_kelas = $_POST['nama_kelas'];
        $id_guru = $_POST['id_guru'];
        $nama_kelas_baru = "Kelas " . $tingkat . ' ' . $jurusan . ' ' . $nama_kelas;
        
        // Insert kelas baru
        $query_kelas = "INSERT INTO kelas (nama, id_guru) VALUES ('$nama_kelas_baru', $id_guru)";
        if (mysqli_query($conn, $query_kelas)) {
            $id_kelas = mysqli_insert_id($conn);
        } else {
            $error = "Error membuat kelas baru: " . mysqli_error($conn);
        }
    }

    if (!isset($error)) {
        $query = "INSERT INTO siswa (nama, jenis_kelamin, tanggal_lahir, id_kelas) 
                  VALUES ('$nama', '$jenis_kelamin', '$tanggal_lahir', $id_kelas)";
        
        if (mysqli_query($conn, $query)) {
            header("Location: index.php");
            exit();
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}

// Ambil daftar kelas yang sudah ada
$query_kelas = "SELECT * FROM kelas ORDER BY nama";
$kelas_result = mysqli_query($conn, $query_kelas);

// Kelompokkan kelas berdasarkan tingkat dan jurusan
$kelas_grouped = [];
while ($kelas = mysqli_fetch_assoc($kelas_result)) {
    preg_match('/Kelas (\d+) (\w+) (\d+)/', $kelas['nama'], $matches);
    if (isset($matches[1]) && isset($matches[2])) {
        $tingkat = $matches[1];
        $jurusan = $matches[2];
        $kelas_grouped[$tingkat][$jurusan][] = $kelas;
    }
}

// Ambil daftar guru untuk wali kelas
$query_guru = "SELECT * FROM guru";
$guru_result = mysqli_query($conn, $query_guru);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Tambah Siswa Baru</h2>
            <a href="index.php" class="btn btn-secondary">Kembali ke Daftar Siswa</a>
        </div>
        <?php if (isset($error)) { ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php } ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" class="form-control" name="nama" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Jenis Kelamin</label>
                <select class="form-control" name="jenis_kelamin" required>
                    <option value="laki-laki">Laki-laki</option>
                    <option value="perempuan">Perempuan</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Tanggal Lahir</label>
                <input type="date" class="form-control" name="tanggal_lahir" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Pilihan Kelas</label>
                <select class="form-control" name="pilihan_kelas" id="pilihan_kelas" required>
                    <option value="existing">Pilih Kelas yang Sudah Ada</option>
                    <option value="new">Buat Kelas Baru</option>
                </select>
            </div>

            <!-- Form untuk kelas yang sudah ada -->
            <div id="existing_kelas" class="mb-3">
                <label class="form-label">Kelas yang Sudah Ada</label>
                <select class="form-control" name="id_kelas">
                    <option value="">Pilih Kelas</option>
                    <?php foreach ($kelas_grouped as $tingkat => $jurusan_group): ?>
                        <optgroup label="Kelas <?php echo $tingkat; ?>">
                            <?php foreach ($jurusan_group as $jurusan => $kelas_list): ?>
                                <?php foreach ($kelas_list as $kelas): ?>
                                    <option value="<?php echo $kelas['id']; ?>">
                                        <?php echo $kelas['nama']; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Form untuk kelas baru -->
            <div id="new_kelas" style="display: none;">
                <div class="mb-3">
                    <label class="form-label">Tingkat Kelas</label>
                    <select class="form-control" name="tingkat">
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jurusan</label>
                    <select class="form-control" name="jurusan">
                        <option value="IPA">IPA</option>
                        <option value="IPS">IPS</option>
                        <option value="BAHASA">BAHASA</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Kelas</label>
                    <select class="form-control" name="nama_kelas">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Wali Kelas</label>
                    <select class="form-control" name="id_guru">
                        <option value="">Pilih Wali Kelas</option>
                        <?php 
                        mysqli_data_seek($guru_result, 0);
                        while ($guru = mysqli_fetch_assoc($guru_result)) { 
                        ?>
                            <option value="<?php echo $guru['id']; ?>"><?php echo $guru['nama']; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="index.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('pilihan_kelas').addEventListener('change', function() {
            var existingKelas = document.getElementById('existing_kelas');
            var newKelas = document.getElementById('new_kelas');
            
            if (this.value === 'existing') {
                existingKelas.style.display = 'block';
                newKelas.style.display = 'none';
                existingKelas.querySelector('select').required = true;
                newKelas.querySelectorAll('select').forEach(select => select.required = false);
            } else {
                existingKelas.style.display = 'none';
                newKelas.style.display = 'block';
                existingKelas.querySelector('select').required = false;
                newKelas.querySelectorAll('select').forEach(select => select.required = true);
            }
        });
    </script>
</body>
</html> 