<?php
header('Content-Type: application/json');
include 'db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (!isset($data['email']) || !isset($data['password'])) {
         echo json_encode(["status" => "error", "message" => "Missing fields"]);
         exit;
    }

    $email = $data['email'];
    $password = $data['password'];

    $sql = "SELECT * FROM admin WHERE Admin_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['admin_Password_Hash'])) {
             $_SESSION['admin_id'] = $row['Admin_ID'];
             $_SESSION['admin_name'] = $row['Admin_first_name'] . ' ' . $row['Admin_last_name'];
             $_SESSION['user_role'] = 'admin';
             echo json_encode([
                 "status" => "success", 
                 "user" => [
                     "id" => $row['Admin_ID'],
                     "name" => $row['Admin_first_name'] . ' ' . $row['Admin_last_name'],
                     "email" => $row['Admin_email'],
                     "role" => "admin"
                 ]
             ]);
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid password"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "User not found"]);
    }
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>
