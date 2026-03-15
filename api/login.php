<?php
header('Content-Type: application/json');
include 'db_connect.php';
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 0);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (!isset($data['email']) || !isset($data['password'])) {
         echo json_encode(["status" => "error", "message" => "Missing fields"]);
         exit;
    }

    $email = $data['email'];
    $password = $data['password'];

    $sql = "SELECT * FROM affecteduser WHERE User_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['user_Password_Hash'])) {
             $_SESSION['user_id'] = $row['User_ID'];
             $_SESSION['user_name'] = $row['First_name'] . ' ' . $row['Last_name'];
             $_SESSION['user_role'] = 'affecteduser';
             echo json_encode([
                 "status" => "success", 
                 "user" => [
                     "id" => $row['User_ID'],
                     "name" => $row['First_name'] . ' ' . $row['Last_name'],
                     "email" => $row['User_email']
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
