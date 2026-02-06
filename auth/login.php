<?php
session_start();

if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: ../dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - EDUFLEX</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/auth.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-left">
            <div class="brand-section">
                <div class="logo">
                    <svg width="50" height="50" viewBox="0 0 40 40">
                        <rect width="40" height="40" rx="5" fill="#7cb342"/>
                        <path d="M10 15 L20 10 L30 15 L20 20 Z" fill="white"/>
                        <path d="M10 20 L10 28 L20 33 L30 28 L30 20" stroke="white" stroke-width="2" fill="none"/>
                    </svg>
                    <span>EDUFLEX</span>
                </div>
                <h1>Welcome Back!</h1>
                <p>Continue your learning journey</p>
            </div>
        </div>
        
        <div class="auth-right">
            <div class="auth-box">
                <h2>Sign In</h2>
                <p class="subtitle">Access your account</p>
                
                <div class="alert" id="loginAlert"></div>
                
                <div id="g_id_onload"
                     data-client_id="901503175288-at3v72t37md846h65kqe46rs7st8qgfv.apps.googleusercontent.com"
                     data-context="signin"
                     data-ux_mode="popup"
                     data-callback="handleCredentialResponse"
                     data-auto_prompt="false">
                </div>

                <div class="g_id_signin"
                     data-type="standard"
                     data-shape="rectangular"
                     data-theme="outline"
                     data-text="signin_with"
                     data-size="large"
                     data-logo_alignment="left"
                     data-width="400">
                </div>
                
                <div class="divider">
                    <span>OR</span>
                </div>
                
                <form id="loginForm">
                    <div class="form-group">
                        <label>Username or Email</label>
                        <input type="text" name="username" placeholder="Enter your username or email" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Enter your password" required>
                    </div>
                    
                    <div class="form-options">
                        <label class="remember">
                            <input type="checkbox" name="remember">
                            <span>Remember me</span>
                        </label>
                        <a href="forgot-password.php" class="forgot">Forgot password?</a>
                    </div>
                    
                    <button type="submit" class="submit-btn">Sign In</button>
                </form>
                
                <div class="switch-auth">
                    Don't have an account? <a href="register.php">Sign Up</a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://accounts.google.com/gsi/client"></script>
    <script>
        function handleCredentialResponse(response) {
            console.log('Google credential received:', response);
            
            fetch('google-login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    credential: response.credential
                })
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.text();
            })
            .then(text => {
                console.log('Raw response:', text);
                try {
                    const data = JSON.parse(text);
                    const alert = document.getElementById('loginAlert');
                    if(data.success) {
                        alert.className = 'alert success';
                        alert.textContent = data.message;
                        setTimeout(() => {
                            window.location.href = '../dashboard.php';
                        }, 1000);
                    } else {
                        alert.className = 'alert error';
                        alert.textContent = data.message;
                    }
                } catch(e) {
                    console.error('JSON parse error:', e);
                    const alert = document.getElementById('loginAlert');
                    alert.className = 'alert error';
                    alert.textContent = 'Server error: ' + text;
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                const alert = document.getElementById('loginAlert');
                alert.className = 'alert error';
                alert.textContent = 'Network error: ' + error.message;
            });
        }
        
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('login-backend.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const alert = document.getElementById('loginAlert');
                if(data.success) {
                    alert.className = 'alert success';
                    alert.textContent = data.message;
                    setTimeout(() => {
                        window.location.href = '../dashboard.php';
                    }, 1000);
                } else {
                    alert.className = 'alert error';
                    alert.textContent = data.message;
                }
            });
        });
    </script>
</body>
</html>