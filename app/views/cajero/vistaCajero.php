<!DOCTYPE html>
<html lang="en">
<head>
    <?=$head?>
    <title><?=$title?></title>
</head>
<body>
    <header><?=$nav?></header>


    <main>
        <!-- Sección Mozo 1 -->
        <div class="waiter-section">
            <div class="waiter-header">
                <div class="waiter-info">
                    <div class="waiter-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <h3>Juan Pérez</h3>
                        <small>ID: M001</small>
                    </div>
                </div>
                <div class="waiter-stats">
                    Mesas activas: 3/5
                </div>
            </div>
            <div class="tables-grid">
                <!-- Mesa 1 -->
                <div class="table-card">
                    <div class="table-status status-reserved"></div>
                    <i class="fas fa-utensils fa-2x"></i>
                    <h4>Mesa 1</h4>
                    <div class="table-info">
                        4 personas • 45 min
                    </div>
                    <div class="table-amount">
                        $2,450
                    </div>
                    <div class="action-buttons">
                        <button class="action-btn btn-view">Ver Cuenta</button>
                        <button class="action-btn btn-close">Cerrar</button>
                    </div>
                </div>

                <!-- Mesa 2 -->
                <div class="table-card">
                    <div class="table-status status-bill-requested"></div>
                    <i class="fas fa-utensils fa-2x"></i>
                    <h4>Mesa 2</h4>
                    <div class="table-info">
                        2 personas • 90 min
                    </div>
                    <div class="table-amount">
                        $1,850
                    </div>
                    <div class="action-buttons">
                        <button class="action-btn btn-view">Ver Cuenta</button>
                        <button class="action-btn btn-close">Cerrar</button>
                    </div>
                </div>

                <!-- Mesa 3 -->
                <div class="table-card">
                    <div class="table-status status-available"></div>
                    <i class="fas fa-utensils fa-2x"></i>
                    <h4>Mesa 3</h4>
                    <div class="table-info">
                        Disponible
                    </div>
                    <div class="table-amount">
                        $0
                    </div>
                    <div class="action-buttons">
                        <button class="action-btn btn-view">Ver Cuenta</button>
                        <button class="action-btn btn-close">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección Mozo 2 -->
        <div class="waiter-section">
            <div class="waiter-header">
                <div class="waiter-info">
                    <div class="waiter-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <h3>María González</h3>
                        <small>ID: M002</small>
                    </div>
                </div>
                <div class="waiter-stats">
                    Mesas activas: 2/4
                </div>
            </div>
            <div class="tables-grid">
                <!-- Mesa 4 -->
                <div class="table-card">
                    <div class="table-status status-reserved"></div>
                    <i class="fas fa-utensils fa-2x"></i>
                    <h4>Mesa 4</h4>
                    <div class="table-info">
                        6 personas • 30 min
                    </div>
                    <div class="table-amount">
                        $3,200
                    </div>
                    <div class="action-buttons">
                        <button class="action-btn btn-view">Ver Cuenta</button>
                        <button class="action-btn btn-close">Cerrar</button>
                    </div>
                </div>

                <!-- Mesa 5 -->
                <div class="table-card">
                    <div class="table-status status-reserved"></div>
                    <i class="fas fa-utensils fa-2x"></i>
                    <h4>Mesa 5</h4>
                    <div class="table-info">
                        3 personas • 15 min
                    </div>
                    <div class="table-amount">
                        $950
                    </div>
                    <div class="action-buttons">
                        <button class="action-btn btn-view">Ver Cuenta</button>
                        <button class="action-btn btn-close">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </main>





    <?=$footer?>
</body>
</html>