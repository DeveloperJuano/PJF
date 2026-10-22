
<?php
require_once __DIR__ . '/../../config/db.php';

class MenuModel
{
    private PDO $conexion;
//=========================================================================================
    public function __construct()
    {
        $db = new Database();
        $this->conexion = $db->conectar();
    }
//=========================================================================================
    // OBTENER
//=========================================================================================
    /*
    CONSULTA PARA OBTENER TODOS LOS DATOS DEL PRODUCTO
    */
    public function obtenerProducto()
    {
        $sql = "SELECT
            p.idProducto,
            p.nombre,
            p.descripcion,
            p.precio,
            p.imagen,
            p.estado,
            p.idCategoria,
            c.nombre AS categoria,
            LOWER(
                REPLACE(
                    REPLACE(TRIM(c.nombre), ' ', '-'),
                    'á', 'a'
                )
            ) AS categoriaFiltro
        FROM producto p
        INNER JOIN categoria c
            ON p.idCategoria = c.idCategoria
        WHERE p.estado = 'activo'
            AND c.estado = 'activo'
        ORDER BY p.nombre ASC";
        $consulta = $this->conexion->query($sql);

        return $consulta->fetchAll();$consulta = $this->conexion->query($sql);

        return $consulta->fetchAll();
    }
//=========================================================================================
    /*
    CONSULTA PARA OBTENER CATEGORIA
    */
    public function obtenerCategorias()
    {
        $sql = "SELECT idCategoria, nombre, estado
                FROM categoria
                ORDER BY nombre ASC";

        $consulta = $this->conexion->query($sql);

        return $consulta->fetchAll();
    }
//=========================================================================================
    //CREACIONES 
//=========================================================================================

    /*
    CONSULTA PARA CREAR CATEGORIA
    */
    public function crearCategoria($nombre)
    {
        $sql = "INSERT INTO categoria (nombre, estado)
                VALUES (:nombre, 'activo')";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            ':nombre' => $nombre
        ]);
    }
//=========================================================================================
    //ACTUALIZAR
//=========================================================================================
    public function actualizarCategoria($idCategoria, $nombre)
    {
        $sql = "UPDATE categoria
                SET nombre = :nombre
                WHERE idCategoria = :idCategoria";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            ':nombre' => $nombre,
            ':idCategoria' => $idCategoria
        ]);

        return $consulta->rowCount() > 0;
    }

    public function cambiarEstadoCategoria($idCategoria, $estado)
    {
        if (!in_array($estado, ['activo', 'inactivo'], true)) {
            return false;
        }

        $sql = "UPDATE categoria
                SET estado = :estado
                WHERE idCategoria = :idCategoria";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            ':estado' => $estado,
            ':idCategoria' => $idCategoria
        ]);

        return $consulta->rowCount() > 0;
    }
// CREAR PRODUCTO

// =====================================================
    public function crearProducto(
        $idCategoria,
        $nombre,
        $descripcion,
        $precio,
        $imagen
        ) {
        $sql = "INSERT INTO producto
                (idCategoria, nombre, descripcion, precio, imagen, estado)
                VALUES
                (:idCategoria, :nombre, :descripcion, :precio, :imagen, 'activo')";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            ':idCategoria' => $idCategoria,
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
            ':precio' => $precio,
            ':imagen' => $imagen
        ]);
    }

// =====================================================
// ACTUALIZAR PRODUCTO
// =====================================================
    public function actualizarProducto(
        $idProducto,
        $idCategoria,
        $nombre,
        $descripcion,
        $precio,
        $imagen
    ) {
        $sql = "UPDATE producto
                SET idCategoria = :idCategoria,
                    nombre = :nombre,
                    descripcion = :descripcion,
                    precio = :precio,
                    imagen = :imagen
                WHERE idProducto = :idProducto";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            ':idCategoria' => $idCategoria,
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
            ':precio' => $precio,
            ':imagen' => $imagen,
            ':idProducto' => $idProducto
        ]);
    }

// =====================================================
// ACTIVAR / DESACTIVAR PRODUCTO
// =====================================================
    public function cambiarEstadoProducto($idProducto, $estado)
    {
        if (!in_array($estado, ['activo', 'inactivo'], true)) {
            return false;
        }

        $sql = "UPDATE producto
                SET estado = :estado
                WHERE idProducto = :idProducto";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            ':estado' => $estado,
            ':idProducto' => $idProducto
        ]);
    }
    // =====================================================
    // OBTENER TODOS LOS PRODUCTOS PARA ADMINISTRACIÓN
    // =====================================================
    public function obtenerProductosAdmin()
    {
        $sql = "SELECT
                    p.idProducto,
                    p.idCategoria,
                    p.nombre,
                    p.descripcion,
                    p.precio,
                    p.imagen,
                    p.estado,
                    c.nombre AS categoria
                FROM producto p
                INNER JOIN categoria c
                    ON p.idCategoria = c.idCategoria
                ORDER BY p.nombre ASC";

        $consulta = $this->conexion->query($sql);

        return $consulta->fetchAll();
    }
}
