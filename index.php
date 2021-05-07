<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'vendor/autoload.php';

use Relay\Relay;
require_once('Controller/MarvelController.php');

$request = Zend\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

$loader = new Twig_Loader_Filesystem('.');
$twig = new \Twig_Environment($loader, array(
    'debug' => true,
    'cache' => false,
));

$router = new Aura\Router\RouterContainer();
$map = $router->getMap();

$map->get('todo.list', '/', function ($request) use ($twig) {
    $tasks = [
        [
            'status' => true,
            'description' => 'welcome to my api',
            'done' => true
        ],
    ];
    $response = new Zend\Diactoros\Response\JsonResponse($tasks);
    return $response;
});

$map->get('allcharacters.list', '/get-characters', function () {
    $controller = new MarvelController();
    $controller->getCharacters();
    
    $heroe = [
        [
            'status' => true,
            'description' => 'Request ok',
            'data' => $controller->getCharacters()
        ],
    ];

    $response = new Zend\Diactoros\Response\JsonResponse($heroe);
    return $response;
});

$map->get('colaborators.list', '/marvel/colaborators/{id}', function ($request) {
    $id = $request->getAttribute('id');
    $controller = new MarvelController();

    $heroe = [
        [
            'status' => true,
            'description' => 'Request ok',
            'data' => $controller->getColaborators($id)
        ],
    ];

    $response = new Zend\Diactoros\Response\JsonResponse($heroe);
    return $response;
});

$map->get('characters.list', '/marvel/characters/{id}', function ($request) {
    $id = $request->getAttribute('id');
    $controller = new MarvelController();

    $heroe = [
        [
            'status' => true,
            'description' => 'Request ok',
            'data' => $controller->getParners($id)
        ],
    ];

    $response = new Zend\Diactoros\Response\JsonResponse($heroe);
    return $response;
});


$relay = new Relay([
    new Middlewares\AuraRouter($router),
    new Middlewares\RequestHandler()
]);

$response = $relay->handle($request);

foreach ($response->getHeaders() as $name => $values) {
    foreach ($values as $value) {
        header(sprintf('%s: %s', $name, $value), false);
    }
}
echo $response->getBody();