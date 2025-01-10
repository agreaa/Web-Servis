<?php
// Koneksi ke database
$host = 'localhost';  // ganti sesuai host Anda
$dbname = 'manajemen_sekolah';  // ganti sesuai nama database Anda
$username = 'root';  // ganti sesuai username Anda
$password = '';  // ganti sesuai password Anda

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['message' => 'Koneksi gagal: ' . $e->getMessage()]);
    exit();
}

// Mendapatkan parameter 'tabel' dari URL
$tabel = isset($_GET['tabel']) ? $_GET['tabel'] : null;
$id = isset($_GET['id']) ? $_GET['id'] : null;

// Fungsi untuk menangani CRUD berdasarkan tabel yang diterima
function handleRequest($tabel, $id = null) {
    global $pdo;
    switch ($tabel) {
        case 'siswa':
            handleSiswaRequest($id);
            break;
        case 'guru':
            handleGuruRequest($id);
            break;
        case 'kelas':
            handleKelasRequest($id);
            break;
        case 'mata_pelajaran':
            handleMataPelajaranRequest($id);
            break;
        case 'kehadiran':
            handleKehadiranRequest($id);
            break;
        default:
            echo json_encode(['message' => 'No endpoint specified']);
            break;
    }
}

// Fungsi CRUD untuk Siswa
function handleSiswaRequest($id = null) {
    global $pdo;
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            if ($id) {
                $stmt = $pdo->prepare("SELECT * FROM siswa WHERE id = :id");
                $stmt->execute(['id' => $id]);
                echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
            } else {
                $stmt = $pdo->prepare("SELECT * FROM siswa");
                $stmt->execute();
                echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            }
            break;
        case 'POST':
            $data = json_decode(file_get_contents("php://input"), true);
            $sql = "INSERT INTO siswa (nama, jenis_kelamin, tanggal_lahir, id_kelas) VALUES (:nama, :jenis_kelamin, :tanggal_lahir, :id_kelas)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'nama' => $data['nama'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'tanggal_lahir' => $data['tanggal_lahir'],
                'id_kelas' => $data['id_kelas']
            ]);
            echo json_encode(['message' => 'Data siswa berhasil ditambahkan']);
            break;
        case 'PUT':
            if ($id) {
                $data = json_decode(file_get_contents("php://input"), true);
                $sql = "UPDATE siswa SET nama = :nama, jenis_kelamin = :jenis_kelamin, tanggal_lahir = :tanggal_lahir, id_kelas = :id_kelas WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'nama' => $data['nama'],
                    'jenis_kelamin' => $data['jenis_kelamin'],
                    'tanggal_lahir' => $data['tanggal_lahir'],
                    'id_kelas' => $data['id_kelas'],
                    'id' => $id
                ]);
                echo json_encode(['message' => 'Data siswa berhasil diperbarui']);
            }
            break;
        case 'DELETE':
            if ($id) {
                $sql = "DELETE FROM siswa WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['id' => $id]);
                echo json_encode(['message' => 'Data siswa berhasil dihapus']);
            }
            break;
        default:
            echo json_encode(['message' => 'Method tidak valid']);
            break;
    }
}

// Fungsi CRUD untuk Guru
function handleGuruRequest($id = null) {
    global $pdo;
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            if ($id) {
                $stmt = $pdo->prepare("SELECT * FROM guru WHERE id = :id");
                $stmt->execute(['id' => $id]);
                echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
            } else {
                $stmt = $pdo->prepare("SELECT * FROM guru");
                $stmt->execute();
                echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            }
            break;
        case 'POST':
            $data = json_decode(file_get_contents("php://input"), true);
            $sql = "INSERT INTO guru (nama, id_mapel, email) VALUES (:nama, :id_mapel, :email)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'nama' => $data['nama'],
                'id_mapel' => $data['id_mapel'],
                'email' => $data['email']
            ]);
            echo json_encode(['message' => 'Data guru berhasil ditambahkan']);
            break;
        case 'PUT':
            if ($id) {
                $data = json_decode(file_get_contents("php://input"), true);
                $sql = "UPDATE guru SET nama = :nama, id_mapel = :id_mapel, email = :email WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'nama' => $data['nama'],
                    'id_mapel' => $data['id_mapel'],
                    'email' => $data['email'],
                    'id' => $id
                ]);
                echo json_encode(['message' => 'Data guru berhasil diperbarui']);
            }
            break;
        case 'DELETE':
            if ($id) {
                $sql = "DELETE FROM guru WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['id' => $id]);
                echo json_encode(['message' => 'Data guru berhasil dihapus']);
            }
            break;
        default:
            echo json_encode(['message' => 'Method tidak valid']);
            break;
    }
}

