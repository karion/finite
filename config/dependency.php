<?php

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Finite\Loader\ArrayLoader;
use Finite\StateMachine\StateMachine;
use karion\Finite\Repository\ObjectRepository;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

$container = $app->getContainer();


$container['view'] = function ($container) {
    $view = new Twig(__DIR__ . "/../template", [
        'cache' => __DIR__ . "/../cache",
        'auto_reload' => true
    ]);
    $view->addExtension(new TwigExtension(
        $container['router'],
        $container['request']->getUri()
    ));

    return $view;
};

$container['db'] = function ($container) {
    $configuration = new Configuration();
    $config = $container['settings']['db'];

    $path = realpath(__DIR__ . DIRECTORY_SEPARATOR . $config['path']);

    $connectionParams = array(
        'path' => $path,
        'driver' => 'pdo_sqlite',
    );

    return DriverManager::getConnection($connectionParams, $configuration);
};


$container['object_repo'] = function ($container) {
    return new ObjectRepository(
        $container['db']
    );
};


$container['state_machine'] = function ($container) {
   
    $stateMachine = new StateMachine;
    $loader       = new ArrayLoader($container['settings']['state_machine']);

    $loader->load($stateMachine);
    return $stateMachine;
};
