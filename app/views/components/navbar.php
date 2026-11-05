<nav class="navbar navbar-expand-lg">

    <div class="container">

        <a class="navbar-brand" href="/PJF">
            Restaurante
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <?php if (isset($_SESSION['idUsuario'])): ?>
                <span class="navbar-text saludo-usuario me-auto">
                    Hola, <?= htmlspecialchars($_SESSION['nombre'], ENT_QUOTES, 'UTF-8') ?>
                </span>
            <?php endif; ?>

            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link" href="/PJF">Inicio</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/PJF/menu">Menú</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/PJF/reserva">Reservar mesa</a>
                </li>

                <?php if (isset($_SESSION['idUsuario'])): ?>

                    <?php if (($_SESSION['rol'] ?? '') === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/PJF/admin/mesas">Panel admin</a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="nav-link" href="/PJF/logout">Cerrar sesión</a>
                    </li>

                <?php else: ?>

                    <li class="nav-item">
                        <a class="nav-link" href="/PJF/login">Iniciar sesión</a>
                    </li>

                <?php endif; ?>

            </ul>

        </div>
    </div>

</nav>