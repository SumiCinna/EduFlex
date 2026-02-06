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
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/dark-mode.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background-color: #f5f7fa;
            color: #2d3748;
            overflow-x: hidden;
        }

        .navbar {
            background-color: #ffffff;
            padding: 20px 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 24px;
            font-weight: 700;
            color: #2d3748;
            font-family: 'Space Mono', monospace;
        }

        .logo-text {
            color: #7cb342;
        }

        .nav-center {
            display: flex;
            align-items: center;
            gap: 40px;
        }

        .nav-center a {
            color: #718096;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
            font-size: 16px;
        }

        .nav-center a:hover {
            color: #7cb342;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .dark-mode-toggle {
            width: 50px;
            height: 26px;
            background-color: #cbd5e0;
            border-radius: 13px;
            position: relative;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .dark-mode-toggle.active {
            background-color: #7cb342;
        }

        .dark-mode-toggle-slider {
            width: 20px;
            height: 20px;
            background-color: white;
            border-radius: 50%;
            position: absolute;
            top: 3px;
            left: 3px;
            transition: transform 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .dark-mode-toggle.active .dark-mode-toggle-slider {
            transform: translateX(24px);
        }

        .btn-signin {
            padding: 10px 24px;
            background-color: transparent;
            color: #7cb342;
            border: 2px solid #7cb342;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 15px;
        }

        .btn-signin:hover {
            background-color: #7cb342;
            color: white;
        }

        .btn-signup {
            padding: 10px 24px;
            background: linear-gradient(135deg, #7cb342 0%, #558b2f 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 15px;
        }

        .btn-signup:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(124, 179, 66, 0.4);
        }

        .hero-section {
            min-height: calc(100vh - 80px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px;
            background: linear-gradient(135deg, #7cb342 0%, #558b2f 100%);
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="2" fill="white" opacity="0.1"/></svg>');
            background-size: 50px 50px;
        }

        .hero-content {
            max-width: 1200px;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .hero-text h1 {
            font-size: 48px;
            font-weight: 700;
            color: white;
            margin-bottom: 24px;
            line-height: 1.2;
        }

        .hero-text p {
            font-size: 18px;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 32px;
            line-height: 1.6;
        }

        .hero-buttons {
            display: flex;
            gap: 16px;
        }

        .btn-hero {
            padding: 16px 32px;
            background-color: white;
            color: #7cb342;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 16px;
        }

        .btn-hero:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .btn-hero-secondary {
            padding: 16px 32px;
            background-color: transparent;
            color: white;
            border: 2px solid white;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 16px;
        }

        .btn-hero-secondary:hover {
            background-color: white;
            color: #7cb342;
        }

        .programming-icons {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            position: relative;
        }

        .icon-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
            transition: all 0.3s;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .icon-card:hover {
            transform: translateY(-10px);
            background: rgba(255, 255, 255, 0.15);
        }

        .icon-card svg {
            width: 60px;
            height: 60px;
        }

        .icon-card span {
            color: white;
            font-weight: 600;
            font-size: 16px;
        }

        .chat-widget {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 1000;
        }

        .chat-button {
            background: linear-gradient(135deg, #7cb342 0%, #558b2f 100%);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 16px 24px;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(124, 179, 66, 0.4);
            font-weight: 600;
            font-size: 15px;
            transition: all 0.2s;
        }

        .chat-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(124, 179, 66, 0.5);
        }

        .chat-box {
            position: fixed;
            bottom: 90px;
            right: 24px;
            width: 350px;
            height: 500px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            display: none;
            flex-direction: column;
            overflow: hidden;
        }

        .chat-box.active {
            display: flex;
        }

        .chat-header {
            background: linear-gradient(135deg, #7cb342 0%, #558b2f 100%);
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
        }

        .chat-close {
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
        }

        .chat-messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            background-color: #f7fafc;
        }

        .chat-message {
            margin-bottom: 16px;
        }

        .chat-message.bot {
            background-color: white;
            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .chat-input-area {
            display: flex;
            padding: 16px;
            background-color: white;
            border-top: 1px solid #e2e8f0;
        }

        .chat-input {
            flex: 1;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            font-family: 'DM Sans', sans-serif;
        }

        .chat-input:focus {
            outline: none;
            border-color: #7cb342;
        }

        .chat-send {
            margin-left: 8px;
            padding: 12px 20px;
            background: linear-gradient(135deg, #7cb342 0%, #558b2f 100%);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
        }

        body.dark-mode .navbar {
            background-color: #1a1f2e;
            border-bottom: 1px solid #2d3748;
        }

        body.dark-mode .logo {
            color: #e2e8f0;
        }

        body.dark-mode .logo-text {
            color: #7cb342;
        }

        body.dark-mode .nav-center a {
            color: #a0aec0;
        }

        body.dark-mode .nav-center a:hover {
            color: #e2e8f0;
        }

        body.dark-mode .hero-section {
            background: linear-gradient(135deg, #558b2f 0%, #33691e 100%);
        }

        body.dark-mode .btn-signin {
            background-color: #2d3748;
            color: #7cb342;
            border: 2px solid #7cb342;
        }

        body.dark-mode .btn-signin:hover {
            background-color: #7cb342;
            color: white;
        }

        body.dark-mode .btn-signup {
            background: linear-gradient(135deg, #7cb342 0%, #558b2f 100%);
        }

        body.dark-mode .btn-hero {
            background-color: white;
            color: #558b2f;
        }

        body.dark-mode .chat-box {
            background-color: #1a1f2e;
        }

        body.dark-mode .chat-messages {
            background-color: #0f1419;
        }

        body.dark-mode .chat-message.bot {
            background-color: #2d3748;
            color: #e2e8f0;
            border-color: #4a5568;
        }

        body.dark-mode .chat-input-area {
            background-color: #1a1f2e;
            border-top-color: #2d3748;
        }

        body.dark-mode .chat-input {
            background-color: #2d3748;
            border-color: #4a5568;
            color: #e2e8f0;
        }

        @media (max-width: 1024px) {
            .hero-content {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .programming-icons {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 20px 30px;
            }

            .nav-center {
                display: none;
            }

            .hero-section {
                padding: 40px 30px;
            }

            .hero-text h1 {
                font-size: 32px;
            }

            .hero-buttons {
                flex-direction: column;
            }

            .programming-icons {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <svg width="40" height="40" viewBox="0 0 40 40">
                <rect width="40" height="40" rx="8" fill="url(#grad1)"/>
                <defs>
                    <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#7cb342;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#558b2f;stop-opacity:1" />
                    </linearGradient>
                </defs>
                <path d="M10 15 L20 10 L30 15 L20 20 Z" fill="white"/>
                <path d="M10 20 L10 28 L20 33 L30 28 L30 20" stroke="white" stroke-width="2" fill="none"/>
            </svg>
            <span>EDU<span class="logo-text">FLEX</span></span>
        </div>
        
        <div class="nav-right">
            <div class="dark-mode-toggle" id="darkModeToggle">
                <div class="dark-mode-toggle-slider"></div>
            </div>
            <button class="btn-signin" onclick="window.location.href='auth/login.php'">Sign In</button>
            <button class="btn-signup" onclick="window.location.href='auth/register.php'">Sign Up</button>
        </div>
    </nav>

    <section class="hero-section">
        <div class="hero-content">
            <div class="hero-text">
                <h1>A Learning Management System Platform for Programming Education for the Computer Studies Department</h1>
                <p>Comprehensive learning platform designed for Computer Studies students. Learn, practice, and excel in programming with interactive courses and real-time feedback.</p>
                <div class="hero-buttons">
                    <button class="btn-hero" onclick="window.location.href='auth/register.php'">Get Started Free</button>
                    <button class="btn-hero-secondary" onclick="window.location.href='auth/login.php'">Sign In</button>
                </div>
            </div>
            <div class="programming-icons">
                <div class="icon-card">
                    <svg width="60" height="60" viewBox="0 0 60 60">
                        <circle cx="30" cy="30" r="25" fill="#61DAFB"/>
                        <circle cx="30" cy="30" r="10" fill="white"/>
                        <ellipse cx="30" cy="30" rx="25" ry="10" fill="none" stroke="white" stroke-width="3"/>
                        <ellipse cx="30" cy="30" rx="25" ry="10" fill="none" stroke="white" stroke-width="3" transform="rotate(60 30 30)"/>
                        <ellipse cx="30" cy="30" rx="25" ry="10" fill="none" stroke="white" stroke-width="3" transform="rotate(120 30 30)"/>
                    </svg>
                    <span>React</span>
                </div>
                <div class="icon-card">
                    <svg width="60" height="60" viewBox="0 0 60 60">
                        <rect x="5" y="5" width="50" height="50" rx="5" fill="#E44D26"/>
                        <text x="30" y="42" font-size="32" font-weight="bold" fill="white" text-anchor="middle">5</text>
                    </svg>
                    <span>HTML5</span>
                </div>
                <div class="icon-card">
                    <svg width="60" height="60" viewBox="0 0 60 60">
                        <circle cx="30" cy="30" r="25" fill="#FFD43B"/>
                        <text x="30" y="40" font-size="28" font-weight="bold" fill="#3776AB" text-anchor="middle">Py</text>
                    </svg>
                    <span>Python</span>
                </div>
                <div class="icon-card">
                    <svg width="60" height="60" viewBox="0 0 60 60">
                        <rect x="10" y="10" width="40" height="40" rx="5" fill="#777BB4"/>
                        <text x="30" y="40" font-size="28" font-weight="bold" fill="white" text-anchor="middle">php</text>
                    </svg>
                    <span>PHP</span>
                </div>
                <div class="icon-card">
                    <svg width="60" height="60" viewBox="0 0 60 60">
                        <circle cx="30" cy="30" r="25" fill="#3C873A"/>
                        <text x="30" y="38" font-size="24" font-weight="bold" fill="white" text-anchor="middle">JS</text>
                    </svg>
                    <span>JavaScript</span>
                </div>
                <div class="icon-card">
                    <svg width="60" height="60" viewBox="0 0 60 60">
                        <rect x="10" y="10" width="40" height="40" rx="5" fill="#00599C"/>
                        <text x="30" y="40" font-size="28" font-weight="bold" fill="white" text-anchor="middle">C++</text>
                    </svg>
                    <span>C++</span>
                </div>
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
                <div class="chat-message bot">
                    Welcome to EDUFLEX! How can we help you today?
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
            userMessageDiv.className = 'chat-message';
            userMessageDiv.style.cssText = 'background: linear-gradient(135deg, #7cb342 0%, #558b2f 100%); color: white; padding: 12px 16px; border-radius: 12px; margin-left: auto; max-width: 80%;';
            userMessageDiv.textContent = message;
            chatMessages.appendChild(userMessageDiv);
            
            chatInput.value = '';
            chatMessages.scrollTop = chatMessages.scrollHeight;
            
            setTimeout(function() {
                const botMessageDiv = document.createElement('div');
                botMessageDiv.className = 'chat-message bot';
                botMessageDiv.textContent = 'Thanks for your message! Our support team will respond shortly.';
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
    </script>
</body>
</html>