
<!doctype html>
<html lang="es" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Reserva tu mesa</title>

    <!-- BOOTSTRAP CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
        crossorigin="anonymous"
    >

    <!-- CSS PROPIO -->
    <link rel="stylesheet" href="/PJF/public/css/style.css">

</head>

<body>

    <!-- NAVBAR -->
    <?php require_once __DIR__ . '/../components/navbar.php'; ?>

    <main>

        <!-- ENCABEZADO DE RESERVAS -->
        <section class="py-5">
            <div class="container">

                <div class="text-center mb-5">
                    <h1 class="display-5 fw-bold mb-3">
                        Reserva tu mesa
                    </h1>

                    <p class="text-body-secondary">
                        Elige tu mesa favorita, selecciona la fecha
                        y disfruta de una excelente experiencia.
                    </p>
                </div>

                <!-- MENSAJES DEL SERVIDOR -->
                <?php if (isset($_SESSION['mensaje_reserva'])): ?>

                    <div class="alert alert-info text-center mx-auto mb-4"
                         style="max-width: 700px;">

                        <?= htmlspecialchars($_SESSION['mensaje_reserva']) ?>

                    </div>

                    <?php unset($_SESSION['mensaje_reserva']); ?>

                <?php endif; ?>

                <!-- PASO 1: SELECCIONAR MESA -->
                <div class="mb-5">

                    <div class="mb-4">
                        <span class="badge text-bg-secondary mb-2">
                            Paso 1
                        </span>

                        <h2 class="h3 fw-semibold">
                            Selecciona una mesa
                        </h2>

                        <p class="text-body-secondary">
                            Explora las mesas disponibles y elige
                            la que mejor se adapte a tu reserva.
                        </p>
                    </div>

                    <!-- CARD MESAS -->
                    <div class="row g-4">

                        <?php foreach ($mesas as $mesa): ?>

                            <div class="col-12 col-sm-6 col-lg-4">

                                <article class="mesa mesa-<?= htmlspecialchars($mesa['estado']) ?> h-100">

                                    <div class="mesa-superior">
                                        <span>
                                            MESA <?= (int) $mesa['numero'] ?>
                                        </span>
                                    </div>

                                    <div class="mesa-centro">

                                        <h4>
                                            <?= (int) $mesa['numero'] ?>
                                        </h4>

                                        <p>
                                            <?= (int) $mesa['capacidad'] ?> personas
                                        </p>

                                        <span>
                                            <?= htmlspecialchars(ucfirst($mesa['estado'])) ?>
                                        </span>

                                    </div>

                                    <!-- BOTON SELECCIONAR MESA -->
                                    <?php if ($mesa['estado'] === 'disponible'): ?>

                                        <button
                                            type="button"
                                            class="btn-agregar-mesa"
                                            data-id-mesa="<?= (int) $mesa['idMesa'] ?>"
                                            data-mesa="<?= (int) $mesa['numero'] ?>"
                                            data-capacidad="<?= (int) $mesa['capacidad'] ?>"
                                        >
                                            Seleccionar mesa
                                        </button>

                                    <?php endif; ?>

                                </article>

                            </div>

                        <?php endforeach; ?>

                    </div>

                    <!-- INDICADOR DE MESA SELECCIONADA -->
                    <div class="card border-secondary mt-4">
                        <div class="card-body text-center py-3">

                            <p class="text-body-secondary small mb-2">
                                TU SELECCIÓN
                            </p>

                            <div id="mesa-seleccionada">
                                <p class="mb-0">
                                    Todavía no has seleccionado ninguna mesa.
                                </p>
                            </div>

                        </div>
                    </div>

                </div>

                <!-- PASO 2: DATOS DE LA RESERVA -->
                <div class="mb-5">

                    <div class="mb-4">
                        <span class="badge text-bg-secondary mb-2">
                            Paso 2
                        </span>

                        <h2 class="h3 fw-semibold">
                            Completa los datos de tu reserva
                        </h2>

                        <p class="text-body-secondary">
                            Indica cuántas personas asistirán
                            y cuándo deseas visitarnos.
                        </p>
                    </div>

                    <div class="card border-secondary shadow-sm">

                        <div class="card-body p-4">

                            <div class="row g-4">

                                <!-- CANTIDAD DE PERSONAS -->
                                <div class="col-12 col-md-4">

                                    <label for="personas" class="form-label fw-semibold">
                                        Cantidad de personas
                                    </label>

                                    <input
                                        type="number"
                                        id="personas"
                                        class="form-control form-control-lg"
                                        min="1"
                                        placeholder="Ej. 4"
                                        required
                                    >

                                    <div id="mensaje-capacidad"
                                         class="small mt-2">
                                    </div>

                                </div>

                                <!-- FECHA DE RESERVA -->
                                <div class="col-12 col-md-4">

                                    <label for="fecha" class="form-label fw-semibold">
                                        Fecha de la reserva
                                    </label>

                                    <input
                                        type="date"
                                        id="fecha"
                                        class="form-control form-control-lg"
                                        required
                                    >

                                </div>

                                <!-- HORA DE RESERVA -->
                                <div class="col-12 col-md-4">

                                    <label for="hora" class="form-label fw-semibold">
                                        Hora de la reserva
                                    </label>

                                    <input
                                        type="time"
                                        id="hora"
                                        class="form-control form-control-lg"
                                        required
                                    >

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- PASO 3: RESUMEN Y CONFIRMACION -->
                <div class="mb-5">

                    <div class="mb-4">
                        <span class="badge text-bg-secondary mb-2">
                            Paso 3
                        </span>

                        <h2 class="h3 fw-semibold">
                            Confirma tu reserva
                        </h2>

                        <p class="text-body-secondary">
                            Revisa los detalles antes de continuar.
                        </p>
                    </div>

                    <div class="card border-secondary shadow-sm">

                        <div class="card-body p-4 p-md-5">

                            <h3 class="h5 fw-semibold mb-4">
                                Resumen de la reserva
                            </h3>

                            <!-- RESUMEN DINAMICO -->
                            <div id="resumen-reserva"
                                 class="mb-4 text-body-secondary">

                                <p>
                                    Completa los datos para revisar tu reserva.
                                </p>

                            </div>

                            <hr class="my-4">

                            <!-- FORMULARIO DE CONFIRMACION -->
                            <form
                                action="/PJF/reserva/guardar"
                                method="POST"
                                id="form-reserva"
                            >

                                <input
                                    type="hidden"
                                    name="idMesa"
                                    id="idMesa"
                                >

                                <input
                                    type="hidden"
                                    name="personas"
                                    id="personasReserva"
                                >

                                <input
                                    type="hidden"
                                    name="fecha"
                                    id="fechaReserva"
                                >

                                <input
                                    type="hidden"
                                    name="hora"
                                    id="horaReserva"
                                >

                                <div class="d-grid col-12 col-md-6 mx-auto">

                                    <button
                                        type="submit"
                                        class="btn btn-primary btn-lg"
                                    >
                                        Confirmar reserva
                                    </button>

                                </div>

                            </form>

                            <!-- MENSAJES DE VALIDACION -->
                            <div
                                id="mensaje-reserva"
                                class="mt-3 text-center"
                                aria-live="polite"
                            ></div>

                        </div>

                    </div>

                </div>

            </div>
        </section>

    </main>

    <!-- MODAL DE AUTENTICACION -->
    <?php require_once __DIR__ . '/../components/authModal.php'; ?>

    <!-- BOOTSTRAP JS -->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js">
    </script>

    <!-- JS DE RESERVAS -->
    <script src="/PJF/public/js/reserva.js"></script>

    <!-- JS DE AUTENTICACION -->
    <script src="/PJF/public/js/login.js"></script>

</body>
</html>
