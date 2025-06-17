<?php include 'config.php'; session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $nama = $_POST['nama'];

    $result = mysqli_query($conn, "SELECT * FROM pengguna WHERE email='$email' AND nama_pengguna='$nama'");
    $user = mysqli_fetch_assoc($result);
    if ($user) {
        $_SESSION['id_pengguna'] = $user['id_pengguna'];
        header("Location: home.php");
    } else {
        echo "Login gagal!";
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Login</title></head>
<body>
<h2>Login</h2>
<form method="post">
    Nama: <input type="text" name="nama" required><br>
    Email: <input type="email" name="email" required><br>
    <button type="submit">Login</button>
</form>
</body>
</html>
