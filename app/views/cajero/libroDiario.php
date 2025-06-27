<!DOCTYPE html>
<html lang="es">
<head>
    <title>Libro Diario - Movimientos</title>
    <link rel="stylesheet" href="/public/css/crud.css">
    <link rel="stylesheet" href="/public/css/listado.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: #fff;
        }
        .planilla-libro {
            max-width: 800px;
            margin: 24px auto 0 auto;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 0 14px rgba(0,0,0,0.06);
            padding: 18px 22px 32px 22px;
        }
        .planilla-encabezado {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-bottom: 8px;
        }
        .planilla-encabezado .inicio-caja {
            background: #fff6e5;
            border: 1.5px solid #f2da9e;
            color: #138a13;
            padding: 8px 24px;
            border-radius: 7px;
            font-size: 1.15rem;
            font-weight: bold;
            letter-spacing: .02em;
        }
        .planilla-movimientos {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }
        .planilla-movimientos th, .planilla-movimientos td {
            border: 1px solid #d2d2d2;
            padding: 6px 4px;
            font-size: 0.97rem;
            text-align: center;
        }
        .planilla-movimientos th {
            background: #f8f8f8;
            color: #222;
            font-weight: 600;
            font-size: 1.04rem;
        }
        /* Hover para todo menos la última fila */
        .planilla-movimientos tbody tr:not(.total):hover td {
            background: #f6f7fa;
        }
        /* Totales SIEMPRE fondo negro, texto blanco */
        .planilla-movimientos tr.total td {
            background: #111 !important;
            color: #fff !important;
            font-weight: bold;
            font-size: 1rem;
        }
        /* Fila inicio de caja: todo verde */
        .planilla-movimientos tr.inicio td {
            color: #1d910a !important;
            background: #eaffed !important;
            font-weight: bold;
        }
        /* Fila gasto o caja fuerte: todo rojo */
        .planilla-movimientos tr.egreso td {
            color: #d10808 !important;
            background: #fff0f0 !important;
            font-weight: bold;
        }
        /* Link a ticket: sutil */
        .planilla-movimientos td a {
            color: #0046a7;
            text-decoration: underline dotted;
        }
        .planilla-movimientos td a:hover {
            color: #095cff;
            text-decoration: underline solid;
        }
        .planilla-botones {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 9px;
        }
        .planilla-botones .btn {
            padding: 6px 25px;
            border: none;
            border-radius: 7px;
            background: #111;
            color: #fff;
            font-size: 1rem;
            cursor: pointer;
            font-weight: 600;
        }
        .planilla-botones .btn:hover { background: #333; }
        @media (max-width: 850px) {
            .planilla-libro { max-width: 99vw; padding: 6px 2vw 18px 2vw; }
        }
        @media (max-width: 600px) {
            .planilla-libro { padding: 4px 1vw 8px 1vw; }
            .planilla-movimientos th, .planilla-movimientos td { font-size: 0.93rem; padding: 3px 1px; }
        }
    </style>
</head>
<body>
<div class="planilla-libro">
    <div class="planilla-encabezado">
        <div class="inicio-caja">
            <b>Inicio de caja:</b> $<?= number_format($inicioCaja, 0, '', '.') ?>
        </div>
    </div>
    <table class="planilla-movimientos">
        <thead>
            <tr>
                <th>Detalle</th>
                <th>Efectivo</th>
                <th>Tarjetas</th>
                <th>QR</th>
                <th>MercadoPago</th>
                <th>Total</th>
                <th>Mesa</th>
                <th>Hora</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($movimientos as $mov):
                // Clases por tipo: inicio=verde, gasto/caja fuerte=rojo, total=negro
                $trClass = '';
                if ($mov['tipo'] === 'inicio') $trClass = 'inicio';
                if (in_array($mov['tipo'], ['gasto', 'caja_fuerte'])) $trClass = 'egreso';
            ?>
                <tr class="<?= $trClass ?>">
                    <td>
                        <?php if($mov['tipo'] === 'venta' && !empty($mov['ticket_url'])): ?>
                            <a href="<?= htmlspecialchars($mov['ticket_url']) ?>" target="_blank">
                                <?= htmlspecialchars($mov['detalle']) ?>
                            </a>
                        <?php else: ?>
                            <?= htmlspecialchars($mov['detalle']) ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?= ($mov['efectivo'] !== '' ? number_format($mov['efectivo'], 0, '', '.') : '') ?>
                    </td>
                    <td>
                        <?= ($mov['tarjeta'] !== '' ? number_format($mov['tarjeta'], 0, '', '.') : '') ?>
                    </td>
                    <td>
                        <?= ($mov['qr'] !== '' ? number_format($mov['qr'], 0, '', '.') : '') ?>
                    </td>
                    <td>
                        <?= ($mov['mp'] !== '' ? number_format($mov['mp'], 0, '', '.') : '') ?>
                    </td>
                    <td>
                        <?= ($mov['total'] !== '' ? number_format($mov['total'], 0, '', '.') : '') ?>
                    </td>
                    <td><?= $mov['mesa'] ?></td>
                    <td><?= date('H:i', strtotime($mov['fecha_hora'])) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr class="total">
                <td><b>Totales</b></td>
                <td><b><?= number_format($totales['efectivo'], 0, '', '.') ?></b></td>
                <td><b><?= number_format($totales['tarjeta'], 0, '', '.') ?></b></td>
                <td><b><?= number_format($totales['qr'], 0, '', '.') ?></b></td>
                <td><b><?= number_format($totales['mp'], 0, '', '.') ?></b></td>
                <td><b><?= number_format($totales['total'], 0, '', '.') ?></b></td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>
    <div class="planilla-botones">
        <button onclick="window.print()" class="btn">Imprimir</button>
    </div>
</div>
</body>
</html>