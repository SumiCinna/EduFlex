<?php
// Debug Registration Issues
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>EDUFLEX Debug Page</h2>";
echo "<hr>";

// Test 1: Check if PHP is working
echo "<h3>✓ Test 1: PHP is working</h3>";
echo "<p>PHP Version: " . phpversion() . "</p>";

// Test 2: Check database connection
echo "<h3>Test 2: Database Connection</h3>";
require_once 'config/database.php';

try {
    $database = new Database();
    $db = $database->connect();
    
    if($db) {
        echo "<p style='color: green;'>✓ Database connection successful!</p>";
        echo "<p>Database: " . DB_NAME . "</p>";
        
        // Test 3: Check if users table exists
        echo "<h3>Test 3: Check Users Table</h3>";
        $query = "DESCRIBE users";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $columns = $stmt->fetchAll();
        
        if(count($columns) > 0) {
            echo "<p style='color: green;'>✓ Users table exists with " . count($columns) . " columns</p>";
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>Column Name</th><th>Type</th></tr>";
            foreach($columns as $col) {
                echo "<tr><td>" . $col['Field'] . "</td><td>" . $col['Type'] . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color: red;'>✗ Users table does not exist!</p>";
        }
        
        // Test 4: Try to insert a test user
        echo "<h3>Test 4: Test User Registration</h3>";
        echo "<form method='POST' action='auth/register.php' style='border: 1px solid #ddd; padding: 20px; max-width: 400px;'>";
        echo "<p><input type='text' name='full_name' placeholder='Full Name' value='Test User' required style='width: 100%; padding: 8px; margin: 5px 0;'></p>";
        echo "<p><input type='text' name='username' placeholder='Username' value='testuser' required style='width: 100%; padding: 8px; margin: 5px 0;'></p>";
        echo "<p><input type='email' name='email' placeholder='Email' value='test@example.com' required style='width: 100%; padding: 8px; margin: 5px 0;'></p>";
        echo "<p><select name='user_type' style='width: 100%; padding: 8px; margin: 5px 0;'><option value='student'>Student</option><option value='teacher'>Teacher</option></select></p>";
        echo "<p><input type='password' name='password' placeholder='Password' value='password123' required style='width: 100%; padding: 8px; margin: 5px 0;'></p>";
        echo "<p><input type='password' name='confirm_password' placeholder='Confirm Password' value='password123' required style='width: 100%; padding: 8px; margin: 5px 0;'></p>";
        echo "<p><button type='submit' style='width: 100%; padding: 10px; background: #7cb342; color: white; border: none; cursor: pointer;'>Test Registration</button></p>";
        echo "</form>";
        
    } else {
        echo "<p style='color: red;'>✗ Database connection failed!</p>";
        echo "<p><strong>Possible issues:</strong></p>";
        echo "<ul>";
        echo "<li>MySQL service is not running</li>";
        echo "<li>Database 'eduflex_db' does not exist</li>";
        echo "<li>Wrong username or password</li>";
        echo "</ul>";
    }
    
} catch(Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h3>Quick Fix Steps:</h3>";
echo "<ol>";
echo "<li><strong>Check MySQL is running:</strong> Look for MySQL in your XAMPP/WAMP control panel</li>";
echo "<li><strong>Create database:</strong> Go to <a href='http://localhost/phpmyadmin' target='_blank'>phpMyAdmin</a> and create 'eduflex_db'</li>";
echo "<li><strong>Import tables:</strong> In phpMyAdmin, select 'eduflex_db' and import 'database_schema.sql'</li>";
echo "<li><strong>Check credentials:</strong> Edit config/database.php if your MySQL password is not empty</li>";
echo "</ol>";

echo "<p><a href='index.php' style='padding: 10px 20px; background: #7cb342; color: white; text-decoration: none; border-radius: 5px;'>Back to Home</a></p>";
?>
