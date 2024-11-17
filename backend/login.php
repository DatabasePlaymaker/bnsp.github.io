<?php
// Konfigurasi koneksi database
$host = 'localhost';
$user = 'root';
$password = ''; // Sesuaikan dengan konfigurasi Laragon
$dbname = 'autentikasi';

// Koneksi ke database
$conn = new mysqli($host, $user, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Memproses data form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek login untuk admin
    if ($username === 'admin' && $password === 'admin123') {
        echo "<script>
                alert('Login berhasil sebagai Admin!');
                window.location.href = '../frontend/dsb_admin.html'; 
              </script>";
        exit();
    }

    // Cek login untuk user
    $sql = "SELECT password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verifikasi password
        if (password_verify($password, $row['password'])) {
            echo "<script>
                    alert('Login berhasil!');
                    window.location.href = '../frontend/dsb_user.html'; 
                  </script>";
        } else {
            echo "<script>
                    alert('Password salah!');
                    window.history.back();
                  </script>";
        }
    } else {
        echo "<script>
                alert('Username tidak ditemukan!');
                window.history.back();
              </script>";
    }

    $stmt->close();
}

// Tutup koneksi
$conn->close();
?>
