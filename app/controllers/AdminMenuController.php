<?php

require_once __DIR__ . '/../models/MenuModel.php';

class AdminMenuController
{
    public function index()
    {
        $model = new MenuModel();
        $productos = $model->obtenerProducto();

        require_once __DIR__ . '/../views/menu/index.php';
    }
    public function categorias()
    {
        $model = new MenuModel();
        $categorias = $model->obtenerCategorias();

        require_once __DIR__ . '/../views/admin/menu/categorias.php';
    }

    public function crearCategoria()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /PJF/admin/menu/categorias');
            exit;
        }

        $nombre = trim($_POST['nombre'] ?? '');

        if ($nombre === '' || strlen($nombre) > 60) {
            $_SESSION['mensaje_menu'] = 'Ingresa un nombre de categoría válido (máximo 60 caracteres).';
            header('Location: /PJF/admin/menu/categorias');
            exit;
        }

        $model = new MenuModel();
        $model->crearCategoria($nombre);

        $_SESSION['mensaje_menu'] = 'Categoría creada correctamente.';

        header('Location: /PJF/admin/menu/categorias');
        exit;
    }

    public function actualizarCategoria()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /PJF/admin/menu/categorias');
            exit;
        }

        $idCategoria = filter_input(INPUT_POST, 'idCategoria', FILTER_VALIDATE_INT);
        $nombre = trim($_POST['nombre'] ?? '');

        if (!$idCategoria || $idCategoria < 1 || $nombre === '' || strlen($nombre) > 60) {
            $_SESSION['mensaje_menu'] = 'Los datos de la categoría no son válidos.';
            header('Location: /PJF/admin/menu/categorias');
            exit;
        }

        $model = new MenuModel();
        $model->actualizarCategoria($idCategoria, $nombre);

        $_SESSION['mensaje_menu'] = 'Categoría actualizada correctamente.';

        header('Location: /PJF/admin/menu/categorias');
        exit;
    }

    public function cambiarEstadoCategoria()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /PJF/admin/menu/categorias');
            exit;
        }

        $idCategoria = filter_input(INPUT_POST, 'idCategoria', FILTER_VALIDATE_INT);
        $estado = $_POST['estado'] ?? '';

        if (!$idCategoria || $idCategoria < 1 || !in_array($estado, ['activo', 'inactivo'], true)) {
            $_SESSION['mensaje_menu'] = 'Los datos enviados no son válidos.';
            header('Location: /PJF/admin/menu/categorias');
            exit;
        }

        $model = new MenuModel();
        $resultado = $model->cambiarEstadoCategoria($idCategoria, $estado);

        $_SESSION['mensaje_menu'] = $resultado
            ? 'Estado de categoría actualizado.'
            : 'No se encontró la categoría o no hubo cambios.';

        header('Location: /PJF/admin/menu/categorias');
        exit;
    }
    // =====================================================
    // LISTAR PRODUCTOS
    // =====================================================
    public function productos()
    {
        $model = new MenuModel();

        $productos = $model->obtenerProductosAdmin();
        $categorias = $model->obtenerCategorias();

        require_once __DIR__ . '/../views/admin/menu/productos.php';
    }
// =====================================================
// CREAR PRODUCTO
// =====================================================
public function crearProducto()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /PJF/admin/menu/productos');
        exit;
    }

    $idCategoria = filter_input(INPUT_POST, 'idCategoria', FILTER_VALIDATE_INT);
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precio = $_POST['precio'] ?? '';
    $imagen = trim($_POST['imagen'] ?? '');

    if (
        !$idCategoria ||
        $idCategoria < 1 ||
        $nombre === '' ||
        strlen($nombre) > 50 ||
        $descripcion === '' ||
        strlen($descripcion) > 50 ||
        !is_numeric($precio) ||
        (float) $precio <= 0 ||
        $imagen === '' ||
        strlen($imagen) > 50
    ) {
        $_SESSION['mensaje_menu'] = 'Verifica los datos del producto.';
        header('Location: /PJF/admin/menu/productos');
        exit;
    }

    $model = new MenuModel();
    $model->crearProducto(
        $idCategoria,
        $nombre,
        $descripcion,
        (float) $precio,
        $imagen
    );

    $_SESSION['mensaje_menu'] = 'Producto creado correctamente.';
    header('Location: /PJF/admin/menu/productos');
    exit;
}

// =====================================================
// ACTUALIZAR PRODUCTO
// =====================================================
public function actualizarProducto()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /PJF/admin/menu/productos');
        exit;
    }

    $idProducto = filter_input(INPUT_POST, 'idProducto', FILTER_VALIDATE_INT);
    $idCategoria = filter_input(INPUT_POST, 'idCategoria', FILTER_VALIDATE_INT);
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precio = $_POST['precio'] ?? '';
    $imagen = trim($_POST['imagen'] ?? '');

    if (
        !$idProducto ||
        $idProducto < 1 ||
        !$idCategoria ||
        $idCategoria < 1 ||
        $nombre === '' ||
        strlen($nombre) > 50 ||
        $descripcion === '' ||
        strlen($descripcion) > 50 ||
        !is_numeric($precio) ||
        (float) $precio <= 0 ||
        $imagen === '' ||
        strlen($imagen) > 50
    ) {
        $_SESSION['mensaje_menu'] = 'Verifica los datos del producto.';
        header('Location: /PJF/admin/menu/productos');
        exit;
    }

    $model = new MenuModel();
    $model->actualizarProducto(
        $idProducto,
        $idCategoria,
        $nombre,
        $descripcion,
        (float) $precio,
        $imagen
    );

    $_SESSION['mensaje_menu'] = 'Producto actualizado correctamente.';
    header('Location: /PJF/admin/menu/productos');
    exit;
}

    // =====================================================
    // CAMBIAR ESTADO DEL PRODUCTO
    // =====================================================
    public function cambiarEstadoProducto()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /PJF/admin/menu/productos');
            exit;
        }

        $idProducto = filter_input(INPUT_POST, 'idProducto', FILTER_VALIDATE_INT);
        $estado = $_POST['estado'] ?? '';

        if (
            !$idProducto ||
            $idProducto < 1 ||
            !in_array($estado, ['activo', 'inactivo'], true)
        ) {
            $_SESSION['mensaje_menu'] = 'Los datos enviados no son válidos.';
            header('Location: /PJF/admin/menu/productos');
            exit;
        }

        $model = new MenuModel();
        $model->cambiarEstadoProducto($idProducto, $estado);

        $_SESSION['mensaje_menu'] = 'Estado del producto actualizado.';
        header('Location: /PJF/admin/menu/productos');
        exit;
    }
}
