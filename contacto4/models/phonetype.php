<?php
require_once('mysqlconnection.php');
require_once('type.php');
require_once('exceptions/recordnotfoundexception.php');
class PhoneType extends Type{
    public function __construct(){
        if(func_num_args()==0){
            $this->id='';
            $this->icon='';
            $this->descripcion='';
        }
        if(func_num_args()==1){
            $arguments=func_get_args();
            $query='select id,icon,description from phonetypes where id=?';
            $connection = MysqlConnection::getConnection();
            $command = $connection->prepare($query);
            $command->bind_param('i',arguments[0]);
            $command->bind_result($id,$icon,$descripcion);
            $command->execute();

            if($command->fetch()){
                $this->id=$id;
                $this->icon=Config::getFillerUrl('icons').$icon;
                $this->descripcion=$descripcion;
            }else{
                throw new RecordNotFoundException($arguments[0]);
            }
            mysql_close_stmt($command);
            $connection->close();
        }
        if(func_num_args() == 3){
            $arguments = func_get_args();
            $this->id = $arguments[0];
            $this->icon=Config::getFillerUrl('icons').$arguments[1];
            $this->descripcion = $arguments[2];
        }
    }
    public static function getAll(){
        $list = array();//create list
   
        $query = 'select id,icon,description from phonetypes ';
           $connection = MysqlConnection::getConnection();
           $command = $connection->prepare($query);
           $command->bind_result($id,$icon, $descripcion);
           $command->execute();
   
           while($command->fetch()){
               array_push($list, new PhoneType($id,$icon, $descripcion));
           }
           mysqli_stmt_close($command);
           $connection->close();
        return $list;//Regresar list
       }
   
       // 
   
       public static function getAlltoJson(){
           $jsonArray = array();
       foreach(self::getAll() as $item){
           array_push($jsonArray, json_decode($item->toJson()));
       }
       return json_encode($jsonArray);
       }
      
}

?>