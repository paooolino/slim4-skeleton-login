<?php
namespace WebApp\Controller;

use Slim\Views\PhpRenderer;

class MsgCtr {
  private $view;
  private $MessageModel;
  
  public function __construct(PhpRenderer $view, \WebApp\Model\MessageModel $MessageModel) {
    $this->view = $view;
    $this->MessageModel = $MessageModel;
  }
  
  public function __invoke($request, $response, $args) {  
    $message = $this->MessageModel->get($args["id"]);
    return $this->view->render($response, 'message.php', [
      "message" => $message
    ]);
  }
}