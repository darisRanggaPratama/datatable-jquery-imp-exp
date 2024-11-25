<?php
require_once 'config.php';

if ($_FILES['fileCSV']['error'] == 0 && pathinfo($_FILES['fileCSV']['name'], PATHINFO_EXTENSION) == 'csv') {
    $file = fopen($_FILES['fileCSV']['tmp_name'], 'r');
    
    // Skip baris header
    fgetcsv($file, 0, ';');
    
    try {
        $conn->beginTransaction();
        
        $sql = "INSERT INTO tmhs (nim, nama, alamat, prodi) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        while (($data = fgetcsv($file, 0, ';')) !== FALSE) {
            $stmt->execute([
                $data[0], // nim
                $data[1], // nama
                $data[2], // alamat
                $data[3]  // prodi
            ]);
        }
        
        $conn->commit();
        fclose($file);
        
        header('Location: index.html?status=success&message=Upload berhasil');
    } catch (Exception $e) {
        $conn->rollBack();
        header('Location: index.html?status=error&message=' . urlencode($e->getMessage()));
    }
} else {
    header('Location: index.html?status=error&message=File tidak valid');
}
?>