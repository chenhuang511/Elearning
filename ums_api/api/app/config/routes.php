<?php
$router = new \Phalcon\Mvc\Router(false);
$api = new \Phalcon\Mvc\Router\Group();
$api->setPrefix('/api');
$api->add('/:controller/:action/:params', [
    'controller' => 1,
    'action' => 2,
    'params' => 3,
]);
$api->add('/:controller/:action', [
    'controller' => 1,
    'action' => 2
]);
$api->add('/:controller', [
    'controller' => 1,
    'action' => ''
]);
$api->add('/:controller/([0-9]+)', [
    'controller' => 1,
    'id' => 2,
    'action' => ''
]);
$api->add('/:controller/([0-9]+)/:action', [
    'controller' => 1,
    'id' => 2,
    'action' => 3
]); 
/**
 * Prefix incoming action name with HTTP method
 */
foreach ($api->getRoutes() as $route) {
    $route->convert('action', function($action) use ($di) {
        $method = strtolower($di['request']->getMethod());
        return $method . ucfirst($action);
    });
}
// Under Phalcon 1.x, the above convert call needs to be replaced
// with the following as the router API differs slightly
// $api->convert('action', function($action) use ($di) {
//     $method = strtolower($di['request']->getMethod());
//     return $method . ucfirst($action);
// });
$router->mount($api);