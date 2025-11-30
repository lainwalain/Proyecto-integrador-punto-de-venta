// calculadora.js - Calculadora integrada para el sistema de cajero
class CalculadoraCajero {
    constructor() {
        this.display = null;
        this.currentInput = '0';
        this.previousInput = '';
        this.operation = null;
        this.waitingForNewInput = false;
        this.isCalculadoraVisible = false;
        
        this.init();
    }
    
    init() {
        this.createCalculadoraHTML();
        this.bindEvents();
        this.hideCalculadora();
    }
    
    createCalculadoraHTML() {
        const calculadoraHTML = `
            <div id="calculadoraOverlay" class="calculadora-overlay" style="display: none;">
                <div class="calculadora-container">
                    <div class="calculadora-header">
                        <h5 class="calculadora-title">Calculadora</h5>
                        <button type="button" class="btn-close-calculadora" id="closeCalculadora">
                            &times;
                        </button>
                    </div>
                    <div class="calculadora-display">
                        <input type="text" id="calculadoraDisplay" class="calculadora-display-input" readonly value="0">
                    </div>
                    <div class="calculadora-buttons">
                        <button class="btn-calculadora btn-clear" data-action="clear">C</button>
                        <button class="btn-calculadora btn-clear" data-action="clearAll">CE</button>
                        <button class="btn-calculadora btn-operation" data-action="backspace">⌫</button>
                        <button class="btn-calculadora btn-operation" data-action="divide">/</button>
                        
                        <button class="btn-calculadora btn-number" data-number="7">7</button>
                        <button class="btn-calculadora btn-number" data-number="8">8</button>
                        <button class="btn-calculadora btn-number" data-number="9">9</button>
                        <button class="btn-calculadora btn-operation" data-action="multiply">×</button>
                        
                        <button class="btn-calculadora btn-number" data-number="4">4</button>
                        <button class="btn-calculadora btn-number" data-number="5">5</button>
                        <button class="btn-calculadora btn-number" data-number="6">6</button>
                        <button class="btn-calculadora btn-operation" data-action="subtract">-</button>
                        
                        <button class="btn-calculadora btn-number" data-number="1">1</button>
                        <button class="btn-calculadora btn-number" data-number="2">2</button>
                        <button class="btn-calculadora btn-number" data-number="3">3</button>
                        <button class="btn-calculadora btn-operation" data-action="add">+</button>
                        
                        <button class="btn-calculadora btn-number btn-zero" data-number="0">0</button>
                        <button class="btn-calculadora btn-number" data-number=".">.</button>
                        <button class="btn-calculadora btn-equals" data-action="calculate">=</button>
                    </div>
                    <div class="calculadora-actions">
                        <button class="btn-calculadora-action" id="usarResultado">Usar Resultado</button>
                        <button class="btn-calculadora-action btn-cancel" id="cancelarCalculadora">Cancelar</button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', calculadoraHTML);
        this.display = document.getElementById('calculadoraDisplay');
    }
    
    bindEvents() {
        // Botones numéricos
        document.querySelectorAll('.btn-number').forEach(button => {
            button.addEventListener('click', (e) => {
                this.inputNumber(e.target.getAttribute('data-number'));
            });
        });
        
        // Botones de operación
        document.querySelectorAll('.btn-operation').forEach(button => {
            button.addEventListener('click', (e) => {
                this.inputOperation(e.target.getAttribute('data-action'));
            });
        });
        
        // Botones especiales
        document.querySelectorAll('[data-action="calculate"]').forEach(button => {
            button.addEventListener('click', () => {
                this.calculate();
            });
        });
        
        document.querySelectorAll('[data-action="clear"]').forEach(button => {
            button.addEventListener('click', () => {
                this.clear();
            });
        });
        
        document.querySelectorAll('[data-action="clearAll"]').forEach(button => {
            button.addEventListener('click', () => {
                this.clearAll();
            });
        });
        
        document.querySelectorAll('[data-action="backspace"]').forEach(button => {
            button.addEventListener('click', () => {
                this.backspace();
            });
        });
        
        // Botones de acción
        document.getElementById('closeCalculadora').addEventListener('click', () => {
            this.hideCalculadora();
        });
        
        document.getElementById('cancelarCalculadora').addEventListener('click', () => {
            this.hideCalculadora();
        });
        
        document.getElementById('usarResultado').addEventListener('click', () => {
            this.usarResultado();
        });
        
        // Eventos de teclado
        document.addEventListener('keydown', (e) => {
            if (!this.isCalculadoraVisible) return;
            
            e.preventDefault();
            
            // Números
            if (e.key >= '0' && e.key <= '9') {
                this.inputNumber(e.key);
            }
            // Punto decimal
            else if (e.key === '.') {
                this.inputNumber('.');
            }
            // Operaciones
            else if (e.key === '+') {
                this.inputOperation('add');
            }
            else if (e.key === '-') {
                this.inputOperation('subtract');
            }
            else if (e.key === '*') {
                this.inputOperation('multiply');
            }
            else if (e.key === '/') {
                this.inputOperation('divide');
            }
            // Enter o = para calcular
            else if (e.key === 'Enter' || e.key === '=') {
                this.calculate();
            }
            // Escape para cerrar
            else if (e.key === 'Escape') {
                this.hideCalculadora();
            }
            // Backspace
            else if (e.key === 'Backspace') {
                this.backspace();
            }
            // C para clear
            else if (e.key === 'c' || e.key === 'C') {
                this.clear();
            }
        });
        
        // Cerrar calculadora al hacer clic fuera
        document.getElementById('calculadoraOverlay').addEventListener('click', (e) => {
            if (e.target.id === 'calculadoraOverlay') {
                this.hideCalculadora();
            }
        });
    }
    
    inputNumber(num) {
        if (this.waitingForNewInput) {
            this.currentInput = num;
            this.waitingForNewInput = false;
        } else {
            this.currentInput = this.currentInput === '0' ? num : this.currentInput + num;
        }
        this.updateDisplay();
    }
    
    inputOperation(op) {
        if (this.waitingForNewInput) {
            this.operation = op;
            return;
        }
        
        if (this.operation !== null) {
            this.calculate();
        }
        
        this.previousInput = this.currentInput;
        this.operation = op;
        this.waitingForNewInput = true;
    }
    
    calculate() {
        if (this.operation === null || this.waitingForNewInput) {
            return;
        }
        
        const prev = parseFloat(this.previousInput);
        const current = parseFloat(this.currentInput);
        
        if (isNaN(prev) || isNaN(current)) return;
        
        let result;
        switch (this.operation) {
            case 'add':
                result = prev + current;
                break;
            case 'subtract':
                result = prev - current;
                break;
            case 'multiply':
                result = prev * current;
                break;
            case 'divide':
                if (current === 0) {
                    alert('Error: División por cero');
                    return;
                }
                result = prev / current;
                break;
            default:
                return;
        }
        
        this.currentInput = result.toString();
        this.operation = null;
        this.previousInput = '';
        this.waitingForNewInput = true;
        this.updateDisplay();
    }
    
    clear() {
        this.currentInput = '0';
        this.updateDisplay();
    }
    
    clearAll() {
        this.currentInput = '0';
        this.previousInput = '';
        this.operation = null;
        this.waitingForNewInput = false;
        this.updateDisplay();
    }
    
    backspace() {
        if (this.currentInput.length > 1) {
            this.currentInput = this.currentInput.slice(0, -1);
        } else {
            this.currentInput = '0';
        }
        this.updateDisplay();
    }
    
    updateDisplay() {
        this.display.value = this.currentInput;
    }
    
    showCalculadora() {
        this.isCalculadoraVisible = true;
        document.getElementById('calculadoraOverlay').style.display = 'flex';
        this.display.focus();
    }
    
    hideCalculadora() {
        this.isCalculadoraVisible = false;
        document.getElementById('calculadoraOverlay').style.display = 'none';
        this.clearAll();
    }
    
    usarResultado() {
        const resultado = this.currentInput;
        this.hideCalculadora();
        
        // Disparar evento personalizado con el resultado
        const event = new CustomEvent('calculadoraResultado', {
            detail: { resultado: resultado }
        });
        document.dispatchEvent(event);
        
        return resultado;
    }
    
    // Método para abrir la calculadora con un valor inicial
    abrirConValor(valorInicial = '') {
        if (valorInicial) {
            this.currentInput = valorInicial.toString();
            this.updateDisplay();
        }
        this.showCalculadora();
    }
}

// CSS para la calculadora (se puede incluir en el mismo archivo o en el CSS principal)
const calculadoraCSS = `
.calculadora-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 10000;
    backdrop-filter: blur(5px);
}

.calculadora-container {
    background: var(--panel);
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    border: 1px solid var(--borde);
    width: 320px;
    max-width: 90vw;
}

.calculadora-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid var(--verde-menta);
}

