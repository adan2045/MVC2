<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Modificación de Usuario</title>
  <style>
    body {
      background-color: #f5f5f5;
      min-height: 100vh;
      display:flex;
      align-items: center;
      justify-content: center;
      padding: 2rem 0;
      margin: 0;
      font-family: sans-serif;
    }

    .registro-container {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 500px;
      margin: 2rem;
      overflow: hidden;
    }

    .registro-header {
      background: linear-gradient(135deg, #ff6b6b 0%, #ff3333 100%);
      color: white;
      padding: 1.5rem;
      text-align: center;
    }

    .registro-header h1 {
      font-size: 1.5rem;
      margin-bottom: 0.5rem;
    }

    .registro-form {
      padding: 2rem;
    }

    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
    }

    @media (max-width: 600px) {
      .form-grid {
        grid-template-columns: 1fr;
      }
    }

    .form-group {
      margin-bottom: 1.5rem;
      position: relative;
    }

    .form-group.full-width {
      grid-column: 1 / -1;
    }

    .form-label {
      display: block;
      margin-bottom: 0.5rem;
      color: #333;
      font-size: 0.9rem;
      font-weight: bold;
    }

    .form-control {
      width: 100%;
      padding: 0.8rem;
      padding-left: 0.8rem;
      border: 1px solid #ddd;
      border-radius: 4px;
      font-size: 1rem;
      transition: all 0.3s ease;
    }

    .form-control:focus {
      outline: none;
      border-color: #ff3333;
      box-shadow: 0 0 0 2px rgba(255, 51, 51, 0.1);
    }

    .form-group select.form-control {
      appearance: none;
      background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
      background-repeat: no-repeat;
      background-position: right 1rem center;
      background-size: 1em;
    }

    .btn {
      width: 100%;
      padding: 0.8rem;
      border: none;
      border-radius: 4px;
      background: #1a1a1a;
      color: white;
      font-size: 1rem;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .btn:hover {
      background: #333;
    }

    .registro-footer {
      padding: 1rem 2rem;
      text-align: center;
      border-top: 1px solid #eee;
    }

    .registro-footer a {
      color: #ff3333;
      text-decoration: none;
      font-size: 0.9rem;
      transition: all 0.3s ease;
    }

    .registro-footer a:hover {
      color: #cc0000;
    }
  </style>
</head>
<body>
  <main>
    <div class="registro-container">
      <div class="registro-header">
        <h1>Modificación de Usuario</h1>
      </div>

      <form action="<?= $ruta ?>/usuario/actualizar" method="POST" class="registro-form">
        <input type="hidden" name="id" value="<?= $usuario['id'] ?>">

        <div class="form-grid">
          <div class="form-group">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" required value="<?= $usuario['nombre'] ?>">
          </div>

          <div class="form-group">
            <label class="form-label">Apellido</label>
            <input type="text" name="apellido" class="form-control" required value="<?= $usuario['apellido'] ?>">
          </div>

          <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required value="<?= $usuario['email'] ?>">
          </div>

          <div class="form-group">
            <label class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control">
          </div>

          <div class="form-group">
            <label class="form-label">DNI</label>
            <input type="text" name="dni" class="form-control" required value="<?= $usuario['dni'] ?>">
          </div>

          <div class="form-group">
            <label class="form-label">Rol</label>
            <select name="rol" class="form-control">
              <option value="mozo" <?= $usuario['rol'] === 'mozo' ? 'selected' : '' ?>>Mozo</option>
              <option value="admin" <?= $usuario['rol'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
          </div>
        </div>

        <button type="submit" class="btn">Actualizar Usuario</button>
      </form>

      <div class="registro-footer">
        <a href="<?= $ruta ?>/usuario/listado">Volver al Listado</a>
      </div>
    </div>
  </main>
</body>
</html>