<?php
namespace WebApp\Controller;
use Slim\Views\PhpRenderer;

class HomeCtr {
  private $view;
  
  public function __construct(PhpRenderer $view) {
    $this->view = $view;
  }
  
  public function __invoke($request, $response, $args) {  
    return $this->view->render($response, 'home.php', [
      // add data here
    ]);
  }
}