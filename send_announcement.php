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
    $content = $_POST['content'] ?? null;
    
    if(!$course_id || !$content) {
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
        
        $stmt = $pdo->prepare("INSERT INTO announcements (course_id, teacher_id, title, content, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$course_id, $_SESSION['user_id'], 'Announcement', $content]);
        
        echo json_encode(['success' => true, 'message' => 'Announcement sent successfully']);
        
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
?>