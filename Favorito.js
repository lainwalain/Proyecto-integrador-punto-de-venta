function toggleMenu() {
  document.getElementById('sidebar').classList.toggle('active');
}

// Manejo de favoritos
function toggleFavorito(productId) {
  let favoritos = JSON.parse(localStorage.getItem('favoritos')) || [];
  if(favoritos.includes(productId)){
    favoritos = favoritos.filter(id => id !== productId);
  } else {
    favoritos.push(productId);
  }
  localStorage.setItem('favoritos', JSON.stringify(favoritos));
  mostrarFavoritos();
}

function mostrarFavoritos() {
  const favoritosList = document.getElementById('favoritosList');
  favoritosList.innerHTML = '';
  const favoritos = JSON.parse(localStorage.getItem('favoritos')) || [];
  if(favoritos.length === 0){
    favoritosList.innerHTML = '<p>No tienes productos favoritos aún.</p>';
    return;
  }
  favoritos.forEach(id => {
    const card = document.querySelector(`.card[data-id='${id}']`);
    if(card){
      const clone = card.cloneNode(true);
      clone.classList.add('favorito-card');
      const btnRemove = document.createElement('button');
      btnRemove.textContent = 'Eliminar';
      btnRemove.classList.add('btn-remove');
      btnRemove.onclick = () => toggleFavorito(id);
      clone.appendChild(btnRemove);
      favoritosList.appendChild(clone);
    }
  });
}

document.addEventListener('DOMContentLoaded', mostrarFavoritos);
