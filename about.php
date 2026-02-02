<?php
session_start();
$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - EDUFLEX</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .about-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 50px 20px;
        }
        
        .about-section {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .about-section h1 {
            color: #333;
            font-size: 36px;
            margin-bottom: 20px;
        }
        
        .about-section h2 {
            color: #7cb342;
            font-size: 28px;
            margin-top: 30px;
            margin-bottom: 15px;
        }
        
        .about-section p {
            color: #666;
            line-height: 1.8;
            font-size: 16px;
            margin-bottom: 15px;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }
        
        .feature-card {
            padding: 30px;
            background-color: #f8f9fa;
            border-radius: 10px;
            text-align: center;
        }
        
        .feature-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
        
        .feature-title {
            font-size: 20px;
            color: #333;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .feature-description {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
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
            <a href="index.php">Tutorials</a>
            <a href="about.php">About Us</a>
        </div>
        <div class="auth-buttons">
            <?php if($isLoggedIn): ?>
                <a href="dashboard.php"><button class="btn-signin">Dashboard</button></a>
            <?php else: ?>
                <a href="index.php"><button class="btn-signin">Sign In</button></a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="about-container">
        <div class="about-section">
            <h1>About EDUFLEX</h1>
            <p>
                EDUFLEX is a comprehensive Learning Management System (LMS) designed specifically for programming education 
                in Computer Science departments. Our platform combines modern teaching methodologies with interactive 
                learning experiences to help students master programming skills.
            </p>
            
            <h2>Our Mission</h2>
            <p>
                We aim to provide an accessible, engaging, and effective learning environment for computer science students. 
                Through our platform, students can learn at their own pace, practice coding problems, and receive immediate 
                feedback on their submissions.
            </p>
            
            <h2>What We Offer</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">📚</div>
                    <div class="feature-title">Comprehensive Courses</div>
                    <div class="feature-description">
                        Access a wide range of programming courses from beginner to advanced levels
                    </div>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">💻</div>
                    <div class="feature-title">Interactive Coding</div>
                    <div class="feature-description">
                        Practice with real coding problems and get instant feedback on your solutions
                    </div>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">🎓</div>
                    <div class="feature-title">Expert Instructors</div>
                    <div class="feature-description">
                        Learn from experienced educators and industry professionals
                    </div>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <div class="feature-title">Progress Tracking</div>
                    <div class="feature-description">
                        Monitor your learning journey with detailed analytics and achievements
                    </div>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">🤝</div>
                    <div class="feature-title">Community Support</div>
                    <div class="feature-description">
                        Connect with fellow learners and get help when you need it
                    </div>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">🏆</div>
                    <div class="feature-title">Achievements</div>
                    <div class="feature-description">
                        Earn badges and certificates as you complete courses and challenges
                    </div>
                </div>
            </div>
            
            <h2>Why Choose EDUFLEX?</h2>
            <p>
                Unlike traditional learning platforms, EDUFLEX is specifically tailored for Computer Science education. 
                We understand the unique challenges students face when learning to code, and our platform is designed 
                to address these challenges effectively.
            </p>
            
            <p>
                Our code execution environment supports multiple programming languages including Python, Java, C++, and 
                JavaScript, allowing students to practice in the language they're learning. Every problem comes with 
                detailed explanations, test cases, and sample solutions to guide your learning.
            </p>
            
            <h2>Get Started Today</h2>
            <p>
                Join thousands of students who are already improving their programming skills with EDUFLEX. 
                Whether you're just starting out or looking to advance your skills, we have the right course for you.
            </p>
        </div>
    </div>
</body>
</html>