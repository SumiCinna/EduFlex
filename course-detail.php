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

$course_id = $_GET['id'] ?? null;
$course = null;
$lessons = [];
$activities = [];
$exams = [];
$students = [];
$announcements = [];

if($pdo && $course_id) {
    try {
       $stmt = $pdo->prepare("SELECT c.*, pl.language_name, s.subject_name, p.program_name 
                       FROM courses c
                       LEFT JOIN programming_languages pl ON c.language_id = pl.language_id
                       LEFT JOIN subjects s ON c.subject_id = s.subject_id
                       LEFT JOIN programs p ON c.program_id = p.program_id
                       WHERE c.id = ? AND c.teacher_id = ?");
        $stmt->execute([$course_id, $_SESSION['user_id']]);
        $course = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if(!$course) {
            header('Location: teacher-dashboard.php');
            exit();
        }
        
        $stmt = $pdo->prepare("SELECT * FROM lessons WHERE course_id = ? ORDER BY lesson_order ASC, created_at DESC");
        $stmt->execute([$course_id]);
        $lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $stmt = $pdo->prepare("SELECT * FROM activities WHERE course_id = ? ORDER BY created_at DESC");
        $stmt->execute([$course_id]);
        $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $stmt = $pdo->prepare("SELECT * FROM exams WHERE course_id = ? ORDER BY created_at DESC");
        $stmt->execute([$course_id]);
        $exams = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $stmt = $pdo->prepare("SELECT e.*, u.full_name, u.email, u.username 
                               FROM enrollments e
                               JOIN users u ON e.user_id = u.id
                               WHERE e.course_id = ?
                               ORDER BY u.full_name ASC");
        $stmt->execute([$course_id]);
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $stmt = $pdo->prepare("SELECT * FROM announcements WHERE course_id = ? ORDER BY created_at DESC LIMIT 5");
        $stmt->execute([$course_id]);
        $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    } catch(PDOException $e) {
        error_log("Database error: " . $e->getMessage());
    }
}

$class_code = $course['class_code'] ?? strtoupper(substr(md5($course_id . 'EDUFLEX'), 0, 6));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $course ? htmlspecialchars($course['language_name'] ?? $course['subject_name'] ?? 'Course') : 'Course'; ?> - EDUFLEX</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/course-detail.css">
    <style>
        
    </style>
</head>
<body>
    <script>
        if (localStorage.getItem('darkMode') === 'enabled') {
            document.body.classList.add('dark-mode');
        }
    </script>
    <div class="course-container">
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
            <header class="course-header">
                <div class="course-info">
                    <div class="course-icon">
                        <svg width="60" height="60" viewBox="0 0 100 100">
                            <circle cx="50" cy="50" r="50" fill="#7cb342"/>
                            <text x="50" y="50" text-anchor="middle" dy=".3em" fill="white" font-size="40" font-weight="bold">
                                <?php echo strtoupper(substr($course['language_name'] ?? $course['subject_name'] ?? 'C', 0, 1)); ?>
                            </text>
                        </svg>
                    </div>
                    <div>
                        <h1><?php echo $course ? htmlspecialchars($course['language_name'] ?? $course['subject_name'] ?? 'Untitled') : 'Untitled'; ?></h1>
                        <p class="course-meta"><?php echo $course ? htmlspecialchars($course['program_name'] ?? '') : ''; ?> • Course/Subject</p>
                    </div>
                </div>
                <div class="header-actions">
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
                <div class="content-grid">
                    <div class="main-column">
                        <div class="class-code-section">
                            <div class="class-code-header">
                                <h3>Class Code</h3>
                            </div>
                            <div class="class-code-display">
                                <span class="code-text" id="classCode"><?php echo $class_code; ?></span>
                                <button class="copy-btn" onclick="copyClassCode()">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                    </svg>
                                    Copy
                                </button>
                            </div>
                            <p class="class-code-info">Share this code with students to join your class</p>
                        </div>
                        
                        <section class="section-card">
                            <div class="section-header">
                                <h2>Lessons</h2>
                                <button class="add-btn" onclick="openLessonModal()">+ Add Lesson</button>
                            </div>
                            <div class="items-list">
                                <?php if(empty($lessons)): ?>
                                <p class="empty-message">No lessons yet</p>
                                <?php else: ?>
                                <?php foreach($lessons as $lesson): ?>
                                <div class="item">
                                    <span><?php echo htmlspecialchars($lesson['title']); ?></span>
                                    <button class="item-menu-btn">⋮</button>
                                </div>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </section>
                        
                        <div class="records-grid">
                            <section class="section-card">
                                <div class="section-header">
                                    <h2>Activities</h2>
                                    <button class="add-btn" onclick="openActivityModal()">+ Add</button>
                                </div>
                                <div class="items-list">
                                    <?php if(empty($activities)): ?>
                                    <p class="empty-message">No activities yet</p>
                                    <?php else: ?>
                                    <?php foreach($activities as $activity): ?>
                                    <div class="item">
                                        <span><?php echo htmlspecialchars($activity['activity_title']); ?></span>
                                        <button class="item-menu-btn">⋮</button>
                                    </div>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </section>
                            
                            <section class="section-card">
                                <div class="section-header">
                                    <h2>Written</h2>
                                </div>
                                <div class="items-list">
                                    <div class="item">
                                        <span>Quizzes</span>
                                        <button class="item-menu-btn">⋮</button>
                                    </div>
                                    <div class="item">
                                        <span>Examinations</span>
                                        <button class="item-menu-btn">⋮</button>
                                    </div>
                                </div>
                            </section>
                        </div>
                        
                        <section class="section-card">
                            <div class="section-header">
                                <h2>Announcements</h2>
                            </div>
                            <div class="announcement-box">
                                <?php if(!empty($announcements)): ?>
                                <?php foreach($announcements as $announcement): ?>
                                <p><?php echo nl2br(htmlspecialchars($announcement['content'])); ?></p>
                                <hr style="margin: 10px 0; border: none; border-top: 1px solid #eee;">
                                <?php endforeach; ?>
                                <?php endif; ?>
                                <form class="announcement-form" id="announcementForm" onsubmit="sendAnnouncement(event)">
                                    <input type="text" name="announcement_content" placeholder="Create an Announcement" class="announcement-input" required>
                                    <button type="submit" class="send-btn">Send</button>
                                </form>
                            </div>
                        </section>
                    </div>
                    
                    <aside class="sidebar-column">
                        <section class="section-card">
                            <h2>Grades</h2>
                            <div class="grade-items">
                                <div class="grade-item">
                                    <span>Students</span>
                                    <button class="item-menu-btn" onclick="openStudentsModal()">⋮</button>
                                    <p class="grade-subtitle">Enrolled Students (<?php echo count($students); ?>)</p>
                                    <div class="students-display-list">
                                        <?php 
                                        $displayStudents = array_slice($students, 0, 3);
                                        foreach($displayStudents as $student): 
                                        ?>
                                        <div class="students-display-item">
                                            <div class="students-display-avatar">
                                                <?php echo strtoupper(substr($student['full_name'], 0, 1)); ?>
                                            </div>
                                            <div class="students-display-name"><?php echo htmlspecialchars($student['full_name']); ?></div>
                                        </div>
                                        <?php endforeach; ?>
                                        <?php if(count($students) > 3): ?>
                                        <button class="students-show-all" onclick="openStudentsModal()">
                                            View All (<?php echo count($students); ?> students)
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="grade-item">
                                    <span>Mid Term Grades</span>
                                    <button class="item-menu-btn">⋮</button>
                                </div>
                                <div class="grade-item">
                                    <span>Final Grades</span>
                                    <button class="item-menu-btn">⋮</button>
                                </div>
                            </div>
                        </section>
                    </aside>
                </div>
            </div>
        </main>
    </div>
    
    <div class="modal" id="lessonModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add Lesson</h2>
                <button class="modal-close" onclick="closeLessonModal()">&times;</button>
            </div>
            <div id="lessonAlert" class="alert"></div>
            <form id="lessonForm" onsubmit="submitLesson(event)">
                <div class="form-group">
                    <label>Lesson Title</label>
                    <input type="text" name="lesson_title" required>
                </div>
                <div class="form-group">
                    <label>Lesson Content</label>
                    <textarea name="lesson_content"></textarea>
                </div>
                <button type="submit" class="btn-submit">Add Lesson</button>
            </form>
        </div>
    </div>
    
    <div class="modal" id="activityModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add Activity</h2>
                <button class="modal-close" onclick="closeActivityModal()">&times;</button>
            </div>
            <div id="activityAlert" class="alert"></div>
            <form id="activityForm" onsubmit="submitActivity(event)">
                <div class="form-group">
                    <label>Activity Title</label>
                    <input type="text" name="activity_title" required>
                </div>
                <div class="form-group">
                    <label>Activity Description</label>
                    <textarea name="activity_description"></textarea>
                </div>
                <div class="form-group">
                    <label>Due Date</label>
                    <input type="datetime-local" name="due_date">
                </div>
                <button type="submit" class="btn-submit">Add Activity</button>
            </form>
        </div>
    </div>
    <div class="modal" id="studentsModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Enrolled Students</h2>
                <button class="modal-close" onclick="closeStudentsModal()">&times;</button>
            </div>
            <div class="student-count">Total: <strong id="totalStudents"><?php echo count($students); ?></strong> students</div>
            <div class="student-list" id="studentListContainer">
                <?php if(empty($students)): ?>
                <p class="empty-message">No students enrolled yet</p>
                <?php endif; ?>
            </div>
            <div class="pagination" id="paginationControls" style="display: none;">
                <button id="prevBtn" onclick="changePage(-1)">Previous</button>
                <span class="page-info" id="pageInfo"></span>
                <button id="nextBtn" onclick="changePage(1)">Next</button>
            </div>
        </div>
    </div>
    
    <script>
        const courseId = <?php echo json_encode($course_id); ?>;
        const allStudents = <?php echo json_encode($students); ?>;
        let currentPage = 1;
        const studentsPerPage = 10;
        function renderStudents() {
            const startIndex = (currentPage - 1) * studentsPerPage;
            const endIndex = startIndex + studentsPerPage;
            const studentsToShow = allStudents.slice(startIndex, endIndex);
            const container = document.getElementById('studentListContainer');
            container.innerHTML = '';
            if(studentsToShow.length === 0) {
                container.innerHTML = '<p class="empty-message">No students enrolled yet</p>';
                document.getElementById('paginationControls').style.display = 'none';
                return;
            }
            studentsToShow.forEach(student => {
                const studentDiv = document.createElement('div');
                studentDiv.className = 'student-item';
                studentDiv.innerHTML = `
                    <div class="student-avatar">${student.full_name.charAt(0).toUpperCase()}</div>
                    <div class="student-details">
                        <div class="student-name">${student.full_name}</div>
                        <div class="student-email">${student.email}</div>
                    </div>
                `;
                container.appendChild(studentDiv);
            });
            const totalPages = Math.ceil(allStudents.length / studentsPerPage);
            if(totalPages > 1) {
                document.getElementById('paginationControls').style.display = 'flex';
                document.getElementById('pageInfo').textContent = `Page ${currentPage} of ${totalPages}`;
                document.getElementById('prevBtn').disabled = currentPage === 1;
                document.getElementById('nextBtn').disabled = currentPage === totalPages;
            } else {
                document.getElementById('paginationControls').style.display = 'none';
            }
        }
        function changePage(direction) {
            const totalPages = Math.ceil(allStudents.length / studentsPerPage);
            const newPage = currentPage + direction;
            if(newPage >= 1 && newPage <= totalPages) {
                currentPage = newPage;
                renderStudents();
            }
        }
        function openStudentsModal() {
            currentPage = 1;
            renderStudents();
            document.getElementById('studentsModal').classList.add('active');
        }
        function closeStudentsModal() {
            document.getElementById('studentsModal').classList.remove('active');
        }
        function copyClassCode() {
            const codeText = document.getElementById('classCode').textContent;
            const copyBtn = event.currentTarget;
            navigator.clipboard.writeText(codeText).then(() => {
                copyBtn.classList.add('copied');
                copyBtn.innerHTML = `
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Copied!
                `;
                setTimeout(() => {
                    copyBtn.classList.remove('copied');
                    copyBtn.innerHTML = `
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                        </svg>
                        Copy
                    `;
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy:', err);
            });
        }
        function openLessonModal() {
            document.getElementById('lessonModal').classList.add('active');
        }
        function closeLessonModal() {
            document.getElementById('lessonModal').classList.remove('active');
            document.getElementById('lessonForm').reset();
            document.getElementById('lessonAlert').style.display = 'none';
        }
        function openActivityModal() {
            document.getElementById('activityModal').classList.add('active');
        }
        function closeActivityModal() {
            document.getElementById('activityModal').classList.remove('active');
            document.getElementById('activityForm').reset();
            document.getElementById('activityAlert').style.display = 'none';
        }
        function submitLesson(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            formData.append('course_id', courseId);
            fetch('add_lesson.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const alert = document.getElementById('lessonAlert');
                alert.className = 'alert ' + (data.success ? 'success' : 'error');
                alert.textContent = data.message;
                if(data.success) {
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
        function submitActivity(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            formData.append('course_id', courseId);
            fetch('add_activity.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const alert = document.getElementById('activityAlert');
                alert.className = 'alert ' + (data.success ? 'success' : 'error');
                alert.textContent = data.message;
                if(data.success) {
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
        function sendAnnouncement(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            formData.append('course_id', courseId);
            formData.append('content', formData.get('announcement_content'));
            fetch('send_announcement.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
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