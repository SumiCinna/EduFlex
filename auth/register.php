<?php
session_start();
require_once '../config/database.php';
require_once '../models/User.php';

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    try {
        $database = new Database();
        $db = $database->connect();
        
        if(!$db) {
            echo json_encode(['success' => false, 'message' => 'Database connection failed']);
            exit();
        }
        
        $user = new User($db);
        
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $full_name = $_POST['full_name'] ?? '';
        $user_type = $_POST['user_type'] ?? 'student';
        
        if(empty($username) || empty($email) || empty($password) || empty($confirm_password) || empty($full_name)) {
            echo json_encode(['success' => false, 'message' => 'All fields are required']);
            exit();
        }
        
        if($password !== $confirm_password) {
            echo json_encode(['success' => false, 'message' => 'Passwords do not match']);
            exit();
        }
        
        if(strlen($password) < 6) {
            echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters']);
            exit();
        }
        
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Invalid email format']);
            exit();
        }
        
        $user->email = $email;
        if($user->emailExists()) {
            echo json_encode(['success' => false, 'message' => 'Email already exists']);
            exit();
        }
        
        $user->username = $username;
        if($user->usernameExists()) {
            echo json_encode(['success' => false, 'message' => 'Username already exists']);
            exit();
        }
        
        $user->password = $password;
        $user->full_name = $full_name;
        $user->user_type = $user_type;
        
        if($user->register()) {
            echo json_encode(['success' => true, 'message' => 'Registration successful! Please sign in.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Registration failed. Please try again.']);
        }
        
    } catch(Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
    }
    
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>