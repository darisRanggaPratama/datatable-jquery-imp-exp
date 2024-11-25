<?php
require_once 'config.php';

// Parameters dari DataTables
$draw = $_POST['draw'];
$start = $_POST['start'];
$length = $_POST['length'];
$search = $_POST['search']['value'];

// Query untuk total data
$sqlTotal = "SELECT COUNT(*) as total FROM tmhs";
$stmtTotal = $conn->prepare($sqlTotal);
$stmtTotal->execute();
$totalRecords = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];

// Query untuk data yang difilter
$sqlFiltered = "SELECT COUNT(*) as total FROM tmhs WHERE 
                nim LIKE :search OR 
                nama LIKE :search OR 
                alamat LIKE :search OR 
                prodi LIKE :search";
$stmtFiltered = $conn->prepare($sqlFiltered);
$searchParam = "%$search%";
$stmtFiltered->bindParam(':search', $searchParam);
$stmtFiltered->execute();
$totalFiltered = $stmtFiltered->fetch(PDO::FETCH_ASSOC)['total'];

// Query untuk data yang ditampilkan
$sql = "SELECT nim, nama, alamat, prodi FROM tmhs 
        WHERE nim LIKE :search OR 
              nama LIKE :search OR 
              alamat LIKE :search OR 
              prodi LIKE :search 
        LIMIT :start, :length";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':search', $searchParam);
$stmt->bindParam(':start', $start, PDO::PARAM_INT);
$stmt->bindParam(':length', $length, PDO::PARAM_INT);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Format response sesuai yang dibutuhkan DataTables
$response = [
    "draw" => intval($draw),
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($totalFiltered),
    "data" => $data
];

header('Content-Type: application/json');
echo json_encode($response);
?>