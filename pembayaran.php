<?php
include 'config.php';
session_start();

if (!isset($_SESSION['id_pengguna'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jumlah = $_POST['jumlah'];
    $metode = $_POST['metode'];
    $tanggal = date('Y-m-d');
    $id_pengguna = $_SESSION['id_pengguna'];

    // Ambil harga tiket dan id_film. Assuming we need an id_film to proceed.
    // If there are multiple films, you'd typically select a specific film ID
    // either from a form input or another mechanism.
    // For this fix, let's assume we are just taking the first film's details.
    $query_film = mysqli_query($conn, "SELECT id_film, harga_tiket FROM film LIMIT 1"); // Added LIMIT 1 for consistency
    if (!$query_film) {
        die("Error fetching film data: " . mysqli_error($conn));
    }
    $film = mysqli_fetch_assoc($query_film);

    if (!$film) {
        die("No film found in the database. Please add film data first.");
    }

    $id_film = $film['id_film']; // Define $id_film here
    $harga = $film['harga_tiket'];
    $total = $harga * $jumlah;

    // Simpan transaksi terlebih dahulu
    // (Assuming database foreign key is corrected where pembayaran.id_transaksi REFERENCES transaksi.id_transaksi)
    $insert_transaksi_query = "INSERT INTO transaksi (id_film, id_pengguna, tanggal_transaksi, jumlah_tiket)
                               VALUES ('$id_film', '$id_pengguna', '$tanggal', '$jumlah')";
    
    if (mysqli_query($conn, $insert_transaksi_query)) {
        $id_transaksi = mysqli_insert_id($conn);

        // Simpan pembayaran menggunakan id_transaksi yang baru didapat
        $insert_pembayaran_query = "INSERT INTO pembayaran (id_transaksi, metode_pembayaran, jumlah_bayar)
                                   VALUES ('$id_transaksi', '$metode', '$total')";
        
        if (mysqli_query($conn, $insert_pembayaran_query)) {
            echo "<p>Pembayaran berhasil! Total: Rp " . number_format($total, 0, ',', '.') . "</p>";
            echo "<a href='home.php'>Kembali ke Beranda</a>";
        } else {
            // If payment insertion fails, you might want to roll back the transaction insertion, or log it.
            echo "Error saving payment: " . mysqli_error($conn);
        }
    } else {
        echo "Error saving transaction: " . mysqli_error($conn);
    }
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran Tiket</title>
</head>
<body>
    <h2>Form Pembayaran</h2>
    <form method="post">
        Jumlah Tiket: <input type="number" name="jumlah" min="1" required><br><br>
        Metode Pembayaran:
        <select name="metode" required>
            <option value="Transfer Bank">Transfer Bank</option>
            <option value="E-Wallet">E-Wallet</option>
            <option value="Tunai">Tunai</option>
        </select><br><br>
        <button type="submit">Bayar</button>
    </form>
</body>
</html>