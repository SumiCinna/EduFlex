<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if(!isset($_SESSION['logged_in']) || $_SESSION['user_type'] != 'teacher') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

function generateClassCode() {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $code = '';
    for ($i = 0; $i < 7; $i++) {
        $code .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $code;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_name = trim($_POST['course_name']);
    $course_type = trim($_POST['course_type']);
    $description = trim($_POST['description']);
    $teacher_id = $_SESSION['user_id'];
    
    $class_code = generateClassCode();
    
    $stmt = $pdo->prepare("SELECT id FROM courses WHERE class_code = ?");
    $stmt->execute([$class_code]);
    
    while($stmt->fetch()) {
        $class_code = generateClassCode();
        $stmt->execute([$class_code]);
    }
    
    $stmt = $pdo->prepare("INSERT INTO courses (title, course_type, description, class_code, instructor_id, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    
    if($stmt->execute([$course_name, $course_type, $description, $class_code, $teacher_id])) {
        echo json_encode(['success' => true, 'message' => 'Class created successfully. Code: ' . $class_code]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create class']);
    }
}
?>