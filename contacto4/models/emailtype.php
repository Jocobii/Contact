<?php
require_once('C:\xampp\htdocs\contacto4\config/config.php');
require_once('type.php');
require_once('mysqlconnection.php');
require_once('exceptions/recordnotfoundexception.php');
class EmailType extends Type{
    public function __construct(){
        if(func_num_args()==0){
            $this->id='';
            $this->icon='';
            $this->descripcion='';
        }
        if (func_num_args() == 1){
        $arguments = func_get_args();//read arguments
        $query = 'select id,icon,description from emailtype where id=? '; //query
        $connection= MySqlConnection::getConnection();
        $command = $connection->prepare($query);
        $command->bind_param('s', $arguments[0]); //tipo dato, parametro
        $command->bind_result($id,$icon, $descripcion); //bind result
        $command->execute(); //execute query
        //read result
        if ($command->fetch()){ 
           //pass values of fields to attributes
          $this->id = $id;
          $this->icon= Config::getFillerUrl('icons').$icon;
          $this->descripcion = $descripcion;
        }else{
            throw new RecordNotFoundException($arguments[0]);
        }
         mysqli_stmt_close($command);  //close statament
        $connection->close();  //close connection
        }
        if(func_num_args()==3){
            //copy arguments to an array
 
            $arguments = func_get_args();
            $this->id=$arguments[0];
            $this->icon=Config::getFillerUrl('icons').$arguments[1];
            $this->descripcion=$arguments[2];
        }
    }
     //gets a list of all the contact types
   public static function getAll(){
    $list = array(); //create list
    $query = 'select id,icon,description from emailtype ';
    $connection= MySqlConnection::getConnection();
    $command = $connection->prepare($query);
    $command->bind_result($id,$icon, $descripcion);//bind result
    $command->execute(); //execute query
    //read result

    while ($command->fetch()){ //fetch es ir y traer
       //populate list
       array_push($list, new EmailType($id,$icon, $descripcion));
    }
    
    mysqli_stmt_close($command);  //close statament
    $connection->close();  //close connection
    return $list; 
}

//get a list of the contact type in JSON array

public static function getAllToJson(){
    $jsonArray= array();
    foreach (self::getAll() as $item) {
        //self hace referencia a un metodo estatico de esta clase
        array_push($jsonArray, json_decode($item->toJson()));
    }

    return json_encode($jsonArray);
}
}
?>