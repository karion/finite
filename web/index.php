<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require __DIR__.'/../vendor/autoload.php';


$config = require __DIR__.'/../config/config.php';

$app = new \Slim\App(['settings' => $config]);

//$app
$container = $app->getContainer();

$container['db'] = function($container) {
    $configuration = new \Doctrine\DBAL\Configuration();
    $config = $container['settings']['db'];

    $path = realpath(__DIR__ . DIRECTORY_SEPARATOR . $config['path']);
//    $connectionParams = [
//        'url' => 'sqlite:///'.$path
//    ];

    $connectionParams = array(
    'path' => $path,
    'user' => 'user',
    'password' => 'secret',

    'driver' => 'pdo_sqlite',
);

    return \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $configuration);
};




$app->get('/a', function (Request $request, Response $response) {

    $db = $this->get('db');
//    $statement = $db->prepare('Select 1');
    $statement = $db->prepare('SELECT * FROM object');
    $statement->execute();
    $objects = $statement->fetchAll();

    echo "<pre>";
    var_dump($objects);
    die();
    $response->getBody()->write("Hello, $name");

    return $response;
});
$app->run();