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

            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link" href="/PJF">
                        Inicio
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/PJF/menu">
                        Menú
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/PJF/reserva">
                        Reservar mesa
                    </a>
                </li>




                <?php if (isset($_SESSION['idUsuario'])): ?>

                    <?php if (($_SESSION['rol'] ?? '') === 'admin'): ?>

                        <!-- <li> del panel del admin-->
                        <li class="nav-item">
                            <a class="nav-link" href="/PJF/admin/mesas">
                                admin test
                            </a>
                        </li>

                    <?php endif; ?>

                    <!-- <li> con el enlace a /PJF/logout -->

                    <li class="nav-item">
                        <a class="nav-link" href="/PJF/logout">
                            Cerrar Sesion
                        </a>
                    </li>

                    <!-- <li> con el nombre -->

                    <li class="nav-item d-flex align-items-center">
                        <span class="navbar-text saludo-usuario">
                            Hola, <?= htmlspecialchars($_SESSION['nombre'], ENT_QUOTES, 'UTF-8')  ?>
                        </span>
                    </li>

                <?php else: ?>

                    <!-- <li> para iniciar sesion-->

                    <li class="nav-item">
                        <a class="nav-link" href="/PJF/login">
                            Iniciar sesión
                        </a>
                    </li>

                <?php endif; ?>
            </ul>

        </div>
    </div>

</nav>