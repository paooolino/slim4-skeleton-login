<?php
namespace WebApp\Model;

class UserModel {
  private $users;
  
  public function __construct() {
    $this->users = [
      "demo" => [
        "name" => "John",
        "surname" => "Doe",
        "email" => "j.doe@example.com",
        "password" => "demo"
      ]
    ];  
  }
  
  public function getUser($u, $p) {  
    if (!isset($this->users[$u]))
      return false;
    
    if ($this->users[$u]["password"] != $p)
      return false;
      
    return $this->users[$u]["email"];
  }
}