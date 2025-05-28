<?php
require_once "../config/database.php";
header("Content-Type: application/json");

// Ambil data dari body JSON
$input = file_get_contents("php://input");
$data = json_decode($input, true); // pakai array assoc

/* 
if (!empty($data->nama) && !empty($data->kelas)) {
    $query = "INSERT INTO siswa (nama, kelas) VALUES (:nama, :kelas)";
    $stmt = $conn->prepare($query);
    
    $stmt->bindParam(":nama", $data->nama);
    $stmt->bindParam(":kelas", $data->kelas);
    
    if ($stmt->execute()) {
        echo json_encode(["message" => "Siswa berhasil ditambahkan.", "data" => $data]);
    } else {
        echo json_encode(["message" => "Gagal menambahkan siswa."]);
    }
} else {
    echo json_encode(["message" => "Data tidak lengkap."]);
}
*/

// Jika JSON kosong, coba ambil dari $_POST
if (!$data || empty($data['nama']) || empty($data['kelas'])) {
    $data = $_POST;
}

// Validasi
if (!empty($data['nama']) && !empty($data['kelas'])) {
    $query = "INSERT INTO siswa (nama, kelas) VALUES (:nama, :kelas)";
    $stmt = $conn->prepare($query);

    $stmt->bindParam(":nama", $data['nama']);
    $stmt->bindParam(":kelas", $data['kelas']);

    if ($stmt->execute()) {
        echo json_encode([
            "message" => "Data berhasil ditambahkan.",
            "data" => $data
        ]);
    } else {
        echo json_encode(["message" => "Gagal menambahkan Data."]);
    }
} else {
    echo json_encode(["message" => "Data tidak lengkap."]);
}
