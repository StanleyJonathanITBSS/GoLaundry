<?php

include 'config.php';

$conn = getConnection();

// Fetch values from $_POST
$nama_depan = isset($_POST['nama_depan']) ? $_POST['nama_depan'] : '';
$nama_belakang = isset($_POST['nama_belakang']) ? $_POST['nama_belakang'] : '';
$nik = isset($_POST['nik']) ? $_POST['nik'] : '';
$no_hp = isset($_POST['no_hp']) ? $_POST['no_hp'] : '';
$alamat = isset($_POST['alamat']) ? $_POST['alamat'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$ktp = isset($_POST['ktp']) ? $_POST['ktp'] : '';

// Check if NIK already exists
$query = "SELECT * FROM members WHERE nik = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $nik);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // If NIK already exists, redirect to success page
    header("Location: sukses.php");
    exit();
}

$stmt->close();

// Insert new member data
$query = "INSERT INTO members (first_name, last_name, nik, phone_number, address, email, ktp) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("sssssss", $nama_depan, $nama_belakang, $nik, $no_hp, $alamat, $email, $ktp);

if ($stmt->execute()) {
    // On successful insertion, redirect to success page
    header("Location: sukses.php");
    exit();
} else {
    // If execution fails, handle the error
    header('Content-Type: application/json');
    echo json_encode(["error" => "Error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
1