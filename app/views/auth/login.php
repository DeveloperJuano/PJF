<!doctype html>
<html lang="es" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesion</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link rel="stylesheet" href="/PJF/public/css/style.css">
</head>

<body>

    <?php require_once __DIR__ . '/../components/navbar.php'; ?>

    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-4">
                <h1 class="h3 mb-4 text-center">Iniciar sesion</h1>

                <!--Mensaje en caso de error-->
                <?php if (isset($_SESSION['mensaje_login'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= htmlspecialchars($_SESSION['mensaje_login'], ENT_QUOTES, 'UTF-8') ?>
                    </div>
                    <?php unset($_SESSION['mensaje_login']); ?>
                <?php endif; ?>

                <!--Formulario-->
                <div id="mensaje-js" class="alert alert-danger d-none" role="alert"></div>
                <form id="form-login" action="/PJF/login/entrar" method="POST" novalidate>

                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo</label>
                        <input type="email" class="form-control" id="correo" name="correo" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" required>
                            <button type="button" class="btn btn-outline-secondary" id="btn-mostrar-password">Mostrar</button>
                        </div>
                    </div>


                    <button type="submit" class="btn btn-primary">Entrar</button>

                </form>
            </div>
        </div>
    </main>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js">
    </script>
    <script src="/PJF/public/js/login.js"></script>

</body>

</html>