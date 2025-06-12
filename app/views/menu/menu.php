<!DOCTYPE html>
<html lang="es">
<html lang="es">
<head>
	<?=$head?>
	<title><?=$title?></title>
</head>
<body>
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
    <div class="menu-category-title">Pizzas</div>

    <?php foreach ($pizzas as $pizza): ?>
        <div class="menu-menu-item">
            <div class="menu-item-info">
                <div class="menu-item-name"><?= htmlspecialchars($pizza['nombre']) ?></div>
                <div class="menu-item-description"><?= htmlspecialchars($pizza['descripcion']) ?></div>
            </div>
            <div class="menu-item-price">$<?= number_format($pizza['precio'], 0, ',', '.') ?></div>
            <div class="menu-quantity-control">
                <button class="menu-quantity-btn">-</button>
                <span class="menu-quantity-display">0</span>
                <button class="menu-quantity-btn">+</button>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<div class="menu-category-title">Bebidas</div>

<?php if (!empty($bebidas)): ?>
    <?php foreach ($bebidas as $bebida): ?>
        <div class="menu-menu-item">
            <div class="menu-item-info">
                <div class="menu-item-name"><?= htmlspecialchars($bebida['nombre']) ?></div>
                <div class="menu-item-description"><?= htmlspecialchars($bebida['descripcion']) ?></div>
            </div>
            <div class="menu-item-price">$<?= number_format($bebida['precio'], 0, ',', '.') ?></div>
            <div class="menu-quantity-control">
                <button class="menu-quantity-btn">-</button>
                <span class="menu-quantity-display">0</span>
                <button class="menu-quantity-btn">+</button>
            </div>
        </div>
            <?php endforeach; ?>
            <?php else: ?>
            <p style="color:red;">No hay bebidas disponibles.</p>
            <?php endif; ?>
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
            const cleanPrice = priceText.replace('$', '').replace(/\./g, '').replace(',', '.');
            const price = parseFloat(cleanPrice);
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
    totalDisplay.textContent = `Total: $${total.toLocaleString('es-AR')}`;
}


        // Botón de enviar pedido
        document.querySelector('.menu-order-btn').addEventListener('click', () => {
            alert('Pedido enviado a cocina');
        });
    </script>
</body>
</html>