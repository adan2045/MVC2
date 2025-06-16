<!DOCTYPE html>
<html lang="es">
<head>
    <?= $head ?>
    <title><?= $title ?? 'Menú Mozo' ?></title>
   
</head>
<body class="menu-terminal-body">
    <header class="menu-header">
        <div class="menu-terminal-info">
            <span class="menu-terminal-title">Terminal - Mozo</span>
            <div class="menu-time-display" id="timeDisplay">00:00:00</div>
        </div>
        <div class="menu-user-info">Mozo: <?= $_SESSION['user_email'] ?? 'Sin sesión' ?></div>
    </header>

    <main class="menu-main">
        <div class="menu-mesa-activa">
            <div class="menu-mesa-header">
                <div>
                    <h2>Enviar pedido</h2>
                    <label>Mesa:
                        <select id="mesaSelect" onchange="resetearVista()">
                            <?php foreach ($mesas as $mesa): ?>
                                <option value="<?= $mesa['numero'] ?>">Mesa <?= $mesa['numero'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                </div>
                <div class="menu-header-buttons">
                    <button onclick="cambiarEstadoMesa('ocupada')">Mesa Ocupada</button>
                    <button onclick="cambiarEstadoMesa('cuenta_solicitada')">Pedir Cuenta</button>
                </div>
            </div>

            <div class="menu-menu-list">
                <div class="menu-category-title">Pizzas</div>
                <?php foreach ($pizzas as $pizza): ?>
                    <div class="menu-menu-item" data-id="<?= $pizza['id'] ?>" data-precio="<?= $pizza['precio'] ?>">
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

                <div class="menu-category-title">Bebidas</div>
                <?php foreach ($bebidas as $bebida): ?>
                    <div class="menu-menu-item" data-id="<?= $bebida['id'] ?>" data-precio="<?= $bebida['precio'] ?>">
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
            </div>

            <div class="menu-mesa-footer">
                <div class="menu-total-amount">Total: $0</div>
                <button class="menu-order-btn">Enviar Pedido</button>
            </div>
        </div>
    </main>

    <script>
        const totalDisplay = document.querySelector('.menu-total-amount');

        function updateClock() {
            document.getElementById('timeDisplay').textContent = new Date().toLocaleTimeString();
        }
        setInterval(updateClock, 1000);
        updateClock();

        function resetearVista() {
            document.querySelectorAll('.menu-quantity-display').forEach(el => el.textContent = '0');
            total = 0;
            updateTotal();
        }

        let total = 0;
        function updateTotal() {
            total = 0;
            document.querySelectorAll('.menu-menu-item').forEach(item => {
                const cantidad = parseInt(item.querySelector('.menu-quantity-display').textContent);
                const precio = parseFloat(item.dataset.precio);
                total += cantidad * precio;
            });
            totalDisplay.textContent = `Total: $${total.toLocaleString('es-AR')}`;
        }

        document.querySelectorAll('.menu-quantity-control').forEach(control => {
            const display = control.querySelector('.menu-quantity-display');
            const parent = control.closest('.menu-menu-item');

            control.querySelectorAll('.menu-quantity-btn')[1].addEventListener('click', () => {
                display.textContent = parseInt(display.textContent) + 1;
                updateTotal();
            });

            control.querySelectorAll('.menu-quantity-btn')[0].addEventListener('click', () => {
                if (parseInt(display.textContent) > 0) {
                    display.textContent = parseInt(display.textContent) - 1;
                    updateTotal();
                }
            });
        });

        document.querySelector('.menu-order-btn').addEventListener('click', () => {
            const mesaId = document.getElementById('mesaSelect').value;
            const productos = [];

            document.querySelectorAll('.menu-menu-item').forEach(item => {
                const cantidad = parseInt(item.querySelector('.menu-quantity-display').textContent);
                if (cantidad > 0) {
                    productos.push({ id: parseInt(item.dataset.id), cantidad });
                }
            });

            fetch('<?= \App::baseUrl() ?>/pedido/guardar', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ mesa_id: mesaId, productos })
            })
            .then(res => res.text())
            .then(res => {
                if (res.trim() === 'ok') {
                    cambiarEstadoMesa('ocupada');
                    resetearVista();
                } else {
                    alert('Error al enviar pedido');
                }
            });
        });

       function cambiarEstadoMesa(estado) {
    const mesaNumero = document.getElementById('mesaSelect').value;
    fetch(`<?= \App::baseUrl() ?>/mesa/cambiarEstadoPorNumero?numero=${mesaNumero}&estado=${estado}`)
        .then(res => res.text())
        .then(res => {
            if (res.trim() !== 'ok') {
                alert("Error al cambiar el estado de la mesa");
            }
        });
}
    </script>
</body>
</html>