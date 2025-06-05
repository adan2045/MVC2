<!DOCTYPE html>
<html lang="es">
<head>
	<?=$head?>
	<title><?=$title?></title>
</head>

<body class="cuenta-body">
    <header class="cuenta-header">
        <div class="cuenta-terminal-info">
            <span class="cuenta-terminal-title">Detalle de Cuenta - Mesa 1</span>
            <div class="cuenta-time-display" id="cuentaTimeDisplay">00:00:00</div>
        </div>
        <div class="cuenta-user-info">
            Mozo: Juan Pérez | ID: M001
        </div>
    </header>

    <main class="cuenta-main">
        <div class="cuenta-bill-container">
            <div class="cuenta-bill-header">
                <div class="cuenta-bill-info">
                    <h2>Mesa 1 - Cuenta Final</h2>
                    <p>4 personas • Inicio: 20:30 • Duración: 45 min</p>
                    <p>Mozo: Juan Pérez</p>
                </div>
                <div class="cuenta-bill-summary">
                    <div class="cuenta-payment-status">Pendiente de Pago</div>
                </div>
            </div>

            <div class="cuenta-bill-content">
                <table class="cuenta-bill-table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th class="cuenta-quantity-col">Cant.</th>
                            <th class="cuenta-price-col">Precio</th>
                            <th class="cuenta-subtotal-col">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="cuenta-item-name">Ensalada César</div>
                                <div class="cuenta-item-description">
                                    Lechuga romana, crutones, parmesano
                                </div>
                            </td>
                            <td class="cuenta-quantity-col">2</td>
                            <td class="cuenta-price-col">$850</td>
                            <td class="cuenta-subtotal-col">$1,700</td>
                        </tr>
                        <tr>
                            <td>
                                <div class="cuenta-item-name">Lomo a la Pimienta</div>
                                <div class="cuenta-item-description">
                                    Con puré de papas y vegetales
                                </div>
                            </td>
                            <td class="cuenta-quantity-col">2</td>
                            <td class="cuenta-price-col">$2,450</td>
                            <td class="cuenta-subtotal-col">$4,900</td>
                        </tr>
                        <tr>
                            <td>
                                <div class="cuenta-item-name">Agua Mineral</div>
                                <div class="cuenta-item-description">
                                    Sin gas 500ml
                                </div>
                            </td>
                            <td class="cuenta-quantity-col">4</td>
                            <td class="cuenta-price-col">$350</td>
                            <td class="cuenta-subtotal-col">$1,400</td>
                        </tr>
                    </tbody>
                </table>

                <div class="cuenta-bill-totals">
                    <div class="cuenta-total-row">
                        <span>Subtotal</span>
                        <span>$8,000</span>
                    </div>
                    <div class="cuenta-total-row">
                        <span>IVA (21%)</span>
                        <span>$1,680</span>
                    </div>
                    <div class="cuenta-total-row">
                        <span>Servicio (10%)</span>
                        <span>$800</span>
                    </div>
                    <div class="cuenta-total-row cuenta-final">
                        <span>TOTAL</span>
                        <span>$10,480</span>
                    </div>
                </div>

                <div class="cuenta-payment-methods">
                    <div class="cuenta-payment-method cuenta-active">
                        <i class="fas fa-money-bill-wave"></i> Efectivo
                    </div>
                    <div class="cuenta-payment-method">
                        <i class="fas fa-credit-card"></i> Tarjeta de Crédito
                    </div>
                    <div class="cuenta-payment-method">
                        <i class="fas fa-mobile-alt"></i> QR
                    </div>
                </div>
            </div>

            <div class="cuenta-bill-actions">
                <button class="cuenta-action-btn cuenta-btn-back">
                    <i class="fas fa-arrow-left"></i> Volver
                </button>
                <button class="cuenta-action-btn cuenta-btn-print">
                    <i class="fas fa-print"></i> Imprimir
                </button>
                <button class="cuenta-action-btn cuenta-btn-close">
                    <i class="fas fa-check"></i> Cerrar Mesa
                </button>
            </div>
        </div>
    </main>

    <script src="main.js"></script>
</body>
</html>