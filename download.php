<?php
require_once 'config.php';

// Set header untuk download file CSV
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="data_mahasiswa.csv"');

// Buka output stream
$output = fopen('php://output', 'w');

// Tulis header CSV
fputcsv($output, ['NIM', 'Nama', 'Alamat', 'Prodi'], ';');

// Ambil data dari database
$sql = "SELECT nim, nama, alamat, prodi FROM tmhs ORDER BY nim";
$stmt = $conn->prepare($sql);
$stmt->execute();

// Tulis data ke CSV
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, $row, ';');
}

fclose($output);
?>