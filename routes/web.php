<?php
require_once __DIR__ . '/../app/controllers/AuthMiddleware.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri = str_replace('/PJF', '', $uri);

//==============================================
//confirmacion de admins

$rutasAdmin = ($uri === '/admin' || str_starts_with($uri, '/admin/'));

if ($rutasAdmin) {
    AuthMiddleware::soloAdmin();
}

//==============================================
//confirmacion de empleados

$rutasEmpleado = ($uri === '/empleado' || str_starts_with($uri, '/empleado/'));

if ($rutasAdmin) {
    AuthMiddleware::soloEmpleado();
}

//==============================================

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
    
    // ================================================================================
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

    // login-guardar
    case '/registro/guardar':
        require_once __DIR__ . '/../app/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->registrar();
        break ;

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
    // =================== ADMINISTRADOR =============================================
    // ===============================================================================
    // INCIO ADMINISTRADOR
    case'/admin':
        require_once __DIR__ . '/../app/controllers/AdminController.php';
        $controller = new AdminController();
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
    // PANEL ADMINISTRADOR - CATEGORIAS

    case '/admin/menu/categorias':
        require_once __DIR__ . '/../app/controllers/AdminMenuController.php';
        $controller = new AdminMenuController();
        $controller->categorias();
        break;

    case '/admin/menu/categorias/crear':
        require_once __DIR__ . '/../app/controllers/AdminMenuController.php';
        $controller = new AdminMenuController();
        $controller->crearCategoria();
        break;

    case '/admin/menu/categorias/actualizar':
        require_once __DIR__ . '/../app/controllers/AdminMenuController.php';
        $controller = new AdminMenuController();
        $controller->actualizarCategoria();
        break;

    case '/admin/menu/categorias/estado':
        require_once __DIR__ . '/../app/controllers/AdminMenuController.php';
        $controller = new AdminMenuController();
        $controller->cambiarEstadoCategoria();
        break;

    // ================================================================================
    // PANEL ADMINISTRADOR - PRODUCTOS

    case '/admin/menu/productos':
        require_once __DIR__ . '/../app/controllers/AdminMenuController.php';
        $controller = new AdminMenuController();
        $controller->productos();
        break;

    case '/admin/menu/productos/crear':
        require_once __DIR__ . '/../app/controllers/AdminMenuController.php';
        $controller = new AdminMenuController();
        $controller->crearProducto();
        break;

    case '/admin/menu/productos/actualizar':
        require_once __DIR__ . '/../app/controllers/AdminMenuController.php';
        $controller = new AdminMenuController();
        $controller->actualizarProducto();
        break;

    case '/admin/menu/productos/estado':
        require_once __DIR__ . '/../app/controllers/AdminMenuController.php';
        $controller = new AdminMenuController();
        $controller->cambiarEstadoProducto();
        break;
    // ================================================================================
    // (REGISTRAR EMPLEADO)
    // MOSTRAR FORMULARIO
    case '/admin/empleado/registrar':
        require_once __DIR__ . '/../app/controllers/AdminEmpleadoController.php';

        $controller = new AdminEmpleadoController();
        $controller->index();
        break;

    // GUARDAR EMPLEADO
    case '/admin/empleado/guardar':
        require_once __DIR__ . '/../app/controllers/AdminEmpleadoController.php';

        $controller = new AdminEmpleadoController();
        $controller->registrar();
        break;

    // ================================================================================
    // =================     PANEL EMPLEADO  ==========================================
    // ===============================================================================

    // Panel
    case '/empleado':
        require_once __DIR__ . '/../app/controllers/EmpleadoPedidoController.php';
        $controller = new EmpleadoPedidoController();
        $controller->panel();
        break;

    // Refresca los pedidos
    case '/empleado/fragmento':
        require_once __DIR__ . '/../app/controllers/EmpleadoPedidoController.php';
        $controller = new EmpleadoPedidoController();
        $controller->fragmento();
        break;
    
    // Toma pedido
    case '/empleado/pedidos/nuevo':
        require_once __DIR__ . '/../app/controllers/EmpleadoPedidoController.php';
        $controller = new EmpleadoPedidoController();
        $controller->nuevo();
        break;

    // Crea pedido
    case '/empleado/pedidos/crear':
        require_once __DIR__ . '/../app/controllers/EmpleadoPedidoController.php';
        $controller = new EmpleadoPedidoController();
        $controller->panel();
        break;

    // Confirma los pedidos
    case '/empleado/pedidos/confirmar':
        require_once __DIR__ . '/../app/controllers/EmpleadoPedidoController.php';
        $controller = new EmpleadoPedidoController();
        $controller->confirmar();
        break;

    // Cancela un pedido
    case '/empleado/pedidos/cancelar':
        require_once __DIR__ . '/../app/controllers/EmpleadoPedidoController.php';
        $controller = new EmpleadoPedidoController();
        $controller->cancelar();
        break;

    // Avanza un pedido de estado
    case '/empleado':
        require_once __DIR__ . '/../app/controllers/EmpleadoPedidoController.php';
        $controller = new EmpleadoPedidoController();
        $controller->avanzarProducto();
        break;
    
    // Apartado para el pago de pedidos
    case '/empleado':
        require_once __DIR__ . '/../app/controllers/EmpleadoPedidoController.php';
        $controller = new EmpleadoPedidoController();
        $controller->panel();
        break;

    // ================================================================================
    // CASO PARA CUANDO TODO FALLA (404)
    default:
        http_response_code(404);
        echo "pagina no encontrada";
        break;
}