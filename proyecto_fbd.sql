-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-11-2026 a las 06:58:46
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
-- Base de datos: `proyecto_fbd`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `RFC` varchar(45) NOT NULL,
  `Nombre` varchar(45) NOT NULL,
  `Apellido` varchar(45) NOT NULL,
  `Calle` varchar(45) NOT NULL,
  `Número` int(10) NOT NULL,
  `Colonia` varchar(45) NOT NULL,
  `Ciudad` varchar(45) NOT NULL,
  `Estado` varchar(45) NOT NULL,
  `Código_Postal` int(10) NOT NULL,
  `Teléfono` varchar(45) NOT NULL,
  `Correo` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`RFC`, `Nombre`, `Apellido`, `Calle`, `Número`, `Colonia`, `Ciudad`, `Estado`, `Código_Postal`, `Teléfono`, `Correo`) VALUES
('', '', '', '', 0, '', '', '', 0, '', ''),
('GODE910203T45', 'Teresa', 'Gómez', 'Ecuador', 5, 'Manantiales', 'Morelia', 'Michoacán', 58000, '443-139-0466', 'teresago12@gmail.com'),
('MAGJ880510P76', 'Juan', 'Martínez', 'Arboleda', 2, 'Revolución', 'Morelia', 'Michoacán', 50000, '443-266-1850', 'juan10@gmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_entrada`
--

CREATE TABLE `detalle_entrada` (
  `Id_Entrada` int(11) NOT NULL,
  `Código_Producto` int(10) NOT NULL,
  `Tipo` enum('Compra','Devolución') NOT NULL,
  `Cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `detalle_entrada`
--

INSERT INTO `detalle_entrada` (`Id_Entrada`, `Código_Producto`, `Tipo`, `Cantidad`) VALUES
(2, 1, 'Compra', 50),
(3, 1, 'Devolución', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_salida`
--

CREATE TABLE `detalle_salida` (
  `Id_Salida` int(10) NOT NULL,
  `Código_Producto` int(10) NOT NULL,
  `Cantidad` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `detalle_salida`
--

INSERT INTO `detalle_salida` (`Id_Salida`, `Código_Producto`, `Cantidad`) VALUES
(1, 2, 2),
(1, 4, 2),
(1, 8, 2),
(3, 1, 2),
(3, 5, 1),
(3, 9, 3),
(4, 3, 2),
(4, 9, 2),
(5, 4, 4),
(6, 3, 3),
(7, 4, 4),
(8, 2, 4),
(9, 1, 2),
(10, 4, 2),
(11, 3, 2),
(12, 5, 4),
(13, 4, 200),
(14, 4, 20),
(15, 4, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `Id_Empleado` int(10) NOT NULL,
  `Contraseña` varchar(70) NOT NULL,
  `Nombre` varchar(45) NOT NULL,
  `Apellido` varchar(45) NOT NULL,
  `Fecha_Ingreso` date NOT NULL,
  `Fecha_Nacimiento` date NOT NULL,
  `Calle` varchar(45) NOT NULL,
  `Número` int(11) NOT NULL,
  `Colonia` varchar(45) NOT NULL,
  `Ciudad` varchar(45) NOT NULL,
  `Estado` varchar(45) NOT NULL,
  `Código_Postal` int(10) NOT NULL,
  `Teléfono` varchar(45) NOT NULL,
  `Rol` enum('Cajero','Administrador') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`Id_Empleado`, `Contraseña`, `Nombre`, `Apellido`, `Fecha_Ingreso`, `Fecha_Nacimiento`, `Calle`, `Número`, `Colonia`, `Ciudad`, `Estado`, `Código_Postal`, `Teléfono`, `Rol`) VALUES
(1, '$2y$10$NhCHDXGbI3OQRhDijiNNv.WkS4xbmaNkOkdkLgtnze1ttO.EblrA.', 'Roberto', 'Cisneros', '2025-05-21', '2005-10-26', 'Redada', 10, 'Concepción', 'Pátzcuaro', 'Michoacán', 61605, '434-266-1850', 'Administrador'),
(2, '$2y$10$md9DPd7EYz6Xx3Eiw41ENe/VO9kPy9CbYu09vPX9GegIS93pA9dH2', 'Emilio', 'Hernández', '2025-05-21', '2005-02-19', 'Romelisco', 8, 'Villas', 'Morelia', 'Michoacán', 58049, '443-354-5775', 'Cajero'),
(3, '$2y$10$pOzC9NRXyv6YTuz1zBCOQOXk1dTHWKSiCjvtmSY93yooUsWLkErju', 'Alonso', 'Pardo', '2025-05-21', '2005-04-04', 'Ramón', 4, 'Pedregal', 'Morelia', 'Michoacán', 58000, '443-157-5104', 'Cajero'),
(4, '$2y$10$I/FXerGsxtBIYYs38aPSm.Kvac5M17lTDb.kLzwvZTcxit0HDzBNi', 'Sebastián', 'Garcia', '2025-05-28', '2005-08-17', 'Alambre', 10, 'Revolución', 'Pátzcuaro', 'Michoacán', 58000, '443-493-4176', 'Cajero');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entrada_producto`
--

CREATE TABLE `entrada_producto` (
  `Id_Entrada` int(11) NOT NULL,
  `Fecha` date NOT NULL,
  `Id_Proveedor` int(11) DEFAULT NULL,
  `Id_Empleado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `entrada_producto`
--

INSERT INTO `entrada_producto` (`Id_Entrada`, `Fecha`, `Id_Proveedor`, `Id_Empleado`) VALUES
(2, '2025-05-29', 2, 3),
(3, '2025-05-29', NULL, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `Id_Factura` int(11) NOT NULL,
  `Fecha` date NOT NULL,
  `RFC` varchar(45) NOT NULL,
  `Id_Salida` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `factura`
--

INSERT INTO `factura` (`Id_Factura`, `Fecha`, `RFC`, `Id_Salida`) VALUES
(2, '2025-05-29', 'MAGJ880510P76', 4),
(3, '2025-05-29', 'GODE910203T45', 5),
(4, '2025-05-29', 'MAGJ880510P76', 7),
(5, '2025-05-29', 'GODE910203T45', 8),
(6, '2025-05-29', 'MAGJ880510P76', 9),
(7, '2025-05-30', 'GODE910203T45', 10),
(8, '2025-05-30', 'MAGJ880510P76', 13),
(9, '2026-01-30', '', 14),
(10, '2026-11-18', 'MAGJ880510P76', 15);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `Código_Producto` int(10) NOT NULL,
  `Nombre_Comercial` varchar(70) NOT NULL,
  `Precio_Público` decimal(10,2) NOT NULL,
  `Existencia` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`Código_Producto`, `Nombre_Comercial`, `Precio_Público`, `Existencia`) VALUES
(1, 'Sabritas', 16.00, 68),
(2, 'Pan Blanco Bimbo Grande', 45.50, 114),
(3, 'Sabritas Adobadas 45g', 18.00, 78),
(4, 'Coca-Cola 600ml', 17.50, 30),
(5, 'Galletas Emperador Chocolate', 15.00, 145),
(6, 'Leche Lala Entera 1L', 24.50, 90),
(7, 'Donas Bimbo Espolvoreadas', 32.00, 60),
(8, 'Rufles Queso 45g', 18.00, 73),
(9, 'Fanta Naranja 600ml', 17.50, 175),
(10, 'Avena Quaker Instantánea', 22.00, 50);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `Id_Proveedor` int(10) NOT NULL,
  `Nombre` varchar(45) NOT NULL,
  `Teléfono` varchar(45) NOT NULL,
  `Correo` varchar(45) NOT NULL,
  `Ciudad` varchar(45) NOT NULL,
  `Calle` varchar(45) NOT NULL,
  `Estado` varchar(45) NOT NULL,
  `Municipio` varchar(45) NOT NULL,
  `Código_Postal` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`Id_Proveedor`, `Nombre`, `Teléfono`, `Correo`, `Ciudad`, `Calle`, `Estado`, `Municipio`, `Código_Postal`) VALUES
(1, 'Bimbo S.A. de C.V.', '55-5123-4567', 'contacto@bimbo.com.mx', 'Ciudad de México', 'Av. Insurgentes Sur 2458', 'Ciudad de México', 'Álvaro Obregón', 1000),
(2, 'Sabritas', '55-5765-4321', 'ventas@sabritas.com.mx', 'Monterrey', 'Av. Lázaro Cárdenas 2400', 'Nuevo León', 'San Pedro Garza García', 66269),
(3, 'Coca-Cola FEMSA', '55-5987-6543', 'atencion@cocacolafemsa.com', 'Guadalajara', 'Av. Vallarta 6500', 'Jalisco', 'Zapopan', 45010),
(4, 'Gamesa-Quaker', '55-5456-7890', 'clientes@gamesa.com.mx', 'Puebla', 'Blvd. Carmen Serdán 101', 'Puebla', 'Puebla', 72000),
(5, 'Lala', '55-5321-6547', 'contacto@lala.com.mx', 'Torreón', 'Blvd. Independencia 240', 'Coahuila', 'Torreón', 27000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salida`
--

CREATE TABLE `salida` (
  `Id_Salida` int(10) NOT NULL,
  `Fecha` date NOT NULL,
  `Id_Empleado` int(10) NOT NULL,
  `Total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `salida`
--

INSERT INTO `salida` (`Id_Salida`, `Fecha`, `Id_Empleado`, `Total`) VALUES
(1, '2025-05-29', 2, 162.00),
(3, '2025-05-29', 2, 97.50),
(4, '2025-05-29', 2, 71.00),
(5, '2025-05-29', 2, 70.00),
(6, '2025-05-29', 4, 54.00),
(7, '2025-05-29', 3, 70.00),
(8, '2025-05-29', 3, 182.00),
(9, '2025-05-29', 3, 30.00),
(10, '2025-05-30', 4, 35.00),
(11, '2025-05-30', 4, 36.00),
(12, '2025-05-30', 4, 60.00),
(13, '2025-05-30', 3, 3500.00),
(14, '2026-01-30', 2, 350.00),
(15, '2026-11-18', 2, 35.00);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`RFC`);

--
-- Indices de la tabla `detalle_entrada`
--
ALTER TABLE `detalle_entrada`
  ADD PRIMARY KEY (`Id_Entrada`,`Código_Producto`),
  ADD KEY `Código_Producto` (`Código_Producto`),
  ADD KEY `Id_Entrada` (`Id_Entrada`);

--
-- Indices de la tabla `detalle_salida`
--
ALTER TABLE `detalle_salida`
  ADD PRIMARY KEY (`Id_Salida`,`Código_Producto`),
  ADD KEY `Código_Producto` (`Código_Producto`),
  ADD KEY `Id_Salida` (`Id_Salida`);

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`Id_Empleado`);

--
-- Indices de la tabla `entrada_producto`
--
ALTER TABLE `entrada_producto`
  ADD PRIMARY KEY (`Id_Entrada`),
  ADD KEY `Id_Proveedor` (`Id_Proveedor`),
  ADD KEY `Id_Empleado` (`Id_Empleado`);

--
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`Id_Factura`),
  ADD KEY `RFC` (`RFC`),
  ADD KEY `Id_Salida` (`Id_Salida`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`Código_Producto`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`Id_Proveedor`);

