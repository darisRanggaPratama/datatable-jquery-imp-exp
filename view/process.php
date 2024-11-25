<?php
require_once 'koneksi.php';

// Read Data
if(isset($_GET['action']) && $_GET['action'] == 'read') {
    try {
        $query = $conn->query("SELECT * FROM tmhs ORDER BY id_mhs DESC");
        $result = array();
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $result[] = array(
                "id_mhs"    => $row['id_mhs'],
                "nim"       => $row['nim'],
                "nama"      => $row['nama'],
                "alamat"    => $row['alamat'],
                "prodi"     => $row['prodi']
            );
        }
        
        // Format yang sesuai dengan DataTables
        $response = array(
            "draw" => isset($_GET['draw']) ? intval($_GET['draw']) : 0,
            "recordsTotal" => count($result),
            "recordsFiltered" => count($result),
            "data" => $result
        );
        
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    } catch(PDOException $e) {
        $response = array(
            "draw" => isset($_GET['draw']) ? intval($_GET['draw']) : 0,
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data" => array(),
            "error" => $e->getMessage()
        );
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}

// Create Data
if(isset($_POST['action']) && $_POST['action'] == 'create') {
    try {
        $nim = $_POST['nim'];
        $nama = $_POST['nama'];
        $alamat = $_POST['alamat'];
        $prodi = $_POST['prodi'];
        
        $query = $conn->prepare("INSERT INTO tmhs (nim, nama, alamat, prodi) VALUES (?, ?, ?, ?)");
        $result = $query->execute([$nim, $nama, $alamat, $prodi]);
        
        header('Content-Type: application/json');
        if($result) {
            echo json_encode(['status' => 'success', 'message' => 'Data berhasil ditambahkan']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menambahkan data']);
        }
        exit;
    } catch(PDOException $e) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}

// Update Data
if(isset($_POST['action']) && $_POST['action'] == 'update') {
    try {
        $id = $_POST['id_mhs'];
        $nim = $_POST['nim'];
        $nama = $_POST['nama'];
        $alamat = $_POST['alamat'];
        $prodi = $_POST['prodi'];
        
        $query = $conn->prepare("UPDATE tmhs SET nim=?, nama=?, alamat=?, prodi=? WHERE id_mhs=?");
        $result = $query->execute([$nim, $nama, $alamat, $prodi, $id]);
        
        header('Content-Type: application/json');
        if($result) {
            echo json_encode(['status' => 'success', 'message' => 'Data berhasil diupdate']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal mengupdate data']);
        }
        exit;
    } catch(PDOException $e) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}

// Delete Data
if(isset($_POST['action']) && $_POST['action'] == 'delete') {
    try {
        $id = $_POST['id_mhs'];
        
        $query = $conn->prepare("DELETE FROM tmhs WHERE id_mhs=?");
        $result = $query->execute([$id]);
        
        header('Content-Type: application/json');
        if($result) {
            echo json_encode(['status' => 'success', 'message' => 'Data berhasil dihapus']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data']);
        }
        exit;
    } catch(PDOException $e) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}
?>