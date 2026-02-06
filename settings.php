<?php
session_start();

if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: index.php');
    exit();
}

require_once 'config/database.php';

$database = new Database();
$pdo = $database->connect();

$user_name = $_SESSION['full_name'];
$user_type = $_SESSION['user_type'];
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - EDUFLEX</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/settings.css">
    
    <link rel="stylesheet" href="css/dark-mode.css">
</head>
<body>
    <div class="dashboard-layout">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <button class="menu-toggle" id="menuToggle">☰</button>
            </div>
            
            <nav class="sidebar-menu">
                <a href="dashboard.php" class="menu-item">
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
                <a href="settings.php" class="menu-item active">
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
                                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
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
    
    <!-- Dark Mode Script - IMPORTANT: Include this in all pages BEFORE other scripts -->
    <script src="js/dark-mode.js"></script>
    
    <script>
        // Sidebar and navigation
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
        
        // Chat widget
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
            userMessageDiv.innerHTML = `<div class="message-content">${escapeHtml(message)}</div>`;
            chatMessages.appendChild(userMessageDiv);
            
            chatInput.value = '';
            chatMessages.scrollTop = chatMessages.scrollHeight;
            
            setTimeout(function() {
                const botMessageDiv = document.createElement('div');
                botMessageDiv.className = 'chat-message bot';
                botMessageDiv.innerHTML = `<div class="bot-avatar">🤖</div><div class="message-content">Thanks for your message! Our support team will respond shortly.</div>`;
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
            const map = {'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'};
            return text.replace(/[&<>"']/g, m => map[m]);
        }

        // Other preference toggles
        document.getElementById('emailToggle').addEventListener('click', function() {
            this.classList.toggle('active');
            const isActive = this.classList.contains('active');
            localStorage.setItem('emailNotifications', isActive);
        });

        document.getElementById('pushToggle').addEventListener('click', function() {
            this.classList.toggle('active');
            const isActive = this.classList.contains('active');
            localStorage.setItem('pushNotifications', isActive);
        });

        // Load saved preferences
        if(localStorage.getItem('emailNotifications') === 'false') {
            document.getElementById('emailToggle').classList.remove('active');
        }
        if(localStorage.getItem('pushNotifications') === 'false') {
            document.getElementById('pushToggle').classList.remove('active');
        }

        // Profile form submission
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('actions/update_profile.php', {
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

        // Password form submission
        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            if(formData.get('new_password') !== formData.get('confirm_password')) {
                const alert = document.getElementById('passwordAlert');
                alert.className = 'alert error';
                alert.textContent = 'Passwords do not match';
                return;
            }
            
            fetch('actions/change_password.php', {
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

        // Delete account
        function confirmDelete() {
            if(confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
                if(confirm('This will permanently delete all your data. Are you absolutely sure?')) {
                    window.location.href = 'actions/delete_account.php';
                }
            }
        }
        
        // Listen for dark mode changes
        window.addEventListener('darkModeChanged', function(e) {
            console.log('Dark mode is now:', e.detail.enabled ? 'enabled' : 'disabled');
        });
    </script>
</body>
</html>