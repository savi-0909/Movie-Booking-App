<?php
class User {
    private $conn;
    private $table = "users";

    public $id;
    public $username;
    public $password;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register() {
        $query = "INSERT INTO " . $this->table . " (username, password) VALUES (?, ?)";

        if ($stmt = $this->conn->prepare($query)) {
            $this->username = htmlspecialchars(strip_tags($this->username));
            $this->password = htmlspecialchars(strip_tags($this->password));

            $hashed_password = password_hash($this->password, PASSWORD_BCRYPT);
            
            $stmt->bind_param("ss", $this->username, $hashed_password);

            if ($stmt->execute()) {
                return true;
            } else {
                error_log("Execute error: " . $stmt->error);
                return false;
            }
        } else {
            error_log("Prepare error: " . $this->conn->error);
            return false;
        }
    }

    public function login() {
        $query = "SELECT * FROM " . $this->table . " WHERE username = ?";

        if ($stmt = $this->conn->prepare($query)) {
            $this->username = htmlspecialchars(strip_tags($this->username));

            $stmt->bind_param("s", $this->username);

            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                    if (password_verify($this->password, $user['password'])) {
                        return true;
                    } else {
                        error_log("Password verification failed for user: " . $this->username);
                        return false;
                    }
                } else {
                    error_log("No user found with username: " . $this->username);
                    return false;
                }
            } else {
                error_log("Execute error: " . $stmt->error);
                return false;
            }
        } else {
            error_log("Prepare error: " . $this->conn->error);
            return false;
        }
    }
}
?>
