<?php

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri = str_replace('/PJF', '', $uri);

switch ($uri) {

    case '':

        // ================================================================================

        // HOME
    case '/':
        require_once __DIR__ . '/../app/controllers/HomeController.php';

        $controller = new HomeController();
        $controller->index();
        break;

    // ================================================================================


    // Login
    case '/login':
        require_once __DIR__ . '/../app/controllers/AuthController.php';

        $controller = new AuthController();
        $controller->mostrarLogin();
        break;


    // Login-Entrar
    case '/login/entrar':
        require_once __DIR__ . '/../app/controllers/AuthController.php';

        $controller = new AuthController();
        $controller->login();
        break;

    // Login-Out
    case '/logout':
        require_once __DIR__ . '/../app/controllers/AuthController.php';

        $controller = new AuthController();
        $controller->logout();
        break;

    // ================================================================================

    // MENU
    case '/menu':
        require_once __DIR__ . '/../app/controllers/MenuController.php';

        $controller = new MenuController();
        $controller->index();
        break;

    // ================================================================================

    // RESERVVA DE MESA CLIENTE

    case '/reserva':
        require_once __DIR__ . '/../app/controllers/ReservaController.php';
        $controller = new ReservaController();
        $controller->index();
        break;

    // ================================================================================

    //PANEL ADMINISTRADOR
    case '/admin/mesas':
        require_once __DIR__ . '/../app/controllers/AdminMesaController.php';
        $controller = new AdminMesaController();
        $controller->index();
        break;

    // ================================================================================

    //PANEL ADMIN(FUNCION CREAR)
    case '/admin/mesas/crear':
        require_once __DIR__ . '/../app/controllers/AdminMesaController.php';

        $controller = new AdminMesaController();
        $controller->crear();
        break;

    // ================================================================================

    //PANEL ADMIN(FUNCION ACTUALIZAR)
    case '/admin/mesas/actualizar-capacidad':
        require_once __DIR__ . '/../app/controllers/AdminMesaController.php';

        $controller = new AdminMesaController();
        $controller->actualizarCapacidad();
        break;

    // ================================================================================
    //PANEL ADMIN(FUNCION DESACTIVAR MESA)
    case '/admin/mesas/desactivar':
        require_once __DIR__ . '/../app/controllers/AdminMesaController.php';

        $controller = new AdminMesaController();
        $controller->desactivar();
        break;
    //PANEL ADMIN(FUNCION ACTIVAR MESA)
    case '/admin/mesas/activar':
        require_once __DIR__ . '/../app/controllers/AdminMesaController.php';

        $controller = new AdminMesaController();
        $controller->activar();
        break;
    // ================================================================================
    // ================================================================================
    default:
        http_response_code(404);
        echo "pagina no encontrada";
        break;
}
