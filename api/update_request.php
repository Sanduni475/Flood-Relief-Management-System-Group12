<?php
header('Content-Type: application/json');
include 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['relief_id'])) {
    echo json_encode(["status" => "error", "message" => "Missing Request ID"]);
    exit;
}

$relief_id = $data['relief_id'];
$user_id = $_SESSION['user_id'];

$type = $data['type'];
$district = $data['district'];
$div_sec = $data['div_sec'];
$gn_div = $data['gn_div'];
$family_members = intval($data['family_members']);
$severity = $data['severity'];
$description = isset($data['description']) ? $data['description'] : '';

if (isset($data['contact_number']) && isset($data['address'])) {
    $contact_number = $data['contact_number'];
    $address = $data['address'];
    $update_user_sql = "UPDATE affecteduser SET Contact_Number = ?, Address = ? WHERE User_ID = ?";
    $stmt_u = $conn->prepare($update_user_sql);
    $stmt_u->bind_param("ssi", $contact_number, $address, $user_id);
    $stmt_u->execute();
    $stmt_u->close();
}

$sql = "UPDATE relief SET Type=?, Flood_severity_level=?, Description=?, District=?, Divisional_Secretariat=?, GN_Division=?, Number_of_family_members=? WHERE Relief_ID=? AND User_ID=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssiii", $type, $severity, $description, $district, $div_sec, $gn_div, $family_members, $relief_id, $user_id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Update failed: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>