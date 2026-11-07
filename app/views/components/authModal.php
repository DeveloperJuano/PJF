
<?php
$mensajeLogin = $_SESSION['mensaje_login'] ?? null;
unset($_SESSION['mensaje_login']);
?>

<div
    class="modal fade"
    id="authModal"
    tabindex="-1"
    aria-labelledby="authModalLabel"
    aria-hidden="true">

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

                <form
                    id="form-login"
                    action="/PJF/login/entrar"
                    method="POST"
                    novalidate>

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

            </div>

        </div>
    </div>
</div>
