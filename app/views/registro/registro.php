<!DOCTYPE html>
<html lang="es">

<head>
	<?=$head?>
	<title><?=$title?></title>
</head>

<body class="body-registro">
    <div class="registro-container">
        <div class="registro-header">
            <h1>Registro de Usuario</h1>
            <p>Sistema de Gestión</p>
        </div>
        
        <form class="registro-form">
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Nombre</label>
                    <i class="fas fa-user"></i>
                    <input type="text" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Apellido</label>
                    <i class="fas fa-user"></i>
                    <input type="text" class="form-control" required>
                </div>

                <div class="form-group full-width">
                    <label class="form-label">Correo Electrónico</label>
                    <i class="fas fa-envelope"></i>
                    <input type="email" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Contraseña</label>
                    <i class="fas fa-lock"></i>
                    <input type="password" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Confirmar Contraseña</label>
                    <i class="fas fa-lock"></i>
                    <input type="password" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">DNI</label>
                    <i class="fas fa-id-card"></i>
                    <input type="text" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Rol</label>
                    <i class="fas fa-user-tag"></i>
                    <select class="form-control" required>
                        <option value="">Seleccionar rol</option>
                        <option value="mozo">Mozo</option>
                        <option value="cajero">Cajero</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>
            </div>

            <div class="terms-check">
                <input type="checkbox" id="terms" required>
                <label for="terms">Acepto los términos y condiciones</label>
            </div>
            
            <button type="submit" class="btn">Dar de Alta</button>
        </form>
        
        <div class="registro-footer">
            <p>¿Ya tienes una cuenta? <a href="http://localhost/MVC2/public/login/login">Iniciar Sesión</a></p>
        </div>
    </div>

    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            // Aquí puedes agregar la lógica de registro
            console.log('Intento de registro');
        });
    </script>
</body>