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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do - EDUFLEX</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/todo.css">
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
                <a href="todo.php" class="menu-item active">
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
                    <h1 class="page-title">To-Do List ✓</h1>
                    <p class="page-subtitle">Organize your tasks and stay productive</p>
                </div>

                <div class="todo-container">
                    <div class="stats-row">
                        <div class="stat-box">
                            <div class="stat-number" id="totalTasks">0</div>
                            <div class="stat-label">Total Tasks</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-number" id="completedTasks">0</div>
                            <div class="stat-label">Completed</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-number" id="pendingTasks">0</div>
                            <div class="stat-label">Pending</div>
                        </div>
                    </div>

                    <div class="todo-input-section">
                        <div class="todo-input-wrapper">
                            <input type="text" class="todo-input" id="todoInput" placeholder="Add a new task...">
                            <button class="add-todo-btn" id="addTodoBtn">Add Task</button>
                        </div>
                    </div>

                    <div class="todo-tabs">
                        <div class="todo-tab active" data-filter="all">All</div>
                        <div class="todo-tab" data-filter="active">Active</div>
                        <div class="todo-tab" data-filter="completed">Completed</div>
                    </div>

                    <div class="todo-list" id="todoList">
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                            </svg>
                            <h3>No tasks yet</h3>
                            <p>Add your first task to get started!</p>
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

        let todos = JSON.parse(localStorage.getItem('todos')) || [];
        let currentFilter = 'all';

        function saveTodos() {
            localStorage.setItem('todos', JSON.stringify(todos));
        }

        function updateStats() {
            const total = todos.length;
            const completed = todos.filter(t => t.completed).length;
            const pending = total - completed;
            
            document.getElementById('totalTasks').textContent = total;
            document.getElementById('completedTasks').textContent = completed;
            document.getElementById('pendingTasks').textContent = pending;
        }

        function renderTodos() {
            const todoList = document.getElementById('todoList');
            let filteredTodos = todos;
            
            if (currentFilter === 'active') {
                filteredTodos = todos.filter(t => !t.completed);
            } else if (currentFilter === 'completed') {
                filteredTodos = todos.filter(t => t.completed);
            }
            
            if (filteredTodos.length === 0) {
                todoList.innerHTML = `
                    <div class="empty-state">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                        </svg>
                        <h3>No tasks here</h3>
                        <p>${currentFilter === 'all' ? 'Add your first task to get started!' : 'No ' + currentFilter + ' tasks'}</p>
                    </div>
                `;
            } else {
                todoList.innerHTML = filteredTodos.map(todo => `
                    <div class="todo-item">
                        <div class="todo-checkbox ${todo.completed ? 'checked' : ''}" onclick="toggleTodo(${todo.id})"></div>
                        <div class="todo-text ${todo.completed ? 'completed' : ''}">${escapeHtml(todo.text)}</div>
                        <div class="todo-actions">
                            <span class="todo-delete" onclick="deleteTodo(${todo.id})">×</span>
                        </div>
                    </div>
                `).join('');
            }
            
            updateStats();
        }

        function addTodo() {
            const input = document.getElementById('todoInput');
            const text = input.value.trim();
            
            if (text === '') return;
            
            const todo = {
                id: Date.now(),
                text: text,
                completed: false
            };
            
            todos.push(todo);
            saveTodos();
            input.value = '';
            renderTodos();
        }

        function toggleTodo(id) {
            const todo = todos.find(t => t.id === id);
            if (todo) {
                todo.completed = !todo.completed;
                saveTodos();
                renderTodos();
            }
        }

        function deleteTodo(id) {
            todos = todos.filter(t => t.id !== id);
            saveTodos();
            renderTodos();
        }

        document.getElementById('addTodoBtn').addEventListener('click', addTodo);
        document.getElementById('todoInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                addTodo();
            }
        });

        document.querySelectorAll('.todo-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.todo-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                currentFilter = this.dataset.filter;
                renderTodos();
            });
        });

        renderTodos();
    </script>
</body>
</html>