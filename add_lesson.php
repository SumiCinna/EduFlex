<?php
session_start();
require_once 'config/database.php';

header('Content-Type: application/json');

if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['user_type'] !== 'teacher') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id = $_POST['course_id'] ?? null;
    $lesson_title = $_POST['lesson_title'] ?? null;
    $lesson_content = $_POST['lesson_content'] ?? '';
    
    if(!$course_id || !$lesson_title) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit();
    }
    
    $database = new Database();
    $pdo = $database->connect();
    
    try {
        $stmt = $pdo->prepare("SELECT id FROM courses WHERE id = ? AND teacher_id = ?");
        $stmt->execute([$course_id, $_SESSION['user_id']]);
        
        if(!$stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Course not found or unauthorized']);
            exit();
        }
        
        $stmt = $pdo->prepare("SELECT COALESCE(MAX(lesson_order), 0) + 1 as next_order FROM lessons WHERE course_id = ?");
        $stmt->execute([$course_id]);
        $next_order = $stmt->fetch(PDO::FETCH_ASSOC)['next_order'];
        
        $stmt = $pdo->prepare("INSERT INTO lessons (course_id, title, content, lesson_order, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$course_id, $lesson_title, $lesson_content, $next_order]);
        
        echo json_encode(['success' => true, 'message' => 'Lesson added successfully']);
        
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
?>