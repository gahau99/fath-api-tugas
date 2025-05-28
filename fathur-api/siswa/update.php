<?php
require_once "../config/database.php";
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->id) && !empty($data->nama) && !empty($data->kelas)) {
    $query = "UPDATE siswa SET nama = :nama, kelas = :kelas WHERE id = :id";
    $stmt = $conn->prepare($query);

    $stmt->bindParam(":id", $data->id);
    $stmt->bindParam(":nama", $data->nama);
    $stmt->bindParam(":kelas", $data->kelas);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Data berhasil diperbarui."]);
    } else {
        echo json_encode(["message" => "Gagal memperbarui Data."]);
    }
} else {
    echo json_encode(["message" => "Data tidak lengkap."]);
}
