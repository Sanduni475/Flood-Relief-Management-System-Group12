<?php
header('Content-Type: application/json');
include 'db_connect.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$total_users = $conn->query("SELECT COUNT(*) as count FROM affecteduser")->fetch_assoc()['count'];
$total_requests = $conn->query("SELECT COUNT(*) as count FROM relief")->fetch_assoc()['count'];
$high_severity = $conn->query("SELECT COUNT(*) as count FROM relief WHERE Flood_severity_level = 'High'")->fetch_assoc()['count'];

$recent_sql = "SELECT r.*, u.First_name, u.Last_name FROM relief r JOIN affecteduser u ON r.User_ID = u.User_ID ORDER BY r.Created_date_time DESC LIMIT 5";
$recent_result = $conn->query($recent_sql);

$recent_requests = [];
while ($row = $recent_result->fetch_assoc()) {
    $recent_requests[] = $row;
}

echo json_encode([
    "status" => "success",
    "data" => [
        "total_users" => intval($total_users),
        "total_requests" => intval($total_requests),
        "high_severity" => intval($high_severity),
        "recent_requests" => $recent_requests
    ]
]);

$conn->close();
?>