<?php
header('Content-Type: application/json');
include 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

if (!isset($_GET['id'])) {
    echo json_encode(["status" => "error", "message" => "Missing Request ID"]);
    exit;
}

$request_id = $_GET['id'];

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM relief WHERE Relief_ID = ? AND User_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $request_id, $user_id);
} else {
    $sql = "SELECT * FROM relief WHERE Relief_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $request_id);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["status" => "success", "data" => $result->fetch_assoc()]);
} else {
    echo json_encode(["status" => "error", "message" => "Request not found"]);
}

$stmt->close();
$conn->close();
?>
