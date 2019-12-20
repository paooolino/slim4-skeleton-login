<?php
use Slim\Views\PhpRenderer;

$containerBuilder->addDefinitions([
  // Services
  PhpRenderer::class => function($c) {
    $templatePath = __DIR__ . '/../templates/default';
    return new PhpRenderer($templatePath);
  }
]);

