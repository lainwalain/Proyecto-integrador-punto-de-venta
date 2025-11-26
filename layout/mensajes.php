<?php

if( (isset($_SESSION['mensaje'])) && (isset($_SESSION['icono'])) ){
    $respuesta = $_SESSION['mensaje'];
    $icono = $_SESSION['icono'];
    ?>
    <script>
        Swal.fire({
            position: 'top-end',
            icon: '<?php echo $icono; ?>',
            title: '<?php echo $respuesta;?>',
            showConfirmButton: false,
            timer: 2500
        })
    </script>
    <?php
    unset($_SESSION['mensaje']);
    unset($_SESSION['icono']);
}
?>