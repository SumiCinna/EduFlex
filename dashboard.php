<?php
session_start();

if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: auth/login.php');
    exit();
}

if($_SESSION['user_type'] === 'teacher') {
    header('Location: teacher-dashboard.php');
    exit();
}

require_once 'config/database.php';

$database = new Database();
$pdo = $database->connect();

$user_name = $_SESSION['full_name'];
$user_type = $_SESSION['user_type'];
$user_id = $_SESSION['user_id'];

$quiz_stats = ['missed' => 0, 'completed' => 0];
$activity_stats = ['missed' => 0, 'completed' => 0];
$exam_stats = ['missed' => 0, 'completed' => 0];
$participation_stats = ['present' => 0, 'absences' => 0, 'recitation' => 0];
$performance_stats = ['assignments' => 0, 'quizzes' => 0, 'exams' => 0];

$courses = [];
if($pdo) {
    try {
        if($user_type == 'student') {
            $stmt = $pdo->prepare("
                SELECT c.*, u.full_name as instructor_name, pl.language_name, s.subject_name, p.program_name
                FROM courses c 
                JOIN enrollments e ON c.id = e.course_id 
                JOIN users u ON c.teacher_id = u.id
                LEFT JOIN programming_languages pl ON c.language_id = pl.language_id
                LEFT JOIN subjects s ON c.subject_id = s.subject_id
                LEFT JOIN programs p ON c.program_id = p.program_id
                WHERE e.user_id = ?
                LIMIT 3
            ");
            $stmt->execute([$user_id]);
            $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $stmt = $pdo->prepare("
                SELECT c.*, u.full_name as instructor_name, pl.language_name, s.subject_name, p.program_name
                FROM courses c 
                JOIN users u ON c.teacher_id = u.id
                LEFT JOIN programming_languages pl ON c.language_id = pl.language_id
                LEFT JOIN subjects s ON c.subject_id = s.subject_id
                LEFT JOIN programs p ON c.program_id = p.program_id
                WHERE c.teacher_id = ?
                LIMIT 3
            ");
            $stmt->execute([$user_id]);
            $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch(PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        $courses = [];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - EDUFLEX</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/dark-mode.css">
</head>
<body>
    <div class="dashboard-layout">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <button class="menu-toggle" id="menuToggle">☰</button>
            </div>
            
            <nav class="sidebar-menu">
                <a href="dashboard.php" class="menu-item active">
                    <svg class="menu-icon" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                    <span class="menu-text">Home</span>
                </a>
                <a href="my-classes.php" class="menu-item">
                    <svg class="menu-icon" viewBox="0 0 24 24"><path d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3zm6.82 6L12 12.72 5.18 9 12 5.28 18.82 9zM17 15.99l-5 2.73-5-2.73v-3.72L12 15l5-2.73v3.72z"/></svg>
                    <span class="menu-text">My Classes</span>
                </a>
                <a href="calendar.php" class="menu-item">
                    <svg class="menu-icon" viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zM9 14H7v-2h2v2zm4 0h-2v-2h2v2zm4 0h-2v-2h2v2zm-8 4H7v-2h2v2zm4 0h-2v-2h2v2zm4 0h-2v-2h2v2z"/></svg>
                    <span class="menu-text">Calendar</span>
                </a>
                <a href="todo.php" class="menu-item">
                    <svg class="menu-icon" viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
                    <span class="menu-text">To-Do</span>
                </a>
                <a href="settings.php" class="menu-item">
                    <svg class="menu-icon" viewBox="0 0 24 24"><path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/></svg>
                    <span class="menu-text">Settings</span>
                </a>
            </nav>
            
            <div class="sidebar-footer">
            </div>
        </aside>
        
        <main class="main-content">
            <div class="top-bar">
                <div class="top-bar-left">
                </div>
                <div class="top-bar-right">
                    <div class="user-badge"><?php echo htmlspecialchars($user_type); ?></div>
                    <button class="icon-btn">
                        🔔
                        <span class="notification-badge"></span>
                    </button>
                    <div class="user-avatar" id="userAvatar">
                        <?php echo strtoupper(substr($user_name, 0, 1)); ?>
                    </div>
                </div>
            </div>
            
            <div class="user-dropdown" id="userDropdown">
                <a href="profile.php" class="dropdown-item">
                    <svg class="dropdown-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                    <span>My Account</span>
                </a>
                <a href="auth/logout.php" class="dropdown-item">
                    <svg class="dropdown-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/></svg>
                    <span>Log Out</span>
                </a>
            </div>
            
            <div class="content-area">
                <div class="page-header">
                    <h1 class="page-title">Hello, <?php echo htmlspecialchars(explode(' ', $user_name)[0]); ?>!</h1>
                    <p class="page-subtitle">Here's your learning overview</p>
                </div>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="card-icon">📝</div>
                        <h3>Quiz</h3>
                        <div class="stat-details">
                            <div class="stat-number">
                                <span class="label">Missed</span>
                                <span class="value"><?php echo $quiz_stats['missed']; ?></span>
                            </div>
                            <div class="stat-number completed">
                                <span class="label">Completed</span>
                                <span class="value"><?php echo $quiz_stats['completed']; ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="card-icon">📚</div>
                        <h3>Activities</h3>
                        <div class="stat-details">
                            <div class="stat-number">
                                <span class="label">Missed</span>
                                <span class="value"><?php echo $activity_stats['missed']; ?></span>
                            </div>
                            <div class="stat-number completed">
                                <span class="label">Completed</span>
                                <span class="value"><?php echo $activity_stats['completed']; ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="card-icon">📊</div>
                        <h3>Exam</h3>
                        <div class="stat-details">
                            <div class="stat-number">
                                <span class="label">Missed</span>
                                <span class="value"><?php echo $exam_stats['missed']; ?></span>
                            </div>
                            <div class="stat-number completed">
                                <span class="label">Completed</span>
                                <span class="value"><?php echo $exam_stats['completed']; ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="analytics-section">
                    <div class="analytics-grid">
                        <div class="analytics-card">
                            <h3>Participation Analytics</h3>
                            <div class="chart-container">
                                <div class="chart-bar">
                                    <div class="bar" style="height: <?php echo $participation_stats['present']; ?>%;">
                                        <span class="bar-value"><?php echo $participation_stats['present']; ?>%</span>
                                    </div>
                                    <span class="bar-label">Present</span>
                                </div>
                                <div class="chart-bar">
                                    <div class="bar" style="height: <?php echo $participation_stats['absences']; ?>%;">
                                        <span class="bar-value"><?php echo $participation_stats['absences']; ?>%</span>
                                    </div>
                                    <span class="bar-label">Absences</span>
                                </div>
                                <div class="chart-bar">
                                    <div class="bar" style="height: <?php echo $participation_stats['recitation']; ?>%;">
                                        <span class="bar-value"><?php echo $participation_stats['recitation']; ?>%</span>
                                    </div>
                                    <span class="bar-label">Recitation</span>
                                </div>
                            </div>
                        </div>

                        <div class="analytics-card">
                            <h3>Academic Performance</h3>
                            <div class="chart-container">
                                <div class="chart-bar">
                                    <div class="bar" style="height: <?php echo $performance_stats['assignments']; ?>%;">
                                        <span class="bar-value"><?php echo $performance_stats['assignments']; ?>%</span>
                                    </div>
                                    <span class="bar-label">Assignments</span>
                                </div>
                                <div class="chart-bar">
                                    <div class="bar" style="height: <?php echo $performance_stats['quizzes']; ?>%;">
                                        <span class="bar-value"><?php echo $performance_stats['quizzes']; ?>%</span>
                                    </div>
                                    <span class="bar-label">Quizzes</span>
                                </div>
                                <div class="chart-bar">
                                    <div class="bar" style="height: <?php echo $performance_stats['exams']; ?>%;">
                                        <span class="bar-value"><?php echo $performance_stats['exams']; ?>%</span>
                                    </div>
                                    <span class="bar-label">Exams</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section-title">Grade per Courses</div>
                <div class="course-preview-grid">
                    <?php if(empty($courses)): ?>
                    <div class="empty-state">
                        <p>No courses enrolled yet. Join a class using a class code!</p>
                    </div>
                    <?php else: ?>
                    <?php foreach($courses as $course): ?>
                    <div class="course-preview-card" onclick="window.location.href='course-view.php?id=<?php echo $course['id']; ?>'">
                        <div class="course-preview-header <?php echo strtolower($course['title'] ?? ''); ?>">
                            <?php 
                            $courseName = $course['language_name'] ?? $course['subject_name'] ?? $course['title'] ?? 'Course';
                            echo strtoupper(substr($courseName, 0, 1));
                            ?>
                        </div>
                        <div class="course-preview-body">
                            <div class="course-preview-title"><?php echo htmlspecialchars($course['language_name'] ?? $course['subject_name'] ?? $course['title'] ?? 'Untitled'); ?></div>
                            <div class="course-preview-instructor">Professor <?php echo htmlspecialchars($course['instructor_name'] ?? 'Unknown'); ?></div>
                            <div class="course-preview-code"><?php echo htmlspecialchars($course['class_code'] ?? 'N/A'); ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
    
    <div class="chat-widget">
        <button class="chat-button" id="chatButton">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="white">
                <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
            </svg>
            Chat with us!
        </button>
        <div class="chat-box" id="chatBox">
            <div class="chat-header">
                <span>Support Chat</span>
                <button class="chat-close" id="chatClose">&times;</button>
            </div>
            <div class="chat-messages" id="chatMessages">
                <div class="chat-message bot">
                    <div class="bot-avatar">🤖</div>
                    <div class="message-content">
                        Welcome to EDUFLEX Support! How can we help you today?
                    </div>
                </div>
            </div>
            <div class="chat-input-area">
                <input type="text" class="chat-input" id="chatInput" placeholder="Type your message...">
                <button class="chat-send" id="chatSend">Send</button>
            </div>
        </div>
    </div>
    <script src="js/dark-mode.js"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const menuToggle = document.getElementById('menuToggle');
        const userAvatar = document.getElementById('userAvatar');
        const userDropdown = document.getElementById('userDropdown');
        
        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('expanded');
        });
        
        userAvatar.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.classList.toggle('active');
        });
        
        document.addEventListener('click', function(e) {
            if (!userDropdown.contains(e.target) && e.target !== userAvatar) {
                userDropdown.classList.remove('active');
            }
        });
        
        const chatButton = document.getElementById('chatButton');
        const chatBox = document.getElementById('chatBox');
        const chatClose = document.getElementById('chatClose');
        const chatInput = document.getElementById('chatInput');
        const chatSend = document.getElementById('chatSend');
        const chatMessages = document.getElementById('chatMessages');
        
        chatButton.addEventListener('click', function() {
            chatBox.classList.add('active');
            chatButton.style.display = 'none';
            chatInput.focus();
        });
        
        chatClose.addEventListener('click', function() {
            chatBox.classList.remove('active');
            chatButton.style.display = 'flex';
        });
        
        function sendMessage() {
            const message = chatInput.value.trim();
            if (message === '') return;
            
            const userMessageDiv = document.createElement('div');
            userMessageDiv.className = 'chat-message user';
            userMessageDiv.innerHTML = `
                <div class="message-content">${escapeHtml(message)}</div>
            `;
            chatMessages.appendChild(userMessageDiv);
            
            chatInput.value = '';
            chatMessages.scrollTop = chatMessages.scrollHeight;
            
            setTimeout(function() {
                const botMessageDiv = document.createElement('div');
                botMessageDiv.className = 'chat-message bot';
                botMessageDiv.innerHTML = `
                    <div class="bot-avatar">🤖</div>
                    <div class="message-content">Thanks for your message! Our support team will respond shortly.</div>
                `;
                chatMessages.appendChild(botMessageDiv);
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }, 1000);
        }
        
        chatSend.addEventListener('click', sendMessage);
        
        chatInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
        
        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, m => map[m]);
        }
    </script>
</body>
</html>