<!doctype html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nuevo pedido</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="/PJF/public/css/style.css">
</head>
<body>

    <?php require_once __DIR__ . '/../components/navbar.php'; ?>

    <main class="container py-4">

        <header class="mb-4">
            <h1 class="h2 mb-1">Nuevo pedido</h1>
            <p class="text-secondary mb-0">
                Anota lo que pide el cliente. Después lo confirmas desde el panel.
            </p>
        </header>

        <?php if (isset($_SESSION['mensaje_empleado'])): ?>

            <div class="alert alert-warning" role="alert">
                <?= e($_SESSION['mensaje_empleado']) ?>
            </div>

            <?php unset($_SESSION['mensaje_empleado']); ?>

        <?php endif; ?>

        <form action="/PJF/empleado/pedidos/crear" method="POST">

            <?= Csrf::campo() ?>

            <!-- DATOS DEL PEDIDO -->
            <div class="row g-3 mb-4">

                <div class="col-12 col-md-6">
                    <label for="nombre" class="form-label">Nombre del cliente</label>
                    <input
                        type="text"
                        class="form-control"
                        id="nombre"
                        name="nombre"
                        maxlength="60"
                        required
                    >
                </div>

                <div class="col-12 col-md-6">
                    <label for="idMesa" class="form-label">Mesa</label>
                    <select class="form-select" id="idMesa" name="idMesa" required>
                        <option value="">Elige una mesa</option>

                        <?php foreach ($mesas as $mesa): ?>
                            <option value="<?= (int) $mesa['idMesa'] ?>">
                                Mesa <?= e($mesa['numero']) ?>
                                (<?= e($mesa['capacidad']) ?> personas)
                                <?= (int) $mesa['ocupada'] === 1 ? ' · ocupada' : '' ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

            </div>

            <!-- PRODUCTOS -->
            <?php foreach ($productosPorCategoria as $categoria => $productos): ?>

                <section class="mb-4">

                    <h2 class="h5 mb-3"><?= e($categoria) ?></h2>

                    <div class="list-group">

                        <?php foreach ($productos as $producto): ?>

                            <div class="list-group-item d-flex justify-content-between align-items-center gap-3">

                                <label
                                    for="producto-<?= (int) $producto['idProducto'] ?>"
                                    class="flex-grow-1 mb-0"
                                >
                                    <strong><?= e($producto['nombre']) ?></strong>
                                    <span class="text-secondary small d-block">
                                        <?= e($producto['descripcion']) ?>
                                    </span>
                                </label>

                                <span class="text-nowrap"><?= e(dinero($producto['precio'])) ?></span>

                                <input
                                    type="number"
                                    class="form-control"
                                    style="width: 5.5rem;"
                                    id="producto-<?= (int) $producto['idProducto'] ?>"
                                    name="cantidad[<?= (int) $producto['idProducto'] ?>]"
                                    min="0"
                                    max="50"
                                    value="0"
                                >

                            </div>

                        <?php endforeach; ?>

                    </div>

                </section>

            <?php endforeach; ?>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">Guardar pedido</button>
                <a href="/PJF/empleado/panel" class="btn btn-outline-secondary">Volver al panel</a>
            </div>

        </form>

    </main>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js">
    </script>

</body>
</html>