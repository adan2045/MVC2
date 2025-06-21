<!DOCTYPE html>
<html lang="es">

<head>
    <?= $head ?>
    <title>Planilla de Ventas</title>
    <link rel="stylesheet" href="/public/css/crud.css">
    <link rel="stylesheet" href="/public/css/listado.css">
    <style>
        <style>.planilla-container {
            width: 100vw;
            margin: 0;
            padding: 0;
            background-color: #fff;
            border-radius: 10px 0 0 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.07);
            font-family: Arial, sans-serif;
        }

        /* Botonera superior */
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
            transition: background 0.15s;
        }

        .vistaCajero-toggle .active {
            background-color: black;
            color: white;
        }

        /* --- Encabezado de la fecha --- */
        .planilla-header {
            display: block;
            width: 100%;
        }

        .fecha-selector {
            display: flex;
            align-items: center;
            gap: 18px;
            font-size: 1.7rem;
            font-weight: 700;
            margin-top: 18px;
            /* Menos espacio arriba */
            margin-bottom: 12px;
            /* Menos espacio abajo */
            justify-content: flex-start;
            /* Izquierda */
            width: 100%;
        }

        .fecha-selector label {
            font-size: 1.8rem;
            font-weight: 700;
            color: #222;
            margin-bottom: 2px;
            margin-right: 8px;
            letter-spacing: 0.01em;
            text-align: left;
            padding-left: 35px;
        }

        .fecha-selector input[type="date"] {
            padding: 10px 16px;
            border: 1.5px solid #888;
            border-radius: 7px;
            font-size: 1.1rem;
            background: #f7f7f7;
            transition: border .18s;
        }

        .fecha-selector input[type="date"]:focus {
            outline: none;
            border: 2px solid #222;
        }

        .fecha-selector button {
            background: #111;
            color: #fff;
            padding: 10px 19px;
            border: none;
            border-radius: 7px;
            font-size: 1.05rem;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 1px 5px rgba(0, 0, 0, .08);
            transition: background 0.15s, transform 0.09s;
        }

        .fecha-selector button:hover {
            background: #353535;
            transform: scale(1.05);
        }

        /* --- Botonera lateral derecha --- */
        .planilla-botones-derecha {
            display: flex;
            flex-direction: column;
            gap: 16px;
            align-items: flex-end;
            min-width: 170px;
            max-width: 200px;
            margin-left: 6px;
            /* Para que no sobresalga */
            margin-top: 2rem;
        }

        .planilla-botones-derecha button,
        .planilla-botones-derecha a button {
            background: black;
            color: white;
            padding: 0.9rem 0;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: 170px;
            font-size: 1rem;
            text-align: center;
            margin: 0;
            display: block;
            transition: background 0.15s;
        }

        .planilla-botones-derecha button:hover,
        .planilla-botones-derecha a button:hover {
            background: #222;
        }

        /* --- Bloques de contenido --- */
        .contenido-principal {
            display: flex;
            align-items: flex-start;
            gap: 0;
            width: 100%;
            margin: 0;
        }

        .planilla-contenido {
            flex: 1 1 0;
            min-width: 0;
            padding: 2rem 2rem 2rem 2rem;
        }

        .planilla-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .planilla-table th,
        .planilla-table td {
            border: 1px solid #ccc;
            padding: 8px;
            font-size: 0.98rem;
        }

        /* --- Títulos alineados a la izquierda y con poco espacio --- */
        .planilla-resumen {
            margin-top: 1.2rem;
            /* Menos margen arriba */
        }

        .planilla-resumen h4 {
            font-size: 1.8rem !important;
            font-weight: 700 !important;
            color: #111 !important;
            font-family: Arial, sans-serif !important;
            margin: 0 !important;
            padding: 0 !important;
            padding-left: 0 !important;
            border: none !important;
            letter-spacing: 0.01em !important;
            text-align: left !important;
            line-height: 1.2 !important;
            display: flex !important;
            align-items: center !important;
        }


        /* Estado de botones pedidos */
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

        /* Responsive */
        @media (max-width: 900px) {
            .contenido-principal {
                flex-direction: column;
            }

            .planilla-botones-derecha {
                align-items: stretch;
                max-width: none;
                margin-left: 0;
                margin-top: 1rem;
            }
        }
    </style>
</head>

