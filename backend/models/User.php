<?php
class User {
    private $id;
    private $firstName;
    private $lastName;
    private $email;

    public function __construct($id, $firstName, $lastName, $email) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
    }

    public function getId() {
        return $this->id;
    }

    public function getFullName() {
        return ($this->firstName . " " . $this->lastName);
    }

    public function getEmail() {
        return $this->email;
    }
}
?>
