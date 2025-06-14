
<!DOCTYPE html>
<html lang="es">
<head>
    <?= $head ?>
    <title><?= $title ?? 'Menú Mozo' ?></title>
    <link rel="stylesheet" href="/public/css/listado.css">
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
                        <select id="mesaSelect">
                            <?php foreach ($mesas as $mesa): ?>
                                <option value="<?= $mesa['id'] ?>">Mesa <?= $mesa['numero'] ?></option>
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
                    <div class="menu-menu-item" data-id="<?= $pizza['id'] ?>">
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
                    <div class="menu-menu-item" data-id="<?= $bebida['id'] ?>">
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
        function updateClock() {
            document.getElementById('timeDisplay').textContent = new Date().toLocaleTimeString();
        }
        setInterval(updateClock, 1000);
        updateClock();

        let total = 0;
        const totalDisplay = document.querySelector('.menu-total-amount');
        const quantityControls = document.querySelectorAll('.menu-quantity-control');

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

        document.querySelector('.menu-order-btn').addEventListener('click', () => {
            const mesaId = document.getElementById('mesaSelect').value;
            const productos = [];

            document.querySelectorAll('.menu-menu-item').forEach(item => {
                const id = item.dataset.id;
                const cantidad = parseInt(item.querySelector('.menu-quantity-display').textContent);
                if (cantidad > 0) {
                    productos.push({ id: parseInt(id), cantidad });
                }
            });

            fetch('<?= App::baseUrl() ?>/pedido/guardar', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ mesa_id: mesaId, productos })
            })
            .then(res => res.text())
            .then(res => {
                if (res.trim() === 'ok') {
                    if (total > 0) {
                        fetch(`/mesa/cambiarEstado?id=${mesaId}&estado=ocupada`)
                            .then(() => location.reload());
                    } else {
                        location.reload();
                    }
                } else {
                    alert('Error al enviar pedido');
                }
            });
        });

        function cambiarEstadoMesa(nuevoEstado) {
            const mesaId = document.getElementById('mesaSelect')?.value;
            if (!mesaId) return alert("Seleccioná una mesa");
            fetch(`/mesa/cambiarEstado?id=${mesaId}&estado=${nuevoEstado}`, { method: 'GET' })
                .then(res => res.text())
                .then(() => location.reload())
                .catch(err => console.error("Error al cambiar estado:", err));
        }
    </script>
</body>
</html>
