
<?php
$mensajeError = $_SESSION['mensaje_empleado'] ?? null;
$mensajeExito = $_SESSION['mensaje_empleado_exito'] ?? null;

unset(
    $_SESSION['mensaje_empleado'],
    $_SESSION['mensaje_empleado_exito']
);
?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Registrar empleado | PJF</title>

    <!-- BOOTSTRAP 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- CSS PRINCIPAL -->
    <link rel="stylesheet" href="/PJF/public/css/style.css">
</head>

<body>

    <!-- NAVBAR -->
    <?php require_once __DIR__ . '/../../components/navbar.php'; ?>

    <main class="container py-5">

        <div class="row justify-content-center">

            <div class="col-12 col-lg-9 col-xl-8">

                <!-- TARJETA PRINCIPAL -->
                <div class="registro-empleado-card">

                    <!-- ENCABEZADO DENTRO DE LA TARJETA -->
                    <div class="registro-empleado-header">

                        <a href="/PJF/admin"
                           class="text-secondary text-decoration-none small">
                            &larr; Volver al panel administrativo
                        </a>

                        <p class="text-secondary small fw-semibold mt-4 mb-2">
                            ADMINISTRACIÓN / PERSONAL
                        </p>

                        <h1 class="fw-bold mb-2">
                            Registrar empleado
                        </h1>

                        <p class="text-secondary mb-0">
                            Completa los siguientes datos para crear
                            una nueva cuenta de empleado.
                        </p>

                    </div>

                    <!-- CONTENIDO DE LA TARJETA -->
                    <div class="p-4 p-md-5">

                        <!-- MENSAJES -->
                        <?php if ($mensajeError): ?>
                            <div class="alert alert-danger mb-4" role="alert">
                                <?= htmlspecialchars($mensajeError, ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($mensajeExito): ?>
                            <div class="alert alert-success mb-4" role="status">
                                <?= htmlspecialchars($mensajeExito, ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        <?php endif; ?>

                        <!-- FORMULARIO -->
                        <form
                            action="/PJF/admin/empleado/guardar"
                            method="POST">

                            <!-- SECCIÓN 1 -->
                            <div class="mb-4">

                                <h2 class="h5 fw-semibold mb-2">
                                    Datos personales
                                </h2>

                                <p class="text-secondary small mb-4">
                                    Información básica del nuevo empleado.
                                </p>

                                <div class="row g-4">

                                    <!-- NOMBRE -->
                                    <div class="col-md-6">

                                        <label for="nombre" class="form-label">
                                            Nombre completo
                                        </label>

                                        <input
                                            type="text"
                                            class="form-control"
                                            id="nombre"
                                            name="nombre"
                                            maxlength="100"
                                            placeholder="Nombre y apellido"
                                            autocomplete="name"
                                            required>

                                    </div>

                                    <!-- TELÉFONO -->
                                    <div class="col-md-6">

                                        <label for="telefono" class="form-label">
                                            Teléfono
                                        </label>

                                        <input
                                            type="tel"
                                            class="form-control"
                                            id="telefono"
                                            name="telefono"
                                            maxlength="10"
                                            pattern="[0-9]{10}"
                                            placeholder="3001234567"
                                            autocomplete="tel"
                                            required>

                                    </div>

                                    <!-- CORREO -->
                                    <div class="col-12">

                                        <label for="correo" class="form-label">
                                            Correo electrónico
                                        </label>

                                        <input
                                            type="email"
                                            class="form-control"
                                            id="correo"
                                            name="correo"
                                            maxlength="150"
                                            placeholder="empleado@correo.com"
                                            autocomplete="email"
                                            required>

                                    </div>

                                </div>

                            </div>

                            <hr class="registro-empleado-divider">

                            <!-- SECCIÓN 2 -->
                            <div class="mb-4">

                                <h2 class="h5 fw-semibold mb-2">
                                    Credenciales de acceso
                                </h2>

                                <p class="text-secondary small mb-4">
                                    Configura la contraseña para ingresar al sistema.
                                </p>

                                <div class="row g-4">

                                    <!-- CONTRASEÑA -->
                                    <div class="col-md-6">

                                        <label for="password" class="form-label">
                                            Contraseña
                                        </label>

                                        <input
                                            type="password"
                                            class="form-control"
                                            id="password"
                                            name="password"
                                            minlength="8"
                                            placeholder="Mínimo 8 caracteres"
                                            autocomplete="new-password"
                                            required>

                                    </div>

                                    <!-- CONFIRMAR CONTRASEÑA -->
                                    <div class="col-md-6">

                                        <label for="confirmarPassword" class="form-label">
                                            Confirmar contraseña
                                        </label>

                                        <input
                                            type="password"
                                            class="form-control"
                                            id="confirmarPassword"
                                            name="confirmarPassword"
                                            minlength="8"
                                            placeholder="Repite la contraseña"
                                            autocomplete="new-password"
                                            required>

                                    </div>

                                </div>

                                <!-- INFORMACIÓN DEL ROL -->
                                <div class="registro-empleado-info mt-4">

                                    <div class="fw-semibold mb-1">
                                        Rol asignado: Empleado
                                    </div>

                                    <p class="small mb-0">
                                        El usuario tendrá permisos de empleado
                                        y su cuenta se creará en estado activo.
                                    </p>

                                </div>

                            </div>

                            <hr class="registro-empleado-divider">

                            <!-- SECCIÓN 3 -->
                            <div class="mb-4">

                                <h2 class="h5 fw-semibold mb-2">
                                    Tratamiento de datos personales
                                </h2>

                                <p class="text-secondary small mb-4">
                                    Confirma que el empleado autorizó el
                                    tratamiento de sus datos personales.
                                </p>

                                <div class="registro-empleado-consentimiento">

                                    <div class="form-check">

                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            id="confirmarConsentimiento"
                                            name="confirmarConsentimiento"
                                            value="1"
                                            required>

                                        <label
                                            class="form-check-label"
                                            for="confirmarConsentimiento">

                                            Confirmo que el empleado ha sido informado
                                            y ha otorgado la autorización correspondiente
                                            para el tratamiento de sus datos personales.

                                        </label>

                                    </div>

                                    <a
                                        href="https://www.funcionpublica.gov.co/eva/gestornormativo/norma.php?i=49981"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="d-inline-block mt-3">

                                        Consultar Ley 1581 de 2012

                                    </a>

                                </div>

                            </div>

                            <!-- BOTONES -->
                            <div class="registro-empleado-acciones mt-5">

                                <a
                                    href="/PJF/admin"
                                    class="btn btn-outline-secondary rounded-pill px-4">
                                    Cancelar
                                </a>

                                <button
                                    type="submit"
                                    class="btn btn-light rounded-pill px-4 fw-semibold">
                                    Registrar empleado
                                </button>

                            </div>

                        </form>

                    </div>

                </div>
                <!-- FIN TARJETA PRINCIPAL -->

            </div>

        </div>

    </main>

    <!-- BOOTSTRAP JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
