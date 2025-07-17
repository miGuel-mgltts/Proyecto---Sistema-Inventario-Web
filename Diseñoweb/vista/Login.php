<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="Assets/CSS/Login.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body class="body">

  <div class="login-contenedor">
    <div class="login-box">
      <h2>Bienvenido</h2>
      <form method="post" action="index.php?accion=login">

        <label for="usuario">Usuario</label>
        <div class="input-icon">
            <i class="fas fa-user"></i>
            <input type="text" placeholder="Usuario" id="usuario" name="usuario" required>
        </div>

        <label for="clave">Clave</label>
        <div class="input-icon">
            <i class="fas fa-lock"></i>
            <input type="password" placeholder="Contraseña" id="clave" name="clave" required>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <button type="submit"><i class="fas fa-sign-in-alt"></i>Ingresar</button>
      </form>
    </div>
  </div>

</body>
</html>
