-- Crear base de datos (si no existe)
CREATE DATABASE IF NOT EXISTS marketgo;
USE marketgo;

-- Crear tabla de productos
CREATE TABLE productos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  descripcion TEXT,
  precio DECIMAL(10,2) NOT NULL,
  stock INT DEFAULT 0,
  categoria VARCHAR(50),
  imagen VARCHAR(255) -- ruta de la imagen (ej: img/manzanas.jpg)
);

-- Insertar algunos productos de prueba
INSERT INTO productos (nombre, descripcion, precio, stock, categoria, imagen) VALUES
('Manzanas', 'Manzanas frescas y dulces', 35.00, 50, 'Frutas', 'manzanas.jpg'),
('Leche', 'Leche entera 1L', 22.00, 30, 'Lácteos', 'leche.jpg'),
('Pan integral', 'Pan artesanal integral', 40.00, 20, 'Panadería', 'pan.jpg'),
('Yogurt', 'Yogurt natural 500ml', 18.00, 15, 'Lácteos', 'yogurt.jpg'),
('Refresco', 'Refresco de cola 2L', 28.00, 25, 'Bebidas', 'refresco.jpg'),
('Botanas', 'Variedad de snacks', 22.00, 15, 'Snacks', 'snacks.jpg');
