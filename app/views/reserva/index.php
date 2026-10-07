<!doctype html>
<html lang="es" data-bs-theme="dark">
    <head>
        <title>Reserva</title>
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
        <!-- nav -->
        <?php require_once __DIR__ . '/../components/navbar.php'; ?><?php require_once __DIR__ . '/../components/navbar.php'; ?>
        
        <main>
            <section class="">

                <div
                    class="container"
                >
                    
                    <div class="text-center mb-5">

                        <h1>
                            Reserva
                        </h1>

                        <p>
                            Reserva la mesa que mas se acomode a tu evento
                        </p>

                    </div>
                </div>
            </section>

            <!-- MESAS -->
            <section class="py-5">
                <div class="container">
                    <!-- INDICADOR DE MESA -->
                    <div id="mesa-seleccionada" class="text-center mt-4">
                        <p>No has seleccionado ninguna mesa.</p>
                    </div>
                    <!-- INFORMACION CLIENTE -->
                    <div class="mt-4 text-center">
                        
                        <label for="personas" class="form-label">
                            ¿Para cuántas personas?
                        </label>

                        <input
                            type="number"
                            id="personas"
                            class="form-control mx-auto"
                            min="1"
                            style="max-width: 200px;"
                        >
                        <div id="mensaje-capacidad" class="mt-2"></div>
                    </div>
                    <div class="row">
                        <?php foreach ($mesas as $mesa):?>
                        <div class="col-md-4 mb-4">
                            <article class="mesa mesa-<?= $mesa['estado'] ?>" >

                                <div class="mesa-superior">

                                    <span> MESA  <?= $mesa['numero'] ?> </span>
                                </div>

                                <div class="mesa-centro">
                                    <h4><?= $mesa['numero'] ?></h4>
                                    <p><?= $mesa['capacidad'] ?> personas</p>
                                    <span><?= $mesa['estado'] ?></span>
                                </div>
                                <!-- BOTON AGREGAR -->
                                <?php if ($mesa['estado'] === 'disponible'): ?>

                                    <button type="button" 
                                    class="btn-agregar-mesa"
                                    data-mesa= "<?= $mesa['numero'] ?>"
                                    data-capacidad="<?= $mesa['capacidad'] ?>"
                                    >
                                        Agregar
                                    </button>

                                <?php endif; ?>
                            </article>
                            
                        </div>
                        <?php endforeach;?>
                    </div>
                </div>
            </section>  

        </main>
    <script src="/PJF/public/js/reserva.js"></script>
    </body>
</html>
