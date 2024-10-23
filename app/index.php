<?php
use Dotenv\Dotenv;
use App\Core\Router;

require_once '../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$router = new Router();

$router->get('/', function () {
    require __DIR__ . '/templates/home.php';
});

$router->get('/api/closest-sites', function () {
    require __DIR__ . '/api/closest-sites.php';
});

$router->dispatch();
