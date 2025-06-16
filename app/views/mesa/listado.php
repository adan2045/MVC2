<!DOCTYPE html>
<html lang="es">
<head>
    <?= $head ?>
    <title><?= $title ?? 'Listado de Mesas' ?></title>
</head>
<body>
    <header><?= $nav ?></header>

    <main>
        <div class="mesa-container">
            <div class="mesa-header">
                <h1>Listado de Mesas</h1>
                <p>Sistema de Gestión</p>
            </div>

            <div class="mesa-top-actions">
                <a href="<?=$ruta?>/mesa/formulario" class="mesa-btn mesa-btn-nueva">➕ Nueva Mesa</a>
            </div>

            <div class="mesa-tabla-wrapper">
                <table class="mesa-tabla">
                    <thead>
                        <tr>
                            
                            <th>Número</th>
                            <th>QR</th>
                            <th>Link QR</th>
                            
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($mesas as $mesa): ?>
                            <tr>
                                
                                <td><?= $mesa['numero'] ?></td>
                                <td>
                                    <?php if (!empty($mesa['qr_code'])): ?>
                                        <img src="<?= App::baseUrl() . str_replace('/public', '', $mesa['qr_code']) ?>" alt="QR Mesa <?= $mesa['numero'] ?>" width="60">
                                    <?php else: ?>
                                        Sin QR
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($mesa['link_qr'])): ?>
                                        <a href="<?= $mesa['link_qr'] ?>" target="_blank"><?= $mesa['link_qr'] ?></a>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                
                                <td>
                                    <div class="mesa-acciones">
                                        <a href="<?=$ruta?>/mesa/modificar?id=<?= $mesa['id'] ?>" class="mesa-btn-mini">Modificar</a>
                                        <a href="<?=$ruta?>/mesa/eliminar?id=<?= $mesa['id'] ?>" class="mesa-btn-mini mesa-btn-eliminar" onclick="return confirm('¿Eliminar esta mesa?');">Eliminar</a>
                                        <a href="<?= App::baseUrl() . str_replace('/public', '', $mesa['qr_code']) ?>" download class="mesa-btn-mini mesa-btn-descargar">Descargar QR</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="mesa-footer">
                <a href="<?=$ruta?>/admin/gestion">Volver al Panel</a>
            </div>
        </div>
    </main>

    <?= $footer ?>
</body>
</html>