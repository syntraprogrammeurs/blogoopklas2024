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

        // Tabelnaam ophalen uit de statische eigenschap van de klasse
        $table = static::$table_name;

        // Eigenschappen van de klasse ophalen als een array
        $properties = $this->get_properties();

        // Verwijder de 'id' eigenschap uit de array indien aanwezig
        // omdat de 'id' meestal automatisch door de database wordt gegenereerd
        if(array_key_exists('id', $properties)){
            unset($properties['id']);
        }

        // De waarden van de eigenschappen beschermen tegen SQL-injectie
        // door elk element door de 'escape_string' functie van het databaseobject te halen
        $escaped_values = array_map([$database, 'escape_string'], $properties);

        // Placeholder voor elke waarde in de query genereren (bijv. '?')
        // Dit wordt gebruikt in een prepared statement
        $placeholders = array_fill(0, count($properties), '?');

        // Een string van veldnamen maken, gescheiden door komma's
        // Dit representeert de kolomnamen in de database
        $fields_string = implode(',', array_keys($properties));

        // Een string van typen maken die het datatypen van elke waarde vertegenwoordigen
        // 'i' voor integers, 'd' voor doubles, en 's' voor strings
        $types_string = "";
        foreach ($properties as $value){
            if(is_int($value)){
                $types_string .= "i";
            } elseif (is_float($value)){
                $types_string .= "d";
            } else {
                $types_string .= "s";
            }
        }

        // Een prepared statement maken voor het invoegen van gegevens
        // Dit verhoogt de veiligheid door SQL-injectie te voorkomen
        $sql = "INSERT INTO $table ($fields_string) VALUES (" . implode(',', $placeholders) . ")";

        // Het prepared statement uitvoeren met de geëscapeerde waarden
        $database->query($sql, $escaped_values);
    }
    public function update(){
        global $database;
        // Tabelnaam ophalen uit de statische eigenschap van de klasse
        $table = static::$table_name;

        // Eigenschappen van de klasse ophalen als een array
        $properties = $this->get_properties();
        unset($properties['id']);

        // De waarden van de eigenschappen beschermen tegen SQL-injectie
        // door elk element door de 'escape_string' functie van het databaseobject te halen
        $escaped_values = array_map([$database, 'escape_string'], $properties);
        $escaped_values[] = $this->id;

        // Placeholder voor elke waarde in de query genereren (bijv. '?')
        // Dit wordt gebruikt in een prepared statement
        $placeholders = array_fill(0, count($properties), '?');

        //maakt een string van de veldnamen en placeholders gescheiden
        // van elkaar.
        $fields_string="";
        $i=0;

        foreach($properties as $key => $value){
            if($i>0){
                $fields_string .=", ";
            }
            $fields_string .= "$key = $placeholders[$i]";
            $i++;
        }
       //prepared statement
        $sql = "UPDATE $table SET $fields_string WHERE id= ?";

        //execute statement
        // Het prepared statement uitvoeren met de geëscapeerde waarden
        $database->query($sql, $escaped_values);
    }

    public function delete(){
        global $database;
        $table = static::$table_name;
        //sql injection prevention
        $escaped_id = $database->escape_string($this->id);
        //create van prepared statement
        $sql = "DELETE FROM $table where id = ?";
        //$params
        $params = [$escaped_id];

        //execute
        $database->query($sql,$params);
    }

    public function save(){
        return isset($this->id) ? $this->update() : $this->create();
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

    public static function find_all(){
        $result = self::find_this_query("SELECT * FROM " . self::$table_name . " ");
        return $result;
    }
    public static function find_by_id($id){
        $result = self::find_this_query("SELECT * FROM " . self::$table_name ." where id=?",[$id]);
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

        $sql = "SELECT * FROM " . self::$table_name . " WHERE ";
        $sql .= "username = ? ";
        $sql .= "AND password = ? ";
        $sql .= "LIMIT 1";

        $the_result_array = self::find_this_query($sql,[$username,$password]);
        return !empty($the_result_array) ? array_shift($the_result_array) : false;
    }
}
?>