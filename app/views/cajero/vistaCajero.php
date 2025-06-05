<!DOCTYPE html>
<html lang="es">
<head>
	<?=$head?>
	<title><?=$title?></title>
</head>

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
        <h2 class="vistaCajero-section-title">Estado de Mesas</h2>
        
        <div class="vistaCajero-tables-grid">
            <!-- Mesa 1 - Ocupada (rojo) -->
            <div class="vistaCajero-table-card vistaCajero-table-ocupada">
                <div class="vistaCajero-table-status vistaCajero-status-ocupada"></div>
                <i class="fas fa-utensils fa-2x vistaCajero-table-icon"></i>
                <h3 class="vistaCajero-table-number">Mesa 1</h3>
                <div class="vistaCajero-table-state">OCUPADA</div>
                <div class="vistaCajero-table-info">
                    <div class="vistaCajero-table-time">45 min</div>
                    <div class="vistaCajero-table-amount">$2,450</div>
                </div>
                <div class="vistaCajero-action-buttons">
                    <button class="vistaCajero-action-btn vistaCajero-btn-view">Ver Cuenta</button>
                    <button class="vistaCajero-action-btn vistaCajero-btn-close">Cerrar Mesa</button>
                </div>
            </div>

            <!-- Mesa 2 - Cuenta Solicitada (amarillo) -->
            <div class="vistaCajero-table-card vistaCajero-table-cuenta-solicitada">
                <div class="vistaCajero-table-status vistaCajero-status-cuenta-solicitada"></div>
                <i class="fas fa-utensils fa-2x vistaCajero-table-icon"></i>
                <h3 class="vistaCajero-table-number">Mesa 2</h3>
                <div class="vistaCajero-table-state">CUENTA SOLICITADA</div>
                <div class="vistaCajero-table-info">
                    <div class="vistaCajero-table-time">90 min</div>
                    <div class="vistaCajero-table-amount">$1,850</div>
                </div>
                <div class="vistaCajero-action-buttons">
                    <button class="vistaCajero-action-btn vistaCajero-btn-view">Ver Cuenta</button>
                    <button class="vistaCajero-action-btn vistaCajero-btn-close">Cerrar Mesa</button>
                </div>
            </div>

            <!-- Mesa 3 - Disponible (verde) -->
            <div class="vistaCajero-table-card vistaCajero-table-disponible">
                <div class="vistaCajero-table-status vistaCajero-status-disponible"></div>
                <i class="fas fa-utensils fa-2x vistaCajero-table-icon"></i>
                <h3 class="vistaCajero-table-number">Mesa 3</h3>
                <div class="vistaCajero-table-state">DISPONIBLE</div>
                <div class="vistaCajero-table-info">
                    <div class="vistaCajero-table-time">Lista para usar</div>
                    <div class="vistaCajero-table-amount">$0</div>
                </div>
                <div class="vistaCajero-action-buttons">
                    <button class="vistaCajero-action-btn vistaCajero-btn-view">Asignar Mesa</button>
                </div>
            </div>

            <!-- Mesas 4 a 10 (patrón similar) -->
            <!-- Mesa 4 - Ocupada -->
            <div class="vistaCajero-table-card vistaCajero-table-ocupada">
                <div class="vistaCajero-table-status vistaCajero-status-ocupada"></div>
                <i class="fas fa-utensils fa-2x vistaCajero-table-icon"></i>
                <h3 class="vistaCajero-table-number">Mesa 4</h3>
                <div class="vistaCajero-table-state">OCUPADA</div>
                <div class="vistaCajero-table-info">
                    <div class="vistaCajero-table-time">30 min</div>
                    <div class="vistaCajero-table-amount">$3,200</div>
                </div>
                <div class="vistaCajero-action-buttons">
                    <button class="vistaCajero-action-btn vistaCajero-btn-view">Ver Cuenta</button>
                    <button class="vistaCajero-action-btn vistaCajero-btn-close">Cerrar Mesa</button>
                </div>
            </div>

            <!-- Mesa 5 - Disponible -->
            <div class="vistaCajero-table-card vistaCajero-table-disponible">
                <div class="vistaCajero-table-status vistaCajero-status-disponible"></div>
                <i class="fas fa-utensils fa-2x vistaCajero-table-icon"></i>
                <h3 class="vistaCajero-table-number">Mesa 5</h3>
                <div class="vistaCajero-table-state">DISPONIBLE</div>
                <div class="vistaCajero-table-info">
                    <div class="vistaCajero-table-time">Lista para usar</div>
                    <div class="vistaCajero-table-amount">$0</div>
                </div>
                <div class="vistaCajero-action-buttons">
                    <button class="vistaCajero-action-btn vistaCajero-btn-view">Asignar Mesa</button>
                </div>
            </div>

            <!-- Mesa 6 - Ocupada -->
            <div class="vistaCajero-table-card vistaCajero-table-ocupada">
                <div class="vistaCajero-table-status vistaCajero-status-ocupada"></div>
                <i class="fas fa-utensils fa-2x vistaCajero-table-icon"></i>
                <h3 class="vistaCajero-table-number">Mesa 6</h3>
                <div class="vistaCajero-table-state">OCUPADA</div>
                <div class="vistaCajero-table-info">
                    <div class="vistaCajero-table-time">65 min</div>
                    <div class="vistaCajero-table-amount">$1,750</div>
                </div>
                <div class="vistaCajero-action-buttons">
                    <button class="vistaCajero-action-btn vistaCajero-btn-view">Ver Cuenta</button>
                    <button class="vistaCajero-action-btn vistaCajero-btn-close">Cerrar Mesa</button>
                </div>
            </div>

            <!-- Mesa 7 - Cuenta Solicitada -->
            <div class="vistaCajero-table-card vistaCajero-table-cuenta-solicitada">
                <div class="vistaCajero-table-status vistaCajero-status-cuenta-solicitada"></div>
                <i class="fas fa-utensils fa-2x vistaCajero-table-icon"></i>
                <h3 class="vistaCajero-table-number">Mesa 7</h3>
                <div class="vistaCajero-table-state">CUENTA SOLICITADA</div>
                <div class="vistaCajero-table-info">
                    <div class="vistaCajero-table-time">85 min</div>
                    <div class="vistaCajero-table-amount">$2,180</div>
                </div>
                <div class="vistaCajero-action-buttons">
                    <button class="vistaCajero-action-btn vistaCajero-btn-view">Ver Cuenta</button>
                    <button class="vistaCajero-action-btn vistaCajero-btn-close">Cerrar Mesa</button>
                </div>
            </div>

            <!-- Mesa 8 - Disponible -->
            <div class="vistaCajero-table-card vistaCajero-table-disponible">
                <div class="vistaCajero-table-status vistaCajero-status-disponible"></div>
                <i class="fas fa-utensils fa-2x vistaCajero-table-icon"></i>
                <h3 class="vistaCajero-table-number">Mesa 8</h3>
                <div class="vistaCajero-table-state">DISPONIBLE</div>
                <div class="vistaCajero-table-info">
                    <div class="vistaCajero-table-time">Lista para usar</div>
                    <div class="vistaCajero-table-amount">$0</div>
                </div>
                <div class="vistaCajero-action-buttons">
                    <button class="vistaCajero-action-btn vistaCajero-btn-view">Asignar Mesa</button>
                </div>
            </div>

            <!-- Mesa 9 - Ocupada -->
            <div class="vistaCajero-table-card vistaCajero-table-ocupada">
                <div class="vistaCajero-table-status vistaCajero-status-ocupada"></div>
                <i class="fas fa-utensils fa-2x vistaCajero-table-icon"></i>
                <h3 class="vistaCajero-table-number">Mesa 9</h3>
                <div class="vistaCajero-table-state">OCUPADA</div>
                <div class="vistaCajero-table-info">
                    <div class="vistaCajero-table-time">25 min</div>
                    <div class="vistaCajero-table-amount">$1,320</div>
                </div>
                <div class="vistaCajero-action-buttons">
                    <button class="vistaCajero-action-btn vistaCajero-btn-view">Ver Cuenta</button>
                    <button class="vistaCajero-action-btn vistaCajero-btn-close">Cerrar Mesa</button>
                </div>
            </div>

            <!-- Mesa 10 - Disponible -->
            <div class="vistaCajero-table-card vistaCajero-table-disponible">
                <div class="vistaCajero-table-status vistaCajero-status-disponible"></div>
                <i class="fas fa-utensils fa-2x vistaCajero-table-icon"></i>
                <h3 class="vistaCajero-table-number">Mesa 10</h3>
                <div class="vistaCajero-table-state">DISPONIBLE</div>
                <div class="vistaCajero-table-info">
                    <div class="vistaCajero-table-time">Lista para usar</div>
                    <div class="vistaCajero-table-amount">$0</div>
                </div>
                <div class="vistaCajero-action-buttons">
                    <button class="vistaCajero-action-btn vistaCajero-btn-view">Asignar Mesa</button>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Reloj en tiempo real
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('es-ES', { hour12: false });
            document.getElementById('vistaCajeroTimeDisplay').textContent = timeString;
        }
        
        setInterval(updateTime, 1000);
        updateTime();
    </script>
</body>
</html>