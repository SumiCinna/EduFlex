<?php
session_start();

if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - EDUFLEX</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .profile-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 30px 20px;
        }
        
        .profile-header {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            margin-bottom: 30px;
        }
        
        .profile-avatar {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #7cb342, #689f38);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: white;
            margin: 0 auto 20px;
        }
        
        .profile-name {
            font-size: 28px;
            color: #333;
            margin-bottom: 5px;
        }
        
        .profile-email {
            color: #666;
            font-size: 16px;
        }
        
        .profile-badge {
            display: inline-block;
            padding: 6px 15px;
            background-color: #7cb342;
            color: white;
            border-radius: 20px;
            margin-top: 10px;
            font-size: 14px;
            text-transform: capitalize;
        }
        
        .profile-section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .section-title {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #7cb342;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .stat-card {
            text-align: center;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        
        .stat-number {
            font-size: 36px;
            font-weight: bold;
            color: #7cb342;
        }
        
        .stat-label {
            color: #666;
            margin-top: 5px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .btn-update {
            background-color: #7cb342;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .btn-update:hover {
            background-color: #689f38;
        }
    </style>
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
            <a href="dashboard.php">Dashboard</a>
            <a href="courses.php">Courses</a>
            <a href="profile.php">Profile</a>
        </div>
        <div class="auth-buttons">
            <a href="auth/logout.php"><button class="btn-signin">Logout</button></a>
        </div>
    </nav>

    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-avatar">
                <?php echo strtoupper(substr($_SESSION['full_name'], 0, 1)); ?>
            </div>
            <div class="profile-name"><?php echo htmlspecialchars($_SESSION['full_name']); ?></div>
            <div class="profile-email"><?php echo htmlspecialchars($_SESSION['email']); ?></div>
            <div class="profile-badge"><?php echo htmlspecialchars($_SESSION['user_type']); ?></div>
        </div>

        <div class="profile-section">
            <h2 class="section-title">Your Statistics</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">0</div>
                    <div class="stat-label">Courses Enrolled</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">0</div>
                    <div class="stat-label">Courses Completed</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">0</div>
                    <div class="stat-label">Problems Solved</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">0</div>
                    <div class="stat-label">Achievements</div>
                </div>
            </div>
        </div>

        <div class="profile-section">
            <h2 class="section-title">Update Profile</h2>
            <form>
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" value="<?php echo htmlspecialchars($_SESSION['full_name']); ?>">
                </div>
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" readonly>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>">
                </div>
                <button type="submit" class="btn-update">Update Profile</button>
            </form>
        </div>
    </div>
</body>
</html>