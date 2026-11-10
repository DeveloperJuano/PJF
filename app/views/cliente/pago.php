<!doctype html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pago de tu pedido</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="/PJF/public/css/style.css">
</head>
<body>

    <?php require_once __DIR__ . '/../components/navbar.php'; ?>

    <main class="container py-4" style="max-width: 760px;">

        <header class="mb-4">
            <h1 class="h2 mb-1">Resumen y pago</h1>
            <p class="text-secondary mb-0">Revisa tu pedido antes de enviarlo.</p>
        </header>

        <?php if (isset($_SESSION['mensaje_pedido'])): ?>

            <div class="alert alert-warning" role="alert">
                <?= e($_SESSION['mensaje_pedido']) ?>
            </div>

            <?php unset($_SESSION['mensaje_pedido']); ?>

        <?php endif; ?>

        <!-- PRODUCTOS (los precios salen de la base de datos) -->
        <section class="card mb-4">
            <div class="card-body">
                <ul class="list-unstyled mb-3">
                    <?php foreach ($resumen['lineas'] as $linea): ?>
                        <li class="d-flex justify-content-between">
                            <span><?= (int) $linea['cantidad'] ?> × <?= e($linea['nombre']) ?></span>
                            <span><?= e(dinero($linea['subtotal'])) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <p class="fw-bold fs-5 d-flex justify-content-between mb-0">
                    <span>Total</span>
                    <span><?= e(dinero($resumen['total'])) ?></span>
                </p>
            </div>
        </section>

        <?php if (!$reservas): ?>

            <div class="alert alert-info">
                Para hacer un pedido necesitas una <strong>reserva activa</strong>
                (desde unos minutos antes de tu hora y hasta que termine).
                <a href="/PJF/reserva" class="alert-link">Reservar una mesa</a>
            </div>

            <a href="/PJF/menu" class="btn btn-outline-secondary">Volver a la carta</a>

        <?php else: ?>

            <form action="/PJF/pedido/pagar" method="POST">

                <?= Csrf::campo() ?>

                <!-- RESERVA -->
                <section class="mb-4">
                    <h2 class="h5">¿En qué reserva lo pides?</h2>

                    <?php foreach ($reservas as $indice => $reserva): ?>
                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="idReserva"
                                id="reserva-<?= (int) $reserva['idReserva'] ?>"
                                value="<?= (int) $reserva['idReserva'] ?>"
                                <?= $indice === 0 ? 'checked' : '' ?>
                            >
                            <label class="form-check-label" for="reserva-<?= (int) $reserva['idReserva'] ?>">
                                Mesa <?= e($reserva['numeroMesa']) ?>
                                · <?= e(substr($reserva['hora'], 0, 5)) ?>
                                (<?= e($reserva['fecha']) ?>)
                            </label>
                        </div>
                    <?php endforeach; ?>
                </section>

                <!-- PAGO SIMULADO -->
                <section class="mb-4">
                    <h2 class="h5">Método de pago</h2>

                    <div class="alert alert-secondary small">
                        Pantalla <strong>representativa</strong>: no se cobra nada
                        ni se piden datos de tarjeta.
                    </div>

                    <?php foreach ($metodos as $indice => $metodo): ?>
                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="metodo"
                                id="metodo-<?= e($metodo) ?>"
                                value="<?= e($metodo) ?>"
                                <?= $indice === 0 ? 'checked' : '' ?>
                            >
                            <label class="form-check-label" for="metodo-<?= e($metodo) ?>">
                                <?= e(strtoupper($metodo)) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </section>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        Pagar <?= e(dinero($resumen['total'])) ?> (simulado)
                    </button>
                    <a href="/PJF/menu" class="btn btn-outline-secondary">Volver a la carta</a>
                </div>

            </form>

        <?php endif; ?>

    </main>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js">
    </script>

</body>
</html>