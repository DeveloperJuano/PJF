<?php

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri = str_replace('/PJF', '', $uri);

switch($uri){

    case '':
        
    case '/':
        require_once __DIR__. '/../app/controllers/HomeController.php';

        $controller = new HomeController();
        $controller->index();
        break;
    
    case '/menu':
        require_once __DIR__.'/../app/controllers/MenuController.php';

        $controller = new MenuController();
        $controller->index();
        break;
    
    case '/reserva':
        require_once __DIR__.'/../app/controllers/ReservaController.php';
        $controller = new ReservaController();
        $controller->index();
        break;
    
    default : 
        http_response_code(404);
        echo "pagina no encontrada";
        break; 
}

?>