<!DOCTYPE html>
<html lang="es">
<head>
    <?= $head ?>
    <title><?= $title ?? 'Terminal Cajero' ?></title>
    <link rel="stylesheet" href="/public/css/crud.css">
    <link rel="stylesheet" href="/public/css/listado.css">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }

        .vistaCajero-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: black;
            color: white;
            padding: 10px 20px;
        }

        .hamburger {
            cursor: pointer;
            font-size: 1.2rem;
            margin-left: 10px;
        }

        .vistaCajero-user-info {
            display: flex;
            align-items: center;
            gap: 0px;
        }

        .logout-menu {
            display: none;
            position: absolute;
            right: 10px;
            top: 0px;
            background: black;
            border: 1px solid #ccc;
            padding: 5px 10px;
            z-index: 999;
        }

        .vistaCajero-toggle {
            display: flex;
            justify-content: center;
            margin: 50px 20px 20px 20px;
        }

        .vistaCajero-toggle button {
            flex: 1;
            padding: 10px;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }

        .vistaCajero-toggle .active {
            background-color: black;
            color: white;
        }

        .vistaCajero-tables-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 12px;
            padding: 0px;
        }

        .vistaCajero-table-card {
            background-color: #fff;
            border-radius: 12px;
            padding: 12px;
            color: black;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            min-height: 160px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .vistaCajero-table-ocupada {
            border-left: 6px solid red;
        }

        .vistaCajero-table-disponible {
            border-left: 6px solid green;
        }

        .vistaCajero-table-cuenta-solicitada {
            border-left: 6px solid orange;
        }

        .vistaCajero-table-number {
            font-size: 1.2rem;
            font-weight: bold;
            color: black;
            margin: 0;
        }

        .vistaCajero-table-state {
            font-weight: bold;
            color:black;
        }

        .vistaCajero-table-info {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
            color:black;
        }

        .vistaCajero-action-buttons {
            margin-top: 8px;
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .vistaCajero-action-btn {
            flex: 1;
            padding: 6px 10px;
            font-size: 0.85rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: white;
        }

        .vistaCajero-btn-view {
            background-color: black;
        }

        .vistaCajero-btn-close {
            background-color: #d35400;
        }

        .vistaCajero-btn-pagado {
            background-color: #27ae60;
        }
    </style>
</head>
<body class="vistaCajero-body">
    <header class="vistaCajero-header">
        <div class="vistaCajero-terminal-info">
            <span class="vistaCajero-terminal-title">Terminal Principal</span>
        </div>
        <div class="vistaCajero-user-info">
            <span><?= $cajero ?></span>
            <span class="hamburger" onclick="document.getElementById('logoutMenu').style.display = 'block'">☰</span>
        </div>
        <div id="logoutMenu" class="logout-menu">
            <a href="<?= $ruta ?>/login/logout">Cerrar sesión</a>
        </div>
    </header>

    <div class="vistaCajero-toggle">
        <button class="active" onclick="mostrar('mesas')">Estado de Mesas</button>
        <button onclick="mostrar('pedidos')">Pedidos en Curso</button>
    </div>

    <main class="vistaCajero-main">
        <div id="vistaMesas">
            <div class="vistaCajero-tables-grid">
                <?php foreach ($mesas as $mesa): ?>
                    <?php
                        $estado = strtolower($mesa['estado']);
                        $class = 'vistaCajero-table-card';
                        if ($estado === 'ocupada') {
                            $class .= ' vistaCajero-table-ocupada';
                            $estadoTexto = 'OCUPADA';
                        } elseif ($estado === 'cuenta_solicitada') {
                            $class .= ' vistaCajero-table-cuenta-solicitada';
                            $estadoTexto = 'CUENTA SOLICITADA';
                        } else {
                            $class .= ' vistaCajero-table-disponible';
                            $estadoTexto = 'DISPONIBLE';
                        }
                    ?>
                    <div class="<?= $class ?>">
                        <h3 class="vistaCajero-table-number">Mesa <?= $mesa['numero'] ?></h3>
                        <div class="vistaCajero-table-state">Estado: <?= $estadoTexto ?></div>
                        <div class="vistaCajero-table-info">
                            <div class="vistaCajero-table-time">
                                <?= $estado === 'disponible' ? 'Lista para usar' : '---' ?>
                            </div>
                            <div class="vistaCajero-table-amount">
                                $<?= number_format($mesa['total'], 0, ',', '.') ?>
                            </div>
                        </div>
                        <div class="vistaCajero-action-buttons">
                            <?php if ($estado === 'disponible'): ?>
                                <button class="vistaCajero-action-btn vistaCajero-btn-view">Asignar Mesa</button>
                            <?php else: ?>
                                <button class="vistaCajero-action-btn vistaCajero-btn-view">Ver Cuenta</button>
                                <button class="vistaCajero-action-btn vistaCajero-btn-close">Cerrar Mesa</button>
                                <button class="vistaCajero-action-btn vistaCajero-btn-pagado" onclick="marcarPagado(<?= $mesa['id'] ?>)">Pagado</button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div id="vistaPedidos" style="display:none; padding:20px;">
            <p>Aquí irán los pedidos en curso.</p>
        </div>
    </main>

    <script>
        function mostrar(vista) {
            document.getElementById('vistaMesas').style.display = vista === 'mesas' ? 'block' : 'none';
            document.getElementById('vistaPedidos').style.display = vista === 'pedidos' ? 'block' : 'none';
            const buttons = document.querySelectorAll('.vistaCajero-toggle button');
            buttons.forEach(btn => btn.classList.remove('active'));
            if (vista === 'mesas') buttons[0].classList.add('active');
            else buttons[1].classList.add('active');
        }

        function marcarPagado(mesaId) {
            fetch('<?= $ruta ?>/cajero/pagarMesa', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'mesa_id=' + mesaId
            })
            .then(res => res.text())
            .then(res => {
                if (res.trim() === 'ok') {
                    location.reload();
                } else {
                    alert('Error al marcar la mesa como pagada');
                }
            });
        }

        document.addEventListener('click', function(e) {
            const menu = document.getElementById('logoutMenu');
            if (!e.target.matches('.hamburger')) {
                menu.style.display = 'none';
            }
        });
    </script>
</body>
</html>