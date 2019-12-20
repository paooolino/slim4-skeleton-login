<?php

namespace spec\Slim;

use PhpSpec\ObjectBehavior;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Slim\Factory\AppFactory;
use Slim\Psr7\Environment;
use Slim\Psr7\Factory\UriFactory;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Request;
use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\Cookie;
use Prophecy\Prophet;

class AppSpec extends ObjectBehavior {
  
  function let() { 
    // set container and dependencies
    $containerBuilder = new \DI\ContainerBuilder();
    $containerBuilder = new \DI\ContainerBuilder();
    require __DIR__ . '/../../app/dependencies.php';
    $container = $containerBuilder->build();
    AppFactory::setContainer($container);
    
    $this->beConstructedThrough(function() { return AppFactory::create(); });
    
    $this->addErrorMiddleware(true, false, false);
    $this->addRoutingMiddleware();
    
    $app = $this;
    require __DIR__ . '/../../app/middleware.php';
    require __DIR__ . '/../../app/routes.php';

    $this->addRoutingMiddleware();    
  }
  
  private function do_request($uri, $method, $post_params=[], $cookies=[]) {  
    $env = Environment::mock([
      'SCRIPT_NAME' => '/index.php',
      'REQUEST_URI' => $uri,
      'REQUEST_METHOD' => $method
    ]);
    $uri = (new UriFactory())->createFromGlobals($env);
    $headers = Headers::createFromGlobals($env);
    $serverParams = $env;
    $body = (new StreamFactory())->createStream();
    $req = new Request($method, $uri, $headers, $cookies, $serverParams, $body);
    if (!empty($post_params)) {
      $req = $req->withParsedBody($post_params);
    }
    if (!empty($cookies)) {
      foreach($cookies as $k => $v) {
        $req = FigRequestCookies::set($req, Cookie::create($k, $v));
      }
    }
    
    $response = $this->handle($req);
    
    return $response;
  }
  
  function it_shows_form_in_home() {
    $res = $this->do_request('/', 'GET');

    $res->getBody()->__toString()->shouldContain("<form");
  }
  
  function it_should_redirect_to_home_if_not_logged() {
    $res = $this->do_request('/profile', 'GET');

    $res->getStatusCode()->shouldBe(302);
    $res->getHeaderLine('Location')->shouldBe('/');
  }
  
  function it_should_authenticate_and_redirect() {
    $res = $this->do_request('/login', 'POST', [
      "U" => "demo",
      "P" => "demo"
    ]);
    
    $res->getStatusCode()->shouldBe(302);
    $res->getHeaderLine('Set-Cookie')->shouldStartWith('token=');
    $res->getHeaderLine('Location')->shouldBe('/profile');
    
    $cookie = FigResponseCookies::get($res->getWrappedObject(), 'token');
    $cookies = [];
    $cookies[$cookie->getName()] = $cookie->getValue();
    
    $res = $this->do_request('/profile', 'GET', [], $cookies);
    
    $res->getStatusCode()->shouldBe(200);
    $res->getBody()->__toString()->shouldContain("<h1>User profile</h1>");
  }
  
  function it_should_not_authenticate_wrong_credentials() {
    $res = $this->do_request('/login', 'POST', [
      "U" => "demo",
      "P" => "wrong password"
    ]);
    
    $res->getStatusCode()->shouldBe(302);
    $res->getHeaderLine('Location')->shouldBe('/message/login-failed');
  }
  
  function it_should_redirect_home_if_invalid_cookie() {
    $res = $this->do_request('/profile', 'GET', [], [
      "token" => "malformed token value"
    ]);
    
    //echo $res->getBody()->getWrappedObject();
    $res->getStatusCode()->shouldBe(302);
    $res->getHeaderLine('Location')->shouldBe('/');
  }
}
