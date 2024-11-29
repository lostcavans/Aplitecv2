<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Aplicaciones Tecnologica S.R.L. - Sistema de gerenciamiento.</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body class="cover" style="background-image: url(assets/img/preview.webp);">

    <div class="container-login center-align">
        <div style="margin:15px 0;">
            <i class="zmdi zmdi-account-circle zmdi-hc-5x"></i>
            <p>Inicia sesión con tu cuenta</p>   
        </div>
        <form id="loginForm">
            <div class="input-field">
                <input id="Email" name="email" type="email" class="validate" required>
                <label for="Email"><i class="zmdi zmdi-email"></i>&nbsp; Email</label>
            </div>
            <div class="input-field col s12">
                <input id="Password" name="password" type="password" class="validate" required>
                <label for="Password"><i class="zmdi zmdi-lock"></i>&nbsp; Contraseña</label>
            </div>
            <button type="submit" class="waves-effect waves-teal btn-flat">Ingresar &nbsp; <i class="zmdi zmdi-mail-send"></i></button>
        </form>
        <div class="divider" style="margin: 20px 0;"></div>
        <a href="register_client.php">Crear cuenta</a>
    </div>
    
    <!--====== Scripts -->
    <script src="./js/jquery-3.1.1.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/material.min.js"></script>
    <script src="./js/ripples.min.js"></script>
    <script src="./js/sweetalert2.min.js"></script>
    <script src="./js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="./js/main.js"></script>

    <!-- Script de JavaScript para manejar el login -->
    <script>
    document.getElementById("loginForm").addEventListener("submit", function(event) {
        event.preventDefault();

        const email = document.getElementById("Email").value;
        const password = document.getElementById("Password").value;

        fetch('loginCN.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                'email': email,
                'password': password
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                // Redirigir a home.php en caso de éxito
                window.location.href = "home.php";
            } else {
                // Mostrar mensaje de error en caso de fallo
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: data.message,
                    confirmButtonColor: '#4caf50',
                    background: '#333',
                    color: '#fff',
                    footer: '¿Olvidaste tu contraseña?</a>'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error de conexión',
                text: "Hubo un problema al conectar con el servidor.",
                confirmButtonColor: '#4caf50',
                background: '#333',
                color: '#fff'
            });
        });
    });
    </script>
</body>
</html>
