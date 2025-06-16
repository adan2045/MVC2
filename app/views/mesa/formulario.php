<!DOCTYPE html>
<html lang="es">
<head>
    <?= $head ?>
    <title><?= $title ?></title>
</head>
<body>
    
    <main>
        <div class="registro-container">
            <div class="registro-header">
                <h1><?= isset($mesa) ? 'Editar Mesa' : 'Nueva Mesa' ?></h1>
            </div>

            <form action="<?= isset($mesa) ? $ruta . '/mesa/actualizar' : $ruta . '/mesa/guardar' ?>" method="POST" class="registro-form">
                <?php if (isset($mesa)): ?>
                    <input type="hidden" name="id" value="<?= $mesa['id'] ?>">
                <?php endif; ?>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Número</label>
                        <input type="number" name="numero" class="form-control" value="<?= $mesa['numero'] ?? '' ?>" required>
                    </div>

                    
                </div>

                <button type="submit" class="btn"><?= isset($mesa) ? 'Actualizar' : 'Guardar' ?> Mesa</button>
            </form>

            <div class="registro-footer">
                <a href="<?= $ruta ?>/mesa/listado">Volver al Listado</a>
            </div>
        </div>
    </main>

</body>
</html>