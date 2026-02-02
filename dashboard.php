<?php
session_start();

if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: index.php');
    exit();
}

$user_name = $_SESSION['full_name'];
$user_type = $_SESSION['user_type'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - EDUFLEX</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px 20px;
        }
        
        .welcome-section {
            background: linear-gradient(135deg, #7cb342 0%, #689f38 100%);
            color: white;
            padding: 40px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .welcome-section h1 {
            font-size: 36px;
            margin-bottom: 10px;
        }
        
        .welcome-section p {
            font-size: 18px;
            opacity: 0.9;
        }
        
        .user-info {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .user-info h2 {
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #7cb342;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .info-item {
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #7cb342;
        }
        
        .info-label {
            font-weight: bold;
            color: #666;
            margin-bottom: 5px;
        }
        
        .info-value {
            color: #333;
            font-size: 18px;
        }
        
        .actions-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        
        .action-card {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            transition: all 0.3s;
        }
        
        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        
        .action-card h3 {
            color: #333;
            margin-bottom: 15px;
        }
        
        .action-card p {
            color: #666;
            margin-bottom: 20px;
        }
        
        .action-btn {
            background-color: #7cb342;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .action-btn:hover {
            background-color: #689f38;
        }
        
        .logout-btn {
            background-color: #d32f2f;
            color: white;
            padding: 10px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }
        
        .logout-btn:hover {
            background-color: #b71c1c;
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

    <div class="dashboard-container">
        <div class="welcome-section">
            <h1>Welcome back, <?php echo htmlspecialchars($user_name); ?>!</h1>
            <p>Continue your learning journey with EDUFLEX</p>
        </div>

        <div class="user-info">
            <h2>Your Account Information</h2>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Full Name</div>
                    <div class="info-value"><?php echo htmlspecialchars($_SESSION['full_name']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Username</div>
                    <div class="info-value"><?php echo htmlspecialchars($_SESSION['username']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value"><?php echo htmlspecialchars($_SESSION['email']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Account Type</div>
                    <div class="info-value" style="text-transform: capitalize;"><?php echo htmlspecialchars($_SESSION['user_type']); ?></div>
                </div>
            </div>
        </div>

        <div class="actions-section">
            <div class="action-card">
                <h3>Browse Courses</h3>
                <p>Explore our wide range of programming courses and tutorials</p>
                <a href="courses.php" class="action-btn">View Courses</a>
            </div>
            
            <div class="action-card">
                <h3>My Learning</h3>
                <p>Continue with your enrolled courses and track your progress</p>
                <a href="my-courses.php" class="action-btn">My Courses</a>
            </div>
            
            <div class="action-card">
                <h3>Practice Problems</h3>
                <p>Solve coding challenges and improve your programming skills</p>
                <a href="problems.php" class="action-btn">Start Practicing</a>
            </div>
            
            <?php if($user_type === 'teacher' || $user_type === 'admin'): ?>
            <div class="action-card">
                <h3>Create Course</h3>
                <p>Create and manage your own programming courses</p>
                <a href="create-course.php" class="action-btn">Create Course</a>
            </div>
            <?php endif; ?>
        </div>

        <div style="text-align: center; margin-top: 40px;">
            <a href="auth/logout.php"><button class="logout-btn">Logout</button></a>
        </div>
    </div>
</body>
</html>