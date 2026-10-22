
<?php
$mensaje = $_SESSION['mensaje_menu'] ?? '';
unset($_SESSION['mensaje_menu']);

$categoriasActivas = array_filter(
    $categorias,
    fn($categoria) => ($categoria['estado'] ?? '') === 'activo'
);
?>
<!doctype html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrar productos | PJF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/PJF/public/css/style.css">
</head>
<body>

<?php require_once __DIR__ . '/../../components/navbar.php'; ?>

<main class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1>Administrar productos</h1>
            <p>Registra y administra los productos del restaurante.</p>
        </div>
        <a href="/PJF/admin/menu/categorias" class="btn btn-outline-secondary">
            Administrar categorías
        </a>
    </div>

    <?php if ($mensaje !== ''): ?>
        <div class="alert alert-info">
            <?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <section class="card p-4 mb-5">
        <h2 class="h4 mb-3">Crear producto</h2>

        <?php if (empty($categoriasActivas)): ?>
            <div class="alert alert-warning">
                Primero debes crear y activar una categoría.
            </div>
        <?php else: ?>
            <form action="/PJF/admin/menu/productos/crear" method="POST">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre"
                           name="nombre" maxlength="50" required>
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <input type="text" class="form-control" id="descripcion"
                           name="descripcion" maxlength="50" required>
                </div>

                <div class="mb-3">
                    <label for="precio" class="form-label">Precio (COP)</label>
                    <input type="number" class="form-control" id="precio"
                           name="precio" min="1" step="1" required>
                </div>

                <div class="mb-3">
                    <label for="idCategoria" class="form-label">Categoría</label>
                    <select class="form-select" id="idCategoria"
                            name="idCategoria" required>
                        <option value="">Selecciona una categoría</option>
                        <?php foreach ($categoriasActivas as $categoria): ?>
                            <option value="<?= (int) $categoria['idCategoria'] ?>">
                                <?= htmlspecialchars($categoria['nombre'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="imagen" class="form-label">
                        Nombre del archivo de imagen
                    </label>
                    <input type="text" class="form-control" id="imagen"
                           name="imagen" maxlength="50"
                           placeholder="hamburguesa.jpg" required>
                    <div class="form-text">
                        El archivo debe existir en public/img.
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    Crear producto
                </button>
            </form>
        <?php endif; ?>
    </section>

    <section>
        <h2 class="h4 mb-3">Productos registrados</h2>

        <?php if (empty($productos)): ?>
            <div class="alert alert-secondary">
                Todavía no hay productos registrados.
            </div>
        <?php else: ?>
            
            <div class="table-responsive">
                <table class="table table-dark table-striped align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Categoría</th>
                            <th>Imagen</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos as $producto): ?>
                            <tr>
                                <td><?= (int) $producto['idProducto'] ?></td>

                                <form
                                    action="/PJF/admin/menu/productos/actualizar"
                                    method="POST"
                                >
                                    <input type="hidden" name="idProducto"
                                        value="<?= (int) $producto['idProducto'] ?>">

                                    <td>
                                        <input type="text" name="nombre"
                                            class="form-control"
                                            maxlength="50" required
                                            value="<?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8') ?>">
                                    </td>

                                    <td>
                                        <input type="text" name="descripcion"
                                            class="form-control"
                                            maxlength="50" required
                                            value="<?= htmlspecialchars($producto['descripcion'], ENT_QUOTES, 'UTF-8') ?>">
                                    </td>

                                    <td>
                                        <input type="number" name="precio"
                                            class="form-control"
                                            min="1" step="1" required
                                            value="<?= htmlspecialchars((string) $producto['precio'], ENT_QUOTES, 'UTF-8') ?>">
                                    </td>

                                    <td>
                                        <select name="idCategoria" class="form-select" required>
                                            <?php foreach ($categoriasActivas as $categoria): ?>
                                                <option
                                                    value="<?= (int) $categoria['idCategoria'] ?>"
                                                    <?= (int) $producto['idCategoria'] === (int) $categoria['idCategoria'] ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($categoria['nombre'], ENT_QUOTES, 'UTF-8') ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>

                                    <td>
                                        <input type="text" name="imagen"
                                            class="form-control"
                                            maxlength="50" required
                                            value="<?= htmlspecialchars($producto['imagen'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($producto['estado'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                    </td>

                                    <td>
                                        <div class="d-flex flex-column gap-2">
                                            <button type="submit" class="btn btn-outline-primary">
                                                Guardar
                                            </button>
                                </form>

                                            <form action="/PJF/admin/menu/productos/estado" method="POST">
                                                <input type="hidden" name="idProducto"
                                                    value="<?= (int) $producto['idProducto'] ?>">

                                                <?php if (($producto['estado'] ?? '') === 'activo'): ?>
                                                    <input type="hidden" name="estado" value="inactivo">
                                                    <button type="submit" class="btn btn-outline-danger">
                                                        Desactivar
                                                    </button>
                                                <?php else: ?>
                                                    <input type="hidden" name="estado" value="activo">
                                                    <button type="submit" class="btn btn-outline-success">
                                                        Activar
                                                    </button>
                                                <?php endif; ?>
                                            </form>
                                        </div>
                                    </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php endif; ?>
    </section>
</main>

</body>
</html>
```