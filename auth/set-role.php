<?php
session_start();
header('Content-Type: application/json');

if(!isset($_SESSION['pending_user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid session']);
    exit();
}

$config_path = __DIR__ . '/../config/database.php';
if(!file_exists($config_path)) {
    echo json_encode(['success' => false, 'message' => 'Database configuration file not found']);
    exit();
}

require_once $config_path;

$database = new Database();
$pdo = $database->connect();

if(!$pdo) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
}

if(!isset($_POST['user_type']) || empty($_POST['user_type'])) {
    echo json_encode(['success' => false, 'message' => 'Please select a role']);
    exit();
}

$user_type = $_POST['user_type'];
$user_id = $_SESSION['pending_user_id'];

if($user_type !== 'student' && $user_type !== 'teacher') {
    echo json_encode(['success' => false, 'message' => 'Invalid role selected']);
    exit();
}

try {
    $stmt = $pdo->prepare("UPDATE users SET user_type = ? WHERE id = ?");
    $stmt->execute([$user_type, $user_id]);
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    
    $_SESSION['logged_in'] = true;
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['user_type'] = $user['user_type'];
    $_SESSION['full_name'] = $user['full_name'];
    
    unset($_SESSION['pending_user_id']);
    
    echo json_encode([
        'success' => true,
        'message' => 'Role set successfully!'
    ]);
    
} catch(PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>