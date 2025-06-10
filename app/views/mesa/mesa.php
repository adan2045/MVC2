<!DOCTYPE html>
<html lang="es">

<head>
    <?= $head ?>
    <title><?= $title ?? 'Gestión de Mesas' ?></title>
    <link rel="stylesheet" href="/public/css/crud.css">
</head>

<body class="body-registro">
    <div class="registro-container">
        <div class="registro-header">
            <h1>Gestión de Mesas</h1>
            <p>Sistema de Gestión</p>
        </div>

        <form class="registro-form" method="POST" action="/mesa/guardar">
            <div class="form-grid">
                <div class="form-group full-width">
                    <label class="form-label">Número de Mesa</label>
                    <i class="fas fa-chair"></i>
                    <input type="number" name="numero" class="form-control" required>
                </div>

                <div class="form-group full-width">
                    <label class="form-label">Estado</label>
                    <i class="fas fa-toggle-on"></i>
                    <select name="estado" class="form-control" required>
                        <option value="">Seleccionar estado</option>
                        <option value="libre">Libre</option>
                        <option value="ocupada">Ocupada</option>
                        <option value="reservada">Reservada</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn">Guardar Mesa</button>
        </form>

        <div class="registro-footer">
            <p><a href="<?=$ruta?>admin/gestion">Volver a Gestión</a></p>
        </div>
    </div>

    <?= $footer ?>
</body>
</html>