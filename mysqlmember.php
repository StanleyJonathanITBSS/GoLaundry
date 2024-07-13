<?php
include 'config.php';

$conn = getConnection();

// Fetch values from $_POST
$nama_depan = isset($_POST['nama_depan']) ? $_POST['nama_depan'] : '';
$nama_belakang = isset($_POST['nama_belakang']) ? $_POST['nama_belakang'] : '';
$no_hp = isset($_POST['no_hp']) ? $_POST['no_hp'] : '';
$alamat = isset($_POST['alamat']) ? $_POST['alamat'] : '';

// Insert new member data
$query = "INSERT INTO members (first_name, last_name, phone_number, address) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssss", $nama_depan, $nama_belakang, $no_hp, $alamat);

if ($stmt->execute()) {
    // On successful insertion
    $response["message"] = "Member berhasil dibuat!";
    if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
        // Not an AJAX request
        header("Location: sukses.php");
        exit();
    }
} else {
    // If execution fails, handle the error
    $response["error"] = "Terjadi kesalahan saat memproses permintaan.";
    if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
        // Not an AJAX request
        header("Location: gagal.php");
        exit();
    }
}

// Send JSON response for AJAX requests
header('Content-Type: application/json');
echo json_encode($response);


$stmt->close();
$conn->close();
