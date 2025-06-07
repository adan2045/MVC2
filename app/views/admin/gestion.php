<!DOCTYPE html>
<html lang="en">
<head>
    <?= $head ?>
    <title><?= $title ?></title>
</head>
<body>
    <header><?= $nav ?></header>
    <main>
    <h2 class="main-title">Gestionar Locales</h2>
    <div class="locals-grid">
        <div class="local-container">
            <div class="local-card tall-card">
                <div class="local-icon">
                    <i class="fas fa-store"></i>
                </div>
                <h3>Pizzeria Santa Maria</h3>
            </div>
            <div class="buttons-column">
             <a href="http://localhost/MVC2/public/mesa/listado" class="action-btn">Mesas</a>
             <a href="http://localhost/MVC2/public/usuario/listado" class="action-btn delete-btn">Usuarios</a>
             <a href="http://localhost/MVC2/public/productos/listado" class="action-btn">Carta</a> <!-- Botón nuevo -->
</div>
        </div>

        <div class="local-card add-local">
            <div class="local-icon">
                <i class="fas fa-plus"></i>
            </div>
            <h3>Agregar Local</h3>
        </div>
    </div>
</main>
    <?= $footer ?>   
</body>
</html>