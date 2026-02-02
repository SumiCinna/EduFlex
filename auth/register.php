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
    <title>Sign Up - EDUFLEX</title>
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
                <h1>Join EDUFLEX</h1>
                <p>Start your programming journey today</p>
            </div>
        </div>
        
        <div class="auth-right">
            <div class="auth-box">
                <h2>Create Account</h2>
                <p class="subtitle">Fill in your details to get started</p>
                
                <div class="alert" id="registerAlert"></div>
                
                <div id="g_id_onload"
                     data-client_id="901503175288-at3v72t37md846h65kqe46rs7st8qgfv.apps.googleusercontent.com"
                     data-context="signup"
                     data-ux_mode="popup"
                     data-callback="handleCredentialResponse"
                     data-auto_prompt="false">
                </div>

                <div class="g_id_signin"
                     data-type="standard"
                     data-shape="rectangular"
                     data-theme="outline"
                     data-text="signup_with"
                     data-size="large"
                     data-logo_alignment="left"
                     data-width="400">
                </div>
                
                <div class="divider">
                    <span>OR</span>
                </div>
                
                <form id="registerForm">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="full_name" placeholder="Enter your full name" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="username" placeholder="Choose a username" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" placeholder="Enter your email" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>User Type</label>
                        <select name="user_type" required>
                            <option value="student">Student</option>
                            <option value="teacher">Teacher</option>
                        </select>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" placeholder="Create a password" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input type="password" name="confirm_password" placeholder="Confirm password" required>
                        </div>
                    </div>
                    
                    <div class="terms">
                        <label>
                            <input type="checkbox" required>
                            <span>I agree to the <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a></span>
                        </label>
                    </div>
                    
                    <button type="submit" class="submit-btn">Create Account</button>
                </form>
                
                <div class="switch-auth">
                    Already have an account? <a href="login.php">Sign In</a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://accounts.google.com/gsi/client"></script>
    <script>
        function handleCredentialResponse(response) {
            console.log('Google credential received:', response);
            
            fetch('google-register.php', {
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
                    const alert = document.getElementById('registerAlert');
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
                    const alert = document.getElementById('registerAlert');
                    alert.className = 'alert error';
                    alert.textContent = 'Server error: ' + text;
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                const alert = document.getElementById('registerAlert');
                alert.className = 'alert error';
                alert.textContent = 'Network error: ' + error.message;
            });
        }
        
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('register.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const alert = document.getElementById('registerAlert');
                if(data.success) {
                    alert.className = 'alert success';
                    alert.textContent = data.message;
                    setTimeout(() => {
                        window.location.href = 'login.php';
                    }, 1500);
                } else {
                    alert.className = 'alert error';
                    alert.textContent = data.message;
                }
            });
        });
    </script>
</body>
</html>