<!DOCTYPE html>
<html lang="es">
<head>
    <?= $head ?>
    <title><?= $title ?? 'Listado de Pedidos' ?></title>
    <link rel="stylesheet" href="/public/css/crud.css">
    <link rel="stylesheet" href="/public/css/listado.css">
    <style>
        .vistaCajero-toggle {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        .vistaCajero-toggle a {
            flex: 1;
            padding: 10px;
            text-align: center;
            text-decoration: none;
            font-size: 1rem;
            border: none;
            background-color: #eee;
            color: #000;
        }

        .vistaCajero-toggle .active {
            background-color: black;
            color: white;
        }

        .estado-btn {
            padding: 4px 10px;
            margin: 2px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #eee;
            color: #333;
            font-size: 0.9rem;
            cursor: pointer;
        }

        .estado-pendiente {
            background-color: #ffc107;
            color: #000;
            font-weight: bold;
            cursor: default;
        }

        .estado-en_proceso {
            background-color: #17a2b8;
            color: #fff;
            font-weight: bold;
            cursor: default;
        }

        .estado-completado {
            background-color: #28a745;
            color: #fff;
            font-weight: bold;
            cursor: default;
        }

        .estado-btn:not(.estado-pendiente):not(.estado-en_proceso):not(.estado-completado) {
            opacity: 0.6;
        }

        .producto-subfila td {
            padding-left: 3rem;
        }
    </style>
</head>
<body>
<header><?= $nav ?></header>

<div class="vistaCajero-toggle">
    <!-- Este botón ya está activo porque estás en listado de pedidos -->
    <a href="<?= $ruta ?>/cajero/vistaCajero">Estado de Mesas</a>
    <a href="<?= $ruta ?>/pedido/listado" class="active">Pedidos en Curso</a>
</div>

<main>
    <div class="listado-tabla-wrapper">
        <table class="listado-tabla">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Mesa</th>
                    <th>Mozo</th>
                    <th>Producto</th>
                    <th>Cant.</th>
                    <th>Hora</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $ultimoPedidoId = null;
                foreach ($pedidos as $pedido):
                    $nuevoPedido = $pedido['pedido_id'] !== $ultimoPedidoId;
                ?>
                    <tr>
                        <?php if ($nuevoPedido): ?>
                            <td><?= $pedido['pedido_id'] ?></td>
                            <td>Mesa <?= $pedido['mesa_numero'] ?></td>
                            <td><?= $pedido['mozo_nombre'] ?> <?= $pedido['mozo_apellido'] ?></td>
                        <?php else: ?>
                            <td></td><td></td><td></td>
                        <?php endif; ?>

                        <td><?= $pedido['producto_nombre'] ?></td>
                        <td><?= $pedido['cantidad'] ?></td>

                        <?php if ($nuevoPedido): ?>
                            <td><?= $pedido['hora'] ?></td>
                        <?php else: ?>
                            <td></td>
                        <?php endif; ?>

                        <td>
                            <div class="listado-acciones">
                                <?php foreach (['pendiente', 'en_proceso', 'completado'] as $estado): ?>
                                    <button
                                        class="estado-btn <?= $pedido['detalle_estado'] === $estado ? 'estado-' . $estado : '' ?>"
                                        data-id="<?= $pedido['detalle_id'] ?>"
                                        data-estado="<?= $estado ?>"
                                    >
                                        <?= ucfirst(str_replace('_', ' ', $estado)) ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </td>
                    </tr>
                <?php $ultimoPedidoId = $pedido['pedido_id']; endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        document.querySelectorAll('.estado-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const detalleId = this.dataset.id;
                const nuevoEstado = this.dataset.estado;

                fetch('<?= $ruta ?>/pedido/actualizarEstadoProducto', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${detalleId}&estado=${nuevoEstado}`
                })
                .then(res => res.text())
                .then(res => {
                    if (res.trim() === 'ok') {
                        location.reload();
                    } else {
                        alert('Error al actualizar el estado');
                    }
                });
            });
        });

        // Recarga automática cada 5s
        setInterval(() => {
            location.reload();
        }, 5000);
    </script>
</main>
</body>
</html>