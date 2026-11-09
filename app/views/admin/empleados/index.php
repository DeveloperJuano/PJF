
<?php
$mensaje = $_SESSION['mensaje_empleado'] ?? '';
$mensajeExito = $_SESSION['mensaje_empleado_exito'] ?? '';

unset(
    $_SESSION['mensaje_empleado'],
    $_SESSION['mensaje_empleado_exito']
);
?>

<!doctype html>
<html lang="es" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Administrar empleados | PJF</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link rel="stylesheet" href="/PJF/public/css/style.css">
</head>

<body>

<?php require_once __DIR__ . '/../../components/navbar.php'; ?>

<main class="container py-5">

    <!-- ENCABEZADO -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">

        <div>
            <a href="/PJF/admin"
               class="text-secondary text-decoration-none">
                &larr; Volver al panel administrativo
            </a>

            <h1 class="mt-4">Administrar empleados</h1>

            <p class="text-secondary">
                Consulta, actualiza y administra las cuentas de los empleados.
            </p>
        </div>

        <a href="/PJF/admin/empleado/registrar"
           class="btn btn-light rounded-pill px-4">
            + Registrar empleado
        </a>

    </div>

    <!-- MENSAJES -->
    <?php if ($mensaje !== ''): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <?php if ($mensajeExito !== ''): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($mensajeExito, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <!-- LISTADO -->
    <section class="card p-4">

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

            <h2 class="h4 mb-0">Empleados registrados</h2>

            <span class="text-secondary">
                Total: <?= count($empleados) ?>
            </span>

        </div>

        <?php if (empty($empleados)): ?>

            <div class="alert alert-secondary mb-0">
                Todavía no hay empleados registrados.
            </div>

        <?php else: ?>

            <div class="table-responsive">

                <table class="table table-dark table-striped align-middle">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Correo electrónico</th>
                            <th>Teléfono</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($empleados as $empleado): ?>

                            <?php
                            $id = (int) $empleado['idUsuario'];
                            $formEditar = 'editar-empleado-' . $id;
                            ?>

                            <tr>

                                <!-- ID -->
                                <td>
                                    <?= $id ?>
                                </td>

                                <!-- NOMBRE -->
                                <td>
                                    <input
                                        type="text"
                                        name="nombre"
                                        form="<?= $formEditar ?>"
                                        class="form-control"
                                        maxlength="100"
                                        required
                                        value="<?= htmlspecialchars($empleado['nombre'], ENT_QUOTES, 'UTF-8') ?>">
                                </td>

                                <!-- CORREO -->
                                <td>
                                    <input
                                        type="email"
                                        name="correo"
                                        form="<?= $formEditar ?>"
                                        class="form-control"
                                        maxlength="150"
                                        required
                                        value="<?= htmlspecialchars($empleado['correo'], ENT_QUOTES, 'UTF-8') ?>">
                                </td>

                                <!-- TELÉFONO -->
                                <td>
                                    <input
                                        type="tel"
                                        name="telefono"
                                        form="<?= $formEditar ?>"
                                        class="form-control"
                                        maxlength="10"
                                        pattern="[0-9]{10}"
                                        required
                                        value="<?= htmlspecialchars($empleado['telefono'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                </td>

                                <!-- ESTADO -->
                                <td>

                                    <?php if ($empleado['estado'] === 'activo'): ?>

                                        <span class="badge text-bg-success">
                                            Activo
                                        </span>

                                    <?php else: ?>

                                        <span class="badge text-bg-secondary">
                                            Inactivo
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <!-- ACCIONES -->
                                <td>

                                    <div class="d-flex flex-column gap-2">

                                        <!-- FORMULARIO DE ACTUALIZACIÓN -->
                                        <form
                                            id="<?= $formEditar ?>"
                                            action="/PJF/admin/empleado/actualizar"
                                            method="POST">

                                            <input
                                                type="hidden"
                                                name="idUsuario"
                                                value="<?= $id ?>">

                                            <button
                                                type="submit"
                                                class="btn btn-outline-primary btn-sm w-100">
                                                Guardar
                                            </button>

                                        </form>

                                        <!-- FORMULARIO DE ESTADO -->
                                        <form
                                            action="/PJF/admin/empleado/estado"
                                            method="POST">

                                            <input
                                                type="hidden"
                                                name="idUsuario"
                                                value="<?= $id ?>">

                                            <?php if ($empleado['estado'] === 'activo'): ?>

                                                <input
                                                    type="hidden"
                                                    name="estado"
                                                    value="inactivo">

                                                <button
                                                    type="submit"
                                                    class="btn btn-outline-danger btn-sm w-100">
                                                    Desactivar
                                                </button>

                                            <?php else: ?>

                                                <input
                                                    type="hidden"
                                                    name="estado"
                                                    value="activo">

                                                <button
                                                    type="submit"
                                                    class="btn btn-outline-success btn-sm w-100">
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
