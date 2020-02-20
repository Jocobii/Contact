<?php
require_once('contacttype.php');
require_once('type.php');
require_once('phonetype.php');
require_once('emailtype.php');
require_once('mysqlconnection.php');
require_once('exceptions/recordnotfoundexception.php');
require_once('phone.php');
require_once('email.php');
require_once('C:\xampp\htdocs\contacto4\config/config.php');
class Contact{
    private $id;
    private $photo;
    private $firstName;
    private $lastName;
    private $type;

    public function getId() {
        return $this->id; 
    }
    public function setId($id) { 
        $this->id = $id; 
    }
    public function getPhoto() {
        return $this->photo; 
    }
    public function setPhoto($photo) { 
        $this->photo = $photo; 
    }
    public function getFirstName() {
        return $this->firstName;
    }
    public function setFirstName($firstName) { 
        $this->firstName = $firstName; 
    }
    public function getLastName() {
        return $this->lastName; 
    }
    public function setLastName($lastName) { 
        $this->lastName = $lastName; 
    }
    
    public function getType() {
        return $this->type; 
    }
    public function setType($type) { 
        $this->type=$type; 
    }
    public function __construct(){

        if(func_num_args() == 0){
            $this->id = 0;
            $this->photo='';
            $this->firstName = '';
            $this->lastName = '';
            $this->type = new ContactType();
        }
        if (func_num_args() == 1){
            $arguments = func_get_args();//read arguments
            $query = 'select c.id,c.photo,c.firstName,c.lastName,c.idType,ct.icon,ct.description
            from contacts as c join contacttypes as ct on c.idType=ct.id where c.id=?'; //query
            $connection= MySqlConnection::getConnection();
            $command = $connection->prepare($query);
            $command->bind_param('i', $arguments[0]); //tipo dato, parametro
            $command->bind_result($id,$photo,$firstName,$lastName,$idtype,$icon,$descripcion); //bind result
            $command->execute(); //execute query
            //read result
            if ($command->fetch()){ 
               //pass values of fields to attributes
              $this->id = $id;
              $this->photo=$photo;
              $this->firstName=$firstName;
              $this->lastName=$lastName;
              $this->type = new ContactType($idtype,$icon,$descripcion);
            }else{
                throw new RecordNotFoundException($arguments[0]);
            }
             mysqli_stmt_close($command);  //close statament
            $connection->close();  //close connection
            }
        
        if(func_num_args() == 5){

            $arguments = func_get_args();
            $this->id=$arguments[0];
            $this->photo=$arguments[1];
            $this->firstName=$arguments[2];
            $this->lastName=$arguments[3];
            $this->type=$arguments[4];
        }
    }    
    public function toJsonHeader(){
        return json_encode(array(
            'id'=>$this->id,
            'photo'=>Config::getFillerUrl('contactphotos').$this->photo,
            'firstName'=>$this->firstName,
            'lastName'=>$this->lastName,
            'type'=>json_decode($this->type->toJson())
            ));
    }
    
    public function toJson(){
        $phoneNumbers = array();
       foreach($this->getPhoneNumbers() as $p){
            array_push($phoneNumbers,json_decode($p->toJson()));
       }
       $emails = array();
       foreach($this->getEmails() as $e){
            array_push($emails,json_decode($e->toJson()));
       }
    
    
        return json_encode(array(
            'id'=>$this->id,
            'photo'=>Config::getFillerUrl('contactphotos').$this->photo,        
            'firstName'=>$this->firstName,
            'lastName'=>$this->lastName,
            'type'=>json_decode($this->type->toJson()),
            'phoneNumbers'=>$phoneNumbers,
            'emails'=>$emails
            ));
    }
    public function getPhoneNumbers(){
        $list = array();
        $query = 'select pn.areacode,pn.number,pt.id,pt.icon,pt.description 
         from contacts as c join phonenumbers as pn on c.id=pn.idcontact join phonetypes as pt on pt.id=pn.idType
         where c.id=?';
  
         $connection= MySqlConnection::getConnection();
         $command = $connection->prepare($query);
         $command->bind_param('i', $this->id);
         $command->bind_result($areacode,$number,$idtype,$icon,$descripcion);//bind result
         $command->execute(); //execute query
         //read result
  
         while ($command->fetch()){ //fetch es ir y traer
            //populate list
            array_push($list, new Phone($areacode, $number, new PhoneType($idtype,$icon,$descripcion)));
         }
          
         mysqli_stmt_close($command);  //close statament
         $connection->close();
         
          return $list;
    }
    public function getEmails(){
        $list = array();
        $query = 'select e.idemail,e.correo,et.id,et.icon,et.description
        from email as e join emailtype et on e.emailtype=et.id where e.idcontact=?';
  
         $connection= MySqlConnection::getConnection();
         $command = $connection->prepare($query);
         $command->bind_param('i', $this->id);
         $command->bind_result($idemail,$correo,$idtype,$icon,$descripcion);//bind result
         $command->execute(); //execute query
         //read result
         while ($command->fetch()){ //fetch es ir y traer
            //populate list
            array_push($list, new Email($idemail, $correo, new EmailType($idtype,$icon,$descripcion)));
         }   
         mysqli_stmt_close($command);  //close statament
         $connection->close();
          return $list;
    }
    public static function getAll(){
            $list = array(); //create list
            $query = 'select c.id,c.photo,c.firstName,c.lastName,c.idType,ct.icon,ct.description
            from contacts as c join contacttypes as ct on c.idType=ct.id ';
            $connection= MySqlConnection::getConnection();
            $command = $connection->prepare($query);    
            $command->bind_result($id,$photo, $firstName,$lastName,$idtype,$icon,$description);//bind result
            $command->execute(); //execute query
            //read result
     
            while ($command->fetch()){ //fetch es ir y traer
               //populate list
               array_push($list, new Contact($id,$photo, $firstName,$lastName, new ContactType($idtype,$icon,$description)));
            }
            mysqli_stmt_close($command);  //close statament
            $connection->close();  //close connection
            return $list; 
    }
    public static function getAllToJson(){
            $jsonArray= array();
            foreach (self::getAll() as $item) {
                # self hace referencia a un metodo estatico de esta clase
                array_push($jsonArray, json_decode($item->toJsonHeader()));
            }
     
            return json_encode($jsonArray);
        }
}

?>