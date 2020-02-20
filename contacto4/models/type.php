<?php

abstract class Type{
    protected $id;
    protected $icon;
    protected $descripcion;

    public function getId(){
        return $this->id;
    }
    public function setId($id){
        $this->id=$id;
    }
    public function getIcon(){
        return $this->icon;
    }
    public function setIcon($icon){
        $this->icon=$icon;
    }

    public function getDescripcion(){
        return $this->descripcion;
    }
    public function setDescripcion($descripcion){
        $this->descripcion=$descripcion;
    }

    public function toJson(){
        return json_encode(array(
            'id'=> $this->id ,
            'icon'=> $this->icon,
            'descripcion' => $this->descripcion
        ));
    }
}

?>