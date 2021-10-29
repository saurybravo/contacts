<?php
require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap.php';

$uri = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

try {
    if ((!isset($uri[1]) or !isset($uri[2])) or !isset($routes[$uri[1]]) or !isset($routes[$uri[1]]['methods'][$uri[2]]))
        throw new ErrorException('1');

    $controller_name = $routes[$uri[1]]['controller'];
    $method_name = $uri[2];

    $file_name = APP_PATH . CONTROLLER_FOLDER . $controller_name . ".php";
    $class_name = CONTROLLER_NAMESPACE . $controller_name;

    if (!file_exists($file_name))
        throw new ErrorException('2');

    if ($_SERVER["REQUEST_METHOD"] !== strtoupper($routes[$uri[1]]['methods'][$uri[2]]))
        throw new ErrorException('3');

    require $file_name;
    $controller = new $class_name();
    $controller->{$method_name}();

} catch (ErrorException $exception) {
    header("HTTP/1.1 404 Not Found");
    exit();
}