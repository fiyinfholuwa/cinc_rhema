<?php
session_start();
require_once "conn.php"; // your DB connection file

// Receive JSON request
$input = json_decode(file_get_contents("php://input"), true);

$username = trim($input["username"]);
$password = trim($input["password"]);

if($username == "" || $password == ""){
    echo json_encode(["status" => "error", "message" => "All fields are required"]);
    exit;
}

$stmt = $conn->prepare("SELECT id, username, password FROM admin_login WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows === 1){
    $user = $result->fetch_assoc();

    // If password is hashed, replace with password_verify()
    if($password === $user['password']){
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        echo json_encode(["status" => "success", "message" => "Login successful"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Incorrect password"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Username not found"]);
}

$stmt->close();
