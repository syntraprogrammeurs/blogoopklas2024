<?php
class User extends Db_Object{
    /*properties*/
    public $id;
    public $username;
    public $password;
    public $first_name;
    public $last_name;
    public $deleted_at;
    protected static $table_name = 'users';

    /*get properties method*/
    //object variabelen retourneren als een array.
    public function get_properties(){
        return [
            'id'=> $this->id,
            'username' => $this->username,
            'password'=> $this->password,
            'first_name'=> $this->first_name,
            'last_name'=> $this->last_name
        ];
    }

    public static function find_user_by_name($last_name){
        global $database;
        // Opkuisen/sanitize van vreemde karakters
        $last_name = $database->escape_string($last_name);
        // Gebruik van de LIKE operator voor gedeeltelijke overeenkomsten
        $result = $database->query("SELECT * FROM users WHERE last_name LIKE '%" . $last_name . "%'");
        return $result;
    }

    /*verify user*/
    public static function verify_user($username, $password){
        global $database;
        $username = $database->escape_string($username);
        $password= $database->escape_string($password);

        //SELECT * FROM users WHERE username = ? and password = ? LIMIT 1

        $sql = "SELECT * FROM " . self::$table_name . " WHERE ";
        $sql .= "username = ? ";
        $sql .= "AND password = ? ";
        $sql .= "LIMIT 1";

        $the_result_array = self::find_this_query($sql,[$username,$password]);
        return !empty($the_result_array) ? array_shift($the_result_array) : false;
    }
}
?>