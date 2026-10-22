
<!doctype html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestión de mesas</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="/PJF/public/css/style.css">
</head>
<body>
    
    <?php require_once __DIR__ . '/../../components/navbar.php'; ?>

    <main class="container py-5">

        <header class="mb-5">
            <h1>Gestión de mesas</h1>
            <p>Administra las mesas y sus capacidades.</p>
        </header>

        <?php if (isset($_SESSION['mensaje_mesas'])): ?>

            <div class="alert alert-info" role="alert">
                <?= htmlspecialchars(
                    $_SESSION['mensaje_mesas'],
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </div>

            <?php unset($_SESSION['mensaje_mesas']); ?>

        <?php endif; ?>

        <section>
            <!--BOTON NUEVA MESA -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Mesas del restaurante</h2>

                <button
                    type="button"
                    class="btn btn-primary"
                    data-bs-toggle="collapse"
                    data-bs-target="#formulario-mesa"
                    aria-expanded="false"
                    aria-controls="formulario-mesa"
                >
                    Nueva mesa
                </button>
            </div>

            <!-- FORMULARIO PARA REGISTRAR UNA MESA -->
            <div class="collapse mb-4" id="formulario-mesa">
                <div class="card card-body">

                    <h3 class="h5 mb-3">Registrar mesa</h3>

                    <form action="/PJF/admin/mesas/crear" method="POST">

                        <div class="mb-3">
                            <label for="numero" class="form-label">
                                Número de mesa
                            </label>

                            <input
                                type="number"
                                class="form-control"
                                id="numero"
                                name="numero"
                                min="1"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label for="capacidad" class="form-label">
                                Capacidad de personas
                            </label>

                            <input
                                type="number"
                                class="form-control"
                                id="capacidad"
                                name="capacidad"
                                min="1"
                                required
                            >
                        </div>

                        <button type="submit" class="btn btn-success">
                            Guardar mesa
                        </button>

                    </form>
                    
                </div>
            </div>
            
            <!-- LISTA DE MESA -->
            <div id="lista-mesas" class="row g-4">
                
                <?php foreach ($mesas as $mesa): ?>
                    
                    <div class="col-12 col-md-6 col-lg-4">

                        <article class="card h-100">
                            <div class="card-body">

                                <!-- nombre de la mesa -->
                                <h3 class="card-title">
                                    Mesa <?= htmlspecialchars(
                                        (string) $mesa['numero'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </h3>

                                <!-- capacidad -->
                                <p class="card-text">
                                    Capacidad:
                                    <?= htmlspecialchars(
                                        (string) $mesa['capacidad'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                    personas
                                </p>

                                <!-- FORMULARIO  DE ACTUALIZACION -->
                                <form action="/PJF/admin/mesas/actualizar-capacidad" method="POST">
                                
                                    <input
                                        type="hidden"
                                        name="numero"
                                        value="<?= (int) $mesa['numero'] ?>"
                                    >

                                    <div class="mb-3">
                                        <label
                                            for="capacidad-<?= (int) $mesa['numero'] ?>"
                                            class="form-label"
                                        >
                                            Nueva capacidad
                                        </label>

                                        <input
                                            type="number"
                                            class="form-control"
                                            id="capacidad-<?= (int) $mesa['numero'] ?>"
                                            name="capacidad"
                                            min="1"
                                            max="100"
                                            value="<?= (int) $mesa['capacidad'] ?>"
                                            required
                                        >
                                    </div>

                                    <button type="submit" class="btn btn-outline-primary">
                                        Actualizar capacidad
                                    </button>

                                </form>
                                <!-- FORMULARIO DE Desactivar -->
                                <?php if ($mesa['estado'] !== 'inactiva'): ?>
                                    <form action="/PJF/admin/mesas/desactivar" method="POST"
                                        onsubmit="return confirm('¿Seguro que deseas desactivar esta mesa?');"
                                        class="mt-3">
                                        <input type="hidden" name="numero" value="<?= (int) $mesa['numero'] ?>">

                                        <button type="submit" class="btn btn-outline-danger">
                                            Desactivar mesa
                                        </button>
                                    </form>
                                    
                                <?php endif; ?>
                                <!-- FORMULARIO DE Activar -->
                                <?php if ($mesa['estado'] === 'inactiva'): ?>
                                        <form action="/PJF/admin/mesas/activar" method="POST" class="mt-3">
                                            <input type="hidden" name="numero" value="<?= (int) $mesa['numero'] ?>">
    
                                            <button type="submit" class="btn btn-outline-success">
                                                Activar mesa
                                            </button>
                                        </form>
                                <?php endif; ?>
                                <!-- ESTADO DE LA MESA -->
                                <p class="card-text">
                                    Estado:
                                    <?= htmlspecialchars(
                                        ucfirst($mesa['estado']),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </p>

                            </div>
                        </article>

                    </div>

                <?php endforeach; ?>

            </div>

        </section>

    </main>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js">
    </script>

</body>
</html>
