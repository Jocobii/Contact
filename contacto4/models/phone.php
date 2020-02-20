<?php

require_once('phonetype.php');

class Phone{

    private $areaCode;
    private $number;
    private $type;


    public function __construct(){

        if(func_num_args() == 0){
            $this->areaCode = 0;
            $this->number = 0;
            $this->type = new PhoneType();
        }

        if(func_num_args() == 3){

            $arguments = func_get_args();
            $this->areaCode = $arguments[0];
            $this->number = $arguments[1];
            $this->type =  $arguments[2];
        }
    }
    public function toJson(){
        return json_encode(array(
            'areaCode'=>$this->areaCode,
            'number'=>$this->number,
            'type'=>json_decode($this->type->toJson())
        ));
    }
   
     
    
}

?>