--
-- Indices de la tabla `salida`
--
ALTER TABLE `salida`
  ADD PRIMARY KEY (`Id_Salida`),
  ADD KEY `Id_Empleado` (`Id_Empleado`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
  MODIFY `Id_Empleado` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `entrada_producto`
--
ALTER TABLE `entrada_producto`
  MODIFY `Id_Entrada` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `factura`
--
ALTER TABLE `factura`
  MODIFY `Id_Factura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `Código_Producto` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `Id_Proveedor` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `salida`
--
ALTER TABLE `salida`
  MODIFY `Id_Salida` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_entrada`
--
ALTER TABLE `detalle_entrada`
  ADD CONSTRAINT `detalle_entrada_ibfk_1` FOREIGN KEY (`Id_Entrada`) REFERENCES `entrada_producto` (`Id_Entrada`),
  ADD CONSTRAINT `detalle_entrada_ibfk_2` FOREIGN KEY (`Código_Producto`) REFERENCES `producto` (`Código_Producto`);

--
-- Filtros para la tabla `detalle_salida`
--
ALTER TABLE `detalle_salida`
  ADD CONSTRAINT `detalle_salida_ibfk_2` FOREIGN KEY (`Id_Salida`) REFERENCES `salida` (`Id_Salida`),
  ADD CONSTRAINT `detalle_salida_ibfk_3` FOREIGN KEY (`Código_Producto`) REFERENCES `producto` (`Código_Producto`);

--
-- Filtros para la tabla `entrada_producto`
--
ALTER TABLE `entrada_producto`
  ADD CONSTRAINT `entrada_producto_ibfk_1` FOREIGN KEY (`Id_Empleado`) REFERENCES `empleado` (`Id_Empleado`),
  ADD CONSTRAINT `entrada_producto_ibfk_2` FOREIGN KEY (`Id_Proveedor`) REFERENCES `proveedor` (`Id_Proveedor`);

--
-- Filtros para la tabla `factura`
--
ALTER TABLE `factura`
  ADD CONSTRAINT `factura_ibfk_1` FOREIGN KEY (`RFC`) REFERENCES `cliente` (`RFC`),
  ADD CONSTRAINT `factura_ibfk_2` FOREIGN KEY (`Id_Salida`) REFERENCES `salida` (`Id_Salida`);

--
-- Filtros para la tabla `salida`
--
ALTER TABLE `salida`
  ADD CONSTRAINT `salida_ibfk_1` FOREIGN KEY (`Id_Empleado`) REFERENCES `empleado` (`Id_Empleado`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
