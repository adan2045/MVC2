<!DOCTYPE html>
<html lang="es">
<head>
	<?=$head?>
	<title><?=$title?></title>
</head>
<body>
<body>
<body class="vistaCajero-body">
    <header class="vistaCajero-header">
        <div class="vistaCajero-terminal-info">
            <span class="vistaCajero-terminal-title">Terminal Principal - Cajero</span>
            <div class="vistaCajero-time-display" id="vistaCajeroTimeDisplay">00:00:00</div>
        </div>
        <div class="vistaCajero-user-info">
            Cajero: Admin | Terminal: T001
        </div>
    </header>

    <main class="vistaCajero-main">
        <!-- Sección Mozo 1 -->
        <div class="vistaCajero-waiter-section">
            <div class="vistaCajero-waiter-header">
                <div class="vistaCajero-waiter-info">
                    <div class="vistaCajero-waiter-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <h3>Juan Pérez</h3>
                        <small>ID: M001</small>
                    </div>
                </div>
                <div class="vistaCajero-waiter-stats">
                    Mesas activas: 3/5
                </div>
            </div>
            <div class="vistaCajero-tables-grid">
                <!-- Mesa 1 -->
                <div class="vistaCajero-table-card">
                    <div class="vistaCajero-table-status vistaCajero-status-reserved"></div>
                    <i class="fas fa-utensils fa-2x"></i>
                    <h4>Mesa 1</h4>
                    <div class="vistaCajero-table-info">
                        4 personas • 45 min
                    </div>
                    <div class="vistaCajero-table-amount">
                        $2,450
                    </div>
                    <div class="vistaCajero-action-buttons">
                        <button class="vistaCajero-action-btn vistaCajero-btn-view">Ver Cuenta</button>
                        <button class="vistaCajero-action-btn vistaCajero-btn-close">Cerrar</button>
                    </div>
                </div>

                <!-- Mesa 2 -->
                <div class="vistaCajero-table-card">
                    <div class="vistaCajero-table-status vistaCajero-status-bill-requested"></div>
                    <i class="fas fa-utensils fa-2x"></i>
                    <h4>Mesa 2</h4>
                    <div class="vistaCajero-table-info">
                        2 personas • 90 min
                    </div>
                    <div class="vistaCajero-table-amount">
                        $1,850
                    </div>
                    <div class="vistaCajero-action-buttons">
                        <button class="vistaCajero-action-btn vistaCajero-btn-view">Ver Cuenta</button>
                        <button class="vistaCajero-action-btn vistaCajero-btn-close">Cerrar</button>
                    </div>
                </div>

                <!-- Mesa 3 -->
                <div class="vistaCajero-table-card">
                    <div class="vistaCajero-table-status vistaCajero-status-available"></div>
                    <i class="fas fa-utensils fa-2x"></i>
                    <h4>Mesa 3</h4>
                    <div class="vistaCajero-table-info">
                        Disponible
                    </div>
                    <div class="vistaCajero-table-amount">
                        $0
                    </div>
                    <div class="vistaCajero-action-buttons">
                        <button class="vistaCajero-action-btn vistaCajero-btn-view">Ver Cuenta</button>
                        <button class="vistaCajero-action-btn vistaCajero-btn-close">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección Mozo 2 -->
        <div class="vistaCajero-waiter-section">
            <div class="vistaCajero-waiter-header">
                <div class="vistaCajero-waiter-info">
                    <div class="vistaCajero-waiter-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <h3>María González</h3>
                        <small>ID: M002</small>
                    </div>
                </div>
                <div class="vistaCajero-waiter-stats">
                    Mesas activas: 2/4
                </div>
            </div>
            <div class="vistaCajero-tables-grid">
                <!-- Mesa 4 -->
                <div class="vistaCajero-table-card">
                    <div class="vistaCajero-table-status vistaCajero-status-reserved"></div>
                    <i class="fas fa-utensils fa-2x"></i>
                    <h4>Mesa 4</h4>
                    <div class="vistaCajero-table-info">
                        6 personas • 30 min
                    </div>
                    <div class="vistaCajero-table-amount">
                        $3,200
                    </div>
                    <div class="vistaCajero-action-buttons">
                        <button class="vistaCajero-action-btn vistaCajero-btn-view">Ver Cuenta</button>
                        <button class="vistaCajero-action-btn vistaCajero-btn-close">Cerrar</button>
                    </div>
                </div>

                <!-- Mesa 5 -->
                <div class="vistaCajero-table-card">
                    <div class="vistaCajero-table-status vistaCajero-status-reserved"></div>
                    <i class="fas fa-utensils fa-2x"></i>
                    <h4>Mesa 5</h4>
                    <div class="vistaCajero-table-info">
                        3 personas • 15 min
                    </div>
                    <div class="vistaCajero-table-amount">
                        $950
                    </div>
                    <div class="vistaCajero-action-buttons">
                        <button class="vistaCajero-action-btn vistaCajero-btn-view">Ver Cuenta</button>
                        <button class="vistaCajero-action-btn vistaCajero-btn-close">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="vistaCajero.js"></script>
</body>
</html>

 