<?php
header('Content-Type: application/json');
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"),true);

    if(!isset($data['full_name']) || !isset($data['email']) || !isset($data['password'])) {
      echo json_encode(["status" => "error", "message" => "Missing fields"]);
      exit;  
    }

    $full_name = $data['full_name'];
    $email = $data['email'];
    $password = $data['password'];

    if(empty($full_name) || empty($email) || empty($password)) {

       echo json_encode(["status" => "error", "message" => "All fields are required"]);
       exit;
    }

    $parts = explode(" ", $full_name, 2);
    $first_name = $parts[0];
    $last_name = isset($parts[1]) ? $parts[1] : '';

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
}

?>