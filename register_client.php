<?php
session_start(); // Iniciar sesión aquí
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Usuario</title>
    <link rel="stylesheet" href="css/register_user.css"> <!-- Enlace al archivo CSS -->
    <style>
        .password-strength {
            height: 5px;
            width: 100%;
            background-color: #e0e0e0;
            margin-top: 5px;
            border-radius: 3px;
        }
        .strength-weak {
            background-color: red;
        }
        .strength-medium {
            background-color: yellow;
        }
        .strength-strong {
            background-color: green;
        }
    </style>
    <script>
        function validatePasswordStrength(password) {
            const minLength = 8;
            const hasUpperCase = /[A-Z]/.test(password);
            const hasLowerCase = /[a-z]/.test(password);
            const hasNumbers = /[0-9]/.test(password);
            const hasSpecialChar = /[!@#$%^&*()]/.test(password);
            
            return password.length >= minLength && hasUpperCase && hasLowerCase && hasNumbers && hasSpecialChar;
        }

        function updatePasswordStrength(password) {
            const strengthBar = document.getElementById('passwordStrength');
            const strengthText = document.getElementById('strengthText');
            let strength = 0;

            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[!@#$%^&*()]/.test(password)) strength++;

            strengthBar.className = 'password-strength';
            if (strength === 0) {
                strengthBar.classList.add('strength-weak');
                strengthText.textContent = 'Muy débil';
            } else if (strength === 1) {
                strengthBar.classList.add('strength-weak');
                strengthText.textContent = 'Débil';
            } else if (strength === 2) {
                strengthBar.classList.add('strength-medium');
                strengthText.textContent = 'Media';
            } else {
                strengthBar.classList.add('strength-strong');
                strengthText.textContent = 'Fuerte';
            }
        }

        function validateForm() {
            const form = document.forms['userForm'];
            const fec_nac = new Date(form['fec_nac'].value);
            const today = new Date();
            const age = today.getFullYear() - fec_nac.getFullYear();
            const isValidAge = age >= 18 && age <= 98;

            if (!isValidAge) {
                alert('La edad debe estar entre 18 y 98 años.');
                return false;
            }

            const password = form['pass_user'].value;
            const confirmPassword = form['confirm_pass_user'].value;

            if (!validatePasswordStrength(password)) {
                alert('La contraseña debe tener al menos 8 caracteres, incluir una letra mayúscula, una letra minúscula, un número y un carácter especial.');
                return false;
            }

            if (password !== confirmPassword) {
                alert('Las contraseñas no coinciden.');
                return false;
            }

            // Confirmar con el usuario antes de enviar el formulario
            return confirm('¿Está seguro de que desea registrar este usuario?');
        }

        async function submitForm(event) {
    event.preventDefault(); // Evitar el envío normal del formulario

    const form = document.forms['userForm'];
    const formData = new FormData(form);
    
    try {
        const response = await fetch('register_user_sol.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert(result.message); // Mostrar mensaje de éxito
            window.location.href = 'login.php'; // Redirigir a login.php
        } else {
            alert('Error: ' + result.message); // Mostrar mensaje de error
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al enviar el formulario. CI, Celular o email ya registrados.');
    }
}


        function togglePasswordVisibility(inputId, toggleId) {
            const input = document.getElementById(inputId);
            const toggle = document.getElementById(toggleId);
            input.type = input.type === 'password' ? 'text' : 'password';
            toggle.classList.toggle('fa-eye');
            toggle.classList.toggle('fa-eye-slash');
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome para el ícono de ojo -->
</head>
<body>


<!-- Content page -->
<section class="full-box dashboard-contentPage">

    <h2>Registrar Usuario</h2>
    <form name="userForm" onsubmit="return validateForm() && submitForm(event)">
        <label for="nombres">Nombres:</label>
        <input type="text" name="nombres" required><br>

        <label for="apel_pat">Apellido Paterno:</label>
        <input type="text" name="apel_pat"><br>

        <label for="apel_mat">Apellido Materno:</label>
        <input type="text" name="apel_mat" required><br>

        <label for="cel">Celular:</label>
        <input type="text" name="cel" required pattern="\d{10,15}"><br>

        <label for="fec_nac">Fecha de Nacimiento:</label>
        <input type="date" name="fec_nac" required><br>

        <label for="CI">CI (Documento de identificación):</label>
        <input type="text" name="CI" required><br>

        <label for="pais">País:</label>
        <input type="text" name="pais" required><br>

        <label for="departamento">Departamento:</label>
        <input type="text" name="departamento" required><br>

        <label for="provincia">Provincia:</label>
        <input type="text" name="provincia" required><br>

        <label for="ciud">Ciudad:</label>
        <input type="text" name="ciud" required><br>

        <label for="zona">Zona:</label>
        <input type="text" name="zona" required><br>

        <label for="comp">Complemento:</label>
        <input type="text" name="comp" required><br>


        <select name="id_cargo" required hidden>
            <option value="2">Activo</option>
        </select><br>

        <label for="pass_user">Contraseña:</label>
        <input type="password" id="pass_user" name="pass_user" required oninput="updatePasswordStrength(this.value)">
        <i class="fas fa-eye" id="togglePassword" onclick="togglePasswordVisibility('pass_user', 'togglePassword')"></i><br>
        <div class="password-strength" id="passwordStrength"></div>
        <div id="strengthText">Requisitos: Al menos 8 caracteres, 1 mayúscula, 1 minúscula, 1 número y 1 carácter especial.</div>

        <label for="confirm_pass_user">Confirmar Contraseña:</label>
        <input type="password" id="confirm_pass_user" name="confirm_pass_user" required>
        <i class="fas fa-eye" id="toggleConfirmPassword" onclick="togglePasswordVisibility('confirm_pass_user', 'toggleConfirmPassword')"></i><br>

        <label for="email">Correo Electrónico:</label>
        <input type="email" name="email" required><br>

        <label for="inst">Institución o Empresa:</label>
        <input type="text" name="inst"><br>


        <select name="stat" hidden>
            <option value="1">Activo</option>
        </select><br>

        <button type="submit">Registrar</button>
    </form>
</section>

</body>
</html>
