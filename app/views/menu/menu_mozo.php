<!DOCTYPE html>
<html lang="es">

<head>
    <?= $head ?>
    <title><?= $title ?? 'Menú Mozo' ?></title>

</head>
<style>
    .menu-btn-azul {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 0.6rem 1.2rem;
        border-radius: 5px;
        font-weight: bold;
        cursor: pointer;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    .menu-btn-azul:hover {
        background-color: #0056b3;
    }

    .menu-btn-amarillo {
        background-color:rgb(53, 158, 58);
        color: white;
        border: none;
        padding: 0.6rem 1.2rem;
        border-radius: 5px;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .menu-btn-amarillo:hover {
        background-color:rgb(23, 123, 20);
    }

    select#mesaSelect {
        padding: 0.5rem;
        border-radius: 5px;
        border: 1px solid #ccc;
        font-weight: bold;
        background-color: #f8f9fa;
        color: #333;
        margin-left: 10px;
    }

    select#mesaSelect:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 3px rgba(0, 123, 255, 0.5);
    }
</style>

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
                    
                    <label>Mesa:
                        <select id="mesaSelect" onchange="resetearVista()">
                            <?php foreach ($mesas as $mesa): ?>
                                <option value="<?= $mesa['numero'] ?>">Mesa <?= $mesa['numero'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                </div>
                <div class="menu-header-buttons">
                    <button class="menu-btn-amarillo" onclick="cambiarEstadoMesa('ocupada')">Mesa Ocupada</button>
                    <button class="menu-btn-azul" onclick="cambiarEstadoMesa('cuenta_solicitada')">Pedir Cuenta</button>
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

    // 🚩 Botón ENVIAR PEDIDO: solo muestra notificación de pedido, NO de mesa ocupada
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
                    cambiarEstadoMesa('ocupada', false); // NOTIFICA NO
                    alert('✅ Pedido enviado correctamente');
                    resetearVista();
                } else {
                    alert('Error al enviar pedido');
                }
            });
    });

    // 🚩 Botón MESA OCUPADA: sí muestra notificación de mesa ocupada
    document.querySelector('.menu-btn-amarillo').addEventListener('click', function () {
        cambiarEstadoMesa('ocupada', true); // NOTIFICA SÍ
    });

    // 🚩 Botón PEDIR CUENTA: igual que antes (puede notificar si querés)
    document.querySelector('.menu-btn-azul').addEventListener('click', function () {
        cambiarEstadoMesa('cuenta_solicitada', true);
    });

    // ⬇️ La función ahora recibe un segundo parámetro: notificar
    function cambiarEstadoMesa(estado, notificar = false) {
        const mesaNumero = document.getElementById('mesaSelect').value;
        fetch(`<?= \App::baseUrl() ?>/mesa/cambiarEstadoPorNumero?numero=${mesaNumero}&estado=${estado}`)
            .then(res => res.text())
            .then(res => {
                if (res.trim() === 'ok') {
                    if (notificar && estado === 'ocupada') {
                        alert("✅ Mesa marcada como ocupada");
                    }
                    if (notificar && estado === 'cuenta_solicitada') {
                        alert("✅ Cuenta solicitada");
                    }
                } else {
                    alert("Error al cambiar el estado de la mesa");
                }
            });
    }
</script>
</body>

</html>