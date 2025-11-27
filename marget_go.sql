-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-11-2025 a las 14:13:31
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `marget.go`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulos`
--

CREATE TABLE `articulos` (
  `id` varchar(50) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `categoria_nombre` varchar(100) DEFAULT NULL,
  `categoria_id` varchar(50) DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `existencia` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `articulos`
--

INSERT INTO `articulos` (`id`, `titulo`, `imagen`, `categoria_nombre`, `categoria_id`, `precio`, `existencia`) VALUES
('carne-01', 'Carne Molida de Res 500g', 'img/carne.png', 'Carnes', 'carnes', 60.00, 100),
('carne-02', 'Pechuga de Pollo 1kg', 'img/pollo.png', 'Carnes', 'carnes', 85.00, 100),
('carne-03', 'Chuleta de Cerdo 1kg', 'img/cerdo.jpg', 'Carnes', 'carnes', 90.00, 100),
('carne-04', 'Costilla de Res 1kg', 'img/costilla.jpg', 'Carnes', 'carnes', 95.00, 100),
('carne-05', 'Filete de Pescado 500g', 'img/filete.jpeg', 'Carnes', 'carnes', 70.00, 100),
('lacteo-01', 'Leche Lala Entera 1L', 'img/lala1l.jpg', 'Lácteos', 'lacteos', 25.00, 100),
('lacteo-02', 'Yogur Natural 500g', 'img/yogur500.jpg', 'Lácteos', 'lacteos', 30.00, 100),
('lacteo-03', 'Queso panela 250g', 'img/panela.jpg', 'Lácteos', 'lacteos', 40.00, 100),
('lacteo-04', 'Crema Ácida 200ml', 'img/crema.jpg', 'Lácteos', 'lacteos', 22.00, 100),
('lacteo-05', 'Mantequilla sin sal 90g', 'img/mantequi.jpg', 'Lácteos', 'lacteos', 35.00, 100),
('verdura-01', 'Tomate Saladet 1kg', 'img/tomate.jpg', 'Verduras', 'verduras', 18.00, 98),
('verdura-02', 'Cebolla Blanca 1kg', 'img/cebolla.jpg', 'Verduras', 'verduras', 15.00, 100),
('verdura-03', 'Zanahoria 1kg', 'img/zanahoria.jpg', 'Verduras', 'verduras', 12.00, 100),
('verdura-04', 'Lechuga Romana', 'img/lechuga.jpg', 'Verduras', 'verduras', 10.00, 100),
('verdura-05', 'Aguacate Hass 1kg', 'img/aguacate.jpg', 'Verduras', 'verduras', 50.00, 100);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`) VALUES
(1, 'Administrador'),
(2, 'Usuario'),
(3, 'Empleado'),
(5, 'Empleado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `rol_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `usuario`, `email`, `password`, `rol_id`) VALUES
(2, 'genesis jocelyn', 'genisjoc', 'genesis@ucol.mx', '8dd95af018fbacc1ef86dab7f0eb330a', 1),
(3, 'mario', 'mariobros', 'mario@ucol.mx', '4e7d4e37f928e8c4d1042cafd92f8deb', 1),
(5, 'zinedine', 'zinchambeador', 'zin@gmail.com', 'd07ed168c86ad792aca756d90db232b2', 1),
(7, 'vane', 'vane1', 'vane@ucol.mx', 'ffca540bb660f1ead87b7366371b4e25', 3),
(9, 'brandon', 'bran', 'brandon@ucol.mx', '123456789', 1),
(12, 'fer', 'fer1', 'ferchis@ucol.mx', '$2y$10$p1b.sMFQ66XS6fV0LbwML.0CyiIkbVlE3Czb9kyw2S/LHZNTjPhyu', 2),
(13, 'eri', 'eri1', 'eri@ucol.mx', '$2y$10$JqnM/j27xSKaLMSdXASuPelKTIn7pM6rIe1YKfXvyS2QR06UXH69u', 2),
(14, 'rose2', 'rose3', 'ros@Wucol.mx', '123456789', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `articulo_id` varchar(50) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `usuario_id`, `articulo_id`, `cantidad`, `fecha`) VALUES
(3, 3, 'verdura-01', 2, '2025-11-27 09:13:16'),
(4, 2, 'verdura-01', 2, '2025-11-27 12:06:52');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `articulos`
--
ALTER TABLE `articulos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `rol_id` (`rol_id`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `articulo_id` (`articulo_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`);

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
