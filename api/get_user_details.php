<?php
header('Content-Type: application/json');
include 'db_connect.php';
session_start();

if(!isset($_SESSION['admin_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

if (!isset($_GET['id'])) {
    echo json_encode(["status" => "error", "message" => "Missing User ID"]);
    exit;    
}

$user_id = intval($_GET['id']);

$user_sql = "SELECT * FROM affecteduser WHERE User_ID = ?";
$stmt = $conn->prepare($user_sql);
$stmt->bind_param("i",$user_id);
$stmt->execute();
$user_result = $stmt->get_result();

if ($user_result->num_rows == 0) {
    echo json_encode(["status" => "error", "message" => "User not found"]);
    $stmt->close();
    $stmt->close();
    exit;
}

$user = $user_result->fetch_assoc();
unset($user['user_Password_Hash']);
$stmt->close();

$req_sql = "SELECT * FROM relief WHERE User_ID = ? ORDER BY Created_date_time DESC";
$stmt2 = $conn->prepare($req_sql);
$stmt2->bind_param("i", $user_id);
$stmt2->execute();
$req_result = $stmt2->get_result();

$requests = [];
while ($row = $req_result->fetch_assoc()) {
    $requests[] = $row;
}
$stmt2->close();

echo json_encode(["status" => "success", "user" => $user, "requests" => $requests]);
$conn->close();

?>