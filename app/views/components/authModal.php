
<?php
$mensajeLogin = $_SESSION['mensaje_login'] ?? null;
$mensajeRegistro = $_SESSION['mensaje_registro'] ?? null;
$mensajeExito = $_SESSION['mensaje_exito'] ?? null;

unset(
    $_SESSION['mensaje_login'],
    $_SESSION['mensaje_registro'],
    $_SESSION['mensaje_exito']
);

$paginaOrigen = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
?>

<div
    class="modal fade"
    id="authModal"
    tabindex="-1"
    aria-labelledby="authModalLabel"
    aria-hidden="true"
    data-error-login="<?= $mensajeLogin ? 'true' : 'false'?>"
    data-error-registro="<?= $mensajeRegistro ? 'true' : 'false' ?>"
    data-exito-registro="<?= $mensajeExito ? 'true' : 'false' ?>">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="authModalLabel">
                    Iniciar sesión
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Cerrar">
                </button>
            </div>

            <div class="modal-body">

                <?php if ($mensajeLogin): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= htmlspecialchars($mensajeLogin, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>

                <div
                    id="mensaje-js"
                    class="alert alert-danger d-none"
                    role="alert">
                </div>

                <div id="contenedorLogin">

                    <?php if ($mensajeExito): ?>
                        <div class="alert alert-success" role="alert">
                            <?= htmlspecialchars($mensajeExito, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endif; ?>
    
                    <!-- FORMULARIO LOGIN -->
                    <form
                        id="form-login"
                        action="/PJF/login/entrar"
                        method="POST"
                        novalidate>
                        <input
                            type="hidden"
                            name="paginaOrigen"
                            value="<?= htmlspecialchars($paginaOrigen, ENT_QUOTES, 'UTF-8') ?>">

                        <div class="mb-3">
                            <label for="correo" class="form-label">
                                Correo electrónico
                            </label>

                            <input
                                type="email"
                                class="form-control"
                                id="correo"
                                name="correo"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                Contraseña
                            </label>

                            <input
                                type="password"
                                class="form-control"
                                id="password"
                                name="password"
                                required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Entrar
                        </button>

                    </form>
                    <!-- LLAMAR A FORMULARIO DE REGISTRO -->
                    <div class="text-center mt-3">
                        <p>
                            ¿No tienes una cuenta?
                            <button
                                type="button"
                                id="mostrarRegistro"
                                class="btn btn-link">
                                Regístrate
                            </button>
                        </p>
                    </div> 
                </div>

                <div id="contenedorRegistro" class="d-none">

                    <?php if ($mensajeRegistro): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= htmlspecialchars($mensajeRegistro, ENT_QUOTES, 'UTF-8') ?>
                        </div>

                    <?php endif; ?>
                    <!--FORMULARIO DE REGISTRO -->
                    <form
                        id="form-registro"
                        action="/PJF/registro/guardar"
                        method="POST">

                        <!-- PAGINA DE ORIGEN -->
                        <input
                            type="hidden"
                            name="paginaOrigen"
                            value="<?= htmlspecialchars($paginaOrigen, ENT_QUOTES, 'UTF-8') ?>">

                        <!-- NOMBRE -->
                        <div class="mb-3">
                            <label for="nombreRegistro" class="form-label">
                                Nombre completo
                            </label>
                            <input
                                type="text"
                                id="nombreRegistro"
                                name="nombre"
                                class="form-control"
                                maxlength="100"
                                required>
                        </div>

                        <!-- CORREO -->
                        <div class="mb-3">
                            <label for="correoRegistro" class="form-label">
                                Correo electrónico
                            </label>
                            <input
                                type="email"
                                id="correoRegistro"
                                name="correo"
                                class="form-control"
                                maxlength="150"
                                required>
                        </div>

                        <!-- TELEFONO -->
                        <div class="mb-3">
                            <label for="telefonoRegistro" class="form-label">
                                Teléfono
                            </label>
                            <input
                                type="tel"
                                id="telefonoRegistro"
                                name="telefono"
                                class="form-control"
                                maxlength="10"
                                pattern="[0-9]{10}"
                                required>
                        </div>

                        <!-- CONTRASEÑA -->
                        <div class="mb-3">
                            <label for="passwordRegistro" class="form-label">
                                Contraseña
                            </label>
                            <input
                                type="password"
                                id="passwordRegistro"
                                name="password"
                                class="form-control"
                                minlength="8"
                                required>
                        </div>

                        <!-- CONFIRMAR CONTRASEÑA -->
                        <div class="mb-3">
                            <label for="confirmarPassword" class="form-label">
                                Confirmar contraseña
                            </label>
                            <input
                                type="password"
                                id="confirmarPassword"
                                name="confirmarPassword"
                                class="form-control"
                                minlength="8"
                                required>
                        </div>

                        <!-- TRATAMIENTO DE DATOS -->
                        <div class="form-check mb-3">
                            <input
                                type="checkbox"
                                id="aceptaTratamiento"
                                name="aceptaTratamiento"
                                value="1"
                                class="form-check-input"
                                required>

                            <label for="aceptaTratamiento" class="form-check-label">
                                Acepto el tratamiento de mis datos personales y declaro
                                haber leído la <a 
                                href="https://www.funcionpublica.gov.co/eva/gestornormativo/norma.php?i=49981"
                                target="_blank"
                                rel="noopener noreferrer">
                                Ley 1581 de 2012</a>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Crear cuenta
                        </button>

                    </form>

                    <div class="text-center mt-3">
                    ¿Ya tienes una cuenta?

                    <button
                        type="button"
                        id="mostrarLogin"
                        class="btn btn-link">
                        Iniciar sesión
                    </button>
                </div>

            

                </div>

            </div>

        </div>
    </div>
</div>
