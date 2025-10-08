let productos = [];

fetch("js/productos.json")
    .then(response => response.json())
    .then(data => {
        productos = data;
        cargarProductos(productos);
    });

const contenedorProductos = document.querySelector("#contenedor-productos");
const botonesCategorias = document.querySelectorAll(".cat-btn");
const tituloPrincipal = document.querySelector("#titulo-principal");
let botonesAgregar = document.querySelectorAll(".producto-agregar");
let botonesFavorito = document.querySelectorAll(".producto-favorito");
const numerito = document.querySelector("#numerito");

botonesCategorias.forEach(boton => boton.addEventListener("click", () => {
    aside.classList.remove("aside-visible");
}));

function cargarProductos(productosElegidos) {
    contenedorProductos.innerHTML = "";

    productosElegidos.forEach(producto => {
        const div = document.createElement("div");
        div.classList.add("producto");
        div.innerHTML = `
            <img class="producto-imagen" src="${producto.imagen}" alt="${producto.titulo}">
            <div class="producto-detalles">
                <h3 class="producto-titulo">${producto.titulo}</h3>
                <p class="producto-precio">$${producto.precio}</p>
                <div class="producto-botones">
                    <button class="producto-agregar" id="${producto.id}">Agregar</button>
                    <button class="producto-favorito" data-id="${producto.id}">❤️</button>
                </div>
            </div>
        `;
        contenedorProductos.append(div);
    });

    actualizarBotonesAgregar();
    actualizarBotonesFavorito();
}

botonesCategorias.forEach(boton => {
    boton.addEventListener("click", (e) => {
        botonesCategorias.forEach(boton => boton.classList.remove("active"));
        e.currentTarget.classList.add("active");

        const categoriaSeleccionada = e.currentTarget.dataset.category;

        if (categoriaSeleccionada !== "todos") {
            const productoCategoria = productos.find(producto => producto.categoria.id === categoriaSeleccionada);
            if (productoCategoria) {
                tituloPrincipal.innerText = productoCategoria.categoria.nombre;
            } else {
                tituloPrincipal.innerText = categoriaSeleccionada;
            }
            const productosBoton = productos.filter(producto => producto.categoria.id === categoriaSeleccionada);
            cargarProductos(productosBoton);
        } else {
            tituloPrincipal.innerText = "Todos los productos";
            cargarProductos(productos);
        }
    });
});

function actualizarBotonesAgregar() {
    botonesAgregar = document.querySelectorAll(".producto-agregar");

    botonesAgregar.forEach(boton => {
        boton.addEventListener("click", agregarAlCarrito);
    });
}

function actualizarBotonesFavorito() {
    botonesFavorito = document.querySelectorAll(".producto-favorito");

    botonesFavorito.forEach(boton => {
        boton.addEventListener("click", agregarAFavoritos);
    });
}

let productosEnCarrito;
let productosEnCarritoLS = localStorage.getItem("productos-en-carrito");

if (productosEnCarritoLS) {
    productosEnCarrito = JSON.parse(productosEnCarritoLS);
    actualizarNumerito();
} else {
    productosEnCarrito = [];
}

function agregarAlCarrito(e) {
    Toastify({
        text: "Producto agregado",
        duration: 3000,
        close: true,
        gravity: "top",
        position: "right",
        stopOnFocus: true,
        style: {
            background: "linear-gradient(to right, #bc5ba2ff, #7700ffff)",
            borderRadius: "2rem",
            textTransform: "uppercase",
            fontSize: ".75rem"
        },
        offset: {
            x: '1.5rem',
            y: '1.5rem'
        },
        onClick: function () { }
    }).showToast();

    const idBoton = e.currentTarget.id;
    const productoAgregado = productos.find(producto => producto.id === idBoton);

    if (productosEnCarrito.some(producto => producto.id === idBoton)) {
        const index = productosEnCarrito.findIndex(producto => producto.id === idBoton);
        productosEnCarrito[index].cantidad++;
    } else {
        productoAgregado.cantidad = 1;
        productosEnCarrito.push(productoAgregado);
    }

    actualizarNumerito();
    localStorage.setItem("productos-en-carrito", JSON.stringify(productosEnCarrito));
}

function actualizarNumerito() {
    let nuevoNumerito = productosEnCarrito.reduce((acc, producto) => acc + producto.cantidad, 0);
    if (numerito) {
        numerito.innerText = nuevoNumerito;
    }
}

// 🆕 FUNCIONALIDAD DE FAVORITOS
let productosFavoritos = JSON.parse(localStorage.getItem("productos-favoritos")) || [];

function agregarAFavoritos(e) {
    const idProducto = e.currentTarget.dataset.id;
    const productoFavorito = productos.find(producto => producto.id === idProducto);

    const yaEsta = productosFavoritos.some(producto => producto.id === idProducto);

    if (!yaEsta) {
        productosFavoritos.push(productoFavorito);
        localStorage.setItem("productos-favoritos", JSON.stringify(productosFavoritos));
        Toastify({
            text: "Agregado a favoritos ❤️",
            duration: 2000,
            gravity: "top",
            position: "left",
            style: {
                background: "linear-gradient(to right, #ff416c, #ff4b2b)",
                borderRadius: "2rem",
                fontSize: ".75rem"
            }
        }).showToast();
    } else {
        Toastify({
            text: "Ya está en favoritos",
            duration: 2000,
            gravity: "top",
            position: "left",
            style: {
                background: "#aaa",
                borderRadius: "2rem",
                fontSize: ".75rem"
            }
        }).showToast();
    }
}