// Fungsi CRUD untuk Kelas
function handleKelasRequest($id = null) {
    global $pdo;
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            if ($id) {
                $stmt = $pdo->prepare("SELECT * FROM kelas WHERE id = :id");
                $stmt->execute(['id' => $id]);
                echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
            } else {
                $stmt = $pdo->prepare("SELECT * FROM kelas");
                $stmt->execute();
                echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            }
            break;
        case 'POST':
            $data = json_decode(file_get_contents("php://input"), true);
            $sql = "INSERT INTO kelas (nama, id_guru) VALUES (:nama, :id_guru)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'nama' => $data['nama'],
                'id_guru' => $data['id_guru']
            ]);
            echo json_encode(['message' => 'Data kelas berhasil ditambahkan']);
            break;
        case 'PUT':
            if ($id) {
                $data = json_decode(file_get_contents("php://input"), true);
                $sql = "UPDATE kelas SET nama = :nama, id_guru = :id_guru WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'nama' => $data['nama'],
                    'id_guru' => $data['id_guru'],
                    'id' => $id
                ]);
                echo json_encode(['message' => 'Data kelas berhasil diperbarui']);
            }
            break;
        case 'DELETE':
            if ($id) {
                $sql = "DELETE FROM kelas WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['id' => $id]);
                echo json_encode(['message' => 'Data kelas berhasil dihapus']);
            }
            break;
        default:
            echo json_encode(['message' => 'Method tidak valid']);
            break;
    }
}

// Fungsi CRUD untuk Mata Pelajaran
function handleMataPelajaranRequest($id = null) {
    global $pdo;
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            if ($id) {
                $stmt = $pdo->prepare("SELECT * FROM mata_pelajaran WHERE id = :id");
                $stmt->execute(['id' => $id]);
                echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
            } else {
                $stmt = $pdo->prepare("SELECT * FROM mata_pelajaran");
                $stmt->execute();
                echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            }
            break;
        case 'POST':
            $data = json_decode(file_get_contents("php://input"), true);
            $sql = "INSERT INTO mata_pelajaran (nama) VALUES (:nama)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['nama' => $data['nama']]);
            echo json_encode(['message' => 'Data mata pelajaran berhasil ditambahkan']);
            break;
        case 'PUT':
            if ($id) {
                $data = json_decode(file_get_contents("php://input"), true);
                $sql = "UPDATE mata_pelajaran SET nama = :nama WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'nama' => $data['nama'],
                    'id' => $id
                ]);
                echo json_encode(['message' => 'Data mata pelajaran berhasil diperbarui']);
            }
            break;
        case 'DELETE':
            if ($id) {
                $sql = "DELETE FROM mata_pelajaran WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['id' => $id]);
                echo json_encode(['message' => 'Data mata pelajaran berhasil dihapus']);
            }
            break;
        default:
            echo json_encode(['message' => 'Method tidak valid']);
            break;
    }
}

// Fungsi CRUD untuk Kehadiran
function handleKehadiranRequest($id = null) {
    global $pdo;
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            if ($id) {
                $stmt = $pdo->prepare("SELECT * FROM kehadiran WHERE id = :id");
                $stmt->execute(['id' => $id]);
                echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
            } else {
                $stmt = $pdo->prepare("SELECT * FROM kehadiran");
                $stmt->execute();
                echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            }
            break;
        case 'POST':
            $data = json_decode(file_get_contents("php://input"), true);
            $sql = "INSERT INTO kehadiran (id_siswa, tanggal, status) VALUES (:id_siswa, :tanggal, :status)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'id_siswa' => $data['id_siswa'],
                'tanggal' => $data['tanggal'],
                'status' => $data['status']
            ]);
            echo json_encode(['message' => 'Data kehadiran berhasil ditambahkan']);
            break;
        case 'PUT':
            if ($id) {
                $data = json_decode(file_get_contents("php://input"), true);
                $sql = "UPDATE kehadiran SET id_siswa = :id_siswa, tanggal = :tanggal, status = :status WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'id_siswa' => $data['id_siswa'],
                    'tanggal' => $data['tanggal'],
                    'status' => $data['status'],
                    'id' => $id
                ]);
                echo json_encode(['message' => 'Data kehadiran berhasil diperbarui']);
            }
            break;
        case 'DELETE':
            if ($id) {
                $sql = "DELETE FROM kehadiran WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['id' => $id]);
                echo json_encode(['message' => 'Data kehadiran berhasil dihapus']);
            }
            break;
        default:
            echo json_encode(['message' => 'Method tidak valid']);
            break;
    }
}

// Menangani permintaan API berdasarkan tabel
handleRequest($tabel, $id);
?>
