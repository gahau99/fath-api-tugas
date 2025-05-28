<?php
header("Content-Type: application/json");

// Koneksi ke database
try {
    $koneksi = mysqli_connect("localhost", "root", "", "api-gratis");
    mysqli_set_charset($koneksi, "utf8");
} catch (Exception $e) {
    echo json_encode(["message" => "Connection error: " . $e->getMessage()]);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
     if (array_key_exists('id', $_GET)) {
         $id = mysqli_real_escape_string($koneksi, $_GET['id']);
         $query = mysqli_query($koneksi, "SELECT * FROM siswa WHERE id = '$id'");
         $result = mysqli_fetch_assoc($query);

         if ($result) {
             echo json_encode($result);
         } else {
             echo json_encode(["message" => "Data yang ber ID = $id tidak ditemukan"]);
         }
     } elseif (count($_GET) === 0) {
         $query = mysqli_query($koneksi, "SELECT * FROM siswa");
         $results = mysqli_fetch_all($query, MYSQLI_ASSOC);
         echo json_encode($results);
     } else {
         echo json_encode(["message" => "Parameter tidak valid"]);
     }
    break;

    case 'POST':
      $contentType = $_SERVER["CONTENT_TYPE"] ?? '';

      if (stripos($contentType, 'application/json') !== false) {
          $data = json_decode(file_get_contents("php://input"), true);
      } else {
          $data = $_POST; // support form-urlencoded
      }

      if (isset($data['nama'], $data['kelas'])) {
          $nama = mysqli_real_escape_string($koneksi, $data['nama']);
          $kelas = mysqli_real_escape_string($koneksi, $data['kelas']);

          $insert = mysqli_query($koneksi, "INSERT INTO siswa (nama, kelas) VALUES ('$nama', '$kelas')");

          if ($insert) {
                $last_id = mysqli_insert_id($koneksi);
              echo json_encode(["message" => "Data berhasil ditambahkan", "id" => $last_id]);
          } else {
              echo json_encode(["message" => "Gagal menambahkan data"]);
          }
      } else {
          echo json_encode(["message" => "Data tidak lengkap"]);
      }
    break;

    case 'PUT':
    $contentType = $_SERVER["CONTENT_TYPE"] ?? '';

    if (stripos($contentType, 'application/json') !== false) {
        // Jika raw JSON
        $data = json_decode(file_get_contents("php://input"), true);
    } else {
        // Jika x-www-form-urlencoded
        parse_str(file_get_contents("php://input"), $data);
    }

    if (isset($data['id'], $data['nama'], $data['kelas'])) {
        $id = mysqli_real_escape_string($koneksi, $data['id']);
        $nama = mysqli_real_escape_string($koneksi, $data['nama']);
        $kelas = mysqli_real_escape_string($koneksi, $data['kelas']);

        $update = mysqli_query($koneksi, "UPDATE siswa SET nama = '$nama', kelas = '$kelas' WHERE id = '$id'");

        if ($update) {
            if (mysqli_affected_rows($koneksi) > 0) {
                echo json_encode(["message" => "Data berhasil diupdate"]);
            } else {
                echo json_encode(["message" => "Tidak ada data yang diupdate (mungkin ID tidak ditemukan atau data sama)"]);
            }
        } else {
            echo json_encode(["message" => "Gagal mengupdate data"]);
        }
    } else {
        echo json_encode(["message" => "Data tidak lengkap"]);
    }
    break;
    
    case 'DELETE':
        // Cek apakah konten adalah JSON
        $contentType = $_SERVER["CONTENT_TYPE"] ?? '';
    
        if (stripos($contentType, 'application/json') !== false) {
            $data = json_decode(file_get_contents("php://input"), true);
        } else {
            // fallback ke $_POST untuk x-www-form-urlencoded
            parse_str(file_get_contents("php://input"), $data);
        }
    
        if (isset($data['id'])) {
            $id = mysqli_real_escape_string($koneksi, $data['id']);
    
            $delete = mysqli_query($koneksi, "DELETE FROM siswa WHERE id = '$id'");
    
            if ($delete) {
                if (mysqli_affected_rows($koneksi) > 0) {
                    echo json_encode(["message" => "Data berhasil dihapus"]);
                } else {
                    echo json_encode(["message" => "ID tidak ditemukan, tidak ada data yang dihapus"]);
                }
            }
        } else {
            echo json_encode(["message" => "ID tidak ditemukan"]);
        }
        break;
    

}
