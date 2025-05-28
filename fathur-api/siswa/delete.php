<?php
require_once "../config/database.php";
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->id)) {
    $query = "DELETE FROM siswa WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":id", $data->id);

    if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
            echo json_encode(["message" => "Data berhasil dihapus."]);
        } else {
            echo json_encode(["message" => "Data dengan ID tersebut tidak ditemukan."]);
        }
    } else {
        echo json_encode(["message" => "Gagal menghapus Data."]);
    }
} else {
    echo json_encode(["message" => "ID Data tidak dikirim."]);
}
