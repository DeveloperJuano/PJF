
<?php
$mensaje = $_SESSION['mensaje_menu'] ?? '';
unset($_SESSION['mensaje_menu']);
?>

<!doctype html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrar categorías | PJF</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">
    <link rel="stylesheet" href="/PJF/public/css/style.css">
</head>
<body>

<?php require_once __DIR__ . '/../../components/navbar.php'; ?>

<main class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>Administrar categorías</h1>
            <p>Gestiona las categorías de los productos del restaurante.</p>
        </div>

        <a href="/PJF/admin/menu/productos" class="btn btn-outline-secondary">
            Volver al menú
        </a>
    </div>

    <?php if ($mensaje !== ''): ?>
        <div class="alert alert-info">
            <?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <section class="card p-4 mb-5">
        <h2 class="h4 mb-3">Crear categoría</h2>

        <form action="/PJF/admin/menu/categorias/crear" method="POST">
            <div class="mb-3">
                <label for="nombre" class="form-label">
                    Nombre de la categoría
                </label>

                <input
                    type="text"
                    class="form-control"
                    id="nombre"
                    name="nombre"
                    maxlength="60"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary">
                Crear categoría
            </button>
        </form>
    </section>

    <section>
        <h2 class="h4 mb-3">Categorías registradas</h2>

        <?php if (empty($categorias)): ?>
            <div class="alert alert-secondary">
                Todavía no hay categorías registradas.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-dark table-striped align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Estado</th>
                            <th>Editar</th>
                            <th>Activar / desactivar</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($categorias as $categoria): ?>
                            <tr>
                                <td><?= (int) $categoria['idCategoria'] ?></td>

                                <td>
                                    <form
                                        action="/PJF/admin/menu/categorias/actualizar"
                                        method="POST"
                                        class="d-flex gap-2"
                                    >
                                        <input
                                            type="hidden"
                                            name="idCategoria"
                                            value="<?= (int) $categoria['idCategoria'] ?>"
                                        >

                                        <input
                                            type="text"
                                            name="nombre"
                                            class="form-control"
                                            maxlength="60"
                                            required
                                            value="<?= htmlspecialchars($categoria['nombre'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                        >

                                        <button type="submit" class="btn btn-outline-primary">
                                            Guardar
                                        </button>
                                    </form>
                                </td>

                                <td>
                                    <?= htmlspecialchars($categoria['estado'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                </td>

                                <td>
                                    <span class="text-secondary">Formulario</span>
                                </td>

                                <td>
                                    <form
                                        action="/PJF/admin/menu/categorias/estado"
                                        method="POST"
                                    >
                                        <input
                                            type="hidden"
                                            name="idCategoria"
                                            value="<?= (int) $categoria['idCategoria'] ?>"
                                        >

                                        <?php if ($categoria['estado'] === 'activo'): ?>
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
