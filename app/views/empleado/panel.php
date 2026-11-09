<!doctype html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel del empleado</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="/PJF/public/css/style.css">
</head>
<body>

    <?php require_once __DIR__ . '/../components/navbar.php'; ?>

    <main class="container py-4">

        <header class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <h1 class="h2 mb-1">Panel del empleado</h1>
                <p class="text-secondary mb-0">Se actualiza solo cada 5 segundos.</p>
            </div>

            <a href="/PJF/empleado/pedidos/nuevo" class="btn btn-primary">
                Nuevo pedido
            </a>
        </header>

        <?php if (isset($_SESSION['mensaje_empleado'])): ?>

            <div class="alert alert-info" role="alert">
                <?= e($_SESSION['mensaje_empleado']) ?>
            </div>

            <?php unset($_SESSION['mensaje_empleado']); ?>

        <?php endif; ?>

        <div id="panel-datos">
            <?php require __DIR__ . '/_secciones.php'; ?>
        </div>

    </main>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js">
    </script>

    <script src="/PJF/public/js/empleado.js"></script>

</body>
</html>
