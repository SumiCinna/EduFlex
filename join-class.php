<?php
session_start();
require_once 'config/database.php';

header('Content-Type: application/json');

if(!isset($_SESSION['logged_in']) || $_SESSION['user_type'] != 'student') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $class_code = trim($_POST['class_code']);
    $student_id = $_SESSION['user_id'];
    
    $database = new Database();
    $pdo = $database->connect();
    
    if(!$pdo) {
        echo json_encode(['success' => false, 'message' => 'Database connection failed']);
        exit();
    }
    
    try {
        $stmt = $pdo->prepare("SELECT id FROM courses WHERE class_code = ?");
        $stmt->execute([$class_code]);
        $course = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if(!$course) {
            echo json_encode(['success' => false, 'message' => 'Invalid class code']);
            exit();
        }
        
        $stmt = $pdo->prepare("SELECT id FROM enrollments WHERE course_id = ? AND user_id = ?");
        $stmt->execute([$course['id'], $student_id]);
        
        if($stmt->fetch(PDO::FETCH_ASSOC)) {
            echo json_encode(['success' => false, 'message' => 'Already enrolled in this class']);
            exit();
        }
        
        $stmt = $pdo->prepare("INSERT INTO enrollments (course_id, user_id, enrolled_at) VALUES (?, ?, NOW())");
        
        if($stmt->execute([$course['id'], $student_id])) {
            echo json_encode(['success' => true, 'message' => 'Successfully joined the class!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to join class']);
        }
    } catch(PDOException $e) {
        error_log("Join class error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Database error occurred']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>