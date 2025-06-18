<?php
session_start();
$mensaje = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <title>Login</title>
    <link href="css/estilos.css" rel="stylesheet">

</head>

<body>

    <div class="login-box">

        <h2>Iniciar Sesión</h2>

        <?php if (!empty($mensaje)): ?>
            <div class="alert alert-danger alert-dismissible fade show w-100 text-center" role="alert" id="alertaMensaje" style="margin-bottom: 20px;">
                <?= htmlspecialchars($mensaje) ?>
            </div>

            <script>
                setTimeout(() => {
                    const alerta = document.getElementById('alertaMensaje');
                    if (alerta) {
                        alerta.classList.remove('show');
                        alerta.classList.add('fade');
                        setTimeout(() => alerta.remove(), 300);
                    }
                }, 2000);
            </script>
        <?php endif; ?>

        <form action="conexion/validad.php" method="POST">
            <div class="input-group">
                <input type="text" name="txtUsu" placeholder="Usuario" required>
                <i class="fa fa-user"></i>
            </div>
            <div class="input-group">
                <input type="password" name="txtPassword" placeholder="Password" required>
                <i class="fa fa-lock"></i>
            </div>
            <button type="submit">Acceder</button>
            <div class="links">
                <p><a href="restablecer.php">¿Olvidaste tu contraseña?</a></p>
            </div>
        </form>


    </div>

</body>

</html>