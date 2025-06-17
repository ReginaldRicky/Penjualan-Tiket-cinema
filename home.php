<?php include 'config.php'; session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Home - Cinema</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Daftar Film</h1>
<?php
$result = mysqli_query($conn, "SELECT * FROM film");
while ($row = mysqli_fetch_assoc($result)) {
    echo "<div class='film'>
            <h2>{$row['judul']}</h2>
            <p>Genre: {$row['genre']}</p>
            <p>Harga Tiket: Rp{$row['harga_tiket']}</p>";
    if (isset($_SESSION['id_pengguna'])) {
        echo "<a href='pembayaran.php'>Pesan Tiket</a>";
    } else {
        echo "<a href='login.php'>Login untuk membeli</a>";
    }
    echo "</div>";
}
?>
<a href="about.php">Tentang Kami</a> 
<?php if (isset($_SESSION['id_pengguna'])): ?>
<a href="logout.php">Logout</a>
<?php else: ?>
<a href="login.php">Login</a>
<?php endif; ?>
</body>
</html>
