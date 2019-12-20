<?php
namespace WebApp\Controller;

use Dflydev\FigCookies\FigRequestCookies;

class LoginPostCtr {
  private $app;
  private $login;
  private $UserModel;
  
  public function __construct(\WebApp\AppService $app, \WebApp\LoginService $login, \WebApp\Model\UserModel $UserModel) {
    $this->app = $app;
    $this->login = $login;
    $this->UserModel = $UserModel;
  }
  
  public function __invoke($request, $response, $args) {  
    $post = $request->getParsedBody();
    
    // see if a user with the provided credentials exists
    $user = $this->UserModel->getUser($post["U"], $post["P"]);
    if (!$user)
      return $response->withRedirect($this->app->router->urlFor("MESSAGE", [
        "id" => "login-failed"
      ]));

    // if ok, sets the token (in cookie and also in the login service instance)
    $response = $this->login->setAuthToken($user, $response);
    
    return $response->withRedirect($this->app->router->urlFor("PROFILE"));
  }
}