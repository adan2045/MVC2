<!DOCTYPE html>
<html lang="es">
<head>
    <?= $head ?>
    <title><?= $title ?? 'Listado de Productos' ?></title>
</head>
<body>
    <header><?= $nav ?></header>

    <main>
        <div class="listado-container">
            <div class="listado-header">
                <h1>Productos del Sistema</h1>
                <p>Sistema de Gestión</p>
            </div>

            <div class="listado-top-actions">
                <a href="<?= App::baseUrl() ?>/producto/formulario" class="listado-btn producto-btn-nuevo">➕ Agregar Producto</a>
            </div>

            <div class="listado-tabla-wrapper">
                <table class="listado-tabla">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Categoría</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos as $producto): ?>
                            <tr>
                                <td><?= $producto['id'] ?></td>
                                <td><?= $producto['nombre'] ?></td>
                                <td><?= $producto['descripcion'] ?></td>
                                <td>$<?= number_format($producto['precio'], 2) ?></td>
                                <td><?= $producto['categoria'] ?></td>
                                <td>
                                    <div class="listado-acciones">
                                        <a href="<?= App::baseUrl() ?>/producto/modificar?id=<?= $producto['id'] ?>" class="listado-btn-mini">Modificar</a>
                                        <a href="<?= App::baseUrl() ?>/producto/eliminar?id=<?= $producto['id'] ?>" class="listado-btn-mini listado-btn-eliminar" onclick="return confirm('¿Eliminar este producto?');">Eliminar</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="listado-footer">
                <a href="<?= App::baseUrl() ?>/admin/gestion">Volver al Panel</a>
            </div>
        </div>
    </main>

    <?= $footer ?>
</body>
</html>