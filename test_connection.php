<?php
require_once 'config/database.php';

echo "<h2>Testing Database Connection</h2>";

try {
    $database = new Database();
    $db = $database->connect();
    
    if($db) {
        echo "<p style='color: green;'>✓ Database connection successful!</p>";
        
        $tables = ['users', 'courses', 'lessons', 'problems', 'submissions', 'enrollments', 'achievements', 'sessions'];
        
        echo "<h3>Checking Tables:</h3>";
        foreach($tables as $table) {
            $query = "SHOW TABLES LIKE '$table'";
            $stmt = $db->prepare($query);
            $stmt->execute();
            
            if($stmt->rowCount() > 0) {
                echo "<p style='color: green;'>✓ Table '$table' exists</p>";
            } else {
                echo "<p style='color: red;'>✗ Table '$table' does not exist</p>";
            }
        }
        
    } else {
        echo "<p style='color: red;'>✗ Database connection failed!</p>";
    }
    
} catch(Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p>If you see errors above, please:</p>";
echo "<ol>";
echo "<li>Make sure MySQL/MariaDB is running</li>";
echo "<li>Check database credentials in config/database.php</li>";
echo "<li>Import database_schema.sql into your database</li>";
echo "<li>Verify the database 'eduflex_db' exists</li>";
echo "</ol>";
?>