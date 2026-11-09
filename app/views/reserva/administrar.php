
<!doctype html>
<html lang="es" data-bs-theme="dark">

<head>
    <title>Administrar reservas</title>

    <!-- META TAGS -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

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

    <main class="container py-5">

        <!-- ========================================================== -->
        <!-- ENCABEZADO -->
        <!-- ========================================================== -->

        <div class="mb-4">

            <h1 class="fw-bold">
                Gestión de reservas
            </h1>

            <p class="text-body-secondary">
                Consulta las reservas del restaurante,
                contacta a los clientes y confirma su asistencia.
            </p>

        </div>

        <!-- ========================================================== -->
        <!-- MENSAJES DEL SERVIDOR -->
        <!-- ========================================================== -->

        <?php if (isset($_SESSION['mensaje_admin_reserva'])): ?>

            <div class="alert alert-info alert-dismissible fade show" role="alert">

                <?= htmlspecialchars($_SESSION['mensaje_admin_reserva']) ?>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Cerrar"
                ></button>

            </div>

            <?php unset($_SESSION['mensaje_admin_reserva']); ?>

        <?php endif; ?>

        <!-- ========================================================== -->
        <!-- CATEGORIAS DE RESERVAS -->
        <!-- ========================================================== -->

        <div class="d-flex flex-wrap gap-2 mb-4">

            <!-- RESERVAS PROXIMAS -->
            <a
                href="/PJF/reserva/administrar?categoria=proximas"
                class="btn <?= $categoria === 'proximas' ? 'btn-primary' : 'btn-outline-primary' ?>"
            >
                Próximas
            </a>

            <!-- RESERVAS PASADAS -->
            <a
                href="/PJF/reserva/administrar?categoria=pasadas"
                class="btn <?= $categoria === 'pasadas' ? 'btn-primary' : 'btn-outline-primary' ?>"
            >
                Pasadas
            </a>

            <!-- TODAS LAS RESERVAS -->
            <a
                href="/PJF/reserva/administrar?categoria=todas"
                class="btn <?= $categoria === 'todas' ? 'btn-primary' : 'btn-outline-primary' ?>"
            >
                Todas
            </a>

        </div>

        <!-- ========================================================== -->
        <!-- PANEL DE RESERVAS -->
        <!-- ========================================================== -->

        <section class="card border-secondary shadow-sm">

            <!-- TITULO DEL PANEL -->
            <div class="card-header py-3">

                <h2 class="h5 mb-0">

                    <?php if ($categoria === 'proximas'): ?>

                        Reservas próximas

                    <?php elseif ($categoria === 'pasadas'): ?>

                        Reservas pasadas

                    <?php else: ?>

                        Todas las reservas

                    <?php endif; ?>

                </h2>

            </div>

            <div class="card-body">

                <!-- ========================================================== -->
                <!-- TABLA DE RESERVAS -->
                <!-- ========================================================== -->

                <div class="table-responsive">

                    <table class="table table-dark table-hover align-middle">

                        <!-- ENCABEZADOS DE TABLA -->
                        <thead>

                            <tr>
                                <th>Cliente</th>
                                <th>Teléfono</th>
                                <th>Mesa</th>
                                <th>Personas</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>

                        </thead>

                        <!-- CUERPO DE TABLA -->
                        <tbody>

                            <?php foreach ($reservas as $reserva): ?>

                                <?php
                                    // VALIDAR SI LA RESERVA ES DE HOY O POSTERIOR
                                    $esFutura = $reserva['fecha'] >= date('Y-m-d');
                                ?>

                                <tr>

                                    <!-- CLIENTE -->
                                    <td>
                                        <?= htmlspecialchars($reserva['cliente']) ?>
                                    </td>

                                    <!-- TELEFONO DEL CLIENTE -->
                                    <td>

                                        <?php if (!empty($reserva['telefono'])): ?>

                                            <a
                                                href="tel:<?= htmlspecialchars($reserva['telefono']) ?>"
                                                class="text-decoration-none"
                                            >
                                                <?= htmlspecialchars($reserva['telefono']) ?>
                                            </a>

                                        <?php else: ?>

                                            <span class="text-body-secondary">
                                                Sin teléfono
                                            </span>

                                        <?php endif; ?>

                                    </td>

                                    <!-- NUMERO DE MESA -->
                                    <td>
                                        Mesa <?= (int) $reserva['numeroMesa'] ?>
                                    </td>

                                    <!-- CANTIDAD DE PERSONAS -->
                                    <td>
                                        <?= (int) $reserva['personas'] ?>
                                    </td>

                                    <!-- FECHA DE RESERVA -->
                                    <td>
                                        <?= htmlspecialchars($reserva['fecha']) ?>
                                    </td>

                                    <!-- HORA DE RESERVA -->
                                    <td>
                                        <?= htmlspecialchars(substr($reserva['hora'], 0, 5)) ?>
                                    </td>

                                    <!-- ========================================================== -->
                                    <!-- ESTADO DE RESERVA -->
                                    <!-- ========================================================== -->

                                    <td>

                                        <?php if ($reserva['estado'] === 'pendiente'): ?>

                                            <span class="badge text-bg-warning">
                                                Pendiente
                                            </span>

                                        <?php elseif ($reserva['estado'] === 'confirmada'): ?>

                                            <span class="badge text-bg-success">
                                                Confirmada
                                            </span>

                                        <?php elseif ($reserva['estado'] === 'cancelada'): ?>

                                            <span class="badge text-bg-danger">
                                                Cancelada
                                            </span>

                                        <?php else: ?>

                                            <span class="badge text-bg-secondary">
                                                <?= htmlspecialchars($reserva['estado']) ?>
                                            </span>

                                        <?php endif; ?>

                                    </td>

                                    <!-- ========================================================== -->
                                    <!-- ACCIONES DE RESERVA -->
                                    <!-- ========================================================== -->

                                    <td>

                                        <div class="d-flex flex-wrap gap-2">

                                            <!-- CONFIRMAR RESERVA -->
                                            <?php if (
                                                $esFutura &&
                                                $reserva['estado'] === 'pendiente'
                                            ): ?>

                                                <form
                                                    action="/PJF/reserva/confirmar"
                                                    method="POST"
                                                >

                                                    <input
                                                        type="hidden"
                                                        name="idReserva"
                                                        value="<?= (int) $reserva['idReserva'] ?>"
                                                    >

                                                    <button
                                                        type="submit"
                                                        class="btn btn-success btn-sm"
                                                        onclick="return confirm('¿El cliente confirmó su asistencia?');"
                                                    >
                                                        Confirmar
                                                    </button>

                                                </form>

                                            <?php endif; ?>

                                            <!-- CANCELAR RESERVA -->
                                            <?php if (
                                                $esFutura &&
                                                in_array(
                                                    $reserva['estado'],
                                                    ['pendiente', 'confirmada'],
                                                    true
                                                )
                                            ): ?>

                                                <form
                                                    action="/PJF/reserva/cancelar"
                                                    method="POST"
                                                >

                                                    <input
                                                        type="hidden"
                                                        name="idReserva"
                                                        value="<?= (int) $reserva['idReserva'] ?>"
                                                    >

                                                    <button
                                                        type="submit"
                                                        class="btn btn-outline-danger btn-sm"
                                                        onclick="return confirm('¿Estás seguro de cancelar esta reserva?');"
                                                    >
                                                        Cancelar
                                                    </button>

                                                </form>

                                            <?php endif; ?>

                                            <!-- RESERVAS SIN ACCIONES -->
                                            <?php if (
                                                !$esFutura ||
                                                $reserva['estado'] === 'cancelada'
                                            ): ?>

                                                <span class="text-body-secondary small">
                                                    Sin acciones
                                                </span>

                                            <?php endif; ?>

                                        </div>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

                <!-- ========================================================== -->
                <!-- MENSAJE CUANDO NO EXISTEN RESERVAS -->
                <!-- ========================================================== -->

                <?php if (empty($reservas)): ?>

                    <div class="text-center py-5">

                        <h3 class="h5">
                            No hay reservas registradas
                        </h3>

                        <p class="text-body-secondary mb-0">

                            <?php if ($categoria === 'proximas'): ?>

                                No hay reservas próximas por gestionar.

                            <?php elseif ($categoria === 'pasadas'): ?>

                                Todavía no hay reservas pasadas.

                            <?php else: ?>

                                Todavía no existen reservas registradas.

                            <?php endif; ?>

                        </p>

                    </div>

                <?php endif; ?>

            </div>

        </section>

    </main>

    <!-- ========================================================== -->
    <!-- MODAL DE AUTENTICACION -->
    <!-- ========================================================== -->

    <?php require_once __DIR__ . '/../components/authModal.php'; ?>

    <!-- ========================================================== -->
    <!-- BOOTSTRAP JS -->
    <!-- ========================================================== -->

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"
    ></script>

    <!-- JS DE AUTENTICACION -->
    <script src="/PJF/public/js/login.js"></script>

</body>
</html>
