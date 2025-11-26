(function () {
  if (window.__tema_inicializado__) return;
  window.__tema_inicializado__ = true;

  const STORAGE_KEY = 'modoOscuroActivo';

  // Mapa por filename (minúsculas)
  const MAPA = {
    'style.css': 'style-dark.css',          // base global (override, no se apaga el claro)
    'main.css': 'mainnegro.css',            // carrito: claro → oscuro
    'main1.css': 'negrofav.css',            // base MarketGo si aplica
    'csscajero.css': 'csscajeronegro.css',

    // Si alguna página usa mayúsculas: 'Carrito.css': 'carritonegro.css'
  };

  function nombreArchivo(href) {
    if (!href) return '';
    return href.split('/').pop().split('?')[0].split('#')[0];
  }

  function construirHrefConNuevoNombre(originalHref, nuevoNombre) {
    if (!originalHref) return nuevoNombre;
    const url = new URL(originalHref, document.baseURI);
    url.pathname = url.pathname.replace(/[^/]+$/, nuevoNombre);
    url.searchParams.set('v', Date.now()); // cache-busting preservando otros params
    return url.toString();
  }

  // Loader estilo Facebook (claro/oscuro)
  function mostrarLoader(modoOscuro) {
    let overlay = document.getElementById('tema-loader');
    if (!overlay) {
      overlay = document.createElement('div');
      overlay.id = 'tema-loader';
      overlay.setAttribute('role', 'status');
      overlay.setAttribute('aria-live', 'polite');
      overlay.style.position = 'fixed';
      overlay.style.inset = '0';
      overlay.style.display = 'flex';
      overlay.style.flexDirection = 'column';
      overlay.style.alignItems = 'center';
      overlay.style.justifyContent = 'center';
      overlay.style.zIndex = 999999;
      overlay.style.transition = 'opacity 0.4s ease';

      const style = document.createElement('style');
      style.id = 'tema-loader-style';
      style.textContent = `
        #tema-loader { opacity: 1; }
        .loader-container { width: min(780px, 92vw); display: grid; grid-template-columns: 1fr; gap: 16px; }
        .loader-title { font-weight: 600; margin-bottom: 12px; text-align: center; }
        .loader-card { border-radius: 12px; overflow: hidden; position: relative; height: 96px; }
        .loader-line { height: 16px; width: 60%; border-radius: 8px; margin: 12px 16px; }
        .shimmer { position: absolute; inset: 0; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.24), transparent); animation: shimmer-move 1.2s infinite; }
        @keyframes shimmer-move { 0% { transform: translateX(-100%);} 100% { transform: translateX(100%);} }
      `;
      document.head.appendChild(style);

      const wrap = document.createElement('div');
      wrap.className = 'loader-container';

      const titulo = document.createElement('div');
      titulo.className = 'loader-title';
      titulo.textContent = 'Cambiando tema...';
      wrap.appendChild(titulo);

      for (let i = 0; i < 3; i++) {
        const card = document.createElement('div'); card.className = 'loader-card';
        const line1 = document.createElement('div'); line1.className = 'loader-line';
        const line2 = document.createElement('div'); line2.className = 'loader-line'; line2.style.width = '40%';
        const shimmer = document.createElement('div'); shimmer.className = 'shimmer';
        card.appendChild(line1); card.appendChild(line2); card.appendChild(shimmer);
        wrap.appendChild(card);
      }

      overlay.appendChild(wrap);
      document.body.appendChild(overlay);
    }

    const bgDark = '#0f1216', bgLight = '#f5f6f7';
    const cardDark = '#151922', cardLight = '#ffffff';
    const lineDark = '#1e2430', lineLight = '#e9edf3';
    const textDark = '#eaeff7', textLight = '#30343a';

    overlay.style.background = modoOscuro ? bgDark : bgLight;
    overlay.querySelectorAll('.loader-card').forEach(c => c.style.background = modoOscuro ? cardDark : cardLight);
    overlay.querySelectorAll('.loader-line').forEach(l => l.style.background = modoOscuro ? lineDark : lineLight);
    const titulo = overlay.querySelector('.loader-title'); if (titulo) titulo.style.color = modoOscuro ? textDark : textLight;

    overlay.style.opacity = '1';
    overlay.style.pointerEvents = 'auto';
  }

  function ocultarLoader() {
    const overlay = document.getElementById('tema-loader');
    const style = document.getElementById('tema-loader-style');
    if (overlay) { overlay.style.opacity = '0'; overlay.style.pointerEvents = 'none'; setTimeout(() => overlay.remove(), 300); }
    if (style) { setTimeout(() => style.remove(), 300); }
  }

  async function aplicarModo(modoOscuro) {
    mostrarLoader(modoOscuro);

    const links = Array.from(document.querySelectorAll('link[rel="stylesheet"]'));
    const pares = [];

    links.forEach(linkClaro => {
      const href = linkClaro.getAttribute('href') || '';
      const nombreClaro = nombreArchivo(href).toLowerCase();
      const nombreOscuro = MAPA[nombreClaro];
      if (!nombreOscuro) return;

      const nuevoHref = construirHrefConNuevoNombre(href, nombreOscuro);
      let linkOscuro = Array.from(document.querySelectorAll('link[rel="stylesheet"]'))
        .find(l => nombreArchivo(l.getAttribute('href') || '').toLowerCase() === nombreOscuro.toLowerCase());

      if (!linkOscuro) {
        linkOscuro = document.createElement('link');
        linkOscuro.rel = 'stylesheet';
        linkOscuro.href = nuevoHref;
        linkOscuro.disabled = true;
        linkOscuro.setAttribute('data-tema-oscuro', nombreOscuro);
        linkClaro.parentNode.insertBefore(linkOscuro, linkClaro.nextSibling);
      }
      pares.push({ claro: linkClaro, oscuro: linkOscuro, nombreClaro });
    });

    if (pares.length === 0) { ocultarLoader(); return; }

    await Promise.all(pares.map(p => new Promise(resolve => {
      if (p.oscuro.sheet) return resolve();
      p.oscuro.addEventListener('load', resolve, { once: true });
      setTimeout(resolve, 1200);
    })));

    // Política:
    // - style.css: nunca se apaga (oscuro como override)
    // - main.css: se apaga en oscuro y se enciende mainnegro.css (carrito)
    // - otros (carrito.css, csscajero.css): claro/oscuro alternan.
    pares.forEach(({ claro, oscuro, nombreClaro }) => {
      const esStyleBase = nombreClaro === 'style.css';
      const esMainCarrito = nombreClaro === 'main.css';

      if (esStyleBase) {
        claro.disabled = false;
        oscuro.disabled = !modoOscuro;
      } else if (esMainCarrito) {
        claro.disabled = modoOscuro;     // apaga main.css en oscuro
        oscuro.disabled = !modoOscuro;   // enciende mainnegro.css en oscuro
      } else {
        claro.disabled = modoOscuro;
        oscuro.disabled = !modoOscuro;
      }

      if (oscuro.previousElementSibling !== claro) {
        claro.parentNode.insertBefore(oscuro, claro.nextSibling);
      }
    });

    const btn = document.getElementById('btn-tema');
    if (btn) btn.setAttribute('aria-pressed', String(modoOscuro));

    setTimeout(ocultarLoader, 260);
  }

  function toggleTema() {
    const activo = localStorage.getItem(STORAGE_KEY) === 'true';
    const nuevo = !activo;
    aplicarModo(nuevo);
    localStorage.setItem(STORAGE_KEY, nuevo ? 'true' : 'false');
  }

  function init() {
    const guardado = localStorage.getItem(STORAGE_KEY) === 'true';
    aplicarModo(guardado);

    const btn = document.getElementById('btn-tema');
    if (btn) {
      btn.setAttribute('aria-pressed', String(guardado));
      btn.addEventListener('click', (e) => { e.preventDefault(); toggleTema(); });
    }

    window.toggleTema = toggleTema;
    window.aplicarModo = aplicarModo;
  }

  document.addEventListener('DOMContentLoaded', init);
})();
