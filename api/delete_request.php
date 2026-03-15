<?php
header('Content-Type: application/json');
include 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['relief_id'])) {
    echo json_encode(["status" => "error", "message" => "Missing Request ID"]);
    exit;
}

$relief_id = $data['relief_id'];

if (isset($_SESSION['admin_id'])) {
    $sql = "DELETE FROM relief WHERE Relief_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $relief_id);
} else {
    $user_id = $_SESSION['user_id'];
    $sql = "DELETE FROM relief WHERE Relief_ID = ? AND User_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $relief_id, $user_id);
}

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Request not found or already deleted"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>