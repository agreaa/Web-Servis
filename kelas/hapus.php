<?php
include '../config/database.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Cek apakah kelas masih memiliki siswa
    $check_query = "SELECT COUNT(*) as total FROM siswa WHERE id_kelas = $id";
    $check_result = mysqli_query($conn, $check_query);
    $check_data = mysqli_fetch_assoc($check_result);
    
    if ($check_data['total'] > 0) {
        echo "<script>
                alert('Kelas tidak dapat dihapus karena masih memiliki siswa!');
                window.location.href = 'index.php';
              </script>";
        exit();
    }
    
    $query = "DELETE FROM kelas WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        header("Location: index.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("Location: index.php");
}
?> 