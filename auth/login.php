<?php
session_start();
require_once '../config/database.php';
require_once '../models/user.php';

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $database = new Database();
    $db = $database->connect();
    $user = new User($db);
    
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if(empty($username) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit();
    }
    
    if($user->login($username, $password)) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['username'] = $user->username;
        $_SESSION['email'] = $user->email;
        $_SESSION['full_name'] = $user->full_name;
        $_SESSION['user_type'] = $user->user_type;
        $_SESSION['profile_image'] = $user->profile_image;
        $_SESSION['logged_in'] = true;
        
        echo json_encode([
            'success' => true, 
            'message' => 'Login successful',
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'full_name' => $user->full_name,
                'user_type' => $user->user_type
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
    }
    
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>