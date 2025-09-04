<?php
class Booking {
    private $conn;
    private $table = "bookings";

    public $id;
    public $movie;
    public $date;
    public $time;
    public $name;
    public $email;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
       
        $query = "INSERT INTO " . $this->table . " (movie_id, date, time, name, email) VALUES (?, ?, ?, ?, ?)";

      
        $stmt = $this->conn->prepare($query);

        if ($stmt) {
          
            $stmt->bind_param("sssss", $this->movie, $this->date, $this->time, $this->name, $this->email);

          
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
}
?>
