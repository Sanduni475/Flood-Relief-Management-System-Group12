<?php
header('Content-Type: application/json');
include 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM relief WHERE User_ID = ? ORDER BY Created_date_time DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$requests = [];
while ($row = $result->fetch_assoc()) {
    $requests[] = $row;
}

echo json_encode(["status" => "success", "data" => $requests]);

$stmt->close();
$conn->close();
?>
