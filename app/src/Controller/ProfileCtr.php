<?php
namespace WebApp\Controller;

class ProfileCtr {
  private $view;
  private $UserModel;
  
  public function __construct(\Slim\Views\PhpRenderer $view, \WebApp\Model\UserModel $UserModel) {
    $this->view = $view;
    $this->UserModel = $UserModel;
  }
  
  public function __invoke($request, $response, $args) {  
    return $this->view->render($response, 'profile.php', [
      // add data here
    ]);
  }
}