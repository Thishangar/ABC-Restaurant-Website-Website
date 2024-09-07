<?php
class Database {
    private $connection;

    public function __construct($host, $username, $password, $database) {
        
        $this->connection = @mysql_connect($host, $username, $password) or die("Cannot be connected to the database.");
        @mysql_select_db($database, $this->connection) or die("Cannot select the database.");
    }

    public function getConnection() {
        return $this->connection;
    }

    public function __destruct() {
       
        if ($this->connection) {
            mysql_close($this->connection);
        }
    }
}


$database = new Database("localhost", "root", "", "Restaurant");
?>
