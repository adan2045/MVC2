<!DOCTYPE html>
<html lang="en">
<head>
    <?=$head?>
    <title><?=$title?></title>
</head>
<body>
    <header><?=$nav?></header>
    <main>
        <h2 class="main-title">Gestionar Locales</h2>
        <div class="locals-grid">
            <div class="local-container">
                <div class="local-card">
                    <div class="local-icon">
                        <i class="fas fa-store"></i>
                    </div>
                    <h3>Pizzeria Santa Maria</h3>
                </div>
                <div class="buttons-column">
                    <button class="action-btn">Mesas</button>
                    <button class="action-btn delete-btn">Usuarios</button>
                </div>
            </div>
            
             <!--<div class="local-container">
                <div class="local-card">
                    <div class="local-icon">
                        <i class="fas fa-store"></i>
                    </div>
                    <h3>Local 2</h3>
                </div>
                <div class="buttons-column">
                    <button class="action-btn">Gestionar</button>
                    <button class="action-btn delete-btn">Eliminar</button>
                </div>
            </div>
-->
            <div class="local-card add-local">
                <div class="local-icon">
                    <i class="fas fa-plus"></i>
                </div>
                <h3>Agregar Local</h3>
            </div>
        </div>
    </main>
    <?=$footer ?>   
</body>
</html>