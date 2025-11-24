// ===========================
// VARIABLES
// ===========================
let productos = [];
let carrito = [];

const buscador = document.getElementById("buscador");
const resultados = document.getElementById("resultado-busqueda");
const listaProductos = document.getElementById("lista-productos");

const carritoDiv = document.getElementById("carrito");
const totalSpan = document.getElementById("total");
const cambioSpan = document.getElementById("cambio");
const pagoInput = document.getElementById("pago");

const btnVaciar = document.getElementById("vaciar");
const btnFinalizar = document.getElementById("finalizar");


// ===========================
// CARGAR PRODUCTOS DESDE PHP
// ===========================
fetch("buscar_productos.php?all=1")
    .then(res => res.json())
    .then(data => {
        productos = data;
        renderProductos(data);
    });


// ===========================
// RENDERIZAR PRODUCTOS
// ===========================
function renderProductos(productos) {
    listaProductos.innerHTML = "";

    productos.forEach(p => {
        let div = document.createElement("div");
        div.classList.add("producto");
        div.innerHTML = `
            <h3>${p.nombre}</h3>
            <p>Precio: $${p.precio}</p>
            <p>Código: ${p.codigo}</p>
            <button onclick="agregarAlCarrito(${p.id})">Agregar</button>
        `;
        listaProductos.appendChild(div);
    });
}


// ===========================
// BUSCADOR EN VIVO
// ===========================
buscador.addEventListener("keyup", () => {
    let texto = buscador.value.trim();

    if (texto.length === 0) {
        resultados.innerHTML = "";
        resultados.style.display = "none";
        return;
    }

    fetch("buscar_productos.php?busqueda=" + texto)
        .then(res => res.json())
        .then(data => {
            resultados.innerHTML = "";
            resultados.style.display = "block";

            if (data.length === 0) {
                resultados.innerHTML = "<p>No hay resultados</p>";
                return;
            }

            data.forEach(p => {
                let item = document.createElement("div");
                item.classList.add("item-resultado");
                item.textContent = `${p.nombre} - $${p.precio}`;
                item.onclick = () => {
                    agregarAlCarrito(p.id);
                    resultados.style.display = "none";
                    buscador.value = "";
                };
                resultados.appendChild(item);
            });
        });
});


// ===========================
// ESCÁNER DE CÓDIGOS DE BARRAS
// ===========================
let buffer = "";
document.addEventListener("keypress", (e) => {
    if (e.key === "Enter") {
        if (buffer.length > 2) {
            buscarPorCodigo(buffer);
        }
        buffer = "";
    } else {
        buffer += e.key;
    }
});

function buscarPorCodigo(codigo) {
    fetch("buscar_productos.php?codigo=" + codigo)
        .then(res => res.json())
        .then(data => {
            if (data.length > 0) {
                agregarAlCarrito(data[0].id);
            }
        });
}


// ===========================
// AGREGAR AL CARRITO
// ===========================
function agregarAlCarrito(id) {
    const producto = productos.find(p => p.id === id);

    const existe = carrito.find(p => p.id === id);

    if (existe) {
        existe.cantidad += 1;
    } else {
        carrito.push({
            id: producto.id,
            nombre: producto.nombre,
            precio: producto.precio,
            cantidad: 1
        });
    }

    renderCarrito();
}


// ===========================
// MOSTRAR CARRITO
// ===========================
function renderCarrito() {
    carritoDiv.innerHTML = "";

    carrito.forEach(item => {
        let div = document.createElement("div");
        div.classList.add("item-carrito");

        div.innerHTML = `
            <p>${item.nombre}</p>
            <p>Cant: ${item.cantidad}</p>
            <p>$${(item.precio * item.cantidad).toFixed(2)}</p>
        `;

        carritoDiv.appendChild(div);
    });

    calcularTotal();
}


// ===========================
// CALCULAR TOTAL
// ===========================
function calcularTotal() {
    let total = carrito.reduce((sum, p) => sum + p.precio * p.cantidad, 0);
    totalSpan.textContent = total.toFixed(2);

    calcularCambio();
}


// ===========================
// CALCULAR CAMBIO
// ===========================
pagoInput.addEventListener("input", calcularCambio);

function calcularCambio() {
    let pago = parseFloat(pagoInput.value) || 0;
    let total = parseFloat(totalSpan.textContent);

    let cambio = pago - total;
    cambioSpan.textContent = cambio >= 0 ? cambio.toFixed(2) : "0.00";
}


// ===========================
// VACIAR CARRITO
// ===========================
btnVaciar.addEventListener("click", () => {
    carrito = [];
    renderCarrito();
});


// ===========================
// FINALIZAR COMPRA
// ===========================
btnFinalizar.addEventListener("click", () => {
    if (carrito.length === 0) {
        alert("El carrito está vacío.");
        return;
    }

    fetch("finalizar_compra.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(carrito)
    })
    .then(res => res.json())
    .then(data => {
        if (data.ok) {
            window.open("ticket.pdf", "_blank");
            carrito = [];
            renderCarrito();
            pagoInput.value = "";
            cambioSpan.textContent = "0.00";
        } else {
            alert("Error al finalizar compra");
        }
    });
});
