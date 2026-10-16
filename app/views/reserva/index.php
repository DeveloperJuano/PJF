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
        <?php require_once __DIR__ . '/../components/navbar.php'; ?>
        
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
                            required
                        >
                        <div id="mensaje-capacidad" class="mt-2"></div>
                    </div>

                    <!-- FECHA DE RESERVA -->
                    <div class="mt-4 text-center">
                        <label for="fecha" class="form-label">
                            Fecha de la reserva
                        </label>

                        <input
                            type="date"
                            id="fecha"
                            class="form-control mx-auto"
                            style="max-width: 250px;"
                            required
                        >
                    </div>

                    <!-- HORA DE RESERVA -->
                    <div class="mt-4 text-center">
                        <label for="hora" class="form-label">
                            Hora de la reserva
                        </label>

                        <input
                            type="time"
                            id="hora"
                            class="form-control mx-auto"
                            style="max-width: 250px;"
                            required
                        >
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
                        
                        <!-- RESUMEN DE LA RESERVA -->
                        <div class="text-center mt-5">
                            <h3>Resumen de la reserva</h3>

                            <div id="resumen-reserva" class="mt-3">
                                <p>Completa los datos para revisar tu reserva.</p>
                            </div>

                            <button
                                type="button"
                                id="btn-confirmar-reserva"
                                class="btn btn-primary mt-3"
                            >
                                Confirmar reserva
                            </button>

                            <div id="mensaje-reserva" class="mt-3"></div>
                        </div>

                    </div>
                </div>
            </section>  

        </main>
    <script src="/PJF/public/js/reserva.js"></script>
    </body>
</html>
