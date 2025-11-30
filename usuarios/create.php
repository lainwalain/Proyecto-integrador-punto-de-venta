<?php
include ('../app/config.php');
include ('../layout/sesion.php');

include ('../layout/parte1.php');

include ('../app/controllers/roles/listado_de_roles.php');

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0">Registro de un nuevo usuario</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-10">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Llene los datos con cuidado</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="card-body" style="display: block;">
                            <div class="row">
                                <div class="col-md-12">
                                    <form action="../app/controllers/usuarios/create.php" method="post" id="registroForm">
                                        <div class="form-group">
                                            <label for="">Nombres</label>
                                            <input type="text" name="nombres" class="form-control" placeholder="Escriba aquí el nombre del nuevo usuario..." required>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Email</label>
                                            <input type="email" name="email" class="form-control" placeholder="Escriba aquí el correo del nuevo usuario..." required>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Rol del usuario</label>
                                            <select name="rol" id="" class="form-control">
                                                <?php
                                                foreach ($roles_datos as $roles_dato){?>
                                                     <option value="<?php echo $roles_dato['id_rol'];?>"><?php echo $roles_dato['rol'];?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        
                                        <!-- Campo de contraseña con validación visual -->
                                        <div class="form-group">
                                            <label for="password_user">Contraseña</label>
                                            <div class="input-group">
                                                <input type="password" name="password_user" id="password_user" class="form-control" 
                                                       placeholder="Mínimo 6 caracteres" required
                                                       minlength="6">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-eye" id="togglePassword" style="cursor: pointer;"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <small class="form-text text-muted">
                                                <i class="fas fa-info-circle"></i> La contraseña debe tener al menos 6 caracteres
                                            </small>
                                            <div class="password-strength mt-1">
                                                <div class="progress" style="height: 5px;">
                                                    <div class="progress-bar" id="passwordStrength" style="width: 0%;"></div>
                                                </div>
                                                <small id="passwordFeedback" class="form-text"></small>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="password_repeat">Repita la Contraseña</label>
                                            <div class="input-group">
                                                <input type="password" name="password_repeat" id="password_repeat" class="form-control" 
                                                       placeholder="Confirme la contraseña" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-eye" id="togglePasswordRepeat" style="cursor: pointer;"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <small id="passwordMatch" class="form-text"></small>
                                        </div>
                                        
                                        <hr>
                                        <div class="form-group">
                                            <a href="index.php" class="btn btn-secondary">Cancelar</a>
                                            <button type="submit" class="btn btn-primary" id="submitBtn">Guardar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password_user');
    const passwordRepeat = document.getElementById('password_repeat');
    const togglePassword = document.getElementById('togglePassword');
    const togglePasswordRepeat = document.getElementById('togglePasswordRepeat');
    const passwordMatch = document.getElementById('passwordMatch');
    const passwordStrength = document.getElementById('passwordStrength');
    const passwordFeedback = document.getElementById('passwordFeedback');
    const submitBtn = document.getElementById('submitBtn');
    const form = document.getElementById('registroForm');

    // Toggle para mostrar/ocultar contraseña
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });

    togglePasswordRepeat.addEventListener('click', function() {
        const type = passwordRepeat.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordRepeat.setAttribute('type', type);
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });

    // Validación de fortaleza de contraseña
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        let strength = 0;
        let feedback = '';

        if (password.length >= 6) {
            strength += 25;
        }
        if (password.match(/[a-z]/)) {
            strength += 25;
        }
        if (password.match(/[A-Z]/)) {
            strength += 25;
        }
        if (password.match(/[0-9]/)) {
            strength += 25;
        }

        // Actualizar barra de progreso
        passwordStrength.style.width = strength + '%';

        // Actualizar color y mensaje
        if (strength < 50) {
            passwordStrength.className = 'progress-bar bg-danger';
            feedback = 'Contraseña débil';
        } else if (strength < 75) {
            passwordStrength.className = 'progress-bar bg-warning';
            feedback = 'Contraseña media';
        } else {
            passwordStrength.className = 'progress-bar bg-success';
            feedback = 'Contraseña fuerte';
        }

        passwordFeedback.textContent = feedback;
        validatePasswords();
    });

    // Validación de coincidencia de contraseñas
    passwordRepeat.addEventListener('input', validatePasswords);

    function validatePasswords() {
        const password = passwordInput.value;
        const confirmPassword = passwordRepeat.value;

        if (confirmPassword === '') {
            passwordMatch.textContent = '';
            passwordMatch.className = 'form-text';
            return;
        }

        if (password === confirmPassword) {
            passwordMatch.textContent = '✓ Las contraseñas coinciden';
            passwordMatch.className = 'form-text text-success';
        } else {
            passwordMatch.textContent = '✗ Las contraseñas no coinciden';
            passwordMatch.className = 'form-text text-danger';
        }
    }

    // Validación del formulario antes de enviar
    form.addEventListener('submit', function(e) {
        const password = passwordInput.value;
        const confirmPassword = passwordRepeat.value;

        // Validar longitud mínima
        if (password.length < 6) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Contraseña muy corta',
                text: 'La contraseña debe tener al menos 6 caracteres',
                confirmButtonColor: '#007bff'
            });
            return;
        }

        // Validar coincidencia
        if (password !== confirmPassword) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Contraseñas no coinciden',
                text: 'Las contraseñas ingresadas no coinciden',
                confirmButtonColor: '#007bff'
            });
            return;
        }

        // Deshabilitar botón para evitar doble envío
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
    });
});
</script>

<style>
.password-strength {
    display: none;
}

#password_user:focus + .password-strength,
.password-strength.show {
    display: block;
}

.progress-bar {
    transition: width 0.3s ease;
}

.input-group-text {
    background-color: #f8f9fa;
    border: 1px solid #ced4da;
}
</style>

<?php include ('../layout/parte2.php'); ?>