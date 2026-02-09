<?php
session_start();

if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: auth/login.php');
    exit();
}

if($_SESSION['user_type'] !== 'teacher') {
    header('Location: dashboard.php');
    exit();
}

require_once 'config/database.php';

$database = new Database();
$pdo = $database->connect();

$languages = [];
$subjects = [];
$programs = [];
$error = '';
$success = '';

if($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM programming_languages ORDER BY language_name");
        $languages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $stmt = $pdo->query("SELECT * FROM subjects ORDER BY subject_name");
        $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $stmt = $pdo->query("SELECT * FROM programs ORDER BY program_name");
        $programs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    } catch(PDOException $e) {
        error_log("Database error: " . $e->getMessage());
    }
}

function generateClassCode($pdo) {
    $attempts = 0;
    do {
        $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM courses WHERE class_code = ?");
        $stmt->execute([$code]);
        $exists = $stmt->fetchColumn() > 0;
        $attempts++;
    } while ($exists && $attempts < 10);
    
    return $code;
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $language_id = $_POST['language_id'] ?? null;
    $subject_id = $_POST['subject_id'] ?? null;
    $program_id = $_POST['program_id'] ?? null;
    $section = trim($_POST['section'] ?? '');
    
    if(empty($section)) {
        $error = 'Section is required';
    } elseif(empty($language_id) && empty($subject_id)) {
        $error = 'Please select either a programming language or a subject';
    } else {
        try {
            $class_code = generateClassCode($pdo);
            
            $title = '';
            if($language_id) {
                $stmt = $pdo->prepare("SELECT language_name FROM programming_languages WHERE language_id = ?");
                $stmt->execute([$language_id]);
                $title = $stmt->fetchColumn();
            } elseif($subject_id) {
                $stmt = $pdo->prepare("SELECT subject_name FROM subjects WHERE subject_id = ?");
                $stmt->execute([$subject_id]);
                $title = $stmt->fetchColumn();
            }
            
            $stmt = $pdo->prepare("INSERT INTO courses (title, class_code, teacher_id, language_id, subject_id, program_id, section, created_at) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
            $stmt->execute([
                $title,
                $class_code,
                $_SESSION['user_id'],
                $language_id ?: null,
                $subject_id ?: null,
                $program_id ?: null,
                $section
            ]);
            
            $course_id = $pdo->lastInsertId();
            header('Location: course-detail.php?id=' . $course_id);
            exit();
            
        } catch(PDOException $e) {
            $error = 'Error creating class: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Class - EDUFLEX</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/create-class.css">
    <style>
       
    </style>
</head>
<body>
    <script>
        if (localStorage.getItem('darkMode') === 'enabled') {
            document.body.classList.add('dark-mode');
        }
    </script>
    <div class="create-class-container">
        <aside class="sidebar">
            <div class="logo">
                <svg width="40" height="40" viewBox="0 0 40 40">
                    <rect width="40" height="40" rx="5" fill="#7cb342"/>
                    <path d="M10 15 L20 10 L30 15 L20 20 Z" fill="white"/>
                    <path d="M10 20 L10 28 L20 33 L30 28 L30 20" stroke="white" stroke-width="2" fill="none"/>
                </svg>
                <span>EDUFLEX</span>
            </div>
            
            <nav class="nav-menu">
                <a href="teacher-dashboard.php" class="nav-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    <span>Dashboard</span>
                </a>
                <a href="teacher-courses.php" class="nav-item active">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                    <span>My Classes</span>
                </a>
                <a href="recent-class.php" class="nav-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <span>Recent Class</span>
                </a>
                <a href="calendar.php" class="nav-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    <span>Calendar</span>
                </a>
                
            </nav>
        </aside>
        
        <main class="main-content">
            <header class="top-bar">
                <h1>Create Class</h1>
                <div class="user-info">
                    <button class="notification-btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg>
                    </button>
                    <div class="user-profile">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['full_name']); ?>&background=7cb342&color=fff" alt="Profile">
                        <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    </div>
                </div>
            </header>
            
            <div class="content">
                <div class="form-container">
                    <h2>Create Class</h2>
                    
                    <?php if($error): ?>
                    <div class="alert alert-error">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if($success): ?>
                    <div class="alert alert-success">
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div class="form-group">
                            <label>Programming Language</label>
                            <select name="language_id" id="language_id" class="form-control">
                                <option value="">Select Language</option>
                                <?php foreach($languages as $lang): ?>
                                <option value="<?php echo $lang['language_id']; ?>">
                                    <?php echo htmlspecialchars($lang['language_name']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Course/Subject</label>
                            <select name="subject_id" id="subject_id" class="form-control">
                                <option value="">Select Subject</option>
                                <?php foreach($subjects as $subject): ?>
                                <option value="<?php echo $subject['subject_id']; ?>">
                                    <?php echo htmlspecialchars($subject['subject_name']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Program</label>
                            <select name="program_id" class="form-control">
                                <option value="">Select Program</option>
                                <?php foreach($programs as $program): ?>
                                <option value="<?php echo $program['program_id']; ?>">
                                    <?php echo htmlspecialchars($program['program_name']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Section *</label>
                            <input type="text" name="section" class="form-control" placeholder="e.g., 101, A, Morning" required>
                        </div>
                        
                        <button type="submit" class="submit-btn">Create Class</button>
                    </form>
                </div>
            </div>
        </main>
    </div>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const userProfile = document.querySelector('.user-profile');
    
    const dropdown = document.createElement('div');
    dropdown.className = 'profile-dropdown';
    dropdown.innerHTML = `
        <a href="teacher-settings.php" class="dropdown-item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="3"></circle>
                <path d="M12 1v6m0 6v6m5.2-13.2L13 9.8m-2 4.4L6.8 18.2m12.4 0L15 14m-6 .2L4.8 5.8"></path>
            </svg>
            Settings
        </a>
        <a href="auth/logout.php" class="dropdown-item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
            Logout
        </a>
    `;
    
    userProfile.style.position = 'relative';
    userProfile.style.cursor = 'pointer';
    userProfile.appendChild(dropdown);
    
    userProfile.addEventListener('click', function(e) {
        e.stopPropagation();
        dropdown.classList.toggle('show');
    });
    
    document.addEventListener('click', function() {
        dropdown.classList.remove('show');
    });
});
</script>
</body>
</html>