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
    <title>Courses - EDUFLEX</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .courses-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px 20px;
        }
        
        .page-header {
            margin-bottom: 30px;
        }
        
        .page-header h1 {
            color: #333;
            font-size: 32px;
            margin-bottom: 10px;
        }
        
        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }
        
        .course-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        
        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        
        .course-thumbnail {
            width: 100%;
            height: 180px;
            background: linear-gradient(135deg, #7cb342, #689f38);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: white;
        }
        
        .course-content {
            padding: 20px;
        }
        
        .course-title {
            font-size: 20px;
            color: #333;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .course-description {
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
            line-height: 1.5;
        }
        
        .course-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        
        .course-difficulty {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .difficulty-beginner {
            background-color: #e8f5e9;
            color: #2e7d32;
        }
        
        .difficulty-intermediate {
            background-color: #fff3e0;
            color: #ef6c00;
        }
        
        .difficulty-advanced {
            background-color: #ffebee;
            color: #c62828;
        }
        
        .enroll-btn {
            background-color: #7cb342;
            color: white;
            padding: 8px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        
        .enroll-btn:hover {
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

    <div class="courses-container">
        <div class="page-header">
            <h1>Available Courses</h1>
            <p>Browse our programming courses and start learning today</p>
        </div>

        <div class="courses-grid">
            <div class="course-card">
                <div class="course-thumbnail">🐍</div>
                <div class="course-content">
                    <div class="course-title">Python Programming</div>
                    <div class="course-description">
                        Learn Python from basics to advanced concepts. Perfect for beginners.
                    </div>
                    <div class="course-meta">
                        <span class="course-difficulty difficulty-beginner">Beginner</span>
                        <button class="enroll-btn">Enroll Now</button>
                    </div>
                </div>
            </div>

            <div class="course-card">
                <div class="course-thumbnail">☕</div>
                <div class="course-content">
                    <div class="course-title">Java Programming</div>
                    <div class="course-description">
                        Master object-oriented programming with Java fundamentals.
                    </div>
                    <div class="course-meta">
                        <span class="course-difficulty difficulty-intermediate">Intermediate</span>
                        <button class="enroll-btn">Enroll Now</button>
                    </div>
                </div>
            </div>

            <div class="course-card">
                <div class="course-thumbnail">🌐</div>
                <div class="course-content">
                    <div class="course-title">Web Development</div>
                    <div class="course-description">
                        HTML, CSS, and JavaScript - Build modern responsive websites.
                    </div>
                    <div class="course-meta">
                        <span class="course-difficulty difficulty-beginner">Beginner</span>
                        <button class="enroll-btn">Enroll Now</button>
                    </div>
                </div>
            </div>

            <div class="course-card">
                <div class="course-thumbnail">⚛️</div>
                <div class="course-content">
                    <div class="course-title">React Development</div>
                    <div class="course-description">
                        Build interactive UIs with React, the popular JavaScript library.
                    </div>
                    <div class="course-meta">
                        <span class="course-difficulty difficulty-intermediate">Intermediate</span>
                        <button class="enroll-btn">Enroll Now</button>
                    </div>
                </div>
            </div>

            <div class="course-card">
                <div class="course-thumbnail">🗄️</div>
                <div class="course-content">
                    <div class="course-title">Database Management</div>
                    <div class="course-description">
                        Learn SQL and database design principles from scratch.
                    </div>
                    <div class="course-meta">
                        <span class="course-difficulty difficulty-beginner">Beginner</span>
                        <button class="enroll-btn">Enroll Now</button>
                    </div>
                </div>
            </div>

            <div class="course-card">
                <div class="course-thumbnail">🔧</div>
                <div class="course-content">
                    <div class="course-title">Data Structures</div>
                    <div class="course-description">
                        Master essential data structures and algorithms for coding interviews.
                    </div>
                    <div class="course-meta">
                        <span class="course-difficulty difficulty-advanced">Advanced</span>
                        <button class="enroll-btn">Enroll Now</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>