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

    if (empty($full_name) || empty($email) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "All fields are required"]);
        exit;
    }

    $parts = explode(" ", $full_name, 2);
    $first_name = $parts[0];
    $last_name = isset($parts[1]) ? $parts[1] : '';

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $check_sql = "SELECT User_email FROM affecteduser WHERE User_email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Email already registered"]);
        $check_stmt->close();
        exit;
    }
    $check_stmt->close();

    $sql = "INSERT INTO affecteduser (First_name, Last_name, User_email, user_Password_Hash) VALUES (?, ?, ?, ?)";
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