.calculadora-title {
    color: var(--texto);
    margin: 0;
    font-weight: 600;
}

.btn-close-calculadora {
    background: none;
    border: none;
    font-size: 24px;
    color: var(--texto);
    cursor: pointer;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.btn-close-calculadora:hover {
    background: var(--verde-menta);
    color: var(--texto-oscuro);
}

.calculadora-display {
    margin-bottom: 15px;
}

.calculadora-display-input {
    width: 100%;
    height: 60px;
    font-size: 24px;
    text-align: right;
    padding: 10px 15px;
    border: 2px solid var(--borde);
    border-radius: 10px;
    background: var(--panel);
    color: var(--texto);
    font-weight: bold;
}

.calculadora-display-input:focus {
    outline: none;
    border-color: var(--verde-menta);
}

.calculadora-buttons {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
    margin-bottom: 15px;
}

.btn-calculadora {
    height: 50px;
    border: none;
    border-radius: 10px;
    font-size: 18px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-number {
    background: var(--panel);
    color: var(--texto);
    border: 2px solid var(--borde);
}

.btn-number:hover {
    background: var(--verde-menta-claro);
    border-color: var(--verde-menta);
}

.btn-zero {
    grid-column: span 2;
}

.btn-operation {
    background: var(--verde-menta);
    color: var(--texto-oscuro);
    border: 2px solid var(--verde-menta);
}

.btn-operation:hover {
    background: var(--verde-menta-oscuro);
    border-color: var(--verde-menta-oscuro);
}

.btn-clear {
    background: #dc3545;
    color: white;
    border: 2px solid #dc3545;
}

.btn-clear:hover {
    background: #c82333;
    border-color: #c82333;
}

.btn-equals {
    background: var(--verde-menta-oscuro);
    color: white;
    border: 2px solid var(--verde-menta-oscuro);
}

.btn-equals:hover {
    background: var(--texto-oscuro);
    border-color: var(--texto-oscuro);
}

.calculadora-actions {
    display: flex;
    gap: 10px;
}

.btn-calculadora-action {
    flex: 1;
    padding: 12px;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-calculadora-action:first-child {
    background: var(--verde-menta);
    color: var(--texto-oscuro);
}

.btn-calculadora-action:first-child:hover {
    background: var(--verde-menta-oscuro);
}

.btn-cancel {
    background: #6c757d;
    color: white;
}

.btn-cancel:hover {
    background: #5a6268;
}

/* Modo oscuro para la calculadora */
[data-theme="dark"] .calculadora-container {
    background: var(--panel-oscuro);
    border-color: var(--borde-oscuro);
}

[data-theme="dark"] .btn-number {
    background: var(--panel-oscuro);
    border-color: var(--borde-oscuro);
}

[data-theme="dark"] .btn-number:hover {
    background: #3a3a3a;
}

[data-theme="dark"] .calculadora-display-input {
    background: var(--panel-oscuro);
    border-color: var(--borde-oscuro);
}
`;

// Agregar CSS al documento
const styleSheet = document.createElement('style');
styleSheet.textContent = calculadoraCSS;
document.head.appendChild(styleSheet);

// Inicializar calculadora cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    window.calculadora = new CalculadoraCajero();
    
    // Agregar botón de calculadora al header si existe
    const headerButtons = document.querySelector('.header-buttons');
    if (headerButtons) {
        const calculadoraBtn = document.createElement('button');
        calculadoraBtn.className = 'btn btn-success theme-toggle';
        calculadoraBtn.innerHTML = '🧮 Calculadora';
        calculadoraBtn.addEventListener('click', () => {
            window.calculadora.showCalculadora();
        });
        headerButtons.insertBefore(calculadoraBtn, headerButtons.firstChild);
    }
    
    // Escuchar evento de resultado de calculadora
    document.addEventListener('calculadoraResultado', function(e) {
        console.log('Resultado de calculadora:', e.detail.resultado);
        
    });
    
    // Atajo de teclado para la calculadora (Ctrl+Shift+C)
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.shiftKey && e.key === 'C') {
            e.preventDefault();
            window.calculadora.showCalculadora();
        }
    });
});

// Exportar para uso global
if (typeof module !== 'undefined' && module.exports) {
    module.exports = CalculadoraCajero;
}