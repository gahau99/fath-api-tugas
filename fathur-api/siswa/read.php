<?php
require_once "../config/database.php";
header("Content-Type: application/json");

// Cek apakah ada parameter 'id'
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    // Ambil data berdasarkan ID
    $id = intval($_GET['id']);
    $query = "SELECT * FROM siswa WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($data) {
        echo json_encode([
            "status" => true,
            "message" => "Data Berhasil dibaca",
            "data" => $data
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "Data tidak ditemukan untuk ID: $id"
        ]);
    }
} else {
    // Ambil semua data
    $query = "SELECT * FROM siswa";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($data) {
        echo json_encode([
            "status" => true,
            "message" => "Data Berhasil dibaca",
            "data" => $data
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "Tidak ada data yang ditemukan"
        ]);
    }
}
?>
