<!DOCTYPE html>
<html lang="es">
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
        
        <form class="login-form" method="POST">
            <!-- Mostrar error general si existe -->
            <?php if (!empty($general_error)): ?>
                <div class="alert alert-info" style="margin-bottom: 1rem; padding: 0.5rem; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 0.25rem; color: #155724;">
                    <?=$general_error?>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <i class="fas fa-user"></i>
                <input type="email" name="mail" class="form-control" placeholder="Correo Electrónico" 
                       value="<?=htmlspecialchars($mail ?? '')?>" required>
                <!-- Mostrar error de email -->
                <?php if (!empty($error_mail)): ?>
                    <div class="error-message" style="color: #dc3545; font-size: 0.875rem; margin-top: 0.25rem;">
                        <?=htmlspecialchars($error_mail)?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" class="form-control" placeholder="Contraseña" 
                       value="<?=htmlspecialchars($password ?? '')?>" required>
                <!-- Mostrar error de contraseña -->
                <?php if (!empty($error_pass)): ?>
                    <div class="error-message" style="color: #dc3545; font-size: 0.875rem; margin-top: 0.25rem;">
                        <?=htmlspecialchars($error_pass)?>
                    </div>
                <?php endif; ?>
            </div>
            
            <button type="submit" name="ingreso" class="btn">Iniciar Sesión</button>
        </form>
        
        <div class="login-footer">
            <p><a href="#">¿Olvidaste tu contraseña?</a></p>
            <p>¿No tienes una cuenta? <a href="http://localhost/MVC2/public/registro/registro">Regístrate</a></p>
        </div>
    </div>

    <!-- Script mejorado con validaciones del profesor -->
    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            // Permitir el envío del formulario para que PHP procese las validaciones
            // Las validaciones principales las maneja PHP (del profesor)
            
            // Validaciones básicas de frontend como respaldo
            const email = document.querySelector('input[name="mail"]').value;
            const password = document.querySelector('input[name="password"]').value;
            
            if (!email.trim()) {
                alert('Por favor ingrese su correo electrónico');
                e.preventDefault();
                return false;
            }
            
            if (!password.trim()) {
                alert('Por favor ingrese su contraseña');
                e.preventDefault();
                return false;
            }
            
            // Validación básica de email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert('Por favor ingrese un correo electrónico válido');
                e.preventDefault();
                return false;
            }
            
            // Las validaciones completas las maneja PHP
            console.log('Enviando formulario con validaciones del profesor...');
        });
    </script>
</body>
</html>