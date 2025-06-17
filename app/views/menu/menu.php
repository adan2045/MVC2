<?php
// Para pruebas usamos la Mesa 1 fija
//$mesaId = 1;

// Si más adelante querés que se lea desde la URL (ej: menu.php?mesa=2), descomentá esta línea:
$mesaId = $_GET['mesa'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?= $head ?>
    <title><?= $title ?? 'Menú Cliente' ?></title>
   <style>* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

.menu-terminal-body {
    background-color: #f5f5f5;
    padding-bottom: 2rem;
}

.menu-header {
    background-color: #1a1a1a;
    color: white;
    padding: 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: fixed;
    width: 100%;
    top: 0;
    z-index: 1000;
}

.menu-terminal-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.menu-terminal-title {
    font-size: 1.2rem;
    font-weight: bold;
}

.menu-main {
    margin-top: 80px;
    padding: 2rem;
}

.menu-mesa-activa {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
}

.menu-mesa-header {
    background: linear-gradient(135deg, #ff6b6b 0%, #ff3333 100%);
    color: white;
    padding: 1rem;
    border-radius: 12px 12px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.menu-menu-list {
    padding: 1rem;
}

.menu-menu-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border-bottom: 1px solid #eee;
}

.menu-menu-item:last-child {
    border-bottom: none;
}

.menu-item-info {
    flex: 1;
}

.menu-item-name {
    font-weight: bold;
    margin-bottom: 0.25rem;
}

.menu-item-description {
    color: #666;
    font-size: 0.9rem;
}

.menu-item-price {
    color: #333;
    font-weight: bold;
    margin-right: 1rem;
}

.menu-quantity-control {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.menu-quantity-btn {
    background: #1a1a1a;
    color: white;
    border: none;
    border-radius: 4px;
    width: 30px;
    height: 30px;
    cursor: pointer;
    font-size: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.menu-quantity-display {
    font-weight: bold;
    min-width: 30px;
    text-align: center;
}
.menu-btn-negro {
    background-color:#007bff;
    color: white;
    border: none;
    padding: 0.6rem 1.2rem;
    border-radius: 5px;
    font-weight: bold;
    cursor: pointer;
    text-decoration: none;
    transition: background-color 0.3s ease;
}
.menu-btn-negro:hover {
    background-color: #333;
}

.menu-mesa-footer {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 0 0 12px 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.menu-total-amount {
    font-size: 1.2rem;
    font-weight: bold;
}

.menu-order-btn {
    background: #28a745;
    color: white;
    border: none;
    border-radius: 4px;
    padding: 0.75rem 1.5rem;
    cursor: pointer;
    font-weight: bold;
}

.menu-category-title {
    padding: 1rem;
    background: #f0f0f0;
    font-weight: bold;
    color: #333;
}

</style>
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
                    <button class="menu-btn-negro" onclick="cambiarEstadoMesa('cuenta_solicitada')">Pedir Cuenta</button>
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

        // 👇 Enviamos el origen 'cliente' para que el backend lo registre así
        fetch('<?= App::baseUrl() ?>/pedido/guardar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ mesa_id: mesaId, productos, origen: "cliente" })
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
        const mesaNumero = <?= $mesaId ?>;
        if (!mesaNumero) return alert("Mesa no definida");

        fetch('<?= App::baseUrl() ?>/mesa/cambiarEstadoPorNumero?numero=' + mesaNumero + '&estado=' + estado, {
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