/* MiCuenta.js
   Lógica de interacción:
   - Menu hamburguesa (móvil)
   - Carga de datos mediante fetch desde MiCuenta.php (GET)
   - Modal para editar información (solo frontend / simulado)
   - Envío simulado de cambios (POST) y actualización UI
*/

document.addEventListener('DOMContentLoaded', () => {
  // Elementos
  const hamburger = document.getElementById('hamburger');
  const mobileMenu = document.getElementById('mobile-menu');
  const navList = document.getElementById('nav-list');

  const modal = document.getElementById('modal');
  const modalBackdrop = document.getElementById('modal-backdrop');
  const btnEditInfo = document.getElementById('btn-edit-info');
  const modalCancel = document.getElementById('modal-cancel');
  const editForm = document.getElementById('edit-form');

  // Campos UI
  const userName = document.getElementById('user-name');
  const userEmail = document.getElementById('user-email');
  const infoNombre = document.getElementById('info-nombre');
  const infoCorreo = document.getElementById('info-correo');
  const infoTelefono = document.getElementById('info-telefono');

  const formNombre = document.getElementById('form-nombre');
  const formCorreo = document.getElementById('form-correo');
  const formTelefono = document.getElementById('form-telefono');

  // Stats
  const statCompras = document.getElementById('stat-compras');
  const statFavs = document.getElementById('stat-favs');
  const statTotal = document.getElementById('stat-total');
  const statPuntos = document.getElementById('stat-puntos');

  // Toggle menú móvil
  hamburger.addEventListener('click', () => {
    const open = mobileMenu.style.display === 'block';
    mobileMenu.style.display = open ? 'none' : 'block';
  });

  // Cerrar menú al hacer click fuera en móvil (opcional)
  document.addEventListener('click', (e) => {
    if (!e.target.closest('.header-inner') && window.innerWidth <= 900) {
      mobileMenu.style.display = 'none';
    }
  });

  // Cargar datos del usuario desde MiCuenta.php
  function cargarDatos() {
    fetch('MiCuenta.php')
      .then(res => {
        if (!res.ok) throw new Error('Error al obtener datos');
        return res.json();
      })
      .then(data => {
        // Actualiza la interfaz con datos recibidos
        userName.textContent = data.nombre || 'Usuario';
        userEmail.textContent = data.correo || '';
        infoNombre.value = data.nombre || '';
        infoCorreo.value = data.correo || '';
        infoTelefono.value = data.telefono || 'No registrado';

        // Badges y stats
        document.getElementById('user-level').textContent = data.nivel || 'Nivel';
        document.getElementById('user-points').textContent = (data.puntos ? data.puntos + ' puntos' : '0 puntos');

        statCompras.textContent = data.estadisticas?.compras ?? 0;
        statFavs.textContent = data.estadisticas?.favoritos ?? 0;
        statTotal.textContent = data.estadisticas?.totalGastado ? ('$' + data.estadisticas.totalGastado) : '$0';
        statPuntos.textContent = data.estadisticas?.puntos ?? 0;
      })
      .catch(err => {
        console.error(err);
        // Mantener datos por defecto si hay error
      });
  }

  // Mostrar modal
  function showModal() {
    // Inicializa campos con valores actuales
    formNombre.value = infoNombre.value;
    formCorreo.value = infoCorreo.value;
    formTelefono.value = (infoTelefono.value === 'No registrado' ? '' : infoTelefono.value);

    modal.classList.add('show');
    modal.setAttribute('aria-hidden', 'false');
  }

  // Cerrar modal
  function hideModal() {
    modal.classList.remove('show');
    modal.setAttribute('aria-hidden', 'true');
  }

  // Event listeners modal
  btnEditInfo.addEventListener('click', showModal);
  modalCancel.addEventListener('click', hideModal);
  modalBackdrop.addEventListener('click', hideModal);

  // Manejar envío del formulario (simulado)
  editForm.addEventListener('submit', (e) => {
    e.preventDefault();

    const payload = {
      nombre: formNombre.value.trim(),
      correo: formCorreo.value.trim(),
      telefono: formTelefono.value.trim()
    };

    // Enviar cambios al servidor (MiCuenta.php) - aquí lo manejamos por POST
    fetch('MiCuenta.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    })
    .then(res => {
      if (!res.ok) throw new Error('Error al guardar');
      return res.json();
    })
    .then(resp => {
      if (resp.success) {
        // Actualiza la UI con datos guardados (simulado)
        infoNombre.value = payload.nombre;
        infoCorreo.value = payload.correo;
        infoTelefono.value = payload.telefono || 'No registrado';

        userName.textContent = payload.nombre;
        userEmail.textContent = payload.correo;

        // Cerrar modal
        hideModal();
        // Mensaje simple (puedes reemplazar por toast)
        alert('Información actualizada (simulado). Tu compañero integrará la BD real.');
      } else {
        throw new Error(resp.message || 'No se pudo guardar');
      }
    })
    .catch(err => {
      console.error(err);
      alert('Ocurrió un error al guardar los cambios (simulado). Revisa la consola.');
    });
  });

  // Cargar datos al inicio
  cargarDatos();
});
