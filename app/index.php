<?php

function route() {
    $requestUri = strtok($_SERVER['REQUEST_URI'], '?');
    $requestMethod = $_SERVER['REQUEST_METHOD'];

    if ($requestMethod !== 'GET') {
        return false;
    }
    
    if ($requestUri === '/' || $requestUri === '') {
        require __DIR__ . '/templates/home.php';

        return true;
    } else if (str_starts_with($requestUri, '/api')) {
        $file = __DIR__ . "$requestUri.php";
        if (file_exists($file)) {
            require $file;

            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

if (!route()) {
    http_response_code(404);
    
    require __DIR__ . '/templates/404.php';
}
