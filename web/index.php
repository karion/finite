<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

require __DIR__.'/../vendor/autoload.php';


$config = require __DIR__.'/../config/config.php';

$app = new App(['settings' => $config]);

//$app
require __DIR__.'/../config/dependency.php';

$app->get('/object', function (Request $request, Response $response) {

    $objectRepo = $this->get('object_repo');
    
    $objects = $objectRepo->findAll();
    
    
    return $this->view->render($response, 'list.html.twig', [
        'objects' => $objects
    ]);
    
})->setName('object_list');

$app->post('/object', function (Request $request, Response $response) {

    $post = $request->getParsedBody();
    
    $object = new karion\Finite\Entity\Object();
    $object->setName(filter_var($post['name'], FILTER_SANITIZE_STRING));
    
    $objectRepo = $this->get('object_repo');
    $object = $objectRepo->save($object);
    
    $objectLog = new karion\Finite\Entity\ObjectLog([
        'object_id' => $object->getId(),
        'become' => $object->getState()
    ]);
    $this->get('object_log_repo')->add($objectLog);
    
    
    $router = $this->router;
    return $response->withRedirect($router->pathFor('object', ['id' => $object->getId()]));
    
})->setName('object_new');;

$app->get('/object/{id}', function (Request $request, Response $response, $args) {

    $id = $args['id'];
    
    $objectRepo = $this->get('object_repo');
    
    $object = $objectRepo->findById($id);
    
    if (!$object) {
        throw new Slim\Exception\NotFoundException($request, $response);
    }

    $stateMachine = $this->get('state_machine');

    /* @var $stateMachine Finite\StateMachine\StateMachine */
    $stateMachine->setObject($object);
    $stateMachine->initialize();

    $transitions = $stateMachine->getCurrentState()->getTransitions();

    $logRepo = $this->get('object_log_repo');
    $logs = $logRepo->findByObjectId($object->getId());
    
    return $this->view->render($response, 'object.html.twig', [
        'object' => $object,
        'transitions' => $transitions,
        'logs' => $logs
    ]);
})->setName('object');


$app->get('/object/{id}/{transition}', function (Request $request, Response $response, $args) {
    $id = $args['id'];
    $transition = $args['transition'];
    
    $objectRepo = $this->get('object_repo');
    
    $object = $objectRepo->findById($id);
    
    if (!$object) {
        throw new Slim\Exception\NotFoundException($request, $response);
    }
    
    $stateMachine = $this->get('state_machine');

    
    /* @var $stateMachine Finite\StateMachine\StateMachine */
    $stateMachine->setObject($object);
    
    $stateMachine->initialize();
    
    try {
        $stateMachine->apply($transition);
    } catch (\Finite\Exception\StateException $e) {
        echo $e->getMessage();
        die();
    }
    
    $objectRepo->save($object);
    
    $router = $this->router;
    
    return $response->withRedirect($router->pathFor('object', ['id' => $object->getId()]));
    
})->setName('object_transition');

$app->run();
