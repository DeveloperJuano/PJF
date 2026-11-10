<?php
// Recibe $datos desde EmpleadoPedidoController (panel o fragmento)
$porConfirmar = $datos['porConfirmar'];
$cola = $datos['cola'];
$porPagar = $datos['porPagar'];
?>

<!-- ========================================================= -->
<!-- POR CONFIRMAR -->
<!-- ========================================================= -->
<section class="mb-5">

    <h2 class="h4 mb-3">
        Por confirmar
        <span class="badge text-bg-warning"><?= count($porConfirmar) ?></span>
    </h2>

    <?php if (!$porConfirmar): ?>
        <p class="text-secondary">No hay pedidos esperando confirmación.</p>
    <?php endif; ?>

    <div class="row g-3">

        <?php foreach ($porConfirmar as $pedido): ?>

            <div class="col-12 col-md-6 col-xl-4">
                <article class="card h-100">
                    <div class="card-body">

                        <h3 class="h5">
                            <?= e(lugar($pedido['mesa'])) ?> · <?= e($pedido['nombre']) ?>
                        </h3>

                        <?php if ($pedido['fechaHoraProgramada']): ?>
                            <span class="badge text-bg-info">
                                Recoge: <?= e(fechaCorta($pedido['fechaHoraProgramada'])) ?>
                            </span>
                        <?php endif; ?>

                        <?php if ((int) $pedido['pagado'] === 1): ?>
                            <span class="badge text-bg-success">Pagado en línea</span>
                        <?php endif; ?>

                        <p class="text-secondary small mb-2">
                            Pedido #<?= (int) $pedido['idPedido'] ?>
                            · tomado a las <?= e(substr($pedido['fechaHoraInicio'], 11, 5)) ?>
                        </p>

                        <ul class="list-unstyled mb-3">
                            <?php foreach ($pedido['lineas'] as $linea): ?>
                                <li>
                                    <?= (int) $linea['cantidadProducto'] ?> × <?= e($linea['producto']) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                        <p class="fw-bold"><?= e(dinero($pedido['total'])) ?></p>

                        <div class="d-flex gap-2">

                            <form action="/PJF/empleado/pedidos/confirmar" method="POST">
                                <?= Csrf::campo() ?>
                                <input type="hidden" name="idPedido" value="<?= (int) $pedido['idPedido'] ?>">
                                <button type="submit" class="btn btn-success">Confirmar</button>
                            </form>

                            <form
                                action="/PJF/empleado/pedidos/cancelar"
                                method="POST"
                                onsubmit="return confirm('¿Cancelar este pedido?');"
                            >
                                <?= Csrf::campo() ?>
                                <input type="hidden" name="idPedido" value="<?= (int) $pedido['idPedido'] ?>">
                                <button type="submit" class="btn btn-outline-danger">Cancelar</button>
                            </form>

                        </div>

                    </div>
                </article>
            </div>

        <?php endforeach; ?>

    </div>

</section>

<!-- ========================================================= -->
<!-- COLA -->
<!-- ========================================================= -->
<section class="mb-5">

    <h2 class="h4 mb-3">
        Cola de preparación
        <span class="badge text-bg-primary"><?= count($cola) ?></span>
    </h2>

    <?php if (!$cola): ?>
        <p class="text-secondary">No hay productos en la cola.</p>
    <?php else: ?>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Lugar</th>
                        <th>Cliente</th>
                        <th>Pedido</th>
                        <th>Producto</th>
                        <th>Espera</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($cola as $fila): ?>

                        <tr>
                            <td><?= e(lugar($fila['mesa'])) ?></td>
                            <td><?= e($fila['cliente']) ?></td>
                            <td>
                                #<?= (int) $fila['idPedido'] ?>
                                <?php if ($fila['fechaHoraProgramada']): ?>
                                    <br><small class="text-info">Recoge <?= e(fechaCorta($fila['fechaHoraProgramada'])) ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?= (int) $fila['cantidadProducto'] ?> × <?= e($fila['producto']) ?></td>
                            <td><?= minutos($fila['segundosEspera']) ?> min</td>
                            <td>
                                <?php if ((int) $fila['estado'] === EstadoProducto::EN_COLA): ?>
                                    <span class="badge text-bg-secondary">
                                        <?= e(EstadoProducto::etiqueta($fila['estado'])) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge text-bg-info">
                                        <?= e(EstadoProducto::etiqueta($fila['estado'])) ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <form action="/PJF/empleado/productos/avanzar" method="POST">
                                    <?= Csrf::campo() ?>
                                    <input type="hidden" name="idDetalle" value="<?= (int) $fila['idDetalle'] ?>">

                                    <?php if ((int) $fila['estado'] === EstadoProducto::EN_COLA): ?>
                                        <button type="submit" class="btn btn-sm btn-outline-info">
                                            Pasar a sirviendo
                                        </button>
                                    <?php else: ?>
                                        <button type="submit" class="btn btn-sm btn-success">
                                            Marcar servido
                                        </button>
                                    <?php endif; ?>
                                </form>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>

    <?php endif; ?>

</section>

<!-- ========================================================= -->
<!-- POR PAGAR -->
<!-- ========================================================= -->
<section class="mb-5">

    <h2 class="h4 mb-3">
        Por pagar
        <span class="badge text-bg-success"><?= count($porPagar) ?></span>
    </h2>

    <?php if (!$porPagar): ?>
        <p class="text-secondary">No hay pedidos esperando pago.</p>
    <?php endif; ?>

    <div class="row g-3">

        <?php foreach ($porPagar as $pedido): ?>

            <div class="col-12 col-md-6 col-xl-4">
                <article class="card h-100">
                    <div class="card-body">

                        <h3 class="h5">
                            <?= e(lugar($pedido['mesa'])) ?> · <?= e($pedido['nombre']) ?>
                        </h3>

                        <?php if ($pedido['fechaHoraProgramada']): ?>
                            <span class="badge text-bg-info">
                                Recoge: <?= e(fechaCorta($pedido['fechaHoraProgramada'])) ?>
                            </span>
                        <?php endif; ?>

                        <?php if ((int) $pedido['pagado'] === 1): ?>
                            <span class="badge text-bg-success">Pagado en línea</span>
                        <?php endif; ?>

                        <p class="text-secondary small mb-2">
                            Pedido #<?= (int) $pedido['idPedido'] ?>
                        </p>

                        <ul class="list-unstyled mb-3">
                            <?php foreach ($pedido['lineas'] as $linea): ?>
                                <li>
                                    <?= (int) $linea['cantidadProducto'] ?> × <?= e($linea['producto']) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                        <p class="fw-bold fs-5"><?= e(dinero($pedido['total'])) ?></p>

                        <form action="/PJF/empleado/pedidos/pagar" method="POST" class="d-flex flex-wrap gap-2">
                            <?= Csrf::campo() ?>
                            <input type="hidden" name="idPedido" value="<?= (int) $pedido['idPedido'] ?>">

                            <button type="submit" name="metodo" value="efectivo" class="btn btn-success">
                                Efectivo
                            </button>
                            <button type="submit" name="metodo" value="tarjeta" class="btn btn-outline-success">
                                Tarjeta
                            </button>
                            <button type="submit" name="metodo" value="transferencia" class="btn btn-outline-success">
                                Transferencia
                            </button>
                        </form>

                    </div>
                </article>
            </div>

        <?php endforeach; ?>

    </div>

</section>
