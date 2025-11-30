// Gestión de idiomas
class LanguageManager {
    constructor() {
        this.languageToggle = document.getElementById('languageToggle');
        this.currentLang = document.documentElement.lang || 'es';
        this.init();
    }

    init() {
        this.setupEventListeners();
    }

    toggleLanguage() {
        // Agregar animación de cambio
        if (this.languageToggle) {
            this.languageToggle.classList.add('changing');
            this.updateButtonText();
        }

        const newLang = this.currentLang === 'es' ? 'en' : 'es';
        
        // Pequeño delay para que se vea la animación
        setTimeout(() => {
            this.changeLanguage(newLang);
        }, 300);
    }

    changeLanguage(lang) {
        // Agregar parámetro de idioma a la URL y recargar
        const url = new URL(window.location.href);
        url.searchParams.set('lang', lang);
        window.location.href = url.toString();
    }

    updateButtonText() {
        if (this.languageToggle) {
            const languageText = this.languageToggle.querySelector('.language-text');
            if (languageText) {
                languageText.textContent = this.currentLang === 'es' ? 'EN' : 'ES';
            }
        }
    }

    setupEventListeners() {
        if (this.languageToggle) {
            this.languageToggle.addEventListener('click', () => {
                this.toggleLanguage();
            });

            // Efecto hover con sonido (opcional)
            this.languageToggle.addEventListener('mouseenter', () => {
                // Podrías agregar un sonido suave aquí si quieres
            });
        }

        // Atajo de teclado: Ctrl+Shift+L para cambiar idioma
        document.addEventListener('keydown', (e) => {
            if (e.ctrlKey && e.shiftKey && e.key === 'L') {
                e.preventDefault();
                this.toggleLanguage();
            }
        });
    }

    getCurrentLang() {
        return this.currentLang;
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    window.languageManager = new LanguageManager();
});

// Función global para cambiar idioma desde la consola
window.changeLanguage = function(lang) {
    if (window.languageManager) {
        window.languageManager.changeLanguage(lang);
    }
};