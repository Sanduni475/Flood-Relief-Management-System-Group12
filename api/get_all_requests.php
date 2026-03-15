<?php
header('Content-Type: application/json');
include 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$sql = "SELECT r.*, u.First_name, u.Last_name, u.Contact_Number FROM relief r JOIN affecteduser u ON r.User_ID = u.User_ID ORDER BY r.Created_date_time DESC";
$result = $conn->query($sql);

$requests = [];
while ($row = $result->fetch_assoc()) {
    $requests[] = $row;
}

echo json_encode(["status" => "success", "data" => $requests]);
$conn->close();
?>