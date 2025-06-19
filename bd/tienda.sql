-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-06-2025 a las 22:07:52
-- Versión del servidor: 10.6.7-MariaDB
-- Versión de PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tienda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `idcategoria` int(11) NOT NULL,
  `nombre_categoria` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`idcategoria`, `nombre_categoria`) VALUES
(4, 'Almacenamiento'),
(5, 'Audio y Video'),
(8, 'Ayuda'),
(1, 'Computadoras'),
(11, 'Electrodomesticos'),
(6, 'Móviles'),
(7, 'Movilesss'),
(2, 'Periféricos'),
(10, 'Pizarras Digitales'),
(3, 'Redes y Video'),
(9, 'Teles');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_venta`
--

CREATE TABLE `detalle_venta` (
  `id` int(11) NOT NULL,
  `venta_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `detalle_venta`
--

INSERT INTO `detalle_venta` (`id`, `venta_id`, `producto_id`, `cantidad`, `precio_unitario`) VALUES
(1, 2, 8, 2, 39.00),
(2, 3, 11, 4, 55.00),
(3, 4, 10, 1, 65.50),
(4, 5, 11, 2, 55.00),
(5, 5, 12, 1, 25.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `prod_codi` int(11) NOT NULL,
  `prod_nombre` varchar(100) NOT NULL,
  `prod_descripcion` text DEFAULT NULL,
  `prod_model` varchar(50) DEFAULT NULL,
  `prod_marca` varchar(50) DEFAULT NULL,
  `idsubcategoria` int(11) NOT NULL,
  `proc_precio` decimal(10,2) NOT NULL,
  `prod_stock` int(11) NOT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`prod_codi`, `prod_nombre`, `prod_descripcion`, `prod_model`, `prod_marca`, `idsubcategoria`, `proc_precio`, `prod_stock`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(5, 'Estetoscopio Moderno', 'Estetoscopio de doble campana, alta sensibilidad', 'ST500', 'CardioTech', 2, 129.50, 12, '2025-06-12 23:25:24', '2025-06-13 00:14:21'),
(6, 'Uniforme quirúrgico000000000000000000000000', 'Uniforme completo de dos piezas, antibacteriano', 'UQ-PRO', 'SaniPro', 3, 149.00, 40, '2025-06-12 23:25:24', '2025-06-13 03:03:17'),
(7, 'Zapatos clínicos antideslizantes', 'Calzado cómodo y seguro para largas jornadas', 'ZCA-01', 'SafeSteps', 4, 199.90, 18, '2025-06-12 23:25:24', '2025-06-12 23:25:24'),
(8, 'Gorro quirúrgico estampado', 'Gorro lavable con diseño, talla única', 'GQE2024', 'ColorScrubs', 5, 39.00, 50, '2025-06-12 23:25:24', '2025-06-12 23:25:24'),
(9, 'Termómetro infrarrojo', 'Medición sin contacto con pantalla digital', 'TI-IRX', 'ThermoScan', 6, 85.00, 29, '2025-06-12 23:25:24', '2025-06-19 15:03:47'),
(10, 'Oxímetro de pulso', 'Dispositivo portátil para medir saturación de oxígeno', 'OX-100', 'PulseMed', 7, 65.50, 44, '2025-06-12 23:25:24', '2025-06-19 14:50:45'),
(11, 'Guantes quirúrgicos', 'Guantes estériles, talla mediana, caja de 100', 'GQ-MED', 'Medigloves', 8, 55.00, 57, '2025-06-12 23:25:24', '2025-06-19 15:03:47'),
(12, 'Lentes protectores', 'Protección ocular con ventilación lateral', 'LP-VT', 'SafeView', 9, 25.00, 80, '2025-06-12 23:25:24', '2025-06-12 23:25:24'),
(13, 'Mascarilla KN95', 'Mascarilla de alta protección, paquete x10', 'MASK-KN95', 'AirShield', 10, 45.00, 98, '2025-06-12 23:25:24', '2025-06-19 14:35:48'),
(14, 'wdwq', 'dwd', 'dw', 'dw', 11, 25.00, 5, '2025-06-13 00:14:54', '2025-06-13 00:14:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subcategoria`
--

CREATE TABLE `subcategoria` (
  `idsubcategoria` int(11) NOT NULL,
  `nombre_subcategoria` varchar(100) NOT NULL,
  `idcategoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `subcategoria`
--

INSERT INTO `subcategoria` (`idsubcategoria`, `nombre_subcategoria`, `idcategoria`) VALUES
(1, 'Laptops', 1),
(2, 'PC de Escritorio', 1),
(3, 'Teclados', 2),
(4, 'Ratones', 2),
(5, 'Routers', 3),
(6, 'Switches', 3),
(7, 'Discos Duros', 4),
(8, 'SSD', 4),
(9, 'Audífonos', 5),
(10, 'Monitores', 5),
(11, 'Celulares', 6),
(12, 'lentes', 8),
(13, 'Nokia', 4),
(14, '55\'', 9),
(15, 'Informatica y Sistemas', 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_cliente`
--

CREATE TABLE `tb_cliente` (
  `client_id` int(11) NOT NULL,
  `client_nombres` varchar(30) NOT NULL,
  `client_apellidos` varchar(30) NOT NULL,
  `client_direccion` varchar(30) NOT NULL,
  `client_telefono` varchar(50) NOT NULL,
  `client_correo` varchar(60) NOT NULL,
  `fecha_registro` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tb_cliente`
--

INSERT INTO `tb_cliente` (`client_id`, `client_nombres`, `client_apellidos`, `client_direccion`, `client_telefono`, `client_correo`, `fecha_registro`) VALUES
(1, 'Carlos Jesus SJ', 'asss', 'ssssss', 'dasdsssss', 'sssssss@gmail.com', '2025-06-10'),
(2, 'Yhamille Alinna', 'Asencio Cuenca', 'Pativilca', '985123447', 'alinyhamita@gmail.com', '2025-06-05'),
(3, 'Piero Alexander', 'Jara Milla', 'Buena Vista', '980303178', 'pierjar@gmail.com', '2025-06-05'),
(4, 'Alejandro Jesus', 'Jara Milla', 'Paramonga zabala 456', '963571482', 'janoelpromasna@gmail.com', '2025-06-05'),
(5, 'Carlos', 'Sipan', 'Arica 3987', '987256413', 'casliyo@gmail.com', '2025-12-12'),
(7, 'Oscar', 'Sipan Lozano', 'Arica 398', '986324150', 'cladldqwklwkl@gmail.com', '2025-12-12'),
(8, 'Carlos', 'Sipan', 'Arica 398', '980303113', 'carlossipanlozano@gmail.com', '2025-12-12'),
(9, 'Carlos', 'Sipan', 'Arica 398', '980303113', 'carlossipanlozano@gmail.com', '2025-12-12'),
(10, 'Jeancarlos', 'Rios', 'Echenique', '986357412', 'rios1232@gmail.com', '2024-12-05'),
(11, 'aaaaaaa', 'cccccccccccccc', 'cccccccccc', '11111111', 'danimasna@gmail.com', '2024-10-05'),
(12, 'Daniella Sipan', 'Marin Sipan', 'Echenique Sipan', '912374685', 'danimasna@gmail.com', '2024-10-05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_usuario`
--

CREATE TABLE `tb_usuario` (
  `usu_id` int(11) NOT NULL,
  `usu_usuario` varchar(255) NOT NULL,
  `usu_pass` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `rol` enum('admin','vendedor') NOT NULL DEFAULT 'vendedor',
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tb_usuario`
--

INSERT INTO `tb_usuario` (`usu_id`, `usu_usuario`, `usu_pass`, `email`, `rol`, `estado`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(1, '73594630', '$2y$10$/Ev7J6.jt2qXqS/FyJK5KOw63FSrp4fax5bMHRSD3TmeTMV6o3XUS', 'carlos@gmail.com', 'vendedor', 1, '2025-06-18 03:56:57', '2025-06-18 20:49:22'),
(9, 'dsadwdw', '$2y$10$8yBeDazgmlYXsePTDczMMe2pNtFdoIN9mlXNXWfH34RNZxbLDXMTW', 'carlodds@gmail.com', 'admin', 1, '2025-06-18 04:44:35', '2025-06-18 15:40:53'),
(10, 'wawadwadwadwdwdw', '$2y$10$IaxC44k780Z/hgS8RamgC.eY6O8koltd7ks0EsPXOng1WTaF1QuaO', 'dadwadwawadw@gmail.com', 'admin', 1, '2025-06-18 04:49:18', '2025-06-18 04:49:18'),
(11, '73423423423', '$2y$10$PfFGVoRibkefrkMDtOvdkeeQSmP/QjMJyfZA79qeZynvKq47BG/6y', 'dwedwedw@gmail.com', 'admin', 1, '2025-06-18 04:54:09', '2025-06-18 04:59:34'),
(12, 'anshi', '$2y$10$gOhJ4Y3mkSC8u/oHsUdn/.TdDfURsdLOCq5Bl1nhNv7RgJelNd4T6', 'aechegaray21@gmail.com', 'vendedor', 1, '2025-06-19 04:39:06', '2025-06-19 18:56:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `cliente_id`, `fecha`, `total`) VALUES
(1, 4, '2025-06-19 14:35:09', 90.00),
(2, 4, '2025-06-19 14:35:48', 78.00),
(3, 12, '2025-06-19 14:36:05', 220.00),
(4, 10, '2025-06-19 14:50:45', 65.50),
(5, 3, '2025-06-19 15:03:47', 135.00);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`idcategoria`),
  ADD UNIQUE KEY `nombre_categoria` (`nombre_categoria`);

--
-- Indices de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `venta_id` (`venta_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`prod_codi`),
  ADD UNIQUE KEY `prod_codi` (`prod_codi`),
  ADD KEY `idsubcategoria` (`idsubcategoria`);

--
-- Indices de la tabla `subcategoria`
--
ALTER TABLE `subcategoria`
  ADD PRIMARY KEY (`idsubcategoria`),
  ADD KEY `idcategoria` (`idcategoria`);

--
-- Indices de la tabla `tb_cliente`
--
ALTER TABLE `tb_cliente`
  ADD PRIMARY KEY (`client_id`);

--
-- Indices de la tabla `tb_usuario`
--
ALTER TABLE `tb_usuario`
  ADD PRIMARY KEY (`usu_id`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `idcategoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `prod_codi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `subcategoria`
--
ALTER TABLE `subcategoria`
  MODIFY `idsubcategoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `tb_cliente`
--
ALTER TABLE `tb_cliente`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `tb_usuario`
--
ALTER TABLE `tb_usuario`
  MODIFY `usu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD CONSTRAINT `detalle_venta_ibfk_1` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`),
  ADD CONSTRAINT `detalle_venta_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`prod_codi`);

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`idsubcategoria`) REFERENCES `subcategoria` (`idsubcategoria`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `subcategoria`
--
ALTER TABLE `subcategoria`
  ADD CONSTRAINT `subcategoria_ibfk_1` FOREIGN KEY (`idcategoria`) REFERENCES `categoria` (`idcategoria`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `tb_cliente` (`client_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
