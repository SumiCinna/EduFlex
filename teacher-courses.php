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

$courses = [];

if($pdo) {
    try {
        $stmt = $pdo->prepare("SELECT c.*, pl.language_name, s.subject_name, p.program_name 
                               FROM courses c
                               LEFT JOIN programming_languages pl ON c.language_id = pl.language_id
                               LEFT JOIN subjects s ON c.subject_id = s.subject_id
                               LEFT JOIN programs p ON c.program_id = p.program_id
                               WHERE c.teacher_id = ?
                               ORDER BY c.created_at DESC");
        $stmt->execute([$_SESSION['user_id']]);
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Database error: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses - EDUFLEX</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/teacher-courses.css">
    
</head>
<body>
    <script>
        if (localStorage.getItem('darkMode') === 'enabled') {
            document.body.classList.add('dark-mode');
        }
    </script>
    <div class="dashboard-container">
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
                <a href="teacher-calendar.php" class="nav-item">
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
                <h1>My Courses</h1>
                <div class="user-info">
                    <div class="user-badge" style="background: linear-gradient(135deg, #7cb342 0%, #558b2f 100%); color: white; padding: 6px 16px; border-radius: 20px; font-size: 13px; font-weight: 600; text-transform: capitalize; margin-right: 12px;"><?php echo htmlspecialchars($_SESSION['user_type']); ?></div>
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
                <div class="courses-container">
                    <div class="courses-grid">
                        <?php foreach($courses as $course): ?>
                        <a href="course-detail.php?id=<?php echo htmlspecialchars($course['id']); ?>" class="course-card">
                            <div class="course-header">
                                <div class="course-icon">
                                    <svg width="60" height="60" viewBox="0 0 100 100">
                                        <circle cx="50" cy="50" r="45" fill="white"/>
                                        <text x="50" y="58" text-anchor="middle" fill="#5B9BD5" font-size="32" font-weight="bold" font-family="Arial"><?php echo strtoupper(substr($course['language_name'] ?? $course['subject_name'] ?? 'C', 0, 1)); ?></text>
                                    </svg>
                                </div>
                                <div class="course-menu">
                                    <button class="menu-btn" onclick="event.preventDefault(); event.stopPropagation();">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                            <circle cx="12" cy="5" r="2" fill="currentColor"/>
                                            <circle cx="12" cy="12" r="2" fill="currentColor"/>
                                            <circle cx="12" cy="19" r="2" fill="currentColor"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="course-body">
                                <h3><?php echo htmlspecialchars($course['language_name'] ?? $course['subject_name']); ?></h3>
                                <p class="course-program"><?php echo htmlspecialchars($course['program_name']); ?></p>
                                <p class="course-section">Class <?php echo htmlspecialchars($course['section']); ?></p>
                            </div>
                        </a>
<?php endforeach; ?>
                        
                        <div class="course-card create-card">
                            <a href="create-class.php" class="create-link">
                                <div class="create-icon">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                </div>
                                <p>Create Class</p>
                            </a>
                        </div>
                    </div>
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