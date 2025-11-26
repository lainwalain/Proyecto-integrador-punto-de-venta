<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Market Go - Sistema de Ventas</title>

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
            --primary-color: #e67e22;
            --primary-dark: #d35400;
            --secondary-color: #f39c12;
            --accent-color: #27ae60;
            --light-color: #fef9f3;
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
            box-shadow: 0 4px 15px rgba(230, 126, 34, 0.3);
        }
        
        .market-go-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(230, 126, 34, 0.4);
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
            box-shadow: 0 0 0 0.2rem rgba(230, 126, 34, 0.25);
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

<?php
// MOVER session_start() AL INICIO DEL ARCHIVO, ANTES DE CUALQUIER SALIDA HTML
session_start();
?>

<div class="login-box">
    <!-- Mensajes de sesión -->
    <?php
    if(isset($_SESSION['mensaje'])){
        $respuesta = $_SESSION['mensaje']; ?>
        <script>
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: '<?php echo $respuesta;?>',
                showConfirmButton: false,
                timer: 2000
            })
        </script>
    <?php
        unset($_SESSION['mensaje']); // Limpiar el mensaje después de mostrarlo
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
            <h4 class="mb-0">Iniciar Sesión</h4>
        </div>
        <div class="card-body p-4">
            <p class="login-box-msg">Ingresa tus credenciales para acceder al sistema</p>

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
                
                <div class="divider">
                    <span class="divider-text">Acceso seguro</span>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <button type="submit" 
                                class="btn market-go-primary btn-block py-3" 
                                id="btnIngresar">
                            <i class="fas fa-sign-in-alt mr-2"></i>Ingresar al Sistema
                        </button>
                    </div>
                </div>
            </form>

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
                    Sistema de ventas y gestión para abarrotes
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
    // Función para mostrar/ocultar contraseña
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('passwordInput');
        const toggleIcon = this;
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
            toggleIcon.style.color = '#e67e22';
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
            toggleIcon.style.color = '';
        }
    });

    // Validación básica del formulario
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        const email = document.querySelector('input[name="email"]');
        const password = document.querySelector('input[name="password_user"]');
        const btnIngresar = document.getElementById('btnIngresar');
        
        if (!email.value || !password.value) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Campos incompletos',
                text: 'Por favor, complete todos los campos requeridos.',
                confirmButtonColor: '#e67e22'
            });
            return;
        }
        
        // Validación de formato de email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email.value)) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Email inválido',
                text: 'Por favor, ingrese un correo electrónico válido.',
                confirmButtonColor: '#e67e22'
            });
            return;
        }
        
        // Cambiar texto del botón durante el envío
        btnIngresar.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Ingresando...';
        btnIngresar.disabled = true;
    });

    // Efecto hover en inputs
    document.querySelectorAll('.form-control').forEach(input => {
        input.addEventListener('focus', function() {
            this.style.borderColor = '#e67e22';
        });
        
        input.addEventListener('blur', function() {
            if (!this.value) {
                this.style.borderColor = '#e0e0e0';
            }
        });
    });

    // Efecto de animación al cargar la página
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