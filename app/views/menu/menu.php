<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terminal Carta - Vista Pedidos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
</head>
<body class="menu-terminal-body">
    <header class="menu-header">
        <div class="menu-terminal-info">
            <span class="menu-terminal-title">Terminal Carta - Mesa 1</span>
            <div class="menu-time-display" id="timeDisplay">00:00:00</div>
        </div>
        <div class="menu-user-info">
            Mozo: Juan Pérez | ID: M001
        </div>
    </header>

    <main class="menu-main">
        <div class="menu-mesa-activa">
            <div class="menu-mesa-header">
                <div>
                    <h2>Bienvenidos</h2>
                    <h2>Mesa 1</h2>
                </div>
                <div class="menu-mesa-status">
                    Pedido en curso
                </div>
            </div>

            <div class="menu-menu-list">
                <div class="menu-category-title">Entradas</div>
                <div class="menu-menu-item">
                    <div class="menu-item-info">
                        <div class="menu-item-name">Ensalada César</div>
                        <div class="menu-item-description">Lechuga romana, crutones, parmesano y aderezo césar</div>
                    </div>
                    <div class="menu-item-price">$850</div>
                    <div class="menu-quantity-control">
                        <button class="menu-quantity-btn">-</button>
                        <span class="menu-quantity-display">0</span>
                        <button class="menu-quantity-btn">+</button>
                    </div>
                </div>

                <div class="menu-category-title">Platos Principales</div>
                <div class="menu-menu-item">
                    <div class="menu-item-info">
                        <div class="menu-item-name">Lomo a la Pimienta</div>
                        <div class="menu-item-description">Con puré de papas y vegetales salteados</div>
                    </div>
                    <div class="menu-item-price">$2,450</div>
                    <div class="menu-quantity-control">
                        <button class="menu-quantity-btn">-</button>
                        <span class="menu-quantity-display">0</span>
                        <button class="menu-quantity-btn">+</button>
                    </div>
                </div>

                <div class="menu-menu-item">
                    <div class="menu-item-info">
                        <div class="menu-item-name">Salmón Grillado</div>
                        <div class="menu-item-description">Con risotto de limón y espárragos</div>
                    </div>
                    <div class="menu-item-price">$2,850</div>
                    <div class="menu-quantity-control">
                        <button class="menu-quantity-btn">-</button>
                        <span class="menu-quantity-display">0</span>
                        <button class="menu-quantity-btn">+</button>
                    </div>
                </div>

                <div class="menu-category-title">Bebidas</div>
                <div class="menu-menu-item">
                    <div class="menu-item-info">
                        <div class="menu-item-name">Agua Mineral</div>
                        <div class="menu-item-description">Con/Sin gas 500ml</div>
                    </div>
                    <div class="menu-item-price">$350</div>
                    <div class="menu-quantity-control">
                        <button class="menu-quantity-btn">-</button>
                        <span class="menu-quantity-display">0</span>
                        <button class="menu-quantity-btn">+</button>
                    </div>
                </div>
            </div>

            <div class="menu-mesa-footer">
                <div class="menu-total-amount">Total: $0</div>
                <button class="menu-order-btn">Enviar Pedido</button>
            </div>
        </div>
    </main>

    <script>
        // Actualizar reloj
        function updateClock() {
            const now = new Date();
            const timeDisplay = document.getElementById('timeDisplay');
            timeDisplay.textContent = now.toLocaleTimeString();
        }

        setInterval(updateClock, 1000);
        updateClock();

        // Control de cantidades y total
        let total = 0;
        const quantityControls = document.querySelectorAll('.menu-quantity-control');
        const totalDisplay = document.querySelector('.menu-total-amount');

        quantityControls.forEach(control => {
            const minusBtn = control.querySelector('.menu-quantity-btn:first-child');
            const plusBtn = control.querySelector('.menu-quantity-btn:last-child');
            const display = control.querySelector('.menu-quantity-display');
            const priceText = control.parentElement.querySelector('.menu-item-price').textContent;
            const price = parseInt(priceText.replace('$', '').replace(',', ''));

            plusBtn.addEventListener('click', () => {
                let quantity = parseInt(display.textContent);
                display.textContent = quantity + 1;
                total += price;
                updateTotal();
            });

            minusBtn.addEventListener('click', () => {
                let quantity = parseInt(display.textContent);
                if (quantity > 0) {
                    display.textContent = quantity - 1;
                    total -= price;
                    updateTotal();
                }
            });
        });

        function updateTotal() {
            totalDisplay.textContent = `Total: $${total.toLocaleString()}`;
        }

        // Botón de enviar pedido
        document.querySelector('.menu-order-btn').addEventListener('click', () => {
            alert('Pedido enviado a cocina');
        });
    </script>
</body>
</html>