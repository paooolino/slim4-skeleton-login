<?php
namespace WebApp\Middleware;

class AuthMiddleware {
  private $app;
  private $login;
  
  public function __construct(\WebApp\AppService $app, \WebApp\LoginService $login) {
    $this->app = $app;
    $this->login = $login;
  }
  
  public function __invoke($request, $handler) {
    $response = $handler->handle($request);
    
    $result = $this->login->checkAuth($request);
    if (!$result) {
      return $response->withRedirect($this->app->router->urlFor("HOME"));
    }
    
    return $response;
  } 
}