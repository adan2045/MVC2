<?php
// Para pruebas usamos la Mesa 1 fija
$mesaId = 1;

// Si más adelante querés que se lea desde la URL (ej: menu.php?mesa=2), descomentá esta línea:
// $mesaId = $_GET['mesa'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?= $head ?>
    <title><?= $title ?? 'Menú Cliente' ?></title>
    <link rel="stylesheet" href="/public/css/listado.css">
</head>
<body class="menu-terminal-body">
    <header class="menu-header">
        <div class="menu-terminal-info">
            <span class="menu-terminal-title">Terminal Carta - Mesa <?= $mesaId ?></span>
            <div class="menu-time-display" id="timeDisplay">00:00:00</div>
        </div>
        <div class="menu-user-info">Bienvenidos</div>
    </header>

    <main class="menu-main">
        <div class="menu-mesa-activa">
            <div class="menu-mesa-header">
                <div>
                    <h2>Mesa <?= $mesaId ?></h2>
                </div>
                <div class="menu-header-buttons">
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
            const mesaId = <?= $mesaId ?>;
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
                    cambiarEstadoMesa('ocupada');
                    alert('Pedido enviado correctamente.');
                    location.reload();
                } else {
                    alert('Error al enviar pedido');
                }
            });
        });

        function cambiarEstadoMesa(estado) {
    const mesaId = <?= $mesaId ?>;
    if (!mesaId) return alert("Mesa no definida");

    fetch('<?= App::baseUrl() ?>/mesa/cambiarEstado?id=' + mesaId + '&estado=' + estado, {
        method: 'GET'
    })
    .then(res => res.text())
    .then(data => {
        if (estado === 'cuenta_solicitada') {
            alert("✅ En breve se acercará un mozo con tu cuenta. Gracias por tu visita.");
        }
        location.reload();
    })
    .catch(err => {
        console.error("Error AJAX:", err);
        alert("Error de red");
    });
}
    </script>
</body>
</html>