<?php
header('Content-Type: application/json');
include 'db_connect.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$sql = "SELECT * FROM affecteduser ORDER BY User_ID DESC";
$result = $conn->query($sql);

$users = [];
while ($row = $result->fetch_assoc()) {
    unset($row['user_Password_Hash']);
    $users[] = $row;
}

echo json_encode(["status" => "success", "data" => $users]);
$conn->close();
?>
