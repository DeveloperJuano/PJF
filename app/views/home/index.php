<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Restaurante</title>

    <!-- Bootstrap 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- CSS propio -->
    <link rel="stylesheet" href="/PJF/public/css/style.css">
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">

            <a class="navbar-brand" href="#">
                Restaurante
            </a>

            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav"
            >
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">

                <ul class="navbar-nav ms-auto">

                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            Inicio
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/PJF/menu">
                            Menú
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            Reservar mesa
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            Iniciar sesión
                        </a>
                    </li>

                </ul>

            </div>

        </div>
    </nav>


    <!-- HERO -->
    <main>

        <section class="hero">

            <div class="container">

                <div class="row align-items-center">

                    <div class="col-md-6">

                        <h1>
                            Sabor que se disfruta
                        </h1>

                        <p>
                            Disfruta nuestros platos y reserva tu mesa
                            de forma rápida y sencilla.
                        </p>
                        <!-- BOTON VER MENU  -->
                        <a href="#" class="btn btn-primary">
                            Ver menú
                        </a>
                        <!-- RESERVAR MESA -->
                        <a href="#" class="btn btn-outline-primary">
                            Reservar mesa
                        </a>

                    </div>

                    <div class="col-md-6">

                        <img
                            src="/PJF/public/img/hero.jpg"
                            alt="Plato del restaurante"
                            class="img-fluid"
                        >

                    </div>

                </div>

            </div>

        </section>


        <!-- PRESENTACIÓN -->
        <section class="py-5">

            <div class="container text-center">

                <h2>
                    Bienvenidos
                </h2>

                <p>
                    Somos un restaurante dedicado a ofrecer
                    una experiencia agradable, buenos platos
                    y un servicio de calidad.
                </p>

            </div>

        </section>


        <!-- PLATOS DESTACADOS -->
        <section class="py-5">

            <div class="container">

                <div class="text-center mb-5">

                    <h2>
                        Platos destacados
                    </h2>

                    <p>
                        Conoce algunos de nuestros platos.
                    </p>

                </div>

                <div class="row">

                    <div class="col-md-4 mb-4">

                        <div class="card">

                            <img
                                src="/PJF/public/img/plato1.jpg"
                                class="card-img-top"
                                alt="Plato destacado"
                            >

                            <div class="card-body">

                                <h5 class="card-title">
                                    Plato especial
                                </h5>

                                <p class="card-text">
                                    Descripción del plato.
                                </p>

                                <span class="fw-bold">
                                    $25.000
                                </span>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-4 mb-4">

                        <div class="card">

                            <img
                                src="/PJF/public/img/plato2.jpg"
                                class="card-img-top"
                                alt="Plato destacado"
                            >

                            <div class="card-body">

                                <h5 class="card-title">
                                    Plato de la casa
                                </h5>

                                <p class="card-text">
                                    Descripción del plato.
                                </p>

                                <span class="fw-bold">
                                    $30.000
                                </span>

                            </div>

                        </div>

                    </div>


                    <div class="col-md-4 mb-4">

                        <div class="card">

                            <img
                                src="/PJF/public/img/plato3.jpg"
                                class="card-img-top"
                                alt="Plato destacado"
                            >

                            <div class="card-body">

                                <h5 class="card-title">
                                    Especial del día
                                </h5>

                                <p class="card-text">
                                    Descripción del plato.
                                </p>

                                <span class="fw-bold">
                                    $28.000
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>


        <!-- RESERVA -->
        <section class="py-5">

            <div class="container text-center">

                <h2>
                    ¿Quieres reservar una mesa?
                </h2>

                <p>
                    Consulta la disponibilidad y realiza tu reserva.
                </p>

                <a href="#" class="btn btn-primary">
                    Reservar mesa
                </a>

            </div>

        </section>

    </main>


    <!-- FOOTER -->
    <footer class="py-4">

        <div class="container text-center">

            <p class="mb-0">
                © 2026 Restaurante. Todos los derechos reservados.
            </p>

        </div>

    </footer>


    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

</body>
</html>