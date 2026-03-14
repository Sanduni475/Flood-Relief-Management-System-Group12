<?php
header('Content-Type: application/json');
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (!isset($data['full_name']) || !isset($data['email']) || !isset($data['password'])) {
        echo json_encode(["status" => "error", "message" => "Missing fields"]);
        exit;
    }

    $full_name = $data['full_name'];
    $email = $data['email'];
    $password = $data['password'];

    $parts = explode(" ", $full_name, 2);
    $first_name = $parts[0];
    $last_name = isset($parts[1]) ? $parts[1] : '';

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("SELECT Admin_email FROM admin WHERE Admin_email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Email already registered"]);
        $stmt->close();
        exit;
    }
    $stmt->close();

    $sql = "INSERT INTO admin (Admin_first_name, Admin_last_name, Admin_email, admin_Password_Hash) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $first_name, $last_name, $email, $hashed_password);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database error: " . $stmt->error]);
    }
    $stmt->close();
    $conn->close();
} else {
   echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>

