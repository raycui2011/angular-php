<?php
class Database{
 
    // specify your own database credentials
    private $host = "127.0.0.1";
    private $db_name = "test";
    private $username = "root";
    private $password = "vagrant";
    public $conn;
    private $dsdn = 'mysql:host=127.0.0.1;dbname=test;port=9306';
    
    // get the database connection
    public function getConnection(){
 
        $this->conn = null;
 
        try{
            $this->conn = new PDO($this->dsdn, $this->username, $this->password);
	    $this->conn->setAttribute( PDO::ATTR_PERSISTENT, TRUE );
	    $this->conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
 
        return $this->conn;
    }
}
?>