<?php
header('Content-Type: application/json');
include 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized. Please login first."]);
    exit;
}

$user_id = $_SESSION['user_id'];
$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(["status" => "error", "message" => "Invalid JSON input"]);
    exit;
}

if (!isset($data['type']) || !isset($data['district'])) {
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
    exit;
}

if (isset($data['contact_number']) && isset($data['address'])) {
    $contact_number = $data['contact_number'];
    $address = $data['address'];
    
    $update_sql = "UPDATE affecteduser SET Contact_Number = ?, Address = ? WHERE User_ID = ?";
    $stmt = $conn->prepare($update_sql);
    if ($stmt) {
        $stmt->bind_param("ssi", $contact_number, $address, $user_id);
        $stmt->execute();
        $stmt->close();
    }
}

$type = $data['type'];
$district = $data['district'];
$div_sec = isset($data['div_sec']) ? $data['div_sec'] : '';
$gn_div = isset($data['gn_div']) ? $data['gn_div'] : '';
$family_members = isset($data['family_members']) ? intval($data['family_members']) : 1;
$severity = isset($data['severity']) ? $data['severity'] : 'Medium';
$description = isset($data['description']) ? $data['description'] : '';

$sql = "INSERT INTO relief (Type, Flood_severity_level, Description, District, Divisional_Secretariat, GN_Division, Number_of_family_members, User_ID) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Database prepare error: " . $conn->error]);
    exit;
}

$stmt->bind_param("ssssssii", $type, $severity, $description, $district, $div_sec, $gn_div, $family_members, $user_id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Database error: " . $stmt->error]);
}
$stmt->close();
$conn->close();
?>