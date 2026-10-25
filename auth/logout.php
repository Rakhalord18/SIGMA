
<?php
session_start(); // Wajib untuk inisialisasi session

session_destroy(); // Hancurkan semua session

header("Location: ../index.php"); // Redirect ke halaman login
exit(); // Selalu exit setelah header
?>
