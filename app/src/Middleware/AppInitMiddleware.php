<?php
namespace WebApp\Middleware;

use Slim\Views\PhpRenderer;

class AppInitMiddleware {
  private $app;
  private $view;
  
  public function __construct(\WebApp\AppService $app, PhpRenderer $view) {
    $this->app = $app;
    $this->view = $view;
  }
  
  public function __invoke($request, $handler) {
    $router = \Slim\Routing\RouteContext::fromRequest($request)->getRouteParser();
    $this->app->router = $router;
    $this->view->addAttribute('router', $router);
    $this->view->addAttribute('templateUrl', "");
    
    $response = $handler->handle($request);
    
    return $response;
  } 
}