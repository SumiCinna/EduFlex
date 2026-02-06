<?php
session_start();

if(!isset($_SESSION['pending_user_id'])) {
    header('Location: auth/login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Role - EDUFLEX</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/role-select.css">
</head>
<body>
    <div class="role-container">
        <div class="logo">
            <svg width="50" height="50" viewBox="0 0 40 40">
                <rect width="40" height="40" rx="5" fill="#7cb342"/>
                <path d="M10 15 L20 10 L30 15 L20 20 Z" fill="white"/>
                <path d="M10 20 L10 28 L20 33 L30 28 L30 20" stroke="white" stroke-width="2" fill="none"/>
            </svg>
            <span>EDUFLEX</span>
        </div>
        
        <h1>Are you?</h1>
        <p class="subtitle">Check your role</p>
        
        <div class="alert" id="roleAlert"></div>
        
        <form id="roleForm">
            <div class="role-options">
                <label class="role-option">
                    <input type="radio" name="user_type" value="teacher" required>
                    <div class="role-card">
                        <div class="radio-circle"></div>
                        <span>Teacher/Professor</span>
                    </div>
                </label>
                
                <label class="role-option">
                    <input type="radio" name="user_type" value="student" required>
                    <div class="role-card">
                        <div class="radio-circle"></div>
                        <span>Student</span>
                    </div>
                </label>
            </div>
            
            <button type="submit" class="continue-btn">Continue</button>
        </form>
    </div>
    
    <script>
        document.getElementById('roleForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('auth/set-role.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const alert = document.getElementById('roleAlert');
                if(data.success) {
                    alert.className = 'alert success';
                    alert.textContent = data.message;
                    setTimeout(() => {
                        window.location.href = 'dashboard.php';
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