<!DOCTYPE html>
<html lang="es">
<!-- quizas haya que sacar el header ya que es un login  -->
<head>
	<?=$head?>
	<title><?=$title?></title>
</head>

<body class="body-login">
    <div class="login-container">
        <div class="login-header">
            <h1>Terminal Principal</h1>
            <p>Sistema de Gestión</p>
        </div>
        
        <form class="login-form">
            <div class="form-group">
                <i class="fas fa-user"></i>
                <input type="text" class="form-control" placeholder="Usuario" required>
            </div>
            
            <div class="form-group">
                <i class="fas fa-lock"></i>
                <input type="password" class="form-control" placeholder="Contraseña" required>
            </div>
            
            <button type="submit" class="btn">Iniciar Sesión</button>
        </form>
        
        <div class="login-footer">
            <p><a href="#">¿Olvidaste tu contraseña?</a></p>
            <p>¿No tienes una cuenta? <a href="http://localhost/MVC2/public/registro/registro">Regístrate</a></p>
        </div>
    </div>

    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            // Aquí puedes agregar la lógica de autenticación
            console.log('Intento de inicio de sesión');
        });
    </script>
</body>