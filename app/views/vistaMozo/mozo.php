<!-- falta separar y agregar un mas de agregar mesas -->
<?php
session_start();
// Simulación de datos del mozo
$mozo = [
    'nombre' => 'Juan Pérez',
    'id' => 'M001',
    'mesas_activas' => 3,
    'mesas_total' => 5
];

// Simulación de datos de las mesas
$mesas = [
    [
        'numero' => 1,
        'estado' => 'reserved',
        'personas' => 4,
        'tiempo' => '45 min',
        'monto' => 2450
    ],
    [
        'numero' => 2,
        'estado' => 'bill-requested',
        'personas' => 2,
        'tiempo' => '90 min',
        'monto' => 1850
    ],
    [
        'numero' => 3,
        'estado' => 'available',
        'personas' => 0,
        'tiempo' => '',
        'monto' => 0
    ]
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terminal Mozo - Gestión de Mesas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            padding-bottom: 2rem;
        }

        main {
            padding: 2rem;
        }

        .waiter-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .waiter-header {
            background: linear-gradient(135deg, #ff6b6b 0%, #ff3333 100%);
            color: white;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .waiter-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .waiter-avatar {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .waiter-stats {
            background: rgba(255,255,255,0.1);
            padding: 0.5rem;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        .tables-grid {
            padding: 1rem;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 1rem;
        }

        .table-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
            position: relative;
            transition: all 0.3s ease;
        }

        .table-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .table-status {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .status-reserved {
            background: #ffc107;
        }

        .status-available {
            background: #28a745;
        }

        .status-bill-requested {
            background: #dc3545;
            animation: pulse 2s infinite;
        }

        .table-info {
            margin-top: 0.5rem;
            font-size: 0.9rem;
            color: #666;
        }

        .table-amount {
            font-weight: bold;
            color: #333;
            margin-top: 0.5rem;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .action-btn {
            flex: 1;
            padding: 0.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.8rem;
            transition: all 0.3s ease;
            font-weight: bold;
        }

        .btn-view {
            background: #1a1a1a;
            color: white;
        }

        .btn-close {
            background: #dc3545;
            color: white;
        }

        .action-btn:hover {
            opacity: 0.9;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
    </style>
</head>
<body>
    <main>
        <div class="waiter-section">
            <div class="waiter-header">
                <div class="waiter-info">
                    <div class="waiter-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <h3><?php echo $mozo['nombre']; ?></h3>
                        <small>ID: <?php echo $mozo['id']; ?></small>
                    </div>
                </div>
                <div class="waiter-stats">
                    Mesas activas: <?php echo $mozo['mesas_activas']; ?>/<?php echo $mozo['mesas_total']; ?>
                </div>
            </div>
            
            <div class="tables-grid">
                <?php foreach($mesas as $mesa): ?>
                <div class="table-card">
                    <div class="table-status status-<?php echo $mesa['estado']; ?>"></div>
                    <i class="fas fa-utensils fa-2x"></i>
                    <h4>Mesa <?php echo $mesa['numero']; ?></h4>
                    <div class="table-info">
                        <?php if($mesa['estado'] == 'available'): ?>
                            Disponible
                        <?php else: ?>
                            <?php echo $mesa['personas']; ?> personas • <?php echo $mesa['tiempo']; ?>
                        <?php endif; ?>
                    </div>
                    <div class="table-amount">
                        $<?php echo number_format($mesa['monto'], 0); ?>
                    </div>
                    <div class="action-buttons">
                        <button class="action-btn btn-view">Ver Cuenta</button>
                        <?php if($mesa['estado'] != 'available'): ?>
                            <button class="action-btn btn-close">Cerrar</button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <script>
        // Agregar interactividad a los botones
        document.querySelectorAll('.action-btn').forEach(button => {
            button.addEventListener('click', function() {
                const action = this.classList.contains('btn-view') ? 'ver cuenta' : 'cerrar';
                const mesa = this.closest('.table-card').querySelector('h4').textContent;
                alert(`Acción: ${action} | ${mesa}`);
            });
        });
    </script>
</body>
</html>