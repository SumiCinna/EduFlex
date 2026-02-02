<?php
class User {
    private $conn;
    private $table = 'users';
    
    public $id;
    public $username;
    public $email;
    public $password;
    public $full_name;
    public $user_type;
    public $profile_image;
    public $created_at;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function register() {
        $query = "INSERT INTO " . $this->table . " 
                  SET username = :username,
                      email = :email,
                      password = :password,
                      full_name = :full_name,
                      user_type = :user_type";
        
        $stmt = $this->conn->prepare($query);
        
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        $this->full_name = htmlspecialchars(strip_tags($this->full_name));
        $this->user_type = htmlspecialchars(strip_tags($this->user_type));
        
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':full_name', $this->full_name);
        $stmt->bindParam(':user_type', $this->user_type);
        
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    public function login($username, $password) {
        $query = "SELECT id, username, email, password, full_name, user_type, profile_image 
                  FROM " . $this->table . " 
                  WHERE (username = :username OR email = :username) 
                  AND is_active = 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            
            if(password_verify($password, $row['password'])) {
                $this->id = $row['id'];
                $this->username = $row['username'];
                $this->email = $row['email'];
                $this->full_name = $row['full_name'];
                $this->user_type = $row['user_type'];
                $this->profile_image = $row['profile_image'];
                
                $this->updateLastLogin();
                
                return true;
            }
        }
        
        return false;
    }
    
    public function emailExists() {
        $query = "SELECT id FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            return true;
        }
        
        return false;
    }
    
    public function usernameExists() {
        $query = "SELECT id FROM " . $this->table . " WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $this->username);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            return true;
        }
        
        return false;
    }
    
    private function updateLastLogin() {
        $query = "UPDATE " . $this->table . " SET last_login = NOW() WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
    }
    
    public function getUserById($id) {
        $query = "SELECT id, username, email, full_name, user_type, profile_image, created_at 
                  FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            return $stmt->fetch();
        }
        
        return false;
    }
}
?>