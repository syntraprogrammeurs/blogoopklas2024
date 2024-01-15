<?php
    require_once("config.php");
    class Database{
      /* properties of variabelen*/
        public $connection;
        /* methods of functions*/
        public function open_db_connection(){
            $this->connection = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
            if(mysqli_connect_errno()){
                printf("Connectie mislukt: %s\n", mysqli_connect_errno());
                exit();
            }
        }
        /*constructors*/
        function __construct(){
            $this->open_db_connection();
        }
    }
    $database = new Database();
?>