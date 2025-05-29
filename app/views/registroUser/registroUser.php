<!DOCTYPE html>
<html lang="es">
<!-- quizas haya que sacar el header ya que es un login  -->
<head>
	<?=$head?>
	<title>Bienvenido - Bar Las Heras</title>
</head>
<body >
    <div class="welcome-container">
        <div class="welcome-header">
            <h1>Bienvenido al Bar Las Heras</h1>
        </div>
        <div class="welcome-content">
            <form id="nombreForm" action="procesar.php" method="POST">
                <div class="form-group">
                    <label for="nombre">Escribí tu nombre:</label>
                    <input type="text" id="nombre" name="nombre" required>
                    <div id="errorNombre" class="error-message">Por favor, ingresa tu nombre</div>
                </div>
            </form>
        </div>
        <div class="welcome-actions">
            <button type="submit" form="nombreForm" class="action-btn">
                <i class="fas fa-arrow-right"></i> Continuar
            </button>
        </div>
    </div>

    <script>
        document.getElementById('nombreForm').addEventListener('submit', function(e) {
            const nombreInput = document.getElementById('nombre');
            const errorNombre = document.getElementById('errorNombre');
            
            if (nombreInput.value.trim() === '') {
                e.preventDefault();
                errorNombre.style.display = 'block';
                nombreInput.focus();
            } else {
                errorNombre.style.display = 'none';
            }
        });
    </script>
</body>
</html>