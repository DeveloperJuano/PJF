
<!doctype html>
<html lang="es" data-bs-theme="dark">
    <head>
        <title>Menu</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <!-- Bootstrap CSS v5.3.8 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
            crossorigin="anonymous"
        />
        <!-- CSS propio -->
        <link rel="stylesheet" href="/PJF/public/css/style.css">
    </head>
    
    <body>
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
    <!-- Encabezado menu -->
    <main>
        <section class="">

            <div
                class="container"
            >
                
                <div class="text-center mb-5">

                    <h1>
                        Carta 
                    </h1>

                    <p>
                        Conoce algunos de nuestros platos.
                    </p>

            
                </div>
            </div>
        </section>
        
        <!-- CATEGORIAS -->
        <section class="py-5">
            
            <div class="container text-center">
                
                <!-- seleccion  de categoria -->
                <div class="d-flex justify-content-center flex-wrap gap-2">

                    <button type="button" class="btn btn-primary" data-filtro="todo">
                        Todo
                    </button>
                    <button type="button" class="btn btn-outline-primary" data-filtro="entradas">
                        Entradas
                    </button>
                    <button type="button" class="btn btn-outline-primary" data-filtro="plato-fuerte">
                        Plato Fuerte
                    </button>
                    <button type="button" class="btn btn-outline-primary" data-filtro="postres">
                        Postre
                    </button>
                    <button type="button" class="btn btn-outline-primary" data-filtro="bebidas">
                        Bebidas
                    </button>

                </div>
                
            </div>

        </section>
        <!-- Pedido -->
        <section class="py-5 ">
            <div
                class="container"
            >
                <h2>Mi pedido</h2>
                <div id="pedido">

                </div>
            </div>
            

        </section>
        
        <!-- PLATOS -->
        <section class="py-5">
            
            <div class="container">
                <div class="row">
                    <?php foreach ($productos as $producto):?>
                    <div class="col-md-4 mb-4">

                        <article class="card" data-categoria="<?= $producto['categoria'] ?>">

                            <img
                                src="/PJF/public/img/<?= $producto['imagen'] ?>"
                                class="card-img-top"
                                alt="<?= $producto['nombre'] ?>"
                            >

                            <div class="card-body">

                                <h5 class="card-title">
                                    <?= $producto['nombre'] ?>
                                </h5>

                                <p class="card-text">
                                    <?= $producto['descripcion'] ?>
                                </p>

                                <span class="fw-bold">
                                    <?= $producto['precio'] ?>
                                </span>

                                <button type="button" 
                                    class="btn" 
                                    data-agregar="<?= $producto['nombre'] ?>"
                                    data-precio="<?= $producto['precio'] ?>"
                                >
                                    Agregar
                                </button>
                                

                                
                            </div>
                        </article>

                    </div>
                    <?php endforeach;?>


                </div>

            </div>

        </section>

    </main>
    <!-- pie -->
    <footer>

    </footer>
    <script src="/PJF/public/js/menu.js"></script>
    </body>
</html>
