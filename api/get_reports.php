<?php
header('Content-Type: application/json');
include 'db_connect.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

//Get filter parameter
$area = isset($_GET['area']) ? trim($_GET['area']) : '';
$reliefType = isset($_GET['relief_type']) ? trim($_GET['relief_type']) : '';

// Build query with optional filters
$whereConditions = [];
$params = [];
$types = "";

if (!empty($area)) {
    $whereConditions[] = "District = ?";
    $params[] = $area;
    $types .= "s";
}

if (!empty($reliefType)) {
    $whereConditions[] = "Type = ?";
    $params[] = $reliefType;
    $types .= "s";
}

$whereClause = "";
if (!empty($whereConditions)) {
    $whereClause = "WHERE " . implode(" AND ", $whereConditions);
}

// Get summary grouped by relief type
$sql = "SELECT Type as relief_type, 
               COUNT(*) as total_requests, 
               SUM(CASE WHEN Flood_severity_level = 'High' THEN 1 ELSE 0 END) as high_severity 
        FROM relief 
        $whereClause 
        GROUP BY Type 
        ORDER BY Type";

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$summary = [];
while ($row = $result->fetch_assoc()) {
    $summary[] = [
        "relief_type" => $row['relief_type'],
        "total_requests" => intval($row['total_requests']),
        "high_severity" => intval($row['high_severity'])
    ];
}

echo json_encode(["status" => "success", "data" => $summary]);

$stmt->close();
$conn->close();
?>
