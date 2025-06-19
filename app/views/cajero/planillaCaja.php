<!DOCTYPE html>
<html lang="es">

<head>
    <?= $head ?>
    <title>Planilla de Ventas</title>
    <link rel="stylesheet" href="/public/css/crud.css">
    <link rel="stylesheet" href="/public/css/listado.css">
    <style>
        .planilla-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            font-family: Arial, sans-serif;
        }

        .planilla-title {
            text-align: center;
            margin-bottom: 1rem;
        }

        .planilla-title h2 {
            margin: 0;
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
            font-size: 0.95rem;
        }

        .planilla-table th {
            background-color: #f4f4f4;
        }

        .planilla-resumen {
            margin-top: 2rem;
        }

        .planilla-botones {
            text-align: center;
            margin-top: 2rem;
        }

        .planilla-botones button {
            background: black;
            color: white;
            padding: 0.7rem 1.2rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin: 0 0.5rem;
        }
    </style>
</head>

<body>
    <header><?= $nav ?></header>

    <main class="planilla-container">
        <div class="planilla-title">
            <h2>Planilla de Caja</h2>
            <p>Fecha: <?= date('d/m/Y') ?></p>
        </div>

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
                <!-- El resto igual -->


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
            <h4>Resumen por Producto</h4>
            <table class="planilla-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Total $</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $prod): ?>
                        <tr>
                            <td><?= htmlspecialchars($prod['nombre']) ?></td>
                            <td><?= number_format($prod['total'], 2) ?></td>
                            <td><?= $prod['cantidad'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="planilla-botones">
            <button onclick="window.print()">Imprimir</button>
            <a href="<?= $ruta ?>/admin/gestion"><button>Cerrar</button></a>
        </div>
    </main>

    <?= $footer ?>
</body>

</html>