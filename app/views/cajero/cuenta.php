<!DOCTYPE html>
<html lang="es">
<head>
    <?= $head ?>
    <title><?= $title ?? 'Detalle de Cuenta' ?></title>
    <link rel="stylesheet" href="/public/css/crud.css">
    <style>
        body.cuenta-body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .cuenta-header {
            background-color: black;
            color: white;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cuenta-terminal-title {
            font-weight: bold;
            font-size: 1.2rem;
        }

        .cuenta-main {
            padding: 2rem;
        }

        .cuenta-bill-container {
            background-color: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .cuenta-bill-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .cuenta-bill-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }

        .cuenta-bill-table th,
        .cuenta-bill-table td {
            padding: 8px;
            border-bottom: 1px solid #ccc;
        }

        .cuenta-bill-totals {
            text-align: right;
            margin-top: 1rem;
        }

        .cuenta-total-row {
            margin: 0.2rem 0;
        }

        .cuenta-final {
            font-weight: bold;
            font-size: 1.2rem;
        }

        .cuenta-action-btn {
            padding: 0.7rem 1.2rem;
            border: none;
            border-radius: 6px;
            margin-right: 0.5rem;
            cursor: pointer;
            color: white;
            font-weight: bold;
        }

        .cuenta-btn-close {
            background-color: #f39c12;
        }

        .cuenta-btn-print {
            background-color: #3498db;
        }

        .cuenta-btn-back {
            background-color: #7f8c8d;
        }
    </style>
</head>
<body class="cuenta-body">
    <header class="cuenta-header">
        <div class="cuenta-terminal-title">Detalle de Cuenta - Mesa <?= $mesa['numero'] ?></div>
        <div class="cuenta-user-info">Mozo: <?= $_SESSION['usuario']['nombre'] ?? 'Sin nombre' ?></div>
    </header>

    <main class="cuenta-main">
        <div class="cuenta-bill-container">
            <h2>Mesa <?= $mesa['numero'] ?> - Cuenta Final</h2>
            <table class="cuenta-bill-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cant.</th>
                        <th>Precio</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $prod): ?>
                        <tr>
                            <td><?= $prod['nombre'] ?><br><small><?= $prod['descripcion'] ?></small></td>
                            <td><?= $prod['cantidad'] ?></td>
                            <td>$<?= number_format($prod['precio'], 2, ',', '.') ?></td>
                            <td>$<?= number_format($prod['subtotal'], 2, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="cuenta-bill-totals">
                <div class="cuenta-total-row">TOTAL: <span>$<?= number_format($total, 2, ',', '.') ?></span></div>
            </div>

            <div class="cuenta-bill-actions">
                <button class="cuenta-action-btn cuenta-btn-close" onclick="solicitarCuenta(<?= $mesa['id'] ?>)">
                    Solicitar Cuenta
                </button>
            </div>
        </div>
    </main>

    <script>
    function solicitarCuenta(mesaId) {
        fetch('<?= $ruta ?>/mesa/solicitarCuenta', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'mesa_id=' + mesaId
        })
        .then(res => res.text())
        .then(res => {
            if (res.trim() === 'ok') {
                // 🚧 Línea para imprimir en el futuro:
                // window.print();

                // Redirigir al cajero
                window.location.href = '<?= $ruta ?>/cajero/vistaCajero';
            } else {
                alert('Error al solicitar la cuenta');
            }
        });
    }
    </script>
</body>
</html>