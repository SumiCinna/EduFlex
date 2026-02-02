<?php
session_start();

if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDUFLEX - Learning Management System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <svg width="40" height="40" viewBox="0 0 40 40">
                <rect width="40" height="40" rx="5" fill="#7cb342"/>
                <path d="M10 15 L20 10 L30 15 L20 20 Z" fill="white"/>
                <path d="M10 20 L10 28 L20 33 L30 28 L30 20" stroke="white" stroke-width="2" fill="none"/>
            </svg>
            <span>EDU<span class="logo-text">FLEX</span></span>
        </div>
        <div class="nav-links">
            <a href="index.php">Tutorials</a>
            <a href="about.php">About Us</a>
        </div>
        <div class="auth-buttons">
            <button class="btn-signin" id="signinBtn">Sign In</button>
            <button class="btn-signup" id="signupBtn">Sign Up</button>
        </div>
    </nav>

    <section class="hero-section">
        <div class="hero-content">
            <div class="programming-icons">
                <div class="icon" style="transform: rotate(-15deg);">
                    <svg width="60" height="60" viewBox="0 0 60 60">
                        <circle cx="30" cy="30" r="25" fill="#61DAFB"/>
                        <circle cx="30" cy="30" r="10" fill="white"/>
                        <ellipse cx="30" cy="30" rx="25" ry="10" fill="none" stroke="white" stroke-width="3"/>
                        <ellipse cx="30" cy="30" rx="25" ry="10" fill="none" stroke="white" stroke-width="3" transform="rotate(60 30 30)"/>
                        <ellipse cx="30" cy="30" rx="25" ry="10" fill="none" stroke="white" stroke-width="3" transform="rotate(120 30 30)"/>
                    </svg>
                </div>
                <div class="icon" style="transform: rotate(8deg);">
                    <svg width="60" height="60" viewBox="0 0 60 60">
                        <path d="M5 5 L25 5 L30 15 L25 25 L5 25 Z" fill="#E44D26"/>
                        <path d="M15 10 L15 20 L10 15 Z" fill="white"/>
                        <text x="35" y="20" font-size="20" font-weight="bold" fill="#E44D26">5</text>
                    </svg>
                </div>
                <div class="icon" style="transform: rotate(-12deg);">
                    <svg width="60" height="60" viewBox="0 0 60 60">
                        <circle cx="20" cy="30" r="12" fill="#FFD43B"/>
                        <circle cx="40" cy="30" r="12" fill="#3776AB"/>
                        <text x="30" y="50" font-size="16" font-weight="bold" fill="#3776AB" text-anchor="middle">PY</text>
                    </svg>
                </div>
                <div class="icon" style="transform: rotate(10deg);">
                    <svg width="60" height="60" viewBox="0 0 60 60">
                        <rect x="10" y="10" width="40" height="40" rx="5" fill="#777BB4"/>
                        <text x="30" y="40" font-size="28" font-weight="bold" fill="white" text-anchor="middle">php</text>
                    </svg>
                </div>
                <div class="icon" style="transform: rotate(-8deg);">
                    <svg width="60" height="60" viewBox="0 0 60 60">
                        <circle cx="30" cy="30" r="25" fill="#3C873A"/>
                        <text x="30" y="38" font-size="24" font-weight="bold" fill="white" text-anchor="middle">C</text>
                    </svg>
                </div>
            </div>
            
            <div class="hero-text">
                <h1>A Learning Management System Platform for Programming Education for the Computer Science Department</h1>
                <button class="btn-hero" id="heroSigninBtn">Sign in</button>
            </div>
        </div>
    </section>

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
                <div style="padding: 10px; background-color: #f0f0f0; border-radius: 5px; margin-bottom: 10px;">
                    Welcome! How can we help you today?
                </div>
            </div>
            <div class="chat-input-area">
                <input type="text" class="chat-input" id="chatInput" placeholder="Type your message...">
                <button class="chat-send" id="chatSend">Send</button>
            </div>
        </div>
    </div>

    <div class="modal" id="signinModal">
        <div class="modal-content">
            <span class="modal-close">&times;</span>
            <h2>Sign In</h2>
            <div class="alert" id="signinAlert"></div>
            <form id="signinForm">
                <div class="form-group">
                    <label for="signin-username">Username or Email</label>
                    <input type="text" id="signin-username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="signin-password">Password</label>
                    <input type="password" id="signin-password" name="password" required>
                </div>
                <button type="submit" class="btn-submit">Sign In</button>
                <div class="switch-form">
                    Don't have an account? <a href="#" onclick="switchToSignup()">Sign Up</a>
                </div>
            </form>
        </div>
    </div>

    <div class="modal" id="signupModal">
        <div class="modal-content">
            <span class="modal-close">&times;</span>
            <h2>Sign Up</h2>
            <div class="alert" id="signupAlert"></div>
            <form id="signupForm">
                <div class="form-group">
                    <label for="signup-fullname">Full Name</label>
                    <input type="text" id="signup-fullname" name="full_name" required>
                </div>
                <div class="form-group">
                    <label for="signup-username">Username</label>
                    <input type="text" id="signup-username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="signup-email">Email</label>
                    <input type="email" id="signup-email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="signup-usertype">User Type</label>
                    <select id="signup-usertype" name="user_type" required>
                        <option value="student">Student</option>
                        <option value="teacher">Teacher</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="signup-password">Password</label>
                    <input type="password" id="signup-password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="signup-confirm">Confirm Password</label>
                    <input type="password" id="signup-confirm" name="confirm_password" required>
                </div>
                <button type="submit" class="btn-submit">Sign Up</button>
                <div class="switch-form">
                    Already have an account? <a href="#" onclick="switchToSignin()">Sign In</a>
                </div>
            </form>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>