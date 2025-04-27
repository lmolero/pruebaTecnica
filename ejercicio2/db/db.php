<?php
class Connect {
    private $host = 'localhost';
    private $user = 'root';
    private $password = '';
    private $database = 'clinic_appointments';
    private $connection;

    public function __construct() {
        $this->connection = new mysqli($this->host, $this->user, $this->password, $this->database);

        if ($this->connection->connect_error) {
            die('Connection failed: ' . $this->connection->connect_error);
        }
    }

    public function getConnection() {
        return $this->connection;
    }
}
?>