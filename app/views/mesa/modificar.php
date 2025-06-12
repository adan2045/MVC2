<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Mesa</title>
    <style>
        body {
            background-color: #f5f5f5;
            min-height: 100vh;
            display: flex;
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
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
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
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
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
                <h1>Editar Mesa</h1>
            </div>

            <form action="/MVC2/mesa/actualizar" method="POST" class="registro-form">
                <input type="hidden" name="id" value="<?= $mesa['id'] ?>">

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Número</label>
                        <input type="number" name="numero" class="form-control" value="<?= $mesa['numero'] ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Código QR</label>
                        <input type="text" name="qr_code" class="form-control" value="<?= $mesa['qr_code'] ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Link QR</label>
                        <input type="text" name="link_qr" class="form-control" value="<?= $mesa['link_qr'] ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-control">
                            <option value="libre" <?= $mesa['estado'] === 'libre' ? 'selected' : '' ?>>Libre</option>
                            <option value="ocupada" <?= $mesa['estado'] === 'ocupada' ? 'selected' : '' ?>>Ocupada</option>
                            <option value="cuenta_solicitada" <?= $mesa['estado'] === 'cuenta_solicitada' ? 'selected' : '' ?>>Cuenta Solicitada</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn">Actualizar Mesa</button>
            </form>

            <div class="registro-footer">
                <a href="/MVC2/mesa/listado">Volver al Listado</a>
            </div>
        </div>
    </main>
</body>
</html>