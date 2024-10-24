<?php

class Database {
    private $pdo;
    public $username;
    public $password;

    public function __construct() {
        $this->pdo = new PDO("sqlite:" . __DIR__ . "/database.db");
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    

    public function Login() {
        $query = "SELECT id, username, password FROM Users WHERE username = :username AND password = :password";
        $stmt = $this->pdo->prepare($query);
        if ($stmt === false) {
            $errorInfo = $this->pdo->errorInfo();
            echo "Error preparing query: " . $errorInfo[2];
            return false;
        }
        $stmt->execute(['username'=>$this->username, 'password'=> $this->password]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function Register() {
        // Check for duplicate username
        $checkQuery = "SELECT COUNT(*) FROM Users WHERE Username = :username";
        $checkStmt = $this->pdo->prepare($checkQuery);
        if ($checkStmt === false) {
            $errorInfo = $this->pdo->errorInfo();
            die("Error preparing check query: " . $errorInfo[2]);
            
        }
        $checkStmt->execute(['username' => $this->username]);
        $count = $checkStmt->fetchColumn();
        
        if ($count > 0) {
            die("Duplicate username");
            
        }
        // Insert new user
        $query = "INSERT INTO Users (Username, Password) VALUES (:username, :password)";
        $stmt = $this->pdo->prepare($query);
        if ($stmt === false) {
            $errorInfo = $this->pdo->errorInfo();
            die("Error preparing query: " . $errorInfo[2]);
        }
        return $stmt->execute(['username' => $this->username, 'password' => $this->password]);
    }

    public function Order($userId, $foodItem) {
        $query = "INSERT INTO Orders (user_id, food_item) VALUES (:user_id, :food_item)";
        $stmt = $this->pdo->prepare($query);
        if ($stmt === false) {
            $errorInfo = $this->pdo->errorInfo();
            die("Error preparing query: " . $errorInfo[2]);
        }
        return $stmt->execute([
            'user_id' => $userId,
            'food_item' => $foodItem
        ]);
    }
    public function getOrdersByUser($userId) {
        $query = "SELECT * FROM Orders WHERE user_id = :user_id";
        $stmt = $this->pdo->prepare($query);
        if ($stmt === false) {
            $errorInfo = $this->pdo->errorInfo();
            die("Error preparing query: " . $errorInfo[2]);
        }
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
