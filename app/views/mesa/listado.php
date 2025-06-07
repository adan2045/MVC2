<!DOCTYPE html>
<html lang="es">
<head>
    <?= $head ?>
    <title><?= $title ?></title>
</head>

<body class="body-registro">
    <div class="mesa-container">
        <div class="mesa-header">
            <h1>Listado de Mesas</h1>
            <p>Sistema de Gestión</p>
        </div>

        <div class="mesa-top-actions">
            <a href="/mesa/formulario" class="mesa-btn mesa-btn-nueva">➕ Nueva Mesa</a>
        </div>

        <div class="mesa-tabla-wrapper">
            <table class="mesa-tabla">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Número</th>
                        <th>QR Code</th>
                        <th>Link QR</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($mesas as $mesa): ?>
                        <tr>
                            <td><?= $mesa['id'] ?></td>
                            <td><?= $mesa['numero'] ?></td>
                            <td><?= $mesa['qr_code'] ?? '-' ?></td>
                            <td><?= $mesa['link_qr'] ?? '-' ?></td>
                            <td><?= $mesa['estado'] ?></td>
                            <td>
                                <div class="mesa-acciones">
                                    <a href="/mesa/modificar?id=<?= $mesa['id'] ?>" class="mesa-btn-mini">Modificar</a>
                                    <a href="/mesa/eliminar?id=<?= $mesa['id'] ?>" class="mesa-btn-mini mesa-btn-eliminar" onclick="return confirm('¿Eliminar esta mesa?');">Eliminar</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="mesa-footer">
            <a href="/admin/gestion">Volver al Panel</a>
        </div>
    </div>

    <?= $footer ?>
</body>
</html>