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
$picture = isset($payload['picture']) ? $payload['picture'] : null;

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? OR google_id = ?");
    $stmt->execute([$email, $google_id]);
    $user = $stmt->fetch();

    if($user) {
        if(empty($user['google_id'])) {
            $stmt = $pdo->prepare("UPDATE users SET google_id = ?, profile_image = ? WHERE id = ?");
            $stmt->execute([$google_id, $picture, $user['id']]);
        }
        
        $stmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
        $stmt->execute([$user['id']]);

        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['user_type'] = $user['user_type'];
        $_SESSION['full_name'] = $user['full_name'];

        echo json_encode([
            'success' => true,
            'message' => 'Login successful!'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No account found. Please sign up first.'
        ]);
    }
} catch(PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>