<body>
    <header><?= $nav ?></header>

    <div class="vistaCajero-toggle">
        <!-- Este botón ya está activo porque estás en listado de pedidos -->
        <a href="<?= $ruta ?>/cajero/vistaCajero">Estado de Mesas</a>
        <a href="<?= $ruta ?>/pedido/listado">Pedidos en Curso</a>
        <a href="<?= $ruta ?>/cajero/planillaCaja" class="active">Planilla del Día</a>
    </div>
    <?php
    $modeloCaja = new \app\models\CajaModel();
   
    ?>
    <main class="planilla-container">
        <div class="planilla-header">

            <div class="fecha-selector">
                <label for="fecha">Planilla del Día de la Fecha:</label>
                <input type="date" id="fecha" name="fecha" value="<?= date('Y-m-d') ?>">
                <button onclick="cambiarFecha()">Cambiar</button>
            </div>
        </div>

        <div class="contenido-principal">
            <div class="planilla-contenido">
                <table class="planilla-table">
                    <thead>
                        <tr>
                            <th>Detalle</th>
                            <th>Total</th>
                            <th>Cantidad</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>FACTURAS B</td>
                            <td><?= number_format($datos['venta_bruta'], 2) ?></td>
                            <td><?= $datos['cantidad_pedidos'] ?></td>
                        </tr>
                        <tr>
                            <td colspan="3" style="font-weight: bold; background: #ddd">TOTAL VENTA BRUTA</td>
                        </tr>
                        <tr>
                            <td>EFECTIVO TOTAL</td>
                            <td><?= number_format($datos['efectivo_total'], 2) ?></td>
                            <td><?= $datos['efectivo_cantidad'] ?></td>
                        </tr>
                        <tr>
                            <td>Qr</td>
                            <td><?= number_format($datos['qr'], 2) ?></td>
                            <td><?= $datos['qr_cantidad'] ?></td>
                        </tr>
                        <tr>
                            <td>MercadoPago</td>
                            <td><?= number_format($datos['mercadopago'], 2) ?></td>
                            <td><?= $datos['mercadopago_cantidad'] ?></td>
                        </tr>
                        <tr>
                            <td>Tarjetas</td>
                            <td><?= number_format($datos['tarjetas'], 2) ?></td>
                            <td><?= $datos['tarjetas_cantidad'] ?></td>
                        </tr>

                        <tr>
                            <td colspan="3" style="font-weight: bold; background: #ddd">TOTAL INGRESO BRUTO</td>
                        </tr>
                        <tr>
                            <td>Inicio de Caja</td>
                            <td><?= number_format($datos['inicio_caja'], 2) ?></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Efectivo por Ventas</td>
                            <td><?= number_format($datos['efectivo_ventas'], 2) ?></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="color:red">Caja Fuerte</td>
                            <td><?= number_format($datos['caja_fuerte'], 2) ?></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">SALDO DE CAJA</td>
                            <td style="font-weight: bold;"><?= number_format($datos['saldo'], 2) ?></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>

                <div class="planilla-resumen">
                    <h4> Resumen por Producto</h4>
                    <table class="planilla-table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Total $</th>
                                <th>Cantidad</th>
                                <th>% Venta</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total_venta = $datos['venta_bruta'];
                            foreach ($productos as $prod):
                                $porcentaje = ($total_venta > 0) ? ($prod['total'] / $total_venta) * 100 : 0;
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($prod['nombre']) ?></td>
                                    <td><?= number_format($prod['total'], 2) ?></td>
                                    <td><?= $prod['cantidad'] ?></td>
                                    <td class="porcentaje"><?= number_format($porcentaje, 1) ?>%</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="planilla-botones-derecha">
                <button onclick="abrirCaja()">Abrir Caja</button>
                <button onclick="Gastos()">Gastos</button>
                <button onclick="abrirCajaFuerte()">Caja Fuerte</button>
                <button onclick="cerrarCaja()">Cerrar Caja</button>
                <button onclick="window.print()">Imprimir</button>
                <a href="<?= $ruta ?>/admin/gestion"><button> Menu Principal </button></a>
            </div>
        </div>
    </main>

    <script>
        function cambiarFecha() {
            const fecha = document.getElementById('fecha').value;
            if (fecha) {
                // Redirigir con la nueva fecha como parámetro
                window.location.href = '<?= $ruta ?>/cajero/planillaCaja?fecha=' + fecha;
            }
        }

        function abrirCajaFuerte() {
            // Aquí puedes agregar la funcionalidad para abrir caja fuerte
            if (confirm('¿Desea abrir la caja fuerte?')) {
                window.location.href = '<?= $ruta ?>/cajero/abrirCajaFuerte';
            }
        }

        function abrirCaja() {
            let monto = prompt("Ingrese el monto inicial de la caja:", "0");
            if (monto !== null && !isNaN(monto)) {
                window.location.href = '<?= $ruta ?>/cajero/abrirCaja?monto=' + monto;
            }
        }

        function cerrarCaja() {
            // Aquí puedes agregar la funcionalidad para cerrar caja
            if (confirm('¿Desea cerrar la caja?')) {
                window.location.href = '<?= $ruta ?>/cajero/cerrarCaja';
            }
        }
    </script>

    <?= $footer ?>
</body>

</html>