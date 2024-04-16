<?php

class Event{
    private $id;

    private $name;

    private $date;

    private $image;

    public function __construct($id, $name, $date, $image){
        $this->id = $id;
        $this->name = $name;
        $this->date = $date;
        $this->image = $image;
    }
    public function getId(){
        return $this->id;
    }
    public function getName(){
        return $this->name;
    }
    public function getDate(){
        return $this->date;
    }
    public function getImage(){
        return $this->image;
    }

}

?>