<?php
require_once('emailtype.php');
class Email{

    private $id;
    private $correo;
    private $type;


    public function __construct(){

        if(func_num_args() == 0){
            $this->id = 0;
            $this->correo = '';
            $this->type = new EmailType();
        }

        if(func_num_args() == 3){

            $arguments = func_get_args();
            $this->id = $arguments[0];
            $this->correo = $arguments[1];
            $this->type =  $arguments[2];
        }
    }
    public function toJson(){
        return json_encode(array(
            'id'=>$this->id,
            'correo'=>$this->correo,
            'type'=>json_decode($this->type->toJson())
        ));
    }
   
     
    
}

?>