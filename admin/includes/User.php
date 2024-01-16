<?php
class User{
    /*properties*/
    public $id;
    public $username;
    public $password;
    public $first_name;
    public $last_name;
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
    /*CRUD*/
    public function create(){
        global $database;
        //tabel naam en de properties
        $table = static::$table_name;
        $properties= $this->get_properties();
        //filter de id property als het bestaat
        if(array_key_exists('id',$properties)){
            unset($properties['id']);
        }
        //escape van the values tegen sql injection
        $escaped_values = array_map([$database,'escape_string'], $properties);

        //placeholders maken
        //result: [?,?,?,?]
        $placeholders = array_fill(0, count($properties), '?');

        //create a string of field names seperated by commas
        //result: username,password,first_name,last_name
        $fields_string = implode(',', array_keys($properties));

        //create a string of types representing data type of each value
        //result: 'ssss'
        $types_string = "";
        foreach ($properties as $value){
            if(is_int($value)){
                $types_string .="i";
            }elseif (is_float($value)){
                $types_string .= "d";
            }else{
                $types_string .= "s";
            }
        }
        //create prepared statement
        $sql = "INSERT INTO $table ($fields_string) VALUES (" . implode(',', $placeholders) .")";
        //execute the statement
        $database->query($sql, $escaped_values);
    }
    /*methods*/

    public static function find_this_query($sql, $values = []){
        global $database;
        $result = $database->query($sql,$values);
        $the_object_array = [];
        while($row = mysqli_fetch_assoc($result)){
            $the_object_array[] = self::instantie($row);
        }
        return $the_object_array;
    }

    public static function find_all_users(){
        $result = self::find_this_query("SELECT * FROM users");
        return $result;
    }
    public static function find_user_by_id($user_id){
        $result = self::find_this_query("SELECT * FROM users where id=?",[$user_id]);
        return !empty($result) ? array_shift($result): false;
    }
    public static function find_user_by_name($last_name){
        global $database;
        // Opkuisen/sanitize van vreemde karakters
        $last_name = $database->escape_string($last_name);
        // Gebruik van de LIKE operator voor gedeeltelijke overeenkomsten
        $result = $database->query("SELECT * FROM users WHERE last_name LIKE '%" . $last_name . "%'");
        return $result;
    }
    public static function instantie($result){
        $the_object = new self;
        foreach($result as $the_attribute => $value){
            if($the_object->has_the_attribute($the_attribute)){
                $the_object->$the_attribute = $value;
            }
        }
        return $the_object;
    }
    public function has_the_attribute($the_attribute){
        $object_properties = get_object_vars($this);
        return array_key_exists($the_attribute, $object_properties);
    }
    /*verify user*/
    public static function verify_user($username, $password){
        global $database;
        $username = $database->escape_string($username);
        $password= $database->escape_string($password);

        //SELECT * FROM users WHERE username = ? and password = ? LIMIT 1

        $sql = "SELECT * FROM users WHERE ";
        $sql .= "username = ? ";
        $sql .= "AND password = ? ";
        $sql .= "LIMIT 1";

        $the_result_array = self::find_this_query($sql,[$username,$password]);
        return !empty($the_result_array) ? array_shift($the_result_array) : false;
    }
}
?>