<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "tas_handmade"; // Ganti dengan nama database kamu

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>

