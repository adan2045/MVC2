<!DOCTYPE html>
<html lang="es">

<head>
    <?= $head ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Terminal Cajero' ?></title>
    <style>
        .vistaCajero-user-info {
            display: flex;
            align-items: center;
            gap: 0px;
        }

        .vistaCajero-toggle {
            display: flex;
            justify-content: center;
            margin: 20px 0px 20px 20px;
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
            transition: background 0.18s;
        }

        .vistaCajero-toggle a.active {
            background-color: #000;
            color: #fff;
        }

        .vistaCajero-toggle a:not(.active) {
            background-color: #eee;
            color: #000;
        }

        .vistaCajero-tables-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 12px;
            padding: 0px;
        }

        .vistaCajero-table-card {
            background-color: #fff;
            border-radius: 12px;
            padding: 12px;
            color: black;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            min-height: 160px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .vistaCajero-table-ocupada {
            border-left: 6px solid red;
        }

        .vistaCajero-table-disponible {
            border-left: 6px solid green;
        }

        .vistaCajero-table-cuenta-solicitada {
            border-left: 6px solid orange;
        }

        .vistaCajero-table-number {
            font-size: 1.2rem;
            font-weight: bold;
            color: black;
            margin: 0;
        }

        .vistaCajero-table-state {
            font-weight: bold;
            color: black;
        }

        .vistaCajero-table-info {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
            color: black;
        }

        .vistaCajero-action-buttons {
            margin-top: 8px;
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .vistaCajero-action-btn {
            flex: 1;
            padding: 6px 10px;
            font-size: 0.85rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: white;
        }

        .vistaCajero-btn-view {
            background-color: black;
        }

        .vistaCajero-btn-close {
            background-color: #d35400;
        }

        .vistaCajero-btn-pagado {
            background-color: #27ae60;
        }

        @media (max-width: 900px) {
            .vistaCajero-tables-grid {
                grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
                gap: 10px;
            }

            .vistaCajero-table-card {
                padding: 10px;
                min-height: 120px;
            }

            .vistaCajero-action-btn {
                font-size: 0.8rem;
                padding: 5px 8px;
            }
        }

        @media (max-width: 600px) {
            .vistaCajero-tables-grid {
                grid-template-columns: 1fr;
                gap: 8px;
            }

            .vistaCajero-main {
                padding: 4px;
            }

            .vistaCajero-table-card {
                padding: 8px;
                min-height: 90px;
            }

            .vistaCajero-table-number {
                font-size: 1rem;
            }

            .vistaCajero-table-state {
                font-size: 0.9rem;
            }

            .vistaCajero-action-buttons {
                flex-direction: column;
                gap: 4px;
            }

            .vistaCajero-action-btn {
                font-size: 0.85rem;
                width: 100%;
                padding: 7px 0;
            }
        }
    </style>
</head>

<body class="vistaCajero-body">
    <header><?= $nav ?></header>

    <div class="vistaCajero-toggle">
        <a href="<?= $ruta ?>/cajero/vistaCajero" class="active">Estado de Mesas</a>
        <a href="<?= $ruta ?>/pedido/listado">Pedidos en Curso</a>
        <a href="<?= $ruta ?>/cajero/planillaCaja">Planilla del Día</a>
    </div>

    <main class="vistaCajero-main">
        <div id="vistaMesas">
            <div class="vistaCajero-tables-grid">
                <?php foreach ($mesas as $mesa): ?>
                    <?php
                    $estado = strtolower($mesa['estado']);
                    $class = 'vistaCajero-table-card';
                    if ($estado === 'ocupada') {
                        $class .= ' vistaCajero-table-ocupada';
                        $estadoTexto = 'OCUPADA';
                    } elseif ($estado === 'cuenta_solicitada') {
                        $class .= ' vistaCajero-table-cuenta-solicitada';
                        $estadoTexto = 'CUENTA SOLICITADA';
                    } else {
                        $class .= ' vistaCajero-table-disponible';
                        $estadoTexto = 'DISPONIBLE';
                    }
                    ?>
                    <div class="<?= $class ?>">
                        <h3 class="vistaCajero-table-number">Mesa <?= $mesa['numero'] ?></h3>
                        <div class="vistaCajero-table-state">Estado: <?= $estadoTexto ?></div>
                        <div class="vistaCajero-table-info">
                            <div class="vistaCajero-table-time">
                                <?= $estado === 'disponible' ? 'Lista para usar' : '---' ?>
                            </div>
                            <div class="vistaCajero-table-amount">
                                $<?= number_format($mesa['total'], 0, ',', '.') ?>
                            </div>
                        </div>
                        <div class="vistaCajero-action-buttons">
                            <?php if ($estado === 'disponible'): ?>
                                <button class="vistaCajero-action-btn vistaCajero-btn-view">Asignar Mesa</button>
                            <?php else: ?>
                                <button class="vistaCajero-action-btn vistaCajero-btn-view"
                                    onclick="location.href='<?= $ruta ?>/cajero/cuenta?id=<?= $mesa['id'] ?>'">Ver
                                    Cuenta</button>
                                <?php if ($mesa['total'] > 0): ?>
                                    <button class="vistaCajero-action-btn vistaCajero-btn-close"
                                        onclick="cerrarMesa(<?= $mesa['id'] ?>)">Cerrar Mesa</button>
                                <?php else: ?>
                                    <button class="vistaCajero-action-btn vistaCajero-btn-close" disabled
                                        style="background-color: gray; cursor: not-allowed;">Sin Consumo</button>
                                <?php endif; ?>
                                <button class="vistaCajero-action-btn vistaCajero-btn-pagado"
                                    onclick="marcarPagado(<?= $mesa['id'] ?>)">Pagado</button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <script>
        function marcarPagado(mesaId) {
            fetch('<?= $ruta ?>/cajero/pagarMesa', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'mesa_id=' + mesaId
            })
                .then(res => res.text())
                .then(res => {
                    if (res.trim() === 'ok') {
                        location.reload();
                    } else {
                        alert('Error al marcar la mesa como pagada');
                    }
                });
        }

        // Ocultar menú hamburguesa si se abre fuera
        document.addEventListener('click', function (e) {
            const menu = document.getElementById('logoutMenu');
            if (menu && !e.target.matches('.hamburger')) {
                menu.style.display = 'none';
            }
        });

        // Recarga automática cada 10 segundos (mantener SIEMPRE ACTIVA)
        setInterval(() => {
            fetch(location.href)
                .then(res => res.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const nuevasMesas = doc.querySelector('#vistaMesas');
                    if (nuevasMesas) {
                        document.querySelector('#vistaMesas').innerHTML = nuevasMesas.innerHTML;
                    }
                });
        }, 10000);

        function cerrarMesa(mesaId) {
            fetch('<?= $ruta ?>/cajero/cerrarMesa', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'mesa_id=' + mesaId
            })
                .then(res => res.text())
                .then(res => {
                    if (res.trim() === 'ok') {
                        location.reload();
                    } else {
                        alert('Error al cerrar la mesa');
                    }
                });
        }
        function cerrarMesa(mesaId) {
            fetch('<?= $ruta ?>/mesa/solicitarCuenta', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'mesa_id=' + mesaId
            })
                .then(res => res.text())
                .then(res => {
                    if (res.trim() === 'ok') {
                        // window.print(); // Descomentá si querés imprimir
                        window.location.href = '<?= $ruta ?>/cajero/vistaCajero';
                    } else {
                        alert('Error al cerrar la mesa');
                    }
                });
        }
            /*
        // ------ BLOQUE DE IMPRESIÓN AUTOMÁTICA DE COMANDA ------
        // El controlador debe pasar la variable $pedidos igual que en listado
        const pedidosJS = <?= json_encode($pedidos ?? []) ?>;

        let ultimoIdGuardado = localStorage.getItem('ultimoPedidoIdCajero') || 0;
        let ultimoPedido = pedidosJS.length ? pedidosJS[0] : null;

        // Solo imprime si hay un pedido nuevo; luego sigue la recarga automática de siempre
        if (ultimoPedido && ultimoPedido.pedido_id != ultimoIdGuardado) {
            imprimirComanda(ultimoPedido.pedido_id);
            localStorage.setItem('ultimoPedidoIdCajero', ultimoPedido.pedido_id);
        }

        function imprimirComanda(pedidoId) {
            const items = pedidosJS.filter(p => p.pedido_id == pedidoId);
            if (!items.length) return;

            // Ajustamos todo: fuente grande, mucho padding, y descripción incluida
            let comandaHTML = `
        <div style="font-family: monospace; font-size: 18px; width: 360px; padding: 22px;">
            <div style="text-align:center;">
                <span style="font-size:26px; font-weight:bold; letter-spacing:2px; display:block;">PIZZERÍA SANTA MARÍA</span>
                <span style="font-size:22px; font-weight:bold; margin-top:6px;">--- COCINA ---</span>
            </div>
            <hr style="margin:18px 0;">
            <div style="font-size:20px; margin-bottom:10px;">Mesa: <strong>${items[0].mesa_numero}</strong></div>
            <div style="font-size:18px; margin-bottom:10px;">Mozo: <strong>${(items[0].mozo_nombre ? items[0].mozo_nombre : "Cliente") + " " + (items[0].mozo_apellido || "")}</strong></div>
            <div style="font-size:18px; margin-bottom:16px;">Hora: <strong>${items[0].hora}</strong></div>
            <div style="font-size:18px;">
                <strong>Pedido:</strong>
                <ul style="padding-left:18px; font-size:18px; margin:10px 0;">
                    ${items.map(item => `<li>
                        <div style="font-size:18px;">
                            <strong>${item.cantidad} x ${item.producto_nombre}</strong><br>
                            <span style="font-size:16px; color:#555;">${item.producto_descripcion || ''}</span>
                        </div>
                    </li>`).join('')}
                </ul>
            </div>
            <hr style="margin:22px 0;">
        </div>
    `;

            const printWindow = window.open('', '', 'width=420,height=700');
            printWindow.document.write(comandaHTML);
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        }*/
    </script>
</body>

</html>