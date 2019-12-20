<?php
namespace WebApp\Model;

class MessageModel {
  
  private $messages;
  private $defaultMessage;
  
  public function __construct() {
    $this->messages = [
      "login-failed" => [
        "title" => "Error",
        "body" => "Invalid credentials."
      ]
    ];
    
    $this->defaultMessage = [
      "title" => "Unknown error",
      "body" => "There was an unhandled error."
    ];
  }
  
  public function get($id) {  
    if (!isset($this->messages[$id]))
      return $this->defaultMessage;
    return $this->messages[$id];
  }
}