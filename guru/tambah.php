<?php
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    
    // Cek apakah menggunakan mata pelajaran yang sudah ada atau membuat baru
    if ($_POST['pilihan_mapel'] == 'existing') {
        $id_mapel = $_POST['id_mapel'];
    } else {
        // Buat mata pelajaran baru
        $nama_mapel = $_POST['nama_mapel'];
        
        // Insert mata pelajaran baru
        $query_mapel = "INSERT INTO mata_pelajaran (nama) VALUES ('$nama_mapel')";
        if (mysqli_query($conn, $query_mapel)) {
            $id_mapel = mysqli_insert_id($conn);
        } else {
            $error = "Error membuat mata pelajaran baru: " . mysqli_error($conn);
        }
    }

    if (!isset($error)) {
        $query = "INSERT INTO guru (nama, id_mapel, email) 
                  VALUES ('$nama', $id_mapel, '$email')";
        
        if (mysqli_query($conn, $query)) {
            header("Location: index.php");
            exit();
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}

// Ambil daftar mata pelajaran yang sudah ada
$query_mapel = "SELECT * FROM mata_pelajaran ORDER BY nama";
$mapel_result = mysqli_query($conn, $query_mapel);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Guru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Tambah Guru Baru</h2>
            <div>
                
                <a href="index.php" class="btn btn-secondary">Kembali ke Daftar Guru</a>
            </div>
        </div>
        <?php if (isset($error)) { ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php } ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nama Guru</label>
                <input type="text" class="form-control" name="nama" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Pilihan Mata Pelajaran</label>
                <select class="form-control" name="pilihan_mapel" id="pilihan_mapel" required>
                    <option value="existing">Pilih Mata Pelajaran yang Sudah Ada</option>
                    <option value="new">Tambah Mata Pelajaran Baru</option>
                </select>
            </div>

            <!-- Form untuk mata pelajaran yang sudah ada -->
            <div id="existing_mapel" class="mb-3">
                <label class="form-label">Mata Pelajaran yang Sudah Ada</label>
                <select class="form-control" name="id_mapel">
                    <option value="">Pilih Mata Pelajaran</option>
                    <?php 
                    mysqli_data_seek($mapel_result, 0);
                    while ($mapel = mysqli_fetch_assoc($mapel_result)) { 
                    ?>
                        <option value="<?php echo $mapel['id']; ?>">
                            <?php echo $mapel['nama']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <!-- Form untuk mata pelajaran baru -->
            <div id="new_mapel" style="display: none;">
                <div class="mb-3">
                    <label class="form-label">Nama Mata Pelajaran Baru</label>
                    <input type="text" class="form-control" name="nama_mapel" placeholder="Contoh: Matematika, Fisika, dll">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="index.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('pilihan_mapel').addEventListener('change', function() {
            var existingMapel = document.getElementById('existing_mapel');
            var newMapel = document.getElementById('new_mapel');
            
            if (this.value === 'existing') {
                existingMapel.style.display = 'block';
                newMapel.style.display = 'none';
                existingMapel.querySelector('select').required = true;
                newMapel.querySelector('input').required = false;
            } else {
                existingMapel.style.display = 'none';
                newMapel.style.display = 'block';
                existingMapel.querySelector('select').required = false;
                newMapel.querySelector('input').required = true;
            }
        });
    </script>
</body>
</html> 