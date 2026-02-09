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

$user_name = $_SESSION['full_name'];
$user_type = $_SESSION['user_type'];
$user_id = $_SESSION['user_id'];

$user = null;
if($pdo) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Database error: " . $e->getMessage());
    }
}

if(!$user) {
    header('Location: auth/logout.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - EDUFLEX</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/teacher-dashboard.css">
    <style>
        .content {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .page-header {
            margin-bottom: 30px;
        }
        .page-title {
            font-size: 32px;
            font-weight: 700;
            color: #2d3748;
            margin: 0 0 8px 0;
        }
        .page-subtitle {
            font-size: 16px;
            color: #718096;
            margin: 0;
        }
        .settings-container {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }
        .settings-section {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .section-title {
            font-size: 20px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #e2e8f0;
        }
        .profile-avatar-section {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 24px;
            padding: 20px;
            background: #f7fafc;
            border-radius: 8px;
        }
        .profile-avatar-large {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #7cb342 0%, #558b2f 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: 700;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 8px;
            font-size: 14px;
        }
        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            transition: all 0.2s;
        }
        .form-group input:focus {
            outline: none;
            border-color: #7cb342;
            box-shadow: 0 0 0 3px rgba(124, 179, 66, 0.1);
        }
        .btn-primary {
            background: #7cb342;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-primary:hover {
            background: #689f38;
            transform: translateY(-1px);
        }
        .btn-danger {
            background: #ef4444;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-danger:hover {
            background: #dc2626;
        }
        .setting-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background: #f7fafc;
            border-radius: 8px;
        }
        .setting-info h3 {
            margin: 0 0 4px 0;
            font-size: 16px;
            font-weight: 600;
            color: #2d3748;
        }
        .setting-info p {
            margin: 0;
            font-size: 14px;
            color: #718096;
        }
        .toggle-switch {
            width: 56px;
            height: 30px;
            background: #cbd5e0;
            border-radius: 15px;
            position: relative;
            cursor: pointer;
            transition: background 0.3s;
        }
        .toggle-switch.active {
            background: #7cb342;
        }
        .toggle-slider {
            width: 24px;
            height: 24px;
            background: white;
            border-radius: 50%;
            position: absolute;
            top: 3px;
            left: 3px;
            transition: transform 0.3s;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        .toggle-switch.active .toggle-slider {
            transform: translateX(26px);
        }
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 14px;
            display: none;
        }
        .alert.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            display: block;
        }
        .alert.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            display: block;
        }
        .user-badge {
            background: linear-gradient(135deg, #7cb342 0%, #558b2f 100%);
            color: white;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            text-transform: capitalize;
        }
        body.dark-mode {
            background: #1a202c;
            color: #e2e8f0;
        }
        body.dark-mode .settings-section {
            background: #2d3748;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }
        body.dark-mode .page-title,
        body.dark-mode .section-title,
        body.dark-mode .setting-info h3,
        body.dark-mode .form-group label {
            color: #e2e8f0;
        }
        body.dark-mode .page-subtitle,
        body.dark-mode .setting-info p {
            color: #a0aec0;
        }
        body.dark-mode .profile-avatar-section,
        body.dark-mode .setting-item {
            background: #374151;
        }
        body.dark-mode .form-group input {
            background: #374151;
            border-color: #4a5568;
            color: #e2e8f0;
        }
        body.dark-mode .form-group input:disabled {
            background: #2d3748;
            color: #a0aec0;
        }
        body.dark-mode .top-bar {
            background: #2d3748;
            border-bottom-color: #4a5568;
        }
        body.dark-mode .sidebar {
            background: #2d3748;
        }
        body.dark-mode .nav-item {
            color: #a0aec0;
        }
        body.dark-mode .nav-item:hover {
            background: #374151;
            color: #e2e8f0;
        }
        body.dark-mode .nav-item.active {
            background: #7cb342;
            color: white;
        }
        body.dark-mode .main-content {
            background: #1a202c;
        }
    </style>
</head>
<body>
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
                <a href="teacher-courses.php" class="nav-item">
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
                <h1>Settings</h1>
                <div class="user-info">
                    <div class="user-badge"><?php echo htmlspecialchars($user_type); ?></div>
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
                <div class="page-header">
                    <h1 class="page-title">Settings ⚙️</h1>
                    <p class="page-subtitle">Manage your account preferences</p>
                </div>

                <div class="settings-container">
                    <div class="settings-section">
                        <div class="section-title">Profile Information</div>
                        <div class="profile-avatar-section">
                            <div class="profile-avatar-large">
                                <?php echo strtoupper(substr($user_name, 0, 1)); ?>
                            </div>
                            <div>
                                <h2 style="margin: 0 0 5px 0; color: #2d3748;"><?php echo htmlspecialchars($user_name); ?></h2>
                                <p style="margin: 0; color: #718096;"><?php echo htmlspecialchars($user['email']); ?></p>
                            </div>
                        </div>
                        
                        <div id="profileAlert"></div>
                        
                        <form id="profileForm">
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled style="background: #f5f5f5; cursor: not-allowed;">
                                <small style="color: #718096; font-size: 12px; margin-top: 4px; display: block;">Email cannot be changed</small>
                            </div>
                            <button type="submit" class="btn-primary">Update Profile</button>
                        </form>
                    </div>

                    <div class="settings-section">
                        <div class="section-title">Change Password</div>
                        <div id="passwordAlert"></div>
                        <form id="passwordForm">
                            <div class="form-group">
                                <label>Current Password</label>
                                <input type="password" name="current_password" required>
                            </div>
                            <div class="form-group">
                                <label>New Password</label>
                                <input type="password" name="new_password" required>
                            </div>
                            <div class="form-group">
                                <label>Confirm New Password</label>
                                <input type="password" name="confirm_password" required>
                            </div>
                            <button type="submit" class="btn-primary">Change Password</button>
                        </form>
                    </div>

                    <div class="settings-section">
                        <div class="section-title">Preferences</div>
                        <div class="setting-item">
                            <div class="setting-info">
                                <h3>Dark Mode 🌙</h3>
                                <p>Switch between light and dark theme (Keyboard shortcut: Ctrl+Shift+D)</p>
                            </div>
                            <div class="toggle-switch" id="darkModeToggle">
                                <div class="toggle-slider"></div>
                            </div>
                        </div>
                    </div>

                    <div class="settings-section">
                        <div class="section-title">Danger Zone</div>
                        <div class="setting-item">
                            <div class="setting-info">
                                <h3>Delete Account</h3>
                                <p>Permanently delete your account and all data</p>
                            </div>
                            <button class="btn-danger" onclick="confirmDelete()">Delete Account</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script>
        const darkModeToggle = document.getElementById('darkModeToggle');
        const body = document.body;

        function loadDarkMode() {
            const isDarkMode = localStorage.getItem('darkMode') === 'enabled';
            if (isDarkMode) {
                body.classList.add('dark-mode');
                darkModeToggle.classList.add('active');
            }
        }

        function toggleDarkMode() {
            body.classList.toggle('dark-mode');
            darkModeToggle.classList.toggle('active');
            
            if (body.classList.contains('dark-mode')) {
                localStorage.setItem('darkMode', 'enabled');
            } else {
                localStorage.setItem('darkMode', 'disabled');
            }
        }

        darkModeToggle.addEventListener('click', toggleDarkMode);

        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.shiftKey && e.key === 'D') {
                e.preventDefault();
                toggleDarkMode();
            }
        });

        loadDarkMode();

        document.getElementById('profileForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('update_profile.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const alert = document.getElementById('profileAlert');
                if(data.success) {
                    alert.className = 'alert success';
                    alert.textContent = data.message;
                    setTimeout(() => location.reload(), 1500);
                } else {
                    alert.className = 'alert error';
                    alert.textContent = data.message;
                }
            });
        });

        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            if(formData.get('new_password') !== formData.get('confirm_password')) {
                const alert = document.getElementById('passwordAlert');
                alert.className = 'alert error';
                alert.textContent = 'Passwords do not match';
                return;
            }
            
            fetch('change_password.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const alert = document.getElementById('passwordAlert');
                if(data.success) {
                    alert.className = 'alert success';
                    alert.textContent = data.message;
                    this.reset();
                } else {
                    alert.className = 'alert error';
                    alert.textContent = data.message;
                }
            });
        });

        function confirmDelete() {
            if(confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
                if(confirm('This will permanently delete all your data. Are you absolutely sure?')) {
                    window.location.href = 'delete_account.php';
                }
            }
        }
    </script>
</body>
</html>