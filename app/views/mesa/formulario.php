<!DOCTYPE html>
<html lang="es">
<head>
    <?= $head ?>
    <title><?= $title ?></title>
</head>
<body>
    <header><?= $nav ?></header>  <!-- Esta línea es la que muestra el menú -->
    <main>
        <div class="registro-container">
            <div class="registro-header">
                <h1><?= isset($mesa) ? 'Editar Mesa' : 'Nueva Mesa' ?></h1>
            </div>

            <form action="<?= isset($mesa) ? '/mesa/actualizar' : '/mesa/guardar' ?>" method="POST" class="registro-form">
                <?php if (isset($mesa)): ?>
                    <input type="hidden" name="id" value="<?= $mesa['id'] ?>">
                <?php endif; ?>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Número</label>
                        <input type="number" name="numero" class="form-control" value="<?= $mesa['numero'] ?? '' ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Código QR</label>
                        <input type="text" name="qr_code" class="form-control" value="<?= $mesa['qr_code'] ?? '' ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Link QR</label>
                        <input type="text" name="link_qr" class="form-control" value="<?= $mesa['link_qr'] ?? '' ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-control">
                            <option value="libre" <?= (isset($mesa['estado']) && $mesa['estado'] == 'libre') ? 'selected' : '' ?>>Libre</option>
                            <option value="ocupada" <?= (isset($mesa['estado']) && $mesa['estado'] == 'ocupada') ? 'selected' : '' ?>>Ocupada</option>
                            <option value="cuenta_solicitada" <?= (isset($mesa['estado']) && $mesa['estado'] == 'cuenta_solicitada') ? 'selected' : '' ?>>Cuenta Solicitada</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn"><?= isset($mesa) ? 'Actualizar' : 'Guardar' ?> Mesa</button>
            </form>

            <div class="registro-footer">
                <a href="<?=$ruta?>mesa/listado">Volver al Listado</a>
            </div>
        </div>
    </main>

    <?= $footer ?>
</body>
</html>