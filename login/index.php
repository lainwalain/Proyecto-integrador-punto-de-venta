<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Market Go - Tu Tienda de Abarrotes</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/icheck-bootstrap/3.0.1/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">

    <!-- Libreria Sweetallert2-->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --primary-color: #2ecc71;
            --primary-dark: #27ae60;
            --secondary-color: #16a085;
            --accent-color: #3498db;
            --light-color: #f8fbf9;
            --dark-color: #2c3e50;
            --text-color: #333;
            --border-radius: 12px;
            --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            --transition: all 0.3s ease;
        }
        
        * {
            box-sizing: border-box;
        }
        
        body {
            background: 
                linear-gradient(rgba(44, 62, 80, 0.85), rgba(44, 62, 80, 0.9)),
                url('https://i.pinimg.com/originals/bd/59/ed/bd59ed2418e9103e576b22a5aebc82c8.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            font-family: 'Source Sans Pro', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            margin: 0;
            color: var(--text-color);
        }
        
        .market-go-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%) !important;
            border: none !important;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(46, 204, 113, 0.3);
        }
        
        .market-go-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(46, 204, 113, 0.4);
            background: linear-gradient(135deg, #27ae60, #2ecc71) !important;
        }
        
        .btn-registro {
            background: linear-gradient(135deg, #16a085, #1abc9c) !important;
            border: none !important;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(22, 160, 133, 0.3);
        }
        
        .btn-registro:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(22, 160, 133, 0.4);
            background: linear-gradient(135deg, #138d75, #16a085) !important;
        }
        
        .login-box {
            width: 100%;
            max-width: 420px;
            animation: fadeIn 0.8s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .card {
            border-radius: var(--border-radius);
            border: none;
            box-shadow: var(--box-shadow);
            overflow: hidden;
            background: var(--light-color);
            transition: var(--transition);
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%) !important;
            border-bottom: none;
            padding: 25px 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .card-header::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,186.7C384,213,480,235,576,213.3C672,192,768,128,864,128C960,128,1056,192,1152,197.3C1248,203,1344,149,1392,122.7L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
            background-size: cover;
            background-position: bottom;
        }
        
        .card-header h4 {
            position: relative;
            z-index: 1;
            color: white;
            font-weight: 700;
            margin: 0;
            font-size: 1.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        .login-logo-container {
            text-align: center;
            margin-bottom: 20px;
            position: relative;
        }
        
        .login-logo-container img {
            width: 100px;
            height: 100px;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2));
            transition: var(--transition);
        }
        
        .login-logo-container:hover img {
            transform: scale(1.05) rotate(5deg);
        }
        
        .login-logo {
            font-size: 2.5rem;
            font-weight: 800;
            margin: 10px 0 5px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .tagline {
            color: white;
            font-size: 1rem;
            font-weight: 500;
            margin-bottom: 30px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            position: relative;
            z-index: 1;
        }
        
        .card-body {
            padding: 30px;
        }
        
        .login-box-msg {
            margin-bottom: 25px;
            text-align: center;
            color: var(--dark-color);
            font-weight: 500;
            font-size: 1rem;
        }
        
        .input-group {
            margin-bottom: 20px;
            position: relative;
        }
        
        .form-control {
            border-radius: var(--border-radius);
            border: 2px solid #e0e0e0;
            padding: 12px 15px;
            font-size: 1rem;
            transition: var(--transition);
            background-color: white;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(46, 204, 113, 0.25);
        }
        
        .input-group-text {
            background-color: white;
            border: 2px solid #e0e0e0;
            border-left: none;
            border-radius: 0 var(--border-radius) var(--border-radius) 0;
            transition: var(--transition);
        }
        
        .input-group:focus-within .input-group-text {
            border-color: var(--primary-color);
        }
        
        .password-toggle {
            cursor: pointer;
            color: #777;
            transition: var(--transition);
        }
        
        .password-toggle:hover {
            color: var(--primary-color);
            transform: scale(1.1);
        }
        
        .icheck-primary input:checked+label::before {
            background-color: var(--primary-color);
            border-color: var(--primary-dark);
        }
        
        .divider {
            display: flex;
            align-items: center;
            margin: 25px 0;
        }
        
        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .divider-text {
            padding: 0 15px;
            color: #777;
            font-size: 0.9rem;
        }
        
        .btn {
            border-radius: var(--border-radius);
            padding: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: var(--transition);
        }
        
        .system-info {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #eaeaea;
        }
        
        .system-info p {
            margin: 5px 0;
            color: #777;
            font-size: 0.85rem;
        }
        
        .features {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
            text-align: center;
        }
        
        .feature {
            flex: 1;
            padding: 0 10px;
        }
        
        .feature i {
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-bottom: 8px;
        }
        
        .feature p {
            font-size: 0.8rem;
            margin: 0;
            color: #777;
        }
        
        .registro-section {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eaeaea;
            text-align: center;
        }
        
        .registro-link {
            color: var(--primary-color);
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
        }
        
        .registro-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }
        
        /* Responsive adjustments */
        @media (max-width: 576px) {
            .login-box {
                max-width: 100%;
            }
            
            .card-body {
                padding: 20px;
            }
            
            .features {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>
<body class="hold-transition login-page">

<div class="login-box">
    <!-- Mensajes de sesión -->
    <?php
    if(isset($_SESSION['mensaje'])){
        $respuesta = $_SESSION['mensaje']; 
        $icono = $_SESSION['icono'] ?? 'info'; ?>
        <script>
            Swal.fire({
                position: 'top-end',
                icon: '<?php echo $icono; ?>',
                title: '<?php echo $respuesta;?>',
                showConfirmButton: false,
                timer: 2000
            })
        </script>
    <?php
        unset($_SESSION['mensaje']);
        unset($_SESSION['icono']);
    }
    ?>

    <!-- Logo Market Go -->
    <div class="text-center mb-4">
        <div class="login-logo-container">
            <img src="https://cdn-icons-png.flaticon.com/512/3063/3063812.png" 
                 alt="Market Go" 
                 width="120">
        </div>
        <h3 class="login-logo mt-2">
            <b>MARKET</b>GO
        </h3>
        <p class="tagline">Tu solución integral para abarrotes</p>
    </div>

    <div class="card card-outline card-primary shadow-lg">
        <div class="card-header text-center py-3">
            <h4 class="mb-0">Bienvenido a Market Go</h4>
        </div>
        <div class="card-body p-4">
            <p class="login-box-msg" id="loginMessage">Inicia sesión en tu cuenta</p>

            <!-- Formulario de Login -->
            <form action="../app/controllers/login/ingreso.php" method="post" id="loginForm">
                <div class="input-group mb-3">
                    <input type="email" 
                           name="email" 
                           class="form-control py-3" 
                           placeholder="Correo electrónico" 
                           required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope text-muted"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" 
                           name="password_user" 
                           class="form-control py-3 password-field" 
                           placeholder="Contraseña" 
                           required
                           id="passwordInput">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-eye password-toggle" id="togglePassword"></span>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember">
                            <label for="remember" class="text-muted">
                                Recordar mis datos
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <button type="submit" 
                                class="btn market-go-primary btn-block py-3" 
                                id="btnIngresar">
                            <i class="fas fa-sign-in-alt mr-2"></i>Iniciar Sesión
                        </button>
                    </div>
                </div>
            </form>

            <!-- Formulario de Registro (oculto inicialmente) -->
            <form action="../app/controllers/login/registro.php" method="post" id="registroForm" style="display: none;">
                <div class="input-group mb-3">
                    <input type="text" 
                           name="nombres" 
                           class="form-control py-3" 
                           placeholder="Nombre completo" 
                           required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user text-muted"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="email" 
                           name="email" 
                           class="form-control py-3" 
                           placeholder="Correo electrónico" 
                           required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope text-muted"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" 
                           name="password_user" 
                           class="form-control py-3 password-field-registro" 
                           placeholder="Contraseña" 
                           required
                           id="passwordInputRegistro">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-eye password-toggle" id="togglePasswordRegistro"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" 
                           name="confirm_password" 
                           class="form-control py-3" 
                           placeholder="Confirmar contraseña" 
                           required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock text-muted"></span>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <button type="submit" 
                                class="btn btn-registro btn-block py-3" 
                                id="btnRegistrar">
                            <i class="fas fa-user-plus mr-2"></i>Crear Cuenta
                        </button>
                    </div>
                </div>
            </form>

            <!-- Sección de registro - SOLO EL LINK -->
            <div class="registro-section">
                <p class="mb-2">¿No tienes cuenta?</p>
                <a href="javascript:void(0)" id="linkRegistro" class="registro-link">
                    <i class="fas fa-user-plus mr-1"></i>Regístrate aquí
                </a>
                <span id="linkLogin" style="display: none;">
                    <a href="javascript:void(0)" class="registro-link">
                        <i class="fas fa-sign-in-alt mr-1"></i>Volver al inicio de sesión
                    </a>
                </span>
            </div>

            <!-- Características del sistema -->
            <div class="features">
                <div class="feature">
                    <i class="fas fa-cash-register"></i>
                    <p>Ventas Rápidas</p>
                </div>
                <div class="feature">
                    <i class="fas fa-boxes"></i>
                    <p>Control de Inventario</p>
                </div>
                <div class="feature">
                    <i class="fas fa-chart-line"></i>
                    <p>Reportes Detallados</p>
                </div>
            </div>

            <!-- Información del sistema -->
            <div class="system-info">
                <p class="mb-1">
                    <i class="fas fa-store mr-1"></i>Market Go v1.0
                </p>
                <p>
                    Tu tienda de abarrotes de confianza
                </p>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>

<script>
    // Alternar entre login y registro
    document.getElementById('linkRegistro').addEventListener('click', function() {
        mostrarRegistro();
    });

    document.getElementById('linkLogin').addEventListener('click', function() {
        mostrarLogin();
    });

    function mostrarRegistro() {
        document.getElementById('loginForm').style.display = 'none';
        document.getElementById('registroForm').style.display = 'block';
        document.getElementById('linkRegistro').style.display = 'none';
        document.getElementById('linkLogin').style.display = 'inline';
        document.getElementById('loginMessage').textContent = 'Crea tu cuenta en Market Go';
    }

    function mostrarLogin() {
        document.getElementById('loginForm').style.display = 'block';
        document.getElementById('registroForm').style.display = 'none';
        document.getElementById('linkRegistro').style.display = 'inline';
        document.getElementById('linkLogin').style.display = 'none';
        document.getElementById('loginMessage').textContent = 'Inicia sesión en tu cuenta';
    }

    // Toggle password para login
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('passwordInput');
        const toggleIcon = this;
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
            toggleIcon.style.color = '#2ecc71';
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
            toggleIcon.style.color = '';
        }
    });

    // Toggle password para registro
    document.getElementById('togglePasswordRegistro').addEventListener('click', function() {
        const passwordInput = document.getElementById('passwordInputRegistro');
        const toggleIcon = this;
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
            toggleIcon.style.color = '#2ecc71';
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
            toggleIcon.style.color = '';
        }
    });

    // Validación del formulario de login
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        const email = document.querySelector('#loginForm input[name="email"]');
        const password = document.querySelector('#loginForm input[name="password_user"]');
        const btnIngresar = document.getElementById('btnIngresar');
        
        if (!email.value || !password.value) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Campos incompletos',
                text: 'Por favor, complete todos los campos requeridos.',
                confirmButtonColor: '#2ecc71'
            });
            return;
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email.value)) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Email inválido',
                text: 'Por favor, ingrese un correo electrónico válido.',
                confirmButtonColor: '#2ecc71'
            });
            return;
        }
        
        btnIngresar.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Iniciando sesión...';
        btnIngresar.disabled = true;
    });

    // Validación del formulario de registro
    document.getElementById('registroForm').addEventListener('submit', function(e) {
        const nombres = document.querySelector('#registroForm input[name="nombres"]');
        const email = document.querySelector('#registroForm input[name="email"]');
        const password = document.querySelector('#registroForm input[name="password_user"]');
        const confirmPassword = document.querySelector('#registroForm input[name="confirm_password"]');
        const btnRegistrar = document.getElementById('btnRegistrar');
        
        if (!nombres.value || !email.value || !password.value || !confirmPassword.value) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Campos incompletos',
                text: 'Por favor, complete todos los campos requeridos.',
                confirmButtonColor: '#2ecc71'
            });
            return;
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email.value)) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Email inválido',
                text: 'Por favor, ingrese un correo electrónico válido.',
                confirmButtonColor: '#2ecc71'
            });
            return;
        }

        if (password.value.length < 6) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Contraseña muy corta',
                text: 'La contraseña debe tener al menos 6 caracteres.',
                confirmButtonColor: '#2ecc71'
            });
            return;
        }

        if (password.value !== confirmPassword.value) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Contraseñas no coinciden',
                text: 'Las contraseñas ingresadas no coinciden.',
                confirmButtonColor: '#2ecc71'
            });
            return;
        }
        
        btnRegistrar.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creando cuenta...';
        btnRegistrar.disabled = true;
    });

    document.querySelectorAll('.form-control').forEach(input => {
        input.addEventListener('focus', function() {
            this.style.borderColor = '#2ecc71';
        });
        
        input.addEventListener('blur', function() {
            if (!this.value) {
                this.style.borderColor = '#e0e0e0';
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('.login-box').style.opacity = '0';
        document.querySelector('.login-box').style.transform = 'translateY(20px)';
        
        setTimeout(function() {
            document.querySelector('.login-box').style.transition = 'opacity 0.8s ease, transform 0.8s ease';
            document.querySelector('.login-box').style.opacity = '1';
            document.querySelector('.login-box').style.transform = 'translateY(0)';
        }, 300);
    });
</script>
</body>
</html>