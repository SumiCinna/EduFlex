<?php
session_start();
header('Content-Type: application/json');

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

$data = json_decode(file_get_contents('php://input'), true);

if(!isset($data['credential'])) {
    echo json_encode(['success' => false, 'message' => 'No credential provided']);
    exit();
}

$credential = $data['credential'];

$parts = explode('.', $credential);
if(count($parts) !== 3) {
    echo json_encode(['success' => false, 'message' => 'Invalid credential format']);
    exit();
}

$payload = json_decode(base64_decode(strtr($parts[1], '-_', '+/')), true);

if(!$payload) {
    echo json_encode(['success' => false, 'message' => 'Failed to decode credential']);
    exit();
}

$email = $payload['email'];
$name = $payload['name'];
$google_id = $payload['sub'];
$picture = isset($payload['picture']) ? $payload['picture'] : 'default.png';

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? OR google_id = ?");
    $stmt->execute([$email, $google_id]);
    $existing_user = $stmt->fetch();

    if($existing_user) {
        echo json_encode([
            'success' => false,
            'message' => 'Account already exists. Please login instead.'
        ]);
        exit();
    }

    $username = strtolower(str_replace(' ', '', $name)) . rand(100, 999);
    
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute([$username]);
    while($stmt->fetchColumn() > 0) {
        $username = strtolower(str_replace(' ', '', $name)) . rand(100, 9999);
        $stmt->execute([$username]);
    }

    $stmt = $pdo->prepare("
        INSERT INTO users (username, email, full_name, password, google_id, profile_image, user_type, created_at, last_login) 
        VALUES (?, ?, ?, NULL, ?, ?, NULL, NOW(), NOW())
    ");
    
    $stmt->execute([$username, $email, $name, $google_id, $picture]);
    
    $user_id = $pdo->lastInsertId();

    $_SESSION['pending_user_id'] = $user_id;

    echo json_encode([
        'success' => true,
        'message' => 'Registration successful! Please select your role.'
    ]);

} catch(PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>