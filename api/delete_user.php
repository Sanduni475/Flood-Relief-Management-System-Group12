<?php
header('Content-Type: application/json');
include 'db_connect.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Missing User ID"]);
    exit;
}

$user_id = intval($data['user_id']);

// Prevent deleting yourself
if ($_SESSION['admin_id'] == $user_id) {
    echo json_encode(["status" => "error", "message" => "Cannot delete your own account"]);
    exit;
}

// Delete user (CASCADE will delete their relief requests)
$sql = "DELETE FROM affecteduser WHERE User_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "User not found"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Error deleting user: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
