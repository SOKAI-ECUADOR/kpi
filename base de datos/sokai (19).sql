-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-01-2020 a las 08:04:11
-- Versión del servidor: 10.4.8-MariaDB
-- Versión de PHP: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sokai`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `area_trabajo`
--

CREATE TABLE `area_trabajo` (
  `id_area` int(11) NOT NULL,
  `are_nombre` varchar(100) NOT NULL,
  `are_estado` varchar(15) NOT NULL,
  `fcrea` date DEFAULT NULL,
  `fmodifica` date DEFAULT NULL,
  `ucrea` int(11) DEFAULT NULL,
  `umodifica` int(11) DEFAULT NULL,
  `iddepa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `area_trabajo`
--

INSERT INTO `area_trabajo` (`id_area`, `are_nombre`, `are_estado`, `fcrea`, `fmodifica`, `ucrea`, `umodifica`, `iddepa`) VALUES
(7, 'Desarrollo', '1', NULL, NULL, NULL, NULL, 2),
(8, 'Mantenimiento', '1', NULL, NULL, NULL, NULL, 2),
(9, 'Contabilidad', '1', NULL, NULL, NULL, NULL, 3),
(10, 'Facturación', '1', NULL, NULL, NULL, NULL, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asientos`
--

CREATE TABLE `asientos` (
  `id_asientos` int(11) NOT NULL,
  `comprobante` varchar(200) NOT NULL,
  `numero` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `razon_social` varchar(200) NOT NULL,
  `ruc_ci` varchar(15) NOT NULL,
  `concepto` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asientos_detalle`
--

CREATE TABLE `asientos_detalle` (
  `ida_detalle` int(11) NOT NULL,
  `proyecto` varchar(200) NOT NULL,
  `debe` int(11) NOT NULL,
  `haber` int(11) NOT NULL,
  `fcrea` date NOT NULL,
  `fmodifica` date NOT NULL,
  `ucrea` int(11) NOT NULL,
  `umodifica` int(11) NOT NULL,
  `id_ctas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `banco`
--

CREATE TABLE `banco` (
  `id_banco` int(11) NOT NULL,
  `nombre_banco` varchar(150) NOT NULL,
  `estado` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `banco`
--

INSERT INTO `banco` (`id_banco`, `nombre_banco`, `estado`) VALUES
(1, 'Banco Guayaquil', '1'),
(2, 'Banco Internacional', '1'),
(3, 'Diners Club', '1'),
(4, 'Citibank', '1'),
(5, 'Banco Pichincha', '1'),
(6, 'Banco ProCredit', '1'),
(7, 'Banco Bolivariano', '1'),
(8, 'Banco General Rumiñahui', '1'),
(9, 'Banco del Pacifico', '1'),
(10, 'Biess', '1'),
(11, 'Produbanco', '1'),
(12, 'Banco Amazonas', '1'),
(13, 'Banco de Desarrollo del Ecuador', '1'),
(14, 'Banco de Loja', '1'),
(15, 'Banco de Machala', '1'),
(16, 'Banco Solidario', '1'),
(17, 'Banco del Austro', '1'),
(18, 'Banco Coopnacional', '1'),
(19, 'Banco VisionFund Ecuador', '1'),
(20, 'Banco del Litoral', '1'),
(21, 'Banco D-MIRO', '1'),
(22, 'Banco Delbank', '1'),
(23, 'Banco Desarrollo', '1'),
(24, 'Banco Finca', '1'),
(25, 'Banco BanEcuador', '1'),
(26, 'Banco Comercial de Manab?', '1'),
(27, 'Banco Capital', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bodega`
--

CREATE TABLE `bodega` (
  `id_bodega` int(11) NOT NULL,
  `codigo` varchar(300) DEFAULT NULL,
  `nombre` varchar(300) DEFAULT NULL,
  `serie_factura` varchar(300) DEFAULT NULL,
  `ubicacion` varchar(300) DEFAULT NULL,
  `direccion` varchar(300) DEFAULT NULL,
  `telefono` varchar(300) DEFAULT NULL,
  `fcrea` datetime DEFAULT NULL,
  `fmodifica` datetime DEFAULT NULL,
  `ucrea` int(11) DEFAULT NULL,
  `umodifica` int(11) DEFAULT NULL,
  `id_establecimiento` int(11) DEFAULT NULL,
  `id_empresa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `bodega`
--

INSERT INTO `bodega` (`id_bodega`, `codigo`, `nombre`, `serie_factura`, `ubicacion`, `direccion`, `telefono`, `fcrea`, `fmodifica`, `ucrea`, `umodifica`, `id_establecimiento`, `id_empresa`) VALUES
(1, '123', 'bodega1', '1234', 'asdj', 'ashdjashdj', '123857', '2019-11-20 00:00:00', '2019-11-20 00:00:00', NULL, NULL, 1, 1),
(2, '1234', 'bodega 5', '1232', '1232', 'twegegtf', '1dt123', '2019-11-27 00:00:00', '2019-12-20 13:11:38', NULL, NULL, 1, 1),
(11, '001', 'BODEGA CENTRAL', '001001', 'LA COLON', 'COLON Y DIEGO D ALMAGRO', '022222222', '2019-12-12 15:46:18', '2019-12-12 20:01:24', NULL, NULL, 1, 1),
(12, '1', '1', '1', '1', '1', '1', '2020-01-09 23:46:44', '2020-01-09 23:46:44', NULL, NULL, 1, 1),
(13, '1', '1', '1', '1', '1', '1', '2020-01-09 23:47:49', '2020-01-09 23:47:49', NULL, NULL, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bodega_egreso`
--

CREATE TABLE `bodega_egreso` (
  `id_bodega_egreso` int(11) NOT NULL,
  `num_egreso` int(11) DEFAULT NULL,
  `fecha_egreso` date DEFAULT NULL,
  `tipo_egreso` varchar(100) DEFAULT NULL,
  `observ_egreso` varchar(300) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `costo` decimal(10,2) DEFAULT NULL,
  `fcrea` datetime DEFAULT NULL,
  `fmodifica` datetime DEFAULT NULL,
  `ucrea` int(11) DEFAULT NULL,
  `umodifica` int(11) DEFAULT NULL,
  `id_producto_bodega` int(11) DEFAULT NULL,
  `id_empresa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `bodega_egreso`
--

INSERT INTO `bodega_egreso` (`id_bodega_egreso`, `num_egreso`, `fecha_egreso`, `tipo_egreso`, `observ_egreso`, `precio`, `cantidad`, `costo`, `fcrea`, `fmodifica`, `ucrea`, `umodifica`, `id_producto_bodega`, `id_empresa`) VALUES
(1, NULL, NULL, NULL, NULL, '12.00', 10, '12.00', '2019-12-20 13:01:01', '2019-12-20 13:01:01', NULL, NULL, NULL, 1),
(2, 1234, '2019-12-20', 'Auto Consumo', '214', '10.00', 10, '10.00', '2019-12-20 13:13:01', '2019-12-20 13:13:01', NULL, NULL, NULL, 1),
(3, 12345, '2019-12-20', 'Egreso por Ajuste', '123456', '10.00', 10, '10.00', '2019-12-20 13:15:31', '2019-12-20 13:15:31', NULL, NULL, NULL, 1),
(4, NULL, '2019-12-20', NULL, NULL, '10.00', 10, '10.00', '2019-12-20 14:15:55', '2019-12-20 14:15:55', NULL, NULL, NULL, 1),
(5, NULL, '2019-12-20', NULL, NULL, '10.00', 100, '10.00', '2019-12-20 14:16:22', '2019-12-20 14:16:22', NULL, NULL, NULL, 1),
(6, NULL, '2019-12-22', 'Auto Consumo', NULL, '10.00', 3, '10.00', '2019-12-22 18:36:45', '2019-12-22 18:36:45', NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bodega_ingreso`
--

CREATE TABLE `bodega_ingreso` (
  `id_bodega_ingreso` int(11) NOT NULL,
  `num_ingreso` int(11) DEFAULT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `tipo_ingreso` varchar(100) DEFAULT NULL,
  `observ_ingreso` varchar(300) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `costo_unitario` decimal(10,2) DEFAULT NULL,
  `costo_total` decimal(10,2) DEFAULT NULL,
  `fcrea` datetime DEFAULT NULL,
  `fmodifica` datetime DEFAULT NULL,
  `ucrea` int(11) DEFAULT NULL,
  `umodifica` int(11) DEFAULT NULL,
  `id_producto_bodega` int(11) DEFAULT NULL,
  `id_empresa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `bodega_ingreso`
--

INSERT INTO `bodega_ingreso` (`id_bodega_ingreso`, `num_ingreso`, `fecha_ingreso`, `tipo_ingreso`, `observ_ingreso`, `cantidad`, `costo_unitario`, `costo_total`, `fcrea`, `fmodifica`, `ucrea`, `umodifica`, `id_producto_bodega`, `id_empresa`) VALUES
(7, 214124, '2019-12-19', NULL, '124124', 50, '5.00', '250.00', '2019-12-19 16:03:03', '2019-12-19 16:03:03', NULL, NULL, NULL, 1),
(8, 214124, '2019-12-19', NULL, '124124', 50, '15.00', '750.00', '2019-12-19 16:03:25', '2019-12-19 16:03:25', NULL, NULL, NULL, 1),
(9, 214124, '2019-12-19', NULL, '124124', 25, '15.00', '750.00', '2019-12-19 16:04:52', '2019-12-19 16:04:52', NULL, NULL, NULL, 1),
(10, 214124, '2019-12-19', NULL, '124124', 25, '10.00', '5.00', '2019-12-19 16:04:52', '2019-12-19 16:04:52', NULL, NULL, NULL, 1),
(11, NULL, '2019-12-19', NULL, NULL, 5, '5.00', '25.00', '2019-12-19 16:11:48', '2019-12-19 16:11:48', NULL, NULL, NULL, 1),
(12, NULL, '2019-12-19', NULL, NULL, 10, '2.00', '20.00', '2019-12-19 16:36:26', '2019-12-19 16:36:26', NULL, NULL, NULL, 1),
(13, 1, '2019-12-19', 'Devolucion por Venta', '2', 5, '5.00', '25.00', '2019-12-19 16:46:11', '2019-12-19 16:46:11', NULL, NULL, NULL, 1),
(14, 123, '2019-12-19', 'Ingreso por Ajuste', 'qwqw', 12, '23.00', '276.00', '2019-12-19 16:47:12', '2019-12-19 16:47:12', NULL, NULL, NULL, 1),
(15, 1234555, '2019-12-19', NULL, 'we', 10, '23.00', '230.00', '2019-12-19 16:47:55', '2019-12-19 16:47:55', NULL, NULL, NULL, 1),
(16, NULL, '2019-12-20', NULL, NULL, 11, '12.00', '132.00', '2019-12-20 14:19:05', '2019-12-20 14:19:05', NULL, NULL, NULL, 1),
(17, 123124, '2019-12-20', NULL, '123424', 50, '23.00', '1150.00', '2019-12-20 17:30:07', '2019-12-20 17:30:07', NULL, NULL, NULL, 1),
(18, 123124, '2019-12-20', NULL, '123424', 60, '34.00', '2040.00', '2019-12-20 17:30:07', '2019-12-20 17:30:07', NULL, NULL, NULL, 1),
(19, 123, '2019-12-21', 'Fabricación', '123', 20, '12.00', '240.00', '2019-12-21 10:54:38', '2019-12-21 10:54:38', NULL, NULL, NULL, 1),
(20, 12345, '2019-12-21', 'Fabricación', '12345', 10, '12.00', '120.00', '2019-12-21 13:29:27', '2019-12-21 13:29:27', NULL, NULL, NULL, 1),
(21, 12345, '2019-12-21', 'Fabricación', '12345', 100, '13.00', '1300.00', '2019-12-21 13:29:27', '2019-12-21 13:29:27', NULL, NULL, NULL, 1),
(22, NULL, '2019-12-22', 'Ingreso por Ajuste', NULL, 5, '10.00', '50.00', '2019-12-22 18:36:03', '2019-12-22 18:36:03', NULL, NULL, NULL, 1),
(23, 99, '2019-12-23', NULL, '99', 5, '5.00', '25.00', '2019-12-23 14:35:44', '2019-12-23 14:35:44', NULL, NULL, NULL, 1),
(24, 222, '2019-12-23', NULL, '222', 50, '12.00', '600.00', '2019-12-23 18:08:21', '2019-12-23 18:08:21', NULL, NULL, NULL, 1),
(26, 222, '2019-12-23', NULL, '222', 50, '12.00', '600.00', '2019-12-23 18:08:21', '2019-12-23 18:08:21', NULL, NULL, NULL, 1),
(28, 12234, '2019-12-26', 'Inventario Inicial', '123', 50, '12.00', '600.00', '2019-12-26 14:31:36', '2019-12-26 14:31:36', NULL, NULL, 10, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bodega_transferencia`
--

CREATE TABLE `bodega_transferencia` (
  `id_bodega_transferencia` int(11) NOT NULL,
  `num_trans` int(11) DEFAULT NULL,
  `f_iniciacion` date DEFAULT NULL,
  `f_finalizacion` date DEFAULT NULL,
  `motivo_trans` varchar(100) DEFAULT NULL,
  `emisor_trans` varchar(300) DEFAULT NULL,
  `receptor_trans` varchar(300) DEFAULT NULL,
  `observ_trans` varchar(300) DEFAULT NULL,
  `transporte` tinyint(1) DEFAULT NULL,
  `marcav` varchar(300) DEFAULT NULL,
  `placasv` varchar(100) DEFAULT NULL,
  `colorv` varchar(100) DEFAULT NULL,
  `transportista` varchar(300) DEFAULT NULL,
  `fcrea` datetime DEFAULT NULL,
  `fmodifica` datetime DEFAULT NULL,
  `ucrea` int(11) DEFAULT NULL,
  `umodifica` int(11) DEFAULT NULL,
  `id_empresa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `bodega_transferencia`
--

INSERT INTO `bodega_transferencia` (`id_bodega_transferencia`, `num_trans`, `f_iniciacion`, `f_finalizacion`, `motivo_trans`, `emisor_trans`, `receptor_trans`, `observ_trans`, `transporte`, `marcav`, `placasv`, `colorv`, `transportista`, `fcrea`, `fmodifica`, `ucrea`, `umodifica`, `id_empresa`) VALUES
(16, 222, '2019-12-23', '2019-12-23', 'Devolución', '11', '2', '222', 1, '222', '222', '222', '222', '2019-12-23 18:05:40', '2019-12-23 18:05:40', NULL, NULL, 1),
(18, 333, NULL, NULL, 'Traspaso', '11', '2', '333', 1, '333', '333', '333', '333', '2019-12-23 18:08:50', '2019-12-23 18:08:50', NULL, NULL, 1),
(19, 12224, '2019-12-26', '2019-12-26', 'Cambio', '11', '2', '123', 1, '123', '123', '123', '123', '2019-12-26 14:55:56', '2019-12-26 14:55:56', NULL, NULL, 1),
(20, 111111, '2019-12-27', '2019-12-27', 'Devolución', '2', '11', NULL, 0, NULL, NULL, NULL, NULL, '2019-12-27 14:33:03', '2019-12-27 14:33:03', NULL, NULL, 1),
(21, 22222, '2019-12-27', '2019-12-27', 'Cambio', '2', '11', NULL, 0, NULL, NULL, NULL, NULL, '2019-12-27 15:18:56', '2019-12-27 15:18:56', NULL, NULL, 1),
(22, 55555, '2020-01-04', '2020-01-04', 'Abastecimiento', '11', '1', 'khkhkkk', 0, NULL, NULL, NULL, NULL, '2020-01-04 14:26:28', '2020-01-04 14:26:28', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caja`
--

CREATE TABLE `caja` (
  `id_caja` int(11) NOT NULL,
  `descrip_caja` varchar(200) DEFAULT NULL,
  `grupo_caja` varchar(50) DEFAULT 'MOVIMIENTO',
  `cuenta_contable` varchar(50) DEFAULT NULL,
  `fcrea` datetime DEFAULT current_timestamp(),
  `fmodifica` datetime DEFAULT NULL,
  `id_moneda` int(11) DEFAULT NULL,
  `id_empresa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `caja`
--

INSERT INTO `caja` (`id_caja`, `descrip_caja`, `grupo_caja`, `cuenta_contable`, `fcrea`, `fmodifica`, `id_moneda`, `id_empresa`) VALUES
(1, 'Caja general', 'MOVIMIENTO', '1.1.1.01.001', '2019-12-26 11:40:28', NULL, 1, 1),
(2, 'Caja Chica', 'MOVIMIENTO', '1.1.1.01.002', '2019-12-26 11:40:28', NULL, 1, 1),
(3, 'Diferencias en caja', 'MOVIMIENTO', '4.1.8.01.05', '2019-12-26 11:40:57', NULL, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calendario_empleado`
--

CREATE TABLE `calendario_empleado` (
  `id_calen_emp` int(11) NOT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `razon` varchar(100) DEFAULT NULL,
  `fcrea` date DEFAULT NULL,
  `fmodifica` date DEFAULT NULL,
  `ucrea` int(11) DEFAULT NULL,
  `umodifica` int(11) DEFAULT NULL,
  `idemplead` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `campo_adicional`
--

CREATE TABLE `campo_adicional` (
  `id_campo_adicional` int(11) NOT NULL,
  `nombrec` varchar(300) NOT NULL,
  `id_empresa` int(11) NOT NULL,
  `fcrea` datetime NOT NULL,
  `fmodifica` datetime NOT NULL,
  `ucrea` int(11) DEFAULT NULL,
  `umodifica` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargas_empleado`
--

CREATE TABLE `cargas_empleado` (
  `id_carga` int(11) NOT NULL,
  `car_dni` varchar(10) DEFAULT NULL,
  `car_nombre` varchar(50) DEFAULT NULL,
  `car_fecha_nacimiento` date DEFAULT NULL,
  `car_edad` int(11) DEFAULT NULL,
  `car_ocupacion` varchar(45) DEFAULT NULL,
  `car_parentezco` varchar(15) DEFAULT NULL,
  `car_discapacidad` varchar(20) DEFAULT NULL,
  `car_discap_porcentaje` int(11) DEFAULT NULL,
  `fcrea` date DEFAULT NULL,
  `fmodifica` date DEFAULT NULL,
  `ucrea` int(11) DEFAULT NULL,
  `umodifica` int(11) DEFAULT NULL,
  `idempleado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargo`
--

CREATE TABLE `cargo` (
  `id_cargo` int(11) NOT NULL,
  `car_nombre` varchar(100) DEFAULT NULL,
  `car_sueldo` decimal(6,2) DEFAULT NULL,
  `car_estado` tinyint(1) DEFAULT NULL,
  `fcrea` date DEFAULT NULL,
  `fmodifica` date DEFAULT NULL,
  `ucrea` int(11) DEFAULT NULL,
  `umodifica` int(11) DEFAULT NULL,
  `idarea` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `cargo`
--

INSERT INTO `cargo` (`id_cargo`, `car_nombre`, `car_sueldo`, `car_estado`, `fcrea`, `fmodifica`, `ucrea`, `umodifica`, `idarea`) VALUES
(3, 'Analista de Sistemas', '1200.00', 1, NULL, NULL, NULL, NULL, 7),
(4, 'Técnico de Hardware', '680.00', 1, NULL, NULL, NULL, NULL, 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ciudad`
--

CREATE TABLE `ciudad` (
  `id_ciudad` int(11) NOT NULL,
  `codigo` varchar(45) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `fcrea` datetime DEFAULT NULL,
  `fmodifica` datetime DEFAULT NULL,
  `ucrea` int(11) DEFAULT NULL,
  `umodifica` int(11) DEFAULT NULL,
  `id_provincia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `ciudad`
--

INSERT INTO `ciudad` (`id_ciudad`, `codigo`, `nombre`, `fcrea`, `fmodifica`, `ucrea`, `umodifica`, `id_provincia`) VALUES
(1, '101', 'CUENCA', NULL, NULL, NULL, NULL, 1),
(2, '102', 'GIRÓN', NULL, NULL, NULL, NULL, 1),
(3, '103', 'GUALACEO', NULL, NULL, NULL, NULL, 1),
(4, '104', 'NABÓN', NULL, NULL, NULL, NULL, 1),
(5, '105', 'PAUTE', NULL, NULL, NULL, NULL, 1),
(6, '106', 'PUCARA', NULL, NULL, NULL, NULL, 1),
(7, '107', 'SAN FERNANDO', NULL, NULL, NULL, NULL, 1),
(8, '108', 'SANTA ISABEL', NULL, NULL, NULL, NULL, 1),
(9, '109', 'SIGSIG', NULL, NULL, NULL, NULL, 1),
(10, '110', 'OÑA', NULL, NULL, NULL, NULL, 1),
(11, '111', 'CHORDELEG', NULL, NULL, NULL, NULL, 1),
(12, '112', 'EL PAN', NULL, NULL, NULL, NULL, 1),
(13, '113', 'SEVILLA DE ORO', NULL, NULL, NULL, NULL, 1),
(14, '114', 'GUACHAPALA', NULL, NULL, NULL, NULL, 1),
(15, '115', 'CAMILO PONCE ENRÍQUEZ', NULL, NULL, NULL, NULL, 1),
(16, '201', 'GUARANDA', NULL, NULL, NULL, NULL, 2),
(17, '202', 'CHILLANES', NULL, NULL, NULL, NULL, 2),
(18, '203', 'CHIMBO', NULL, NULL, NULL, NULL, 2),
(19, '204', 'ECHEANDÍA', NULL, NULL, NULL, NULL, 2),
(20, '205', 'SAN MIGUEL', NULL, NULL, NULL, NULL, 2),
(21, '206', 'CALUMA', NULL, NULL, NULL, NULL, 2),
(22, '207', 'LAS NAVES', NULL, NULL, NULL, NULL, 2),
(23, '301', 'AZOGUES', NULL, NULL, NULL, NULL, 3),
(24, '302', 'BIBLIÁN', NULL, NULL, NULL, NULL, 3),
(25, '303', 'CAÑAR', NULL, NULL, NULL, NULL, 3),
(26, '304', 'LA TRONCAL', NULL, NULL, NULL, NULL, 3),
(27, '305', 'EL TAMBO', NULL, NULL, NULL, NULL, 3),
(28, '306', 'DÉLEG', NULL, NULL, NULL, NULL, 3),
(29, '307', 'SUSCAL', NULL, NULL, NULL, NULL, 3),
(30, '401', 'TULCÁN', NULL, NULL, NULL, NULL, 4),
(31, '402', 'BOLÍVAR', NULL, NULL, NULL, NULL, 4),
(32, '403', 'ESPEJO', NULL, NULL, NULL, NULL, 4),
(33, '404', 'MIRA', NULL, NULL, NULL, NULL, 4),
(34, '405', 'MONTÚFAR', NULL, NULL, NULL, NULL, 4),
(35, '406', 'SAN PEDRO DE HUACA', NULL, NULL, NULL, NULL, 4),
(36, '501', 'LATACUNGA', NULL, NULL, NULL, NULL, 5),
(37, '502', 'LA MANÁ', NULL, NULL, NULL, NULL, 5),
(38, '503', 'PANGUA', NULL, NULL, NULL, NULL, 5),
(39, '504', 'PUJILI', NULL, NULL, NULL, NULL, 5),
(40, '505', 'SALCEDO', NULL, NULL, NULL, NULL, 5),
(41, '506', 'SAQUISILÍ', NULL, NULL, NULL, NULL, 5),
(42, '507', 'SIGCHOS', NULL, NULL, NULL, NULL, 5),
(43, '601', 'RIOBAMBA', NULL, NULL, NULL, NULL, 6),
(44, '602', 'ALAUSI', NULL, NULL, NULL, NULL, 6),
(45, '603', 'COLTA', NULL, NULL, NULL, NULL, 6),
(46, '604', 'CHAMBO', NULL, NULL, NULL, NULL, 6),
(47, '605', 'CHUNCHI', NULL, NULL, NULL, NULL, 6),
(48, '606', 'GUAMOTE', NULL, NULL, NULL, NULL, 6),
(49, '607', 'GUANO', NULL, NULL, NULL, NULL, 6),
(50, '608', 'PALLATANGA', NULL, NULL, NULL, NULL, 6),
(51, '609', 'PENIPE', NULL, NULL, NULL, NULL, 6),
(52, '610', 'CUMANDÁ', NULL, NULL, NULL, NULL, 6),
(53, '701', 'MACHALA', NULL, NULL, NULL, NULL, 7),
(54, '702', 'ARENILLAS', NULL, NULL, NULL, NULL, 7),
(55, '703', 'ATAHUALPA', NULL, NULL, NULL, NULL, 7),
(56, '704', 'BALSAS', NULL, NULL, NULL, NULL, 7),
(57, '705', 'CHILLA', NULL, NULL, NULL, NULL, 7),
(58, '706', 'EL GUABO', NULL, NULL, NULL, NULL, 7),
(59, '707', 'HUAQUILLAS', NULL, NULL, NULL, NULL, 7),
(60, '708', 'MARCABELÍ', NULL, NULL, NULL, NULL, 7),
(61, '709', 'PASAJE', NULL, NULL, NULL, NULL, 7),
(62, '710', 'PIÑAS', NULL, NULL, NULL, NULL, 7),
(63, '711', 'PORTOVELO', NULL, NULL, NULL, NULL, 7),
(64, '712', 'SANTA ROSA', NULL, NULL, NULL, NULL, 7),
(65, '713', 'ZARUMA', NULL, NULL, NULL, NULL, 7),
(66, '714', 'LAS LAJAS', NULL, NULL, NULL, NULL, 7),
(67, '801', 'ESMERALDAS  ', NULL, NULL, NULL, NULL, 8),
(68, '802', 'ELOY ALFARO', NULL, NULL, NULL, NULL, 8),
(69, '803', 'MUISNE', NULL, NULL, NULL, NULL, 8),
(70, '804', 'QUININDÉ', NULL, NULL, NULL, NULL, 8),
(71, '805', 'SAN LORENZO', NULL, NULL, NULL, NULL, 8),
(72, '806', 'ATACAMES', NULL, NULL, NULL, NULL, 8),
(73, '807', 'RIOVERDE', NULL, NULL, NULL, NULL, 8),
(74, '808', 'LA CONCORDIA', NULL, NULL, NULL, NULL, 8),
(75, '901', 'GUAYAQUIL', NULL, NULL, NULL, NULL, 9),
(76, '902', 'ALFREDO BAQUERIZO MORENO', NULL, NULL, NULL, NULL, 9),
(77, '903', 'BALAO', NULL, NULL, NULL, NULL, 9),
(78, '904', 'BALZAR', NULL, NULL, NULL, NULL, 9),
(79, '905', 'COLIMES', NULL, NULL, NULL, NULL, 9),
(80, '906', 'DAULE', NULL, NULL, NULL, NULL, 9),
(81, '907', 'DURÁN', NULL, NULL, NULL, NULL, 9),
(82, '908', 'EL EMPALME', NULL, NULL, NULL, NULL, 9),
(83, '909', 'EL TRIUNFO', NULL, NULL, NULL, NULL, 9),
(84, '910', 'MILAGRO', NULL, NULL, NULL, NULL, 9),
(85, '911', 'NARANJAL', NULL, NULL, NULL, NULL, 9),
(86, '912', 'NARANJITO', NULL, NULL, NULL, NULL, 9),
(87, '913', 'PALESTINA', NULL, NULL, NULL, NULL, 9),
(88, '914', 'PEDRO CARBO', NULL, NULL, NULL, NULL, 9),
(89, '916', 'SAMBORONDÓN', NULL, NULL, NULL, NULL, 9),
(90, '918', 'SANTA LUCÍA', NULL, NULL, NULL, NULL, 9),
(91, '919', 'SALITRE', NULL, NULL, NULL, NULL, 9),
(92, '920', 'SAN JACINTO DE YAGUACHI', NULL, NULL, NULL, NULL, 9),
(93, '921', 'PLAYAS', NULL, NULL, NULL, NULL, 9),
(94, '922', 'SIMÓN BOLÍVAR', NULL, NULL, NULL, NULL, 9),
(95, '923', 'CORONEL MARCELINO MARIDUEÑA', NULL, NULL, NULL, NULL, 9),
(96, '924', 'LOMAS DE SARGENTILLO', NULL, NULL, NULL, NULL, 9),
(97, '925', 'NOBOL', NULL, NULL, NULL, NULL, 9),
(98, '927', 'GENERAL ANTONIO ELIZALDE', NULL, NULL, NULL, NULL, 9),
(99, '928', 'ISIDRO AYORA', NULL, NULL, NULL, NULL, 9),
(100, '1001', 'IBARRA', NULL, NULL, NULL, NULL, 10),
(101, '1002', 'ANTONIO ANTE', NULL, NULL, NULL, NULL, 10),
(102, '1003', 'COTACACHI', NULL, NULL, NULL, NULL, 10),
(103, '1004', 'OTAVALO', NULL, NULL, NULL, NULL, 10),
(104, '1005', 'PIMAMPIRO', NULL, NULL, NULL, NULL, 10),
(105, '1006', 'SAN MIGUEL DE URCUQUÍ', NULL, NULL, NULL, NULL, 10),
(106, '1101', 'LOJA  ', NULL, NULL, NULL, NULL, 11),
(107, '1102', 'CALVAS', NULL, NULL, NULL, NULL, 11),
(108, '1103', 'CATAMAYO', NULL, NULL, NULL, NULL, 11),
(109, '1104', 'CELICA', NULL, NULL, NULL, NULL, 11),
(110, '1105', 'CHAGUARPAMBA', NULL, NULL, NULL, NULL, 11),
(111, '1106', 'ESPÍNDOLA', NULL, NULL, NULL, NULL, 11),
(112, '1107', 'GONZANAMÁ', NULL, NULL, NULL, NULL, 11),
(113, '1108', 'MACARÁ', NULL, NULL, NULL, NULL, 11),
(114, '1109', 'PALTAS', NULL, NULL, NULL, NULL, 11),
(115, '1110', 'PUYANGO', NULL, NULL, NULL, NULL, 11),
(116, '1111', 'SARAGURO', NULL, NULL, NULL, NULL, 11),
(117, '1112', 'SOZORANGA', NULL, NULL, NULL, NULL, 11),
(118, '1113', 'ZAPOTILLO', NULL, NULL, NULL, NULL, 11),
(119, '1114', 'PINDAL', NULL, NULL, NULL, NULL, 11),
(120, '1115', 'QUILANGA', NULL, NULL, NULL, NULL, 11),
(121, '1116', 'OLMEDO', NULL, NULL, NULL, NULL, 11),
(122, '1201', 'BABAHOYO', NULL, NULL, NULL, NULL, 12),
(123, '1202', 'BABA', NULL, NULL, NULL, NULL, 12),
(124, '1203', 'MONTALVO', NULL, NULL, NULL, NULL, 12),
(125, '1204', 'PUEBLOVIEJO', NULL, NULL, NULL, NULL, 12),
(126, '1205', 'QUEVEDO', NULL, NULL, NULL, NULL, 12),
(127, '1206', 'URDANETA', NULL, NULL, NULL, NULL, 12),
(128, '1207', 'VENTANAS', NULL, NULL, NULL, NULL, 12),
(129, '1208', 'VÍNCES', NULL, NULL, NULL, NULL, 12),
(130, '1209', 'PALENQUE', NULL, NULL, NULL, NULL, 12),
(131, '1210', 'BUENA FÉ', NULL, NULL, NULL, NULL, 12),
(132, '1211', 'VALENCIA', NULL, NULL, NULL, NULL, 12),
(133, '1212', 'MOCACHE', NULL, NULL, NULL, NULL, 12),
(134, '1213', 'QUINSALOMA', NULL, NULL, NULL, NULL, 12),
(135, '1301', 'PORTOVIEJO', NULL, NULL, NULL, NULL, 13),
(136, '1302', 'BOLÍVAR', NULL, NULL, NULL, NULL, 13),
(137, '1303', 'CHONE', NULL, NULL, NULL, NULL, 13),
(138, '1304', 'EL CARMEN', NULL, NULL, NULL, NULL, 13),
(139, '1305', 'FLAVIO ALFARO', NULL, NULL, NULL, NULL, 13),
(140, '1306', 'JIPIJAPA', NULL, NULL, NULL, NULL, 13),
(141, '1307', 'JUNÍN', NULL, NULL, NULL, NULL, 13),
(142, '1308', 'MANTA', NULL, NULL, NULL, NULL, 13),
(143, '1309', 'MONTECRISTI', NULL, NULL, NULL, NULL, 13),
(144, '1310', 'PAJÁN', NULL, NULL, NULL, NULL, 13),
(145, '1311', 'PICHINCHA', NULL, NULL, NULL, NULL, 13),
(146, '1312', 'ROCAFUERTE', NULL, NULL, NULL, NULL, 13),
(147, '1313', 'SANTA ANA', NULL, NULL, NULL, NULL, 13),
(148, '1314', 'SUCRE', NULL, NULL, NULL, NULL, 13),
(149, '1315', 'TOSAGUA', NULL, NULL, NULL, NULL, 13),
(150, '1316', '24 DE MAYO', NULL, NULL, NULL, NULL, 13),
(151, '1317', 'PEDERNALES', NULL, NULL, NULL, NULL, 13),
(152, '1318', 'OLMEDO', NULL, NULL, NULL, NULL, 13),
(153, '1319', 'PUERTO LÓPEZ', NULL, NULL, NULL, NULL, 13),
(154, '1320', 'JAMA', NULL, NULL, NULL, NULL, 13),
(155, '1321', 'JARAMIJÓ', NULL, NULL, NULL, NULL, 13),
(156, '1322', 'SAN VICENTE', NULL, NULL, NULL, NULL, 13),
(157, '1401', 'MORONA', NULL, NULL, NULL, NULL, 14),
(158, '1402', 'GUALAQUIZA', NULL, NULL, NULL, NULL, 14),
(159, '1403', 'LIMÓN INDANZA', NULL, NULL, NULL, NULL, 14),
(160, '1404', 'PALORA', NULL, NULL, NULL, NULL, 14),
(161, '1405', 'SANTIAGO', NULL, NULL, NULL, NULL, 14),
(162, '1406', 'SUCÚA', NULL, NULL, NULL, NULL, 14),
(163, '1407', 'HUAMBOYA', NULL, NULL, NULL, NULL, 14),
(164, '1408', 'SAN JUAN BOSCO', NULL, NULL, NULL, NULL, 14),
(165, '1409', 'TAISHA', NULL, NULL, NULL, NULL, 14),
(166, '1410', 'LOGROÑO', NULL, NULL, NULL, NULL, 14),
(167, '1411', 'PABLO SE TO', NULL, NULL, NULL, NULL, 14),
(168, '1412', 'TIWINTZA', NULL, NULL, NULL, NULL, 14),
(169, '1501', 'TENA', NULL, NULL, NULL, NULL, 15),
(170, '1503', 'ARCHIDONA', NULL, NULL, NULL, NULL, 15),
(171, '1504', 'EL CHACO', NULL, NULL, NULL, NULL, 15),
(172, '1507', 'QUIJOS', NULL, NULL, NULL, NULL, 15),
(173, '1509', 'CARLOS JULIO AROSEMENA TOLA', NULL, NULL, NULL, NULL, 15),
(174, '1601', 'PASTAZA  ', NULL, NULL, NULL, NULL, 16),
(175, '1602', 'MERA', NULL, NULL, NULL, NULL, 16),
(176, '1603', 'SANTA CLARA', NULL, NULL, NULL, NULL, 16),
(177, '1604', 'ARAJUNO', NULL, NULL, NULL, NULL, 16),
(178, '1701', 'QUITO', NULL, NULL, NULL, NULL, 17),
(179, '1702', 'CAYAMBE', NULL, NULL, NULL, NULL, 17),
(180, '1703', 'MEJIA', NULL, NULL, NULL, NULL, 17),
(181, '1704', 'PEDRO MONCAYO', NULL, NULL, NULL, NULL, 17),
(182, '1705', 'RUMIÑAHUI', NULL, NULL, NULL, NULL, 17),
(183, '1707', 'SAN MIGUEL DE LOS BANCOS', NULL, NULL, NULL, NULL, 17),
(184, '1708', 'PEDRO VICENTE MALDONADO', NULL, NULL, NULL, NULL, 17),
(185, '1709', 'PUERTO QUITO', NULL, NULL, NULL, NULL, 17),
(186, '1801', 'AMBATO', NULL, NULL, NULL, NULL, 18),
(187, '1802', 'BAÑOS DE AGUA SANTA', NULL, NULL, NULL, NULL, 18),
(188, '1803', 'CEVALLOS', NULL, NULL, NULL, NULL, 18),
(189, '1804', 'MOCHA', NULL, NULL, NULL, NULL, 18),
(190, '1805', 'PATATE', NULL, NULL, NULL, NULL, 18),
(191, '1806', 'QUERO', NULL, NULL, NULL, NULL, 18),
(192, '1807', 'SAN PEDRO DE PELILEO', NULL, NULL, NULL, NULL, 18),
(193, '1808', 'SANTIAGO DE PÍLLARO', NULL, NULL, NULL, NULL, 18),
(194, '1809', 'TISALEO', NULL, NULL, NULL, NULL, 18),
(195, '1901', 'ZAMORA', NULL, NULL, NULL, NULL, 19),
(196, '1902', 'CHINCHIPE', NULL, NULL, NULL, NULL, 19),
(197, '1903', 'NANGARITZA', NULL, NULL, NULL, NULL, 19),
(198, '1904', 'YACUAMBI', NULL, NULL, NULL, NULL, 19),
(199, '1905', 'YANTZAZA', NULL, NULL, NULL, NULL, 19),
(200, '1906', 'EL PANGUI', NULL, NULL, NULL, NULL, 19),
(201, '1907', 'CENTINELA DEL CÓNDOR', NULL, NULL, NULL, NULL, 19),
(202, '1908', 'PALANDA', NULL, NULL, NULL, NULL, 19),
(203, '1909', 'PAQUISHA', NULL, NULL, NULL, NULL, 19),
(204, '2001', 'SAN CRISTÓBAL', NULL, NULL, NULL, NULL, 20),
(205, '2002', 'ISABELA', NULL, NULL, NULL, NULL, 20),
(206, '2003', 'SANTA CRUZ', NULL, NULL, NULL, NULL, 20),
(207, '2101', 'LAGO AGRIO', NULL, NULL, NULL, NULL, 21),
(208, '2102', 'GONZALO PIZARRO', NULL, NULL, NULL, NULL, 21),
(209, '2103', 'PUTUMAYO', NULL, NULL, NULL, NULL, 21),
(210, '2104', 'SHUSHUFINDI', NULL, NULL, NULL, NULL, 21),
(211, '2105', 'SUCUMBÍOS  ', NULL, NULL, NULL, NULL, 21),
(212, '2106', 'CASCALES', NULL, NULL, NULL, NULL, 21),
(213, '2107', 'CUYABENO', NULL, NULL, NULL, NULL, 21),
(214, '2201', 'ORELLANA  ', NULL, NULL, NULL, NULL, 22),
(215, '2202', 'AGUARICO', NULL, NULL, NULL, NULL, 22),
(216, '2203', 'LA JOYA DE LOS SACHAS', NULL, NULL, NULL, NULL, 22),
(217, '2204', 'LORETO', NULL, NULL, NULL, NULL, 22),
(218, '2301', 'SANTO DOMINGO', NULL, NULL, NULL, NULL, 23),
(219, '2401', 'SANTA ELENA  ', NULL, NULL, NULL, NULL, 24),
(220, '2402', 'LA LIBERTAD', NULL, NULL, NULL, NULL, 24),
(221, '2403', 'SALINAS', NULL, NULL, NULL, NULL, 24),
(222, '9001', 'LAS GOLONDRINAS', NULL, NULL, NULL, NULL, 25),
(223, '9003', 'MANGA DEL CURA', NULL, NULL, NULL, NULL, 25),
(224, '9004', 'EL PIEDRERO', NULL, NULL, NULL, NULL, 25);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `id_cliente` int(11) NOT NULL,
  `codigo` varchar(45) DEFAULT NULL,
  `nombre` varchar(150) DEFAULT NULL,
  `identificacion` varchar(50) DEFAULT NULL,
  `direccion` varchar(150) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `contacto` varchar(150) DEFAULT NULL,
  `estado` varchar(150) DEFAULT NULL,
  `cuenta_contable` varchar(500) DEFAULT NULL,
  `comentario` varchar(250) DEFAULT NULL,
  `descuento` decimal(11,2) DEFAULT NULL,
  `num_pago` int(11) DEFAULT NULL,
  `tipo_identificacion` varchar(100) DEFAULT NULL,
  `codigo_pais` varchar(50) DEFAULT NULL,
  `grupo_tributario` varchar(100) DEFAULT NULL,
  `canton` varchar(100) DEFAULT NULL,
  `parroquia` varchar(100) DEFAULT NULL,
  `provincia` varchar(100) DEFAULT NULL,
  `parte_relacionada` varchar(45) DEFAULT NULL,
  `lista_precios` varchar(45) DEFAULT NULL,
  `limite_credito` varchar(45) DEFAULT NULL,
  `formas_pago` varchar(45) DEFAULT NULL,
  `fcrea` datetime DEFAULT NULL,
  `fmodifica` datetime DEFAULT NULL,
  `umodifica` int(11) DEFAULT NULL,
  `ucrea` int(11) DEFAULT NULL,
  `id_grupo_cliente` int(11) DEFAULT NULL,
  `id_empresa` int(11) DEFAULT NULL,
  `id_tipo_cliente` int(11) DEFAULT NULL,
  `id_vendedor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id_cliente`, `codigo`, `nombre`, `identificacion`, `direccion`, `email`, `telefono`, `contacto`, `estado`, `cuenta_contable`, `comentario`, `descuento`, `num_pago`, `tipo_identificacion`, `codigo_pais`, `grupo_tributario`, `canton`, `parroquia`, `provincia`, `parte_relacionada`, `lista_precios`, `limite_credito`, `formas_pago`, `fcrea`, `fmodifica`, `umodifica`, `ucrea`, `id_grupo_cliente`, `id_empresa`, `id_tipo_cliente`, `id_vendedor`) VALUES
(9, 'C-9', 'diana', '1723521215', 'UYIUI', 'fgg@gmail.com', '54656', '566', 'Cortante', '1.1.1.01.002', '45', '554.00', 455, 'Cédula de Identidad', 'EC', 'Persona Natural', '73', '501', '8', '1', '2', '4545', 'Cheque', '2019-12-13 18:21:34', '2019-12-17 16:34:47', NULL, NULL, NULL, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `codigo_pais`
--

CREATE TABLE `codigo_pais` (
  `id_codigo_pais` int(11) NOT NULL,
  `codigo_ISO_alpha_numerico` varchar(50) DEFAULT NULL,
  `codigo_ISO_alpha_2` varchar(50) DEFAULT NULL,
  `nombre_pais` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `codigo_pais`
--

INSERT INTO `codigo_pais` (`id_codigo_pais`, `codigo_ISO_alpha_numerico`, `codigo_ISO_alpha_2`, `nombre_pais`) VALUES
(1, '004', 'AF', 'Afganistán'),
(2, '248', 'AX', 'Åland'),
(3, '008', 'AL', 'Albania'),
(4, '276', 'DE', 'Alemania'),
(5, '020', 'AD', 'Andorra'),
(6, '024', 'AO', 'Angola'),
(7, '660', 'AI', 'Anguila'),
(8, '010', 'AQ', 'Antártida'),
(9, '028', 'AG', 'Antigua y Barbuda'),
(10, '530', 'AN', 'Antillas Neerlandesas'),
(11, '682', 'SA', 'Arabia Saudita'),
(12, '012', 'DZ', 'Argelia'),
(13, '032', 'AR', 'Argentina'),
(14, '051', 'AM', 'Armenia'),
(15, '533', 'AW', 'Aruba'),
(16, '036', 'AU', 'Australia'),
(17, '040', 'AT', 'Austria'),
(18, '031', 'AZ', 'Azerbaiyán'),
(19, '044', 'BS', 'Bahamas'),
(20, '048', 'BH', 'Bahréin'),
(21, '050', 'BD', 'Bangladesh'),
(22, '052', 'BB', 'Barbados'),
(23, '112', 'BY', 'Bielorrusia'),
(24, '056', 'BE', 'Bélgica'),
(25, '084', 'BZ', 'Belice'),
(26, '204', 'BJ', 'Benín'),
(27, '060', 'BM', 'Bermudas'),
(28, '064', 'BT', 'Bután'),
(29, '068', 'BO', 'Bolivia'),
(30, '070', 'BA', 'Bosnia y Herzegovina'),
(31, '072', 'BW', 'Botsuana'),
(32, '074', 'BV', 'Isla Bouvet'),
(33, '076', 'BR', 'Brasil'),
(34, '096', 'BN', 'Brunéi'),
(35, '100', 'BG', 'Bulgaria'),
(36, '854', 'BF', 'Burkina Faso'),
(37, '108', 'BI', 'Burundi'),
(38, '132', 'CV', 'Cabo Verde'),
(39, '136', 'KY', 'Islas Caimán'),
(40, '116', 'KH', 'Camboya'),
(41, '120', 'CM', 'Camerún'),
(42, '124', 'CA', 'Canadá'),
(43, '140', 'CF', 'República Centroafricana'),
(44, '148', 'TD', 'Chad'),
(45, '203', 'CZ', 'República Checa'),
(46, '152', 'CL', 'Chile'),
(47, '156', 'CN', 'China'),
(48, '196', 'CY', 'Chipre'),
(49, '166', 'CC', 'Islas Cocos'),
(50, '170', 'CO', 'Colombia'),
(51, '174', 'KM', 'Comoras'),
(52, '178', 'CG', 'República del Congo'),
(53, '180', 'CD', 'República Democrática del Congo'),
(54, '184', 'CK', 'Islas Cook'),
(55, '408', 'KP', 'Corea del Norte'),
(56, '410', 'KR', 'Corea del Sur'),
(57, '384', 'CI', 'Costa de Marfil'),
(58, '188', 'CR', 'Costa Rica'),
(59, '191', 'HR', 'Croacia'),
(60, '192', 'CU', 'Cuba'),
(61, '208', 'DK', 'Dinamarca'),
(62, '212', 'DM', 'Dominica'),
(63, '214', 'DO', 'República Dominicana'),
(64, '218', 'EC', 'Ecuador'),
(65, '818', 'EG', 'Egipto'),
(66, '222', 'SV', 'El Salvador'),
(67, '784', 'AE', 'Emiratos Árabes Unidos'),
(68, '232', 'ER', 'Eritrea'),
(69, '703', 'SK', 'Eslovaquia'),
(70, '705', 'SI', 'Eslovenia'),
(71, '724', 'ES', 'España'),
(72, '724', 'EA', 'España (Ceuta y Melilla)'),
(73, '840', 'US', 'Estados Unidos'),
(74, '581', 'UM', 'Islas ultramarinas de Estados Unidos'),
(75, '233', 'EE', 'Estonia'),
(76, '231', 'ET', 'Etiopía'),
(77, '234', 'FO', 'Islas Feroe'),
(78, '608', 'PH', 'Filipinas'),
(79, '246', 'FI', 'Finlandia'),
(80, '242', 'FJ', 'Fiyi'),
(81, '250', 'FR', 'Francia'),
(82, '266', 'GA', 'Gabón'),
(83, '270', 'GM', 'Gambia'),
(84, '268', 'GE', 'Georgia'),
(85, '239', 'GS', 'Islas Georgias del Sur y Sandwich del Sur'),
(86, '288', 'GH', 'Ghana'),
(87, '292', 'GI', 'Gibraltar'),
(88, '308', 'GD', 'Granada'),
(89, '300', 'GR', 'Grecia'),
(90, '304', 'GL', 'Groenlandia'),
(91, '312', 'GP', 'Guadalupe'),
(92, '316', 'GU', 'Guam'),
(93, '320', 'GT', 'Guatemala'),
(94, '254', 'GF', 'Guayana Francesa'),
(95, '831', 'GG', 'Guernsey'),
(96, '324', 'GN', 'Guinea'),
(97, '226', 'GQ', 'Guinea Ecuatorial'),
(98, '624', 'GW', 'Guinea-Bissau'),
(99, '328', 'GY', 'Guyana'),
(100, '332', 'HT', 'Haití'),
(101, '334', 'HM', 'Islas Heard y McDonald'),
(102, '340', 'HN', 'Honduras'),
(103, '344', 'HK', 'Hong Kong'),
(104, '348', 'HU', 'Hungría I'),
(105, '356', 'IN', 'India'),
(106, '360', 'ID', 'Indonesia'),
(107, '364', 'IR', 'Irán'),
(108, '368', 'IQ', 'Iraq'),
(109, '372', 'IE', 'Irlanda'),
(110, '352', 'IS', 'Islandia'),
(111, '376', 'IL', 'Israel'),
(112, '380', 'IT', 'Italia J'),
(113, '388', 'JM', 'Jamaica'),
(114, '392', 'JP', 'Japón'),
(115, '832', 'JE', 'Jersey'),
(116, '400', 'JO', 'Jordania K'),
(117, '398', 'KZ', 'Kazajistán'),
(118, '404', 'KE', 'Kenia'),
(119, '417', 'KG', 'Kirguistán'),
(120, '296', 'KI', 'Kiribati'),
(121, '414', 'KW', 'Kuwait L'),
(122, '418', 'LA', 'Laos'),
(123, '426', 'LS', 'Lesoto'),
(124, '428', 'LV', 'Letonia'),
(125, '422', 'LB', 'Líbano'),
(126, '430', 'LR', 'Liberia'),
(127, '434', 'LY', 'Libia'),
(128, '438', 'LI', 'Liechtenstein'),
(129, '440', 'LT', 'Lituania'),
(130, '442', 'LU', 'Luxemburgo M'),
(131, '446', 'MO', 'Macao'),
(132, '807', 'MK', 'ARY Macedonia'),
(133, '450', 'MG', 'Madagascar'),
(134, '458', 'MY', 'Malasia'),
(135, '454', 'MW', 'Malawi'),
(136, '462', 'MV', 'Maldivas'),
(137, '466', 'ML', 'Malí'),
(138, '470', 'MT', 'Malta'),
(139, '238', 'FK', 'Islas Malvinas'),
(140, '833', 'IM', 'Isla de Man'),
(141, '580', 'MP', 'Islas Marianas del Norte'),
(142, '504', 'MA', 'Marruecos'),
(143, '584', 'MH', 'Islas Marshall'),
(144, '474', 'MQ', 'Martinica'),
(145, '480', 'MU', 'Mauricio'),
(146, '478', 'MR', 'Mauritania'),
(147, '175', 'YT', 'Mayotte'),
(148, '484', 'MX', 'México'),
(149, '583', 'FM', 'Micronesia'),
(150, '498', 'MD', 'Moldavia'),
(151, '492', 'MC', 'Mónaco'),
(152, '496', 'MN', 'Mongolia'),
(153, '499', 'ME', 'Montenegro'),
(154, '500', 'MS', 'Montserrat'),
(155, '508', 'MZ', 'Mozambique'),
(156, '104', 'MM', 'Myanmar'),
(157, '516', 'NA', 'Namibia'),
(158, '520', 'NR', 'Nauru'),
(159, '162', 'CX', 'Isla de Navidad'),
(160, '524', 'NP', 'Nepal'),
(161, '558', 'NI', 'Nicaragua'),
(162, '562', 'NE', 'Níger'),
(163, '566', 'NG', 'Nigeria'),
(164, '570', 'NU', 'Niue'),
(165, '574', 'NF', 'Norfolk'),
(166, '578', 'NO', 'Noruega'),
(167, '540', 'NC', 'Nueva Caledonia'),
(168, '554', 'NZ', 'Nueva Zelanda'),
(169, '512', 'OM', 'Omán'),
(170, '528', 'NL', 'Países Bajos'),
(171, '586', 'PK', 'Pakistán'),
(172, '585', 'PW', 'Palaos'),
(173, '275', 'PS', 'Palestina (ANP)'),
(174, '591', 'PA', 'Panamá'),
(175, '598', 'PG', 'Papúa Nueva Guinea'),
(176, '600', 'PY', 'Paraguay'),
(177, '604', 'PE', 'Perú'),
(178, '612', 'PN', 'Islas Pitcairn'),
(179, '258', 'PF', 'Polinesia Francesa'),
(180, '616', 'PL', 'Polonia'),
(181, '620', 'PT', 'Portugal'),
(182, '630', 'PR', 'Puerto Rico'),
(183, '634', 'QA', 'Qatar'),
(184, '826', 'GB', 'Reino Unido'),
(185, '638', 'RE', 'Reunión'),
(186, '646', 'RW', 'Ruanda'),
(187, '642', 'RO', 'Rumania'),
(188, '643', 'RU', 'Rusia'),
(189, '732', 'EH', 'Sahara Occidental'),
(190, '090', 'SB', 'Islas Salomón'),
(191, '882', 'WS', 'Samoa'),
(192, '016', 'AS', 'Samoa Americana'),
(193, '659', 'KN', 'San Cristóbal y Nieves'),
(194, '674', 'SM', 'San Marino'),
(195, '666', 'PM', 'San Pedro y Miquelón'),
(196, '670', 'VC', 'San Vicente y las Granadinas'),
(197, '654', 'SH', 'Santa Helena'),
(198, '662', 'LC', 'Santa Lucía'),
(199, '678', 'ST', 'Santo Tomé y Príncipe'),
(200, '686', 'SN', 'Senegal'),
(201, '688', 'RS', 'Serbia'),
(202, '690', 'SC', 'Seychelles'),
(203, '694', 'SL', 'Sierra Leona'),
(204, '702', 'SG', 'Singapur'),
(205, '760', 'SY', 'Siria'),
(206, '706', 'SO', 'Somalia'),
(207, '144', 'LK', 'Sri Lanka'),
(208, '748', 'SZ', 'Suazilandia'),
(209, '710', 'ZA', 'Sudáfrica'),
(210, '736', 'SD', 'Sudán'),
(211, '752', 'SE', 'Suecia'),
(212, '756', 'CH', 'Suiza'),
(213, '740', 'SR', 'Surinam'),
(214, '744', 'SJ', 'Svalbard y Jan Mayen'),
(215, '764', 'TH', 'Tailandia'),
(216, '158', 'TW', 'Taiwán'),
(217, '834', 'TZ', 'Tanzania'),
(218, '762', 'TJ', 'Tayikistán'),
(219, '086', 'IO', 'Territorio Británico del Océano Índico'),
(220, '260', 'TF', 'Territorios Australes Franceses'),
(221, '626', 'TL', 'Timor Oriental'),
(222, '768', 'TG', 'Togo'),
(223, '772', 'TK', 'Tokelau'),
(224, '776', 'TO', 'Tonga'),
(225, '780', 'TT', 'Trinidad y Tobago'),
(226, '788', 'TN', 'Túnez'),
(227, '796', 'TC', 'Islas Turcas y Caicos'),
(228, '795', 'TM', 'Turkmenistán'),
(229, '792', 'TR', 'Turquía'),
(230, '798', 'TV', 'Tuvalu'),
(231, '804', 'UA', 'Ucrania'),
(232, '800', 'UG', 'Uganda'),
(233, '858', 'UY', 'Uruguay'),
(234, '860', 'UZ', 'Uzbekistán'),
(235, '548', 'VU', 'Vanuatu'),
(236, '336', 'VA', 'Ciudad del Vaticano'),
(237, '862', 'VE', 'Venezuela'),
(238, '704', 'VN', 'Vietnam'),
(239, '092', 'VG', 'Islas Vírgenes Británicas'),
(240, '850', 'VI', 'Islas Vírgenes Estadounidenses'),
(241, '876', 'WF', 'Wallis y Futuna'),
(242, '887', 'YE', 'Yemen'),
(243, '262', 'DJ', 'Yibuti'),
(244, '894', 'ZM', 'Zambia'),
(245, '716', 'ZW', 'Zimbabue');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `credito_compras`
--

CREATE TABLE `credito_compras` (
  `id_credcompra` int(11) NOT NULL,
  `periodo` varchar(50) DEFAULT NULL,
  `plazo` varchar(80) DEFAULT NULL,
  `plazos_pagado` decimal(11,2) DEFAULT NULL,
  `monto` decimal(11,2) DEFAULT NULL,
  `letras` decimal(11,2) DEFAULT NULL,
  `fcrea` datetime DEFAULT current_timestamp(),
  `fmodifica` datetime DEFAULT NULL,
  `id_factcompra` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `credito_compras`
--

INSERT INTO `credito_compras` (`id_credcompra`, `periodo`, `plazo`, `plazos_pagado`, `monto`, `letras`, `fcrea`, `fmodifica`, `id_factcompra`) VALUES
(5, 'Meses', '12', '2.20', '17.80', '1.48', '2020-01-03 20:29:38', '2020-01-03 20:29:38', 10),
(6, NULL, NULL, '2.20', NULL, NULL, '2020-01-06 11:01:48', '2020-01-06 11:01:48', 13);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ctas_cobrar`
--

CREATE TABLE `ctas_cobrar` (
  `id_ctascobrar` int(11) NOT NULL,
  `unidad_tiempo` varchar(50) DEFAULT NULL,
  `nro_cuota` int(11) DEFAULT NULL,
  `cuotas_totales` int(11) DEFAULT NULL,
  `forma_pago` varchar(80) DEFAULT NULL,
  `banco` varchar(100) DEFAULT NULL,
  `nro_tarjeta` int(11) DEFAULT NULL,
  `cta_contable` int(11) DEFAULT NULL,
  `monto` decimal(11,2) DEFAULT NULL,
  `abono` decimal(11,2) DEFAULT NULL,
  `saldo` decimal(11,2) DEFAULT NULL,
  `descuento` int(11) DEFAULT NULL,
  `estado` varchar(15) DEFAULT NULL,
  `tipo` int(11) DEFAULT NULL,
  `comentario` varchar(300) DEFAULT NULL,
  `fcrea` datetime DEFAULT NULL,
  `fmodifica` datetime DEFAULT NULL,
  `freguistro` date DEFAULT NULL,
  `fvencimiento` date DEFAULT NULL,
  `ucrea` int(11) DEFAULT NULL,
  `umodifica` int(11) DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `id_factura` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `ctas_cobrar`
--

INSERT INTO `ctas_cobrar` (`id_ctascobrar`, `unidad_tiempo`, `nro_cuota`, `cuotas_totales`, `forma_pago`, `banco`, `nro_tarjeta`, `cta_contable`, `monto`, `abono`, `saldo`, `descuento`, `estado`, `tipo`, `comentario`, `fcrea`, `fmodifica`, `freguistro`, `fvencimiento`, `ucrea`, `umodifica`, `id_cliente`, `id_factura`) VALUES
(4, '0012', NULL, 0, '1', NULL, NULL, NULL, '20.00', '5.00', '25.00', NULL, '1', NULL, NULL, '2019-12-20 00:00:00', '2019-12-20 00:00:00', '2019-12-06', '2019-12-28', NULL, NULL, 13, NULL),
(5, '3', 1, 2, 'Crédito', NULL, NULL, NULL, '162.00', '0.00', '162.00', NULL, '1', NULL, NULL, '2019-12-31 00:00:00', '2019-12-31 00:00:00', '2019-12-31', NULL, NULL, NULL, NULL, 3),
(6, '4', 1, NULL, 'Crédito', NULL, NULL, NULL, NULL, '0.00', NULL, NULL, '1', NULL, NULL, '2019-12-31 00:00:00', '2019-12-31 00:00:00', '2019-12-31', NULL, NULL, NULL, NULL, 4),
(7, '5', 1, NULL, 'Crédito', NULL, NULL, NULL, NULL, '0.00', NULL, NULL, '1', NULL, NULL, '2019-12-31 00:00:00', '2019-12-31 00:00:00', '2019-12-31', NULL, NULL, NULL, NULL, 5),
(8, '6', 1, NULL, 'Crédito', NULL, NULL, NULL, NULL, '0.00', NULL, NULL, '1', NULL, NULL, '2019-12-31 00:00:00', '2019-12-31 00:00:00', '2019-12-31', NULL, NULL, NULL, NULL, 6),
(9, '7', 1, NULL, 'Crédito', NULL, NULL, NULL, NULL, '0.00', NULL, NULL, '1', NULL, NULL, '2019-12-31 00:00:00', '2019-12-31 00:00:00', '2019-12-31', NULL, NULL, NULL, NULL, 7),
(10, '8', 1, NULL, 'Crédito', NULL, NULL, NULL, NULL, '0.00', NULL, NULL, '1', NULL, NULL, '2019-12-31 00:00:00', '2019-12-31 00:00:00', '2019-12-31', NULL, NULL, NULL, NULL, 8),
(11, '10', 1, 3, 'Crédito', NULL, NULL, NULL, '726.80', '0.00', '726.80', NULL, '1', NULL, NULL, '2020-01-02 00:00:00', '2020-01-02 00:00:00', '2020-01-02', NULL, NULL, NULL, NULL, 10),
(12, '14', 1, 2, 'Crédito', NULL, NULL, NULL, '100.00', '0.00', '100.00', NULL, '1', NULL, NULL, '2020-01-02 00:00:00', '2020-01-02 00:00:00', '2020-01-02', NULL, NULL, NULL, NULL, 14),
(13, 'Dias', 1, 24, 'Crédito', NULL, NULL, NULL, '50.00', '0.00', '50.00', NULL, '1', NULL, NULL, '2020-01-02 00:00:00', '2020-01-02 00:00:00', '2020-01-02', NULL, NULL, NULL, NULL, 15),
(14, 'Meses', 1, 3, 'Crédito', NULL, NULL, NULL, '200.00', '0.00', '200.00', NULL, '1', NULL, NULL, '2020-01-02 00:00:00', '2020-01-02 00:00:00', '2020-01-02', NULL, NULL, NULL, NULL, 16),
(15, NULL, 1, NULL, 'Crédito', NULL, NULL, NULL, NULL, '0.00', NULL, NULL, '1', NULL, NULL, '2020-01-03 00:00:00', '2020-01-03 00:00:00', '2020-01-03', NULL, NULL, NULL, NULL, 17),
(16, NULL, 1, NULL, 'Crédito', NULL, NULL, NULL, NULL, '0.00', NULL, NULL, '1', NULL, NULL, '2020-01-03 00:00:00', '2020-01-03 00:00:00', '2020-01-03', NULL, NULL, NULL, NULL, 18),
(17, NULL, 1, NULL, 'Crédito', NULL, NULL, NULL, NULL, '0.00', NULL, NULL, '1', NULL, NULL, '2020-01-03 00:00:00', '2020-01-03 00:00:00', '2020-01-03', NULL, NULL, NULL, NULL, 19),
(18, NULL, 1, NULL, 'Crédito', NULL, NULL, NULL, NULL, '0.00', NULL, NULL, '1', NULL, NULL, '2020-01-03 00:00:00', '2020-01-03 00:00:00', '2020-01-03', NULL, NULL, NULL, NULL, 20),
(19, NULL, 1, NULL, 'Crédito', NULL, NULL, NULL, NULL, '0.00', NULL, NULL, '1', NULL, NULL, '2020-01-03 00:00:00', '2020-01-03 00:00:00', '2020-01-03', NULL, NULL, NULL, NULL, 21),
(20, NULL, 1, NULL, 'Crédito', NULL, NULL, NULL, NULL, '0.00', NULL, NULL, '1', NULL, NULL, '2020-01-03 00:00:00', '2020-01-03 00:00:00', '2020-01-03', NULL, NULL, NULL, NULL, 22),
(21, NULL, 1, NULL, 'Crédito', NULL, NULL, NULL, NULL, '0.00', NULL, NULL, '1', NULL, NULL, '2020-01-03 00:00:00', '2020-01-03 00:00:00', '2020-01-03', NULL, NULL, NULL, NULL, 23),
(22, NULL, 1, NULL, 'Crédito', NULL, NULL, NULL, NULL, '0.00', NULL, NULL, '1', NULL, NULL, '2020-01-03 00:00:00', '2020-01-03 00:00:00', '2020-01-03', NULL, NULL, NULL, NULL, 24),
(23, NULL, 1, NULL, 'Crédito', NULL, NULL, NULL, NULL, '0.00', NULL, NULL, '1', NULL, NULL, '2020-01-03 00:00:00', '2020-01-03 00:00:00', '2020-01-03', NULL, NULL, NULL, NULL, 25),
(24, NULL, 1, NULL, 'Crédito', NULL, NULL, NULL, NULL, '0.00', NULL, NULL, '1', NULL, NULL, '2020-01-03 00:00:00', '2020-01-03 00:00:00', '2020-01-03', NULL, NULL, NULL, NULL, 26),
(25, NULL, 1, NULL, 'Crédito', NULL, NULL, NULL, NULL, '0.00', NULL, NULL, '1', NULL, NULL, '2020-01-03 00:00:00', '2020-01-03 00:00:00', '2020-01-03', NULL, NULL, NULL, NULL, 30),
(26, NULL, 1, NULL, 'Crédito', NULL, NULL, NULL, NULL, '0.00', NULL, NULL, '1', NULL, NULL, '2020-01-03 00:00:00', '2020-01-03 00:00:00', '2020-01-03', NULL, NULL, NULL, NULL, 32),
(27, NULL, 1, NULL, 'Crédito', NULL, NULL, NULL, NULL, '0.00', NULL, NULL, '1', NULL, NULL, '2020-01-03 00:00:00', '2020-01-03 00:00:00', '2020-01-03', NULL, NULL, NULL, NULL, 33),
(28, 'Meses', 1, 5, 'Crédito', NULL, NULL, NULL, '13.00', '0.00', '13.00', NULL, '1', NULL, NULL, '2020-01-03 00:00:00', '2020-01-03 00:00:00', '2020-01-03', NULL, NULL, NULL, NULL, 34),
(29, NULL, 1, NULL, 'Crédito', NULL, NULL, NULL, NULL, '0.00', NULL, NULL, '1', NULL, NULL, '2020-01-03 00:00:00', '2020-01-03 00:00:00', '2020-01-03', NULL, NULL, NULL, NULL, 35),
(30, 'Dias', 1, 1, 'Efectivo', NULL, NULL, NULL, '0.06', '0.00', '0.00', NULL, '1', NULL, 'ejemplo', '2020-01-03 00:00:00', '2020-01-03 00:00:00', '2020-01-03', NULL, NULL, NULL, NULL, 36),
(31, 'Meses', 1, 2, 'Crédito', NULL, NULL, NULL, '0.25', '0.00', '0.25', NULL, '1', NULL, NULL, '2020-01-03 00:00:00', '2020-01-03 00:00:00', '2020-01-03', NULL, NULL, NULL, NULL, 36),
(32, 'Dias', 1, 1, 'Efectivo', NULL, NULL, NULL, '0.06', '0.00', '0.00', NULL, '1', NULL, 'ejemplo', '2020-01-03 00:00:00', '2020-01-03 00:00:00', '2020-01-03', NULL, NULL, NULL, NULL, 37),
(33, 'Meses', 1, 2, 'Crédito', NULL, NULL, NULL, '0.50', '0.00', '0.50', NULL, '1', NULL, NULL, '2020-01-03 00:00:00', '2020-01-03 00:00:00', '2020-01-03', NULL, NULL, NULL, NULL, 37),
(34, 'Dias', 1, 1, 'Cheque', NULL, NULL, NULL, '0.06', '0.00', '0.00', NULL, '1', NULL, 'ejemplo1', '2020-01-03 00:00:00', '2020-01-03 00:00:00', '2020-01-03', NULL, NULL, NULL, NULL, 38),
(35, 'Meses', 1, 2, 'Crédito', NULL, NULL, NULL, '0.50', '0.00', '0.50', NULL, '1', NULL, NULL, '2020-01-03 00:00:00', '2020-01-03 00:00:00', '2020-01-03', NULL, NULL, NULL, NULL, 38),
(36, 'Dias', 1, 1, 'Deposito', NULL, NULL, NULL, '0.06', '0.00', '0.00', NULL, '1', NULL, 'ejemplo', '2020-01-03 00:00:00', '2020-01-03 00:00:00', '2020-01-03', NULL, NULL, NULL, NULL, 39),
(37, 'Dias', 1, 2, 'Crédito', NULL, NULL, NULL, '0.50', '0.00', '0.50', NULL, '1', NULL, NULL, '2020-01-03 00:00:00', '2020-01-03 00:00:00', '2020-01-03', NULL, NULL, NULL, NULL, 39),
(38, 'Dias', 1, 2, 'Crédito', NULL, NULL, NULL, '0.56', '0.00', '0.56', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 40),
(39, 'Dias', 1, 2, 'Crédito', NULL, NULL, NULL, '0.56', '0.00', '0.56', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 41),
(40, 'Dias', 1, 2, 'Crédito', NULL, NULL, NULL, '0.56', '0.00', '0.56', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 42),
(41, 'Dias', 1, 2, 'Crédito', NULL, NULL, NULL, '0.56', '0.00', '0.56', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 43),
(42, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '0.56', '0.00', '0.56', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 44),
(43, 'Dias', 1, 1, 'Deposito', NULL, NULL, NULL, '0.06', '0.00', '0.00', NULL, '1', NULL, 'ejemplo3', '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 45),
(44, 'Dias', 1, 2, 'Crédito', NULL, NULL, NULL, '1.00', '0.00', '1.00', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 45),
(45, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '0.56', '0.00', '0.56', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 46),
(46, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '0.56', '0.00', '0.56', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 47),
(47, 'Dias', 1, 2, 'Crédito', NULL, NULL, NULL, '0.56', '0.00', '0.56', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 48),
(48, 'Dias', 1, 2, 'Crédito', NULL, NULL, NULL, '0.56', '0.00', '0.56', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 49),
(49, 'Dias', 1, 2, 'Crédito', NULL, NULL, NULL, '0.56', '0.00', '0.56', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 50),
(50, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '0.56', '0.00', '0.56', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 51),
(51, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '0.56', '0.00', '0.56', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 52),
(52, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '0.56', '0.00', '0.56', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 53),
(53, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '0.56', '0.00', '0.56', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 54),
(54, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '0.56', '0.00', '0.56', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 55),
(55, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '0.56', '0.00', '0.56', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 56),
(56, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '0.56', '0.00', '0.56', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 57),
(57, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '0.56', '0.00', '0.56', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 58),
(58, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '0.56', '0.00', '0.56', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 59),
(59, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '0.56', '0.00', '0.56', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 60),
(60, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '0.56', '0.00', '0.56', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 61),
(61, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '0.56', '0.00', '0.56', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 62),
(62, 'Dias', 1, 1, 'Crédito', NULL, NULL, NULL, '0.56', '0.00', '0.56', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 63),
(63, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '0.56', '0.00', '0.56', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 64),
(64, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '1.00', '0.00', '1.00', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 65),
(65, 'Meses', 1, 1, 'Crédito', NULL, NULL, NULL, '10.00', '0.00', '10.00', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 66),
(66, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '1.12', '0.00', '1.12', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 67),
(67, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '1.12', '0.00', '1.12', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 68),
(68, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '1.12', '0.00', '1.12', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 69),
(69, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '1.12', '0.00', '1.12', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 70),
(70, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '1.12', '0.00', '1.12', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 71),
(71, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '1.12', '0.00', '1.12', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 72),
(72, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '1.12', '0.00', '1.12', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 73),
(73, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '1.12', '0.00', '1.12', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 74),
(74, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '1.12', '0.00', '1.12', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 75),
(75, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '1.00', '0.00', '1.00', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 77),
(76, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '1.12', '0.00', '1.12', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 78),
(77, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '1.12', '0.00', '1.12', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 79),
(78, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '1.12', '0.00', '1.12', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 80),
(79, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '1.12', '0.00', '1.12', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 81),
(80, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '1.12', '0.00', '1.12', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 82),
(81, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '1.12', '0.00', '1.12', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 83),
(82, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '1.12', '0.00', '1.12', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 84),
(83, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '1.12', '0.00', '1.12', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 85),
(84, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '1.12', '0.00', '1.12', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 86),
(85, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '1.12', '0.00', '1.12', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 87),
(86, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '1.12', '0.00', '1.12', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 88),
(87, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '1.12', '0.00', '1.12', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 89),
(88, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '1.12', '0.00', '1.12', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 90),
(89, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '1.12', '0.00', '1.12', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 91),
(90, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '1.12', '0.00', '1.12', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 92),
(91, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '1.12', '0.00', '1.12', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 93),
(92, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '1.12', '0.00', '1.12', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 94),
(93, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '1.12', '0.00', '1.12', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 95),
(94, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '1.12', '0.00', '1.12', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 96),
(95, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '1.12', '0.00', '1.12', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 97),
(96, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '1.12', '0.00', '1.12', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 98),
(97, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '1.12', '0.00', '1.12', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 99),
(98, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '1.12', '0.00', '1.12', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 100),
(99, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '1.12', '0.00', '1.12', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 101),
(100, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '1.12', '0.00', '1.12', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 102),
(101, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '1.62', '0.00', '1.62', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 103),
(102, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '1.62', '0.00', '1.62', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 104),
(103, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '1.62', '0.00', '1.62', NULL, '1', NULL, NULL, '2020-01-04 00:00:00', '2020-01-04 00:00:00', '2020-01-04', NULL, NULL, NULL, NULL, 105),
(104, 'Dias', 1, 1, 'Deposito', 'pichincha', NULL, NULL, '0.12', '0.00', '0.00', NULL, '1', 2, 'ejemplo ff', '2020-01-06 00:00:00', '2020-01-06 00:00:00', '2020-01-06', NULL, NULL, NULL, NULL, 110),
(105, 'Dias', 1, 2, 'Crédito', NULL, NULL, NULL, '1.00', '0.00', '1.00', NULL, '1', 1, NULL, '2020-01-06 00:00:00', '2020-01-06 00:00:00', '2020-01-06', NULL, NULL, NULL, NULL, 110),
(106, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '1.12', '0.00', '1.12', NULL, '1', 1, NULL, '2020-01-06 00:00:00', '2020-01-06 00:00:00', '2020-01-06', NULL, NULL, NULL, NULL, 111),
(107, 'Dias', 1, 1, 'Efectivo', NULL, NULL, NULL, '250.00', '0.00', '0.00', NULL, '1', 2, NULL, '2020-01-07 00:00:00', '2020-01-07 00:00:00', '2020-01-07', NULL, NULL, NULL, NULL, 112),
(108, 'Dias', 1, 2, 'Crédito', NULL, NULL, NULL, '368.24', '0.00', '368.24', NULL, '1', 1, NULL, '2020-01-07 00:00:00', '2020-01-07 00:00:00', '2020-01-07', NULL, NULL, NULL, NULL, 112),
(109, 'Dias', 1, 1, 'Efectivo', NULL, NULL, NULL, '250.00', '0.00', '0.00', NULL, '1', 2, NULL, '2020-01-07 00:00:00', '2020-01-07 00:00:00', '2020-01-07', NULL, NULL, NULL, NULL, 113),
(110, 'Dias', 1, 2, 'Crédito', NULL, NULL, NULL, '368.24', '0.00', '368.24', NULL, '1', 1, NULL, '2020-01-07 00:00:00', '2020-01-07 00:00:00', '2020-01-07', NULL, NULL, NULL, NULL, 113),
(111, 'Dias', 1, 1, 'Efectivo', NULL, NULL, NULL, '250.00', '0.00', '0.00', NULL, '1', 2, NULL, '2020-01-07 00:00:00', '2020-01-07 00:00:00', '2020-01-07', NULL, NULL, NULL, NULL, 114),
(112, 'Dias', 1, 2, 'Crédito', NULL, NULL, NULL, '368.24', '0.00', '368.24', NULL, '1', 1, NULL, '2020-01-07 00:00:00', '2020-01-07 00:00:00', '2020-01-07', NULL, NULL, NULL, NULL, 114),
(113, 'Dias', 1, 1, 'Efectivo', NULL, NULL, NULL, '250.00', '0.00', '0.00', NULL, '1', 2, NULL, '2020-01-07 00:00:00', '2020-01-07 00:00:00', '2020-01-07', NULL, NULL, NULL, NULL, 115),
(114, 'Dias', 1, 2, 'Crédito', NULL, NULL, NULL, '368.24', '0.00', '368.24', NULL, '1', 1, NULL, '2020-01-07 00:00:00', '2020-01-07 00:00:00', '2020-01-07', NULL, NULL, NULL, NULL, 115),
(115, 'Dias', 1, 1, 'Deposito', 'PICHINCHA', NULL, NULL, '300.00', '0.00', '0.00', NULL, '1', 2, 'EJEMPLO P', '2020-01-07 00:00:00', '2020-01-07 00:00:00', '2020-01-07', NULL, NULL, NULL, NULL, 116),
(116, 'Dias', 1, 3, 'Crédito', NULL, NULL, NULL, '332.00', '0.00', '332.00', NULL, '1', 1, NULL, '2020-01-07 00:00:00', '2020-01-07 00:00:00', '2020-01-07', NULL, NULL, NULL, NULL, 116),
(117, 'Dias', 1, 1, 'Efectivo', NULL, NULL, NULL, '0.50', '0.00', '0.00', NULL, '1', 2, 'ejemplo final', '2020-01-07 00:00:00', '2020-01-07 00:00:00', '2020-01-07', NULL, NULL, NULL, NULL, 117),
(118, 'Dias', 1, 1, 'Crédito', NULL, NULL, NULL, '0.50', '0.00', '0.50', NULL, '1', 1, NULL, '2020-01-07 00:00:00', '2020-01-07 00:00:00', '2020-01-07', NULL, NULL, NULL, NULL, 117),
(119, 'Dias', 1, 1, 'Efectivo', NULL, NULL, NULL, '400.00', '0.00', '0.00', NULL, '1', 2, 'banco final', '2020-01-07 00:00:00', '2020-01-07 00:00:00', '2020-01-07', NULL, NULL, NULL, NULL, 118),
(120, 'Dias', 1, 4, 'Crédito', NULL, NULL, NULL, '218.24', '0.00', '218.24', NULL, '1', 1, NULL, '2020-01-07 00:00:00', '2020-01-07 00:00:00', '2020-01-07', NULL, NULL, NULL, NULL, 118);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ctas_pagar`
--

CREATE TABLE `ctas_pagar` (
  `id_ctaspagar` int(11) NOT NULL,
  `unidad_tiempo` varchar(50) DEFAULT NULL,
  `nro_cuota` int(11) DEFAULT NULL,
  `cuotas_totales` int(11) DEFAULT NULL,
  `forma_pago` varchar(80) DEFAULT NULL,
  `banco` varchar(100) DEFAULT NULL,
  `nro_tarjeta` int(11) DEFAULT NULL,
  `cta_contable` int(11) DEFAULT NULL,
  `nro_trans` varchar(50) DEFAULT NULL,
  `monto` decimal(11,2) DEFAULT NULL,
  `abono` decimal(11,2) DEFAULT NULL,
  `saldo` decimal(11,2) DEFAULT NULL,
  `descuento` int(11) DEFAULT NULL,
  `estado` varchar(15) DEFAULT NULL,
  `tipo` int(11) DEFAULT NULL,
  `fcrea` datetime DEFAULT NULL,
  `fmodifica` datetime DEFAULT NULL,
  `freguistro` date DEFAULT NULL,
  `fvencimiento` date DEFAULT NULL,
  `ucrea` int(11) DEFAULT NULL,
  `umodifica` int(11) DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `id_factura` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamento`
--

CREATE TABLE `departamento` (
  `id_departamento` int(11) NOT NULL,
  `dep_nombre` varchar(100) DEFAULT NULL,
  `dep_estado` varchar(15) DEFAULT NULL,
  `fcrea` date DEFAULT NULL,
  `fmodifica` date DEFAULT NULL,
  `ucrea` int(11) DEFAULT NULL,
  `umodifica` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `departamento`
--

INSERT INTO `departamento` (`id_departamento`, `dep_nombre`, `dep_estado`, `fcrea`, `fmodifica`, `ucrea`, `umodifica`) VALUES
(1, 'Recursos Humanos', '1', NULL, NULL, NULL, NULL),
(2, 'Sistemas', '1', NULL, NULL, NULL, NULL),
(3, 'Financiero', '1', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle`
--

CREATE TABLE `detalle` (
  `id_detalle` int(11) NOT NULL,
  `nombre` varchar(300) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `descuento` decimal(10,2) DEFAULT NULL,
  `%descuento` tinyint(1) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `iva` int(11) DEFAULT NULL,
  `ice` int(11) DEFAULT NULL,
  `irbpnr` decimal(10,2) DEFAULT NULL,
  `fcrea` datetime NOT NULL,
  `fmodifica` datetime NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_factura` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `detalle`
--

INSERT INTO `detalle` (`id_detalle`, `nombre`, `cantidad`, `precio`, `descuento`, `%descuento`, `total`, `iva`, `ice`, `irbpnr`, `fcrea`, `fmodifica`, `id_producto`, `id_factura`) VALUES
(1, '1', 11, '1.00', '1.00', NULL, '11.20', 2, 1, '1.00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 42, 1),
(3, 'producto1', 21, '13.00', '2.00', NULL, '271.00', 3, 2, NULL, '2019-12-31 01:06:41', '2019-12-31 01:06:41', 42, 3),
(8, 'a', 10, '10.00', NULL, NULL, '100.00', 4, 6, NULL, '2019-12-31 17:09:09', '2019-12-31 17:09:09', 44, 8),
(9, 'a', 10, '10.00', NULL, NULL, '100.00', 4, 6, NULL, '2020-01-02 11:57:39', '2020-01-02 11:57:39', 44, 9),
(10, 'producto1', 20, '15.00', '10.00', NULL, '290.00', 3, 2, NULL, '2020-01-02 14:51:48', '2020-01-02 14:51:48', 42, 10),
(12, 'producto2', 10, '10.00', '10.00', NULL, '90.00', 2, 1, NULL, '2020-01-02 15:04:00', '2020-01-02 15:04:00', 43, 11),
(13, 'producto1', 10, '10.00', '10.00', NULL, '90.00', 3, 2, NULL, '2020-01-02 15:04:00', '2020-01-02 15:04:00', 42, 11),
(14, 'producto1', 10, '13.00', '1.00', NULL, '129.00', 3, 2, NULL, '2020-01-02 17:27:27', '2020-01-02 17:27:27', 42, 12),
(15, 'producto1', 10, '13.00', '1.00', NULL, '129.00', 3, 2, NULL, '2020-01-02 17:27:40', '2020-01-02 17:27:40', 42, 13),
(16, 'producto1', 10, '13.00', '1.00', NULL, '129.00', 3, 2, NULL, '2020-01-02 17:28:54', '2020-01-02 17:28:54', 42, 14),
(17, 'producto2', 10, '10.00', '10.00', NULL, '90.00', 2, 1, NULL, '2020-01-02 17:37:42', '2020-01-02 17:37:42', 43, 11),
(18, 'producto1', 10, '10.00', '10.00', NULL, '90.00', 3, 2, NULL, '2020-01-02 17:37:42', '2020-01-02 17:37:42', 42, 11),
(19, 'producto2', 10, '10.00', NULL, NULL, '100.00', 2, 1, NULL, '2020-01-02 17:44:13', '2020-01-02 17:44:13', 43, 15),
(20, 'producto2', 24, '5.00', '20.00', NULL, '100.00', 2, 1, NULL, '2020-01-02 18:11:46', '2020-01-02 18:11:46', 43, 16),
(21, 'producto2', 20, '16.00', '20.00', NULL, '300.00', 2, 1, NULL, '2020-01-02 18:11:46', '2020-01-02 18:11:46', 43, 16),
(22, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-03 14:05:43', '2020-01-03 14:05:43', 43, 17),
(23, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-03 14:10:02', '2020-01-03 14:10:02', 43, 18),
(24, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-03 14:10:40', '2020-01-03 14:10:40', 43, 19),
(25, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-03 14:10:42', '2020-01-03 14:10:42', 43, 20),
(26, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-03 14:11:52', '2020-01-03 14:11:52', 43, 21),
(27, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-03 14:12:22', '2020-01-03 14:12:22', 43, 22),
(28, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-03 14:16:08', '2020-01-03 14:16:08', 43, 23),
(29, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-03 14:16:29', '2020-01-03 14:16:29', 43, 24),
(30, 'a', 10, '10.00', NULL, NULL, '100.00', 4, 6, NULL, '2020-01-03 14:30:18', '2020-01-03 14:30:18', 44, 25),
(32, 'a', 10, '10.00', NULL, NULL, '100.00', 4, 6, NULL, '2020-01-03 14:45:46', '2020-01-03 14:45:46', 44, 30),
(33, 'producto2', 10, '10.00', '1.00', NULL, '99.00', 2, 1, NULL, '2020-01-03 14:54:55', '2020-01-03 14:54:55', 43, 31),
(34, 'producto1', 10, '10.00', '1.00', NULL, '99.00', 3, 2, NULL, '2020-01-03 14:54:55', '2020-01-03 14:54:55', 42, 31),
(35, 'producto2', 10, '10.00', '1.00', NULL, '99.00', 2, 1, NULL, '2020-01-03 15:00:15', '2020-01-03 15:00:15', 43, 32),
(36, 'producto1', 10, '10.00', '1.00', NULL, '99.00', 3, 2, NULL, '2020-01-03 15:00:15', '2020-01-03 15:00:15', 42, 32),
(37, 'producto2', 10, '10.00', '10.00', NULL, '90.00', 2, 1, NULL, '2020-01-03 15:04:12', '2020-01-03 15:04:12', 43, 33),
(38, 'producto1', 10, '10.00', '10.00', NULL, '90.00', 3, 2, NULL, '2020-01-03 15:04:12', '2020-01-03 15:04:12', 42, 33),
(39, 'producto2', 10, '10.00', '10.00', NULL, '90.00', 2, 1, NULL, '2020-01-03 15:04:12', '2020-01-03 15:04:12', 43, 33),
(40, 'producto1', 10, '10.00', '10.00', NULL, '90.00', 3, 2, NULL, '2020-01-03 15:04:12', '2020-01-03 15:04:12', 42, 33),
(41, 'producto1', 1, '13.00', NULL, NULL, '13.00', 3, 2, NULL, '2020-01-03 15:05:39', '2020-01-03 15:05:39', 42, 34),
(42, 'a', 10, '10.00', NULL, NULL, '100.00', 4, 6, NULL, '2020-01-03 15:17:15', '2020-01-03 15:17:15', 44, 35),
(43, 'producto2', 10, '10.00', '1.00', NULL, '99.00', 2, 1, NULL, '2020-01-03 15:51:50', '2020-01-03 15:51:50', 43, 31),
(44, 'producto1', 10, '10.00', '1.00', NULL, '99.00', 3, 2, NULL, '2020-01-03 15:51:50', '2020-01-03 15:51:50', 42, 31),
(45, 'producto2', 1, '0.50', NULL, NULL, '0.50', 2, 1, NULL, '2020-01-03 16:52:34', '2020-01-03 16:52:34', 43, 36),
(46, 'producto2', 1, '0.50', NULL, NULL, '0.50', 2, 1, NULL, '2020-01-03 17:27:27', '2020-01-03 17:27:27', 43, 37),
(47, 'producto2', 1, '0.50', NULL, NULL, '0.50', 2, 1, NULL, '2020-01-03 17:48:03', '2020-01-03 17:48:03', 43, 38),
(48, 'producto2', 1, '0.50', NULL, NULL, '0.50', 2, 1, NULL, '2020-01-03 18:10:25', '2020-01-03 18:10:25', 43, 39),
(49, 'producto2', 1, '0.50', NULL, NULL, '0.50', 2, 1, NULL, '2020-01-04 11:55:25', '2020-01-04 11:55:25', 43, 40),
(50, 'producto2', 1, '0.50', NULL, NULL, '0.50', 2, 1, NULL, '2020-01-04 11:59:56', '2020-01-04 11:59:56', 43, 41),
(51, 'producto2', 1, '0.50', NULL, NULL, '0.50', 2, 1, NULL, '2020-01-04 12:09:25', '2020-01-04 12:09:25', 43, 42),
(52, 'producto2', 1, '0.50', NULL, NULL, '0.50', 2, 1, NULL, '2020-01-04 12:11:54', '2020-01-04 12:11:54', 43, 43),
(53, 'producto2', 1, '0.50', NULL, NULL, '0.50', 2, 1, NULL, '2020-01-04 12:18:02', '2020-01-04 12:18:02', 43, 44),
(54, 'producto2', 1, '0.50', NULL, NULL, '0.50', 2, 1, NULL, '2020-01-04 12:42:22', '2020-01-04 12:42:22', 43, 45),
(55, 'producto2', 1, '0.50', NULL, NULL, '0.50', 2, 1, NULL, '2020-01-04 12:44:20', '2020-01-04 12:44:20', 43, 46),
(56, 'producto2', 1, '0.50', NULL, NULL, '0.50', 2, 1, NULL, '2020-01-04 12:48:15', '2020-01-04 12:48:15', 43, 47),
(57, 'producto2', 1, '0.50', NULL, NULL, '0.50', 2, 1, NULL, '2020-01-04 12:50:37', '2020-01-04 12:50:37', 43, 48),
(58, 'producto2', 1, '0.50', NULL, NULL, '0.50', 2, 1, NULL, '2020-01-04 12:52:12', '2020-01-04 12:52:12', 43, 49),
(59, 'producto2', 1, '0.50', NULL, NULL, '0.50', 2, 1, NULL, '2020-01-04 12:55:20', '2020-01-04 12:55:20', 43, 50),
(60, 'producto2', 1, '0.50', NULL, NULL, '0.50', 2, 1, NULL, '2020-01-04 12:57:02', '2020-01-04 12:57:02', 43, 51),
(61, 'producto2', 1, '0.50', NULL, NULL, '0.50', 2, 1, NULL, '2020-01-04 13:06:05', '2020-01-04 13:06:05', 43, 52),
(62, 'producto2', 1, '0.50', NULL, NULL, '0.50', 2, 1, NULL, '2020-01-04 13:06:12', '2020-01-04 13:06:12', 43, 53),
(63, 'producto2', 1, '0.50', NULL, NULL, '0.50', 2, 1, NULL, '2020-01-04 13:09:24', '2020-01-04 13:09:24', 43, 54),
(64, 'producto2', 1, '0.50', NULL, NULL, '0.50', 2, 1, NULL, '2020-01-04 13:09:25', '2020-01-04 13:09:25', 43, 55),
(65, 'producto2', 1, '0.50', NULL, NULL, '0.50', 2, 1, NULL, '2020-01-04 13:09:56', '2020-01-04 13:09:56', 43, 56),
(66, 'producto2', 1, '0.50', NULL, NULL, '0.50', 2, 1, NULL, '2020-01-04 13:16:46', '2020-01-04 13:16:46', 43, 57),
(67, 'producto2', 1, '0.50', NULL, NULL, '0.50', 2, 1, NULL, '2020-01-04 13:20:36', '2020-01-04 13:20:36', 43, 58),
(68, 'producto2', 1, '0.50', NULL, NULL, '0.50', 2, 1, NULL, '2020-01-04 13:22:32', '2020-01-04 13:22:32', 43, 59),
(69, 'producto2', 1, '0.50', NULL, NULL, '0.50', 2, 1, NULL, '2020-01-04 13:30:53', '2020-01-04 13:30:53', 43, 60),
(70, 'producto2', 1, '0.50', NULL, NULL, '0.50', 2, 1, NULL, '2020-01-04 13:34:25', '2020-01-04 13:34:25', 43, 61),
(71, 'producto2', 1, '0.50', NULL, NULL, '0.50', 2, 1, NULL, '2020-01-04 13:38:55', '2020-01-04 13:38:55', 43, 62),
(72, 'producto2', 1, '0.50', NULL, NULL, '0.50', 2, 1, NULL, '2020-01-04 13:45:41', '2020-01-04 13:45:41', 43, 63),
(73, 'producto2', 1, '0.50', NULL, NULL, '0.50', 2, 1, NULL, '2020-01-04 13:56:49', '2020-01-04 13:56:49', 43, 64),
(74, 'a', 1, '1.00', NULL, NULL, '1.00', 4, 6, NULL, '2020-01-04 14:01:48', '2020-01-04 14:01:48', 44, 65),
(75, 'producto1', 1, '13.00', '3.00', NULL, '10.00', 3, 2, NULL, '2020-01-04 14:02:57', '2020-01-04 14:02:57', 42, 2),
(76, 'producto1', 1, '13.00', '3.00', NULL, '10.00', 3, 2, NULL, '2020-01-04 14:03:33', '2020-01-04 14:03:33', 42, 66),
(77, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-04 14:17:37', '2020-01-04 14:17:37', 43, 67),
(78, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-04 14:23:15', '2020-01-04 14:23:15', 43, 68),
(79, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-04 14:25:02', '2020-01-04 14:25:02', 43, 69),
(80, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-04 14:28:50', '2020-01-04 14:28:50', 43, 70),
(81, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-04 14:29:30', '2020-01-04 14:29:30', 43, 71),
(82, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-04 14:30:23', '2020-01-04 14:30:23', 43, 72),
(83, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-04 14:31:19', '2020-01-04 14:31:19', 43, 73),
(84, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-04 14:32:01', '2020-01-04 14:32:01', 43, 74),
(85, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-04 14:32:37', '2020-01-04 14:32:37', 43, 75),
(86, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-04 14:34:11', '2020-01-04 14:34:11', 43, 77),
(87, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-04 14:46:59', '2020-01-04 14:46:59', 43, 78),
(88, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-04 14:47:51', '2020-01-04 14:47:51', 43, 79),
(89, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-04 15:32:28', '2020-01-04 15:32:28', 43, 80),
(90, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-04 15:34:51', '2020-01-04 15:34:51', 43, 81),
(91, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-04 15:35:24', '2020-01-04 15:35:24', 43, 82),
(92, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-04 15:35:55', '2020-01-04 15:35:55', 43, 83),
(93, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-04 15:40:28', '2020-01-04 15:40:28', 43, 84),
(94, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-04 15:42:44', '2020-01-04 15:42:44', 43, 85),
(95, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-04 15:44:16', '2020-01-04 15:44:16', 43, 86),
(96, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-04 15:44:59', '2020-01-04 15:44:59', 43, 87),
(97, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-04 15:45:30', '2020-01-04 15:45:30', 43, 88),
(98, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-04 15:47:23', '2020-01-04 15:47:23', 43, 89),
(99, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-04 15:47:45', '2020-01-04 15:47:45', 43, 90),
(100, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-04 15:48:30', '2020-01-04 15:48:30', 43, 91),
(101, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-04 15:49:48', '2020-01-04 15:49:48', 43, 92),
(102, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-04 15:50:00', '2020-01-04 15:50:00', 43, 93),
(103, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-04 15:52:52', '2020-01-04 15:52:52', 43, 94),
(104, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-04 15:53:51', '2020-01-04 15:53:51', 43, 95),
(105, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-04 15:54:04', '2020-01-04 15:54:04', 43, 96),
(106, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-04 15:54:45', '2020-01-04 15:54:45', 43, 97),
(107, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-04 15:57:12', '2020-01-04 15:57:12', 43, 98),
(108, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-04 16:02:41', '2020-01-04 16:02:41', 43, 99),
(109, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-04 16:06:57', '2020-01-04 16:06:57', 43, 100),
(110, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-04 16:16:48', '2020-01-04 16:16:48', 43, 101),
(111, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-04 16:18:35', '2020-01-04 16:18:35', 43, 102),
(112, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-04 16:20:43', '2020-01-04 16:20:43', 43, 103),
(113, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-04 16:21:25', '2020-01-04 16:21:25', 43, 104),
(114, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-04 16:22:12', '2020-01-04 16:22:12', 43, 105),
(115, 'producto2', 1, '30.00', NULL, NULL, '30.00', 2, 1, NULL, '2020-01-06 09:56:40', '2020-01-06 09:56:40', 43, 106),
(116, 'producto2', 2, '60.00', NULL, NULL, '120.00', 2, 1, NULL, '2020-01-06 09:56:40', '2020-01-06 09:56:40', 43, 106),
(117, 'producto2', 1, '30.00', NULL, NULL, '30.00', 2, 1, NULL, '2020-01-06 09:56:52', '2020-01-06 09:56:52', 43, 107),
(118, 'producto2', 2, '60.00', NULL, NULL, '120.00', 2, 1, NULL, '2020-01-06 09:56:52', '2020-01-06 09:56:52', 43, 107),
(119, 'producto2', 1, '30.00', NULL, NULL, '30.00', 2, 1, NULL, '2020-01-06 09:57:06', '2020-01-06 09:57:06', 43, 108),
(120, 'producto2', 2, '60.00', NULL, NULL, '120.00', 2, 1, NULL, '2020-01-06 09:57:06', '2020-01-06 09:57:06', 43, 108),
(121, 'producto2', 1, '30.00', NULL, NULL, '30.00', 2, 1, NULL, '2020-01-06 09:57:16', '2020-01-06 09:57:16', 43, 109),
(122, 'producto2', 2, '60.00', NULL, NULL, '120.00', 2, 1, NULL, '2020-01-06 09:57:16', '2020-01-06 09:57:16', 43, 109),
(123, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-06 15:56:02', '2020-01-06 15:56:02', 43, 110),
(125, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-06 15:59:47', '2020-01-06 15:59:47', 43, 111),
(126, 'producto2', 21, '32.00', NULL, NULL, '672.00', 2, 1, NULL, '2020-01-07 10:36:45', '2020-01-07 10:36:45', 43, 112),
(127, 'producto2', 21, '32.00', NULL, NULL, '672.00', 2, 1, NULL, '2020-01-07 10:36:54', '2020-01-07 10:36:54', 43, 113),
(128, 'producto2', 21, '32.00', NULL, NULL, '672.00', 2, 1, NULL, '2020-01-07 10:39:27', '2020-01-07 10:39:27', 43, 114),
(129, 'producto2', 21, '32.00', NULL, NULL, '672.00', 2, 1, NULL, '2020-01-07 10:40:59', '2020-01-07 10:40:59', 43, 115),
(130, 'producto2', 21, '32.00', NULL, NULL, '672.00', 2, 1, NULL, '2020-01-07 10:50:00', '2020-01-07 10:50:00', 43, 116),
(131, 'producto2', 1, '1.00', NULL, NULL, '1.00', 2, 1, NULL, '2020-01-07 11:13:53', '2020-01-07 11:13:53', 43, 117),
(132, 'producto2', 21, '32.00', NULL, NULL, '672.00', 2, 1, NULL, '2020-01-07 11:18:08', '2020-01-07 11:18:08', 43, 118);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_factura_compra`
--

CREATE TABLE `detalle_factura_compra` (
  `id_detalle_factura_compra` int(11) NOT NULL,
  `nombre` varchar(300) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `descuento` decimal(10,2) DEFAULT NULL,
  `p_descuento` tinyint(1) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `iva` int(11) DEFAULT NULL,
  `ice` int(11) DEFAULT NULL,
  `irbpnr` decimal(10,2) DEFAULT NULL,
  `fcrea` datetime DEFAULT NULL,
  `fmodifica` datetime DEFAULT NULL,
  `id_producto` int(11) NOT NULL,
  `id_factura` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `detalle_factura_compra`
--

INSERT INTO `detalle_factura_compra` (`id_detalle_factura_compra`, `nombre`, `cantidad`, `precio`, `descuento`, `p_descuento`, `total`, `iva`, `ice`, `irbpnr`, `fcrea`, `fmodifica`, `id_producto`, `id_factura`) VALUES
(6, 'producto2', 20, '10.00', '10.00', 0, '180.00', 2, 1, '0.00', '2020-01-07 23:25:36', '2020-01-07 23:25:36', 43, 14);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentacion`
--

CREATE TABLE `documentacion` (
  `id_documento` int(11) NOT NULL,
  `doc_descripcion` varchar(100) NOT NULL,
  `doc_estatus` varchar(15) NOT NULL,
  `fcrea` date DEFAULT NULL,
  `fmodifica` date DEFAULT NULL,
  `ucrea` int(11) DEFAULT NULL,
  `umodifica` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `documentacion`
--

INSERT INTO `documentacion` (`id_documento`, `doc_descripcion`, `doc_estatus`, `fcrea`, `fmodifica`, `ucrea`, `umodifica`) VALUES
(1, 'Evalación Psicológica', 'Activo', '2019-12-27', '2019-12-27', NULL, NULL),
(2, 'Certificado Médico', 'Activo', '2019-12-27', '2019-12-27', NULL, NULL),
(3, 'A fis', 'Activo', '2019-12-27', '2019-12-27', NULL, NULL),
(4, 'Acta de Matrimonio', 'Activo', '2019-12-27', '2019-12-27', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docu_empleado`
--

CREATE TABLE `docu_empleado` (
  `id_docu_emp` int(11) NOT NULL,
  `doc_url` varchar(100) DEFAULT NULL,
  `doc_estado` varchar(45) DEFAULT NULL,
  `fcrea` date DEFAULT NULL,
  `fmodifica` date DEFAULT NULL,
  `ucrea` int(11) DEFAULT NULL,
  `umodifica` int(11) DEFAULT NULL,
  `iddocu` int(11) DEFAULT NULL,
  `idemple` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `id_empleado` int(11) NOT NULL,
  `tipo_dni` varchar(15) DEFAULT NULL,
  `dni` varchar(13) DEFAULT NULL,
  `primer_nombre` varchar(45) DEFAULT NULL,
  `segundo_nombre` varchar(45) DEFAULT NULL,
  `apellido_paterno` varchar(45) DEFAULT NULL,
  `apellido_materno` varchar(45) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `edad` varchar(25) DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `lugar_nacimiento` varchar(30) DEFAULT NULL,
  `nacionalidad` varchar(45) DEFAULT NULL,
  `estado_civil` varchar(45) DEFAULT NULL,
  `sexo` varchar(45) DEFAULT NULL,
  `direccion_residencia` varchar(100) DEFAULT NULL,
  `telefono` varchar(10) DEFAULT NULL,
  `celular` varchar(10) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `tipo_sangre` varchar(4) DEFAULT NULL,
  `profesion` varchar(45) DEFAULT NULL,
  `discapacidad` varchar(45) DEFAULT NULL,
  `discap_porcentaje` int(11) DEFAULT NULL,
  `tipo_iden_discap` varchar(15) DEFAULT NULL,
  `num_iden_discap` varchar(15) DEFAULT NULL,
  `num_iess` varchar(15) DEFAULT NULL,
  `num_libreta_militar` varchar(15) DEFAULT NULL,
  `banco` varchar(55) DEFAULT NULL,
  `tipo_cuenta` varchar(15) DEFAULT NULL,
  `num_cuenta` varchar(15) DEFAULT NULL,
  `num_cargas` int(11) DEFAULT NULL,
  `estado` varchar(15) DEFAULT NULL,
  `observaciones` varchar(255) DEFAULT NULL,
  `fcrea` date DEFAULT NULL,
  `fmodifica` date DEFAULT NULL,
  `ucrea` int(11) DEFAULT NULL,
  `umodifica` int(11) DEFAULT NULL,
  `id_empresa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`id_empleado`, `tipo_dni`, `dni`, `primer_nombre`, `segundo_nombre`, `apellido_paterno`, `apellido_materno`, `fecha_nacimiento`, `edad`, `foto`, `lugar_nacimiento`, `nacionalidad`, `estado_civil`, `sexo`, `direccion_residencia`, `telefono`, `celular`, `email`, `tipo_sangre`, `profesion`, `discapacidad`, `discap_porcentaje`, `tipo_iden_discap`, `num_iden_discap`, `num_iess`, `num_libreta_militar`, `banco`, `tipo_cuenta`, `num_cuenta`, `num_cargas`, `estado`, `observaciones`, `fcrea`, `fmodifica`, `ucrea`, `umodifica`, `id_empresa`) VALUES
(1, 'Cedula', '1721897765', 'Cristian', NULL, 'dasdasd', NULL, NULL, '12', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(3, 'Cédula', '2342342', 'werrqwer', 'qwerqwer', 'qwerqwer', 'qwerqwe', '2017-10-29', '12', NULL, '1', 'ECUATORIANA', 'Soltero', 'Masculino', 'wrqwerq', NULL, '234234', 'slkdjalksd@mail.com', 'O-', NULL, 'Ninguna', 23, 'Carnet CONADIS', '234234', NULL, NULL, 'PROAMÉRICA', 'AHORROS', '23442342', NULL, 'Activo', NULL, '2019-12-09', '2019-12-09', NULL, NULL, 1),
(8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL, '2019-12-27', '2019-12-27', NULL, NULL, 1),
(9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL, '2019-12-27', '2019-12-27', NULL, NULL, 1),
(10, 'Cédula', '1726290289', 'qwq', 'www', 'asd', 'ss', '1987-01-22', '21', NULL, 'BELLAVISTA', 'ECUATORIANA', 'Soltero', 'Masculino', 'assss', '333', '5432', 'w@gmal.com', 'O+', 'ssss', 'Ninguna', NULL, NULL, NULL, '1222', '2333', 'Banco del Pacifico', 'AHORROS', '222', 1, 'Activo', 'were', '2019-12-27', '2019-12-27', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado_cargo`
--

CREATE TABLE `empleado_cargo` (
  `id_emp_cargo` int(11) NOT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `fecha_salida` date DEFAULT NULL,
  `tipo_horario` varchar(20) DEFAULT NULL,
  `tipo_contrato` varchar(15) DEFAULT NULL,
  `bonos` varchar(5) DEFAULT NULL,
  `aporte_iess` varchar(5) DEFAULT NULL,
  `fondo_reserva` varchar(5) DEFAULT NULL,
  `decimo_tercero` varchar(5) DEFAULT NULL,
  `decimo_cuarto` varchar(5) DEFAULT NULL,
  `cuenta_contable` varchar(15) DEFAULT NULL,
  `observaciones` varchar(255) DEFAULT NULL,
  `fcrea` date DEFAULT NULL,
  `fmodifica` date DEFAULT NULL,
  `ucrea` int(11) DEFAULT NULL,
  `umodifica` int(11) DEFAULT NULL,
  `id_empleado` int(11) NOT NULL,
  `id_cargo` int(11) NOT NULL,
  `id_grupo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `id_empresa` int(11) NOT NULL,
  `codigo_empresa` varchar(45) DEFAULT NULL,
  `periodo_empresa` year(4) DEFAULT NULL,
  `nombre_empresa` varchar(45) DEFAULT NULL,
  `razon_social` varchar(200) DEFAULT NULL,
  `ruc_empresa` varchar(50) DEFAULT NULL,
  `direccion_empresa` varchar(150) DEFAULT NULL,
  `telefono` int(11) DEFAULT NULL,
  `email_empresa` varchar(100) DEFAULT NULL,
  `password` varchar(30) DEFAULT NULL,
  `servidor_correo` varchar(80) DEFAULT NULL,
  `puerto_correo` int(6) DEFAULT NULL,
  `seguridad_correo` varchar(50) DEFAULT NULL,
  `tipo_identidicacion_empresa` varchar(80) DEFAULT NULL,
  `obligado_contabilidad` varchar(20) DEFAULT NULL,
  `ruc_contador` varchar(45) DEFAULT NULL,
  `nombre_contador` varchar(200) DEFAULT NULL,
  `fecha` varchar(45) DEFAULT NULL,
  `identificaion_rep` varchar(45) DEFAULT NULL,
  `nomb_representante` varchar(200) DEFAULT NULL,
  `clave_duracion` date DEFAULT NULL,
  `periodo_inicio` date DEFAULT NULL,
  `periodo_fin` date DEFAULT NULL,
  `mascara_empresa` varchar(80) DEFAULT NULL,
  `recalculo` date DEFAULT NULL,
  `balance` varchar(10) DEFAULT NULL,
  `empresa_asociada` int(11) DEFAULT NULL,
  `noresolucion` varchar(11) DEFAULT NULL,
  `nocontribuyente` varchar(11) DEFAULT NULL,
  `codigo_entidad` varchar(100) DEFAULT NULL,
  `contribuyente` int(11) DEFAULT NULL,
  `tipo_ctas` varchar(80) DEFAULT 'Ctas Niif',
  `logo` varchar(200) DEFAULT NULL,
  `firma` varchar(200) DEFAULT NULL,
  `pass_firma` varchar(255) DEFAULT NULL,
  `actualizacion_firma` datetime DEFAULT NULL,
  `fecha_firma` datetime DEFAULT NULL,
  `fecha_expiracion_firma` date DEFAULT NULL,
  `fcierre` datetime DEFAULT NULL,
  `fresolucion` date DEFAULT NULL,
  `tipo_emision` int(11) DEFAULT NULL,
  `ambiente` int(11) DEFAULT NULL,
  `negativo` int(1) DEFAULT NULL,
  `fmodifica` date DEFAULT NULL,
  `fcrea` date NOT NULL DEFAULT current_timestamp(),
  `ucrea` int(11) DEFAULT NULL,
  `umodifica` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_moneda` int(11) DEFAULT NULL,
  `id_establecimiento` varchar(11) DEFAULT NULL,
  `id_provincia` int(11) DEFAULT NULL,
  `id_ciudad` int(11) DEFAULT NULL,
  `cta_resultado` varchar(80) DEFAULT NULL,
  `cta_ingreso` varchar(100) DEFAULT NULL,
  `cta_costo` varchar(100) DEFAULT NULL,
  `cta_activo` varchar(100) DEFAULT NULL,
  `cta_pasivo` varchar(100) DEFAULT NULL,
  `cta_patrimonio` varchar(100) DEFAULT NULL,
  `cta_gasto` varchar(100) DEFAULT NULL,
  `cta_orden` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`id_empresa`, `codigo_empresa`, `periodo_empresa`, `nombre_empresa`, `razon_social`, `ruc_empresa`, `direccion_empresa`, `telefono`, `email_empresa`, `password`, `servidor_correo`, `puerto_correo`, `seguridad_correo`, `tipo_identidicacion_empresa`, `obligado_contabilidad`, `ruc_contador`, `nombre_contador`, `fecha`, `identificaion_rep`, `nomb_representante`, `clave_duracion`, `periodo_inicio`, `periodo_fin`, `mascara_empresa`, `recalculo`, `balance`, `empresa_asociada`, `noresolucion`, `nocontribuyente`, `codigo_entidad`, `contribuyente`, `tipo_ctas`, `logo`, `firma`, `pass_firma`, `actualizacion_firma`, `fecha_firma`, `fecha_expiracion_firma`, `fcierre`, `fresolucion`, `tipo_emision`, `ambiente`, `negativo`, `fmodifica`, `fcrea`, `ucrea`, `umodifica`, `id_user`, `id_moneda`, `id_establecimiento`, `id_provincia`, `id_ciudad`, `cta_resultado`, `cta_ingreso`, `cta_costo`, `cta_activo`, `cta_pasivo`, `cta_patrimonio`, `cta_gasto`, `cta_orden`) VALUES
(1, '009', 2019, 'SOLMOCONTADORES', 'SOLMOCONTADORES SOCIEDAD DE HECHO', '0930087465001', 'COLON Y DIEGO DE ALMAGRO', 98329785, 'facturacion@solmocontadores.com', 'Solmo2015*-', 'mail.solmocontadores.com', 465, 'ssl', 'Cedula', '0', '0930087465001', 'RICARDO SOLIS', NULL, '0930087465001', 'JORGE SOLIS', '2019-11-30', '2019-01-01', NULL, '1', '2018-12-31', 'No', NULL, NULL, NULL, '001', 0, 'Ctas Niif', 'images.jpg', 'danny_javier_quintuna_esmeraldas.p12', 'LunaZaky0914', '2019-12-22 18:31:38', '2019-12-22 18:31:38', '2020-07-20', '2018-12-31 00:00:00', NULL, 1, 1, 0, '2020-01-04', '2019-11-14', NULL, NULL, NULL, 1, '001', 17, 178, '3.', '4.', '5.', '1.', '2.', '3.', '6.', '6.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `establecimiento`
--

CREATE TABLE `establecimiento` (
  `id_establecimiento` int(11) NOT NULL,
  `nombre` varchar(200) DEFAULT NULL,
  `codigo` varchar(20) DEFAULT NULL,
  `urlweb` varchar(150) DEFAULT NULL,
  `nombre_comercial` varchar(200) DEFAULT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `estado` varchar(2) DEFAULT NULL,
  `fcrea` datetime DEFAULT NULL,
  `fmodifica` datetime DEFAULT NULL,
  `umodifica` int(11) DEFAULT NULL,
  `ucrea` int(11) DEFAULT NULL,
  `id_empresa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `establecimiento`
--

INSERT INTO `establecimiento` (`id_establecimiento`, `nombre`, `codigo`, `urlweb`, `nombre_comercial`, `direccion`, `estado`, `fcrea`, `fmodifica`, `umodifica`, `ucrea`, `id_empresa`) VALUES
(1, 'SOLMOCONTADORES', '001', NULL, 'SOLMOCONTADORES SOCIEDAD DE HECHO', 'COLON Y DIEGO DE ALMAGRO', '1', '2019-12-17 17:00:00', '2019-12-28 11:08:14', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `id_factura` int(11) NOT NULL,
  `respuesta` varchar(50) DEFAULT NULL,
  `modo` tinyint(1) NOT NULL,
  `ambiente` int(11) DEFAULT NULL,
  `tipo_emision` int(11) DEFAULT NULL,
  `fecha_emision` date DEFAULT NULL,
  `fecha_expiracion` date DEFAULT NULL,
  `estatus` tinyint(1) DEFAULT NULL,
  `forma_pago` varchar(300) DEFAULT NULL,
  `clave_acceso` varchar(300) DEFAULT NULL,
  `observacion` text DEFAULT NULL,
  `subtotal_sin_impuesto` decimal(10,2) DEFAULT NULL,
  `subtotal_12` decimal(10,2) DEFAULT NULL,
  `subtotal_0` decimal(10,2) DEFAULT NULL,
  `subtotal_no_obj_iva` decimal(10,2) DEFAULT NULL,
  `descuento` decimal(10,2) DEFAULT NULL,
  `valor_ice` decimal(10,2) DEFAULT NULL,
  `valor_irbpnr` decimal(10,2) DEFAULT NULL,
  `iva_12` decimal(10,2) DEFAULT NULL,
  `propina` decimal(10,2) DEFAULT NULL,
  `%propina` tinyint(1) DEFAULT NULL,
  `valor_total` decimal(10,2) DEFAULT NULL,
  `fcrea` datetime NOT NULL,
  `fmodifica` datetime NOT NULL,
  `umodifica` int(11) DEFAULT NULL,
  `ucrea` int(11) DEFAULT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_punto_emision` int(11) NOT NULL,
  `id_empresa` int(11) NOT NULL,
  `id_establecimiento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `factura`
--

INSERT INTO `factura` (`id_factura`, `respuesta`, `modo`, `ambiente`, `tipo_emision`, `fecha_emision`, `fecha_expiracion`, `estatus`, `forma_pago`, `clave_acceso`, `observacion`, `subtotal_sin_impuesto`, `subtotal_12`, `subtotal_0`, `subtotal_no_obj_iva`, `descuento`, `valor_ice`, `valor_irbpnr`, `iva_12`, `propina`, `%propina`, `valor_total`, `fcrea`, `fmodifica`, `umodifica`, `ucrea`, `id_cliente`, `id_user`, `id_punto_emision`, `id_empresa`, `id_establecimiento`) VALUES
(1, 'Error', 1, 1, 1, '2019-12-15', NULL, NULL, '1', '1', '1', '1.00', '1.00', '1.00', '1.00', '1.00', '11.00', '1.00', '1.00', '1.00', NULL, '1.00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, 9, 6, 1, 1, 1),
(2, 'Error', 0, 1, 1, '2019-12-30', NULL, 1, NULL, '3012201901179251089900110020010000000022809280911', NULL, '10.00', '0.00', '0.00', '10.00', '3.00', '0.00', '0.00', '0.00', NULL, NULL, '10.00', '2019-12-30 09:38:48', '2020-01-04 14:03:33', NULL, NULL, 9, 6, 1, 1, 1),
(3, 'Error', 1, 1, 1, '2019-12-30', NULL, NULL, 'Efectivo', '3012201901179251089900110020010000000032809280915', NULL, '271.00', '0.00', '0.00', '271.00', '2.00', '0.00', '0.00', '0.00', '4.00', NULL, '275.00', '2019-12-31 01:06:41', '2019-12-31 01:06:41', NULL, NULL, 9, 6, 1, 1, 1),
(8, 'Error', 0, 1, 1, '2019-12-31', NULL, 1, NULL, '3112201901179251089900110020010000000052809280910', NULL, '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, NULL, '100.00', '2019-12-31 17:09:09', '2019-12-31 17:09:09', NULL, NULL, 9, 9, 1, 1, 1),
(9, 'Error', 0, 1, 1, '2020-01-02', '2020-01-03', 1, 'Efectivo', '0201202001179251089900110010010000000062809280915', 'sa', '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, NULL, '100.00', '2020-01-02 11:57:39', '2020-01-03 15:17:15', NULL, NULL, 9, 9, 1, 1, 1),
(10, 'Error', 1, 1, 1, '2020-01-02', NULL, NULL, 'Cheque', '0201202001179251089900110010010000000072809280910', NULL, '680.00', '390.00', '0.00', '290.00', '20.00', '0.00', '0.00', '46.80', NULL, NULL, '726.80', '2020-01-02 14:51:48', '2020-01-02 14:51:48', NULL, NULL, 9, 6, 1, 1, 1),
(11, 'Error', 0, 1, 1, '2020-01-02', '2020-01-03', 1, 'Cheque', NULL, NULL, '180.00', '90.00', '0.00', '90.00', '20.00', '0.00', '0.00', '10.80', NULL, NULL, '190.80', '2020-01-02 15:04:00', '2020-01-03 15:04:12', NULL, NULL, 9, 9, 1, 1, 1),
(12, 'Error', 1, 1, 1, '2020-01-02', NULL, NULL, 'Efectivo', '0201202001179251089900110010010000000092809280911', 'sq', '129.00', '0.00', '0.00', '129.00', '1.00', '0.00', '0.00', '0.00', NULL, NULL, '129.00', '2020-01-02 17:27:27', '2020-01-02 17:27:27', NULL, NULL, 9, 9, 1, 1, 1),
(13, 'Error', 1, 1, 1, '2020-01-02', NULL, NULL, 'Efectivo', '0201202001179251089900110010010000000092809280911', 'sq', '129.00', '0.00', '0.00', '129.00', '1.00', '0.00', '0.00', '0.00', NULL, NULL, '129.00', '2020-01-02 17:27:40', '2020-01-02 17:27:40', NULL, NULL, 9, 9, 1, 1, 1),
(14, 'Error', 1, 1, 1, '2020-01-02', NULL, NULL, 'Efectivo', '0201202001179251089900110010010000000092809280911', 'sq', '129.00', '0.00', '0.00', '129.00', '1.00', '0.00', '0.00', '0.00', NULL, NULL, '129.00', '2020-01-02 17:28:54', '2020-01-02 17:28:54', NULL, NULL, 9, 9, 1, 1, 1),
(15, 'Error', 1, 1, 1, '2020-01-02', NULL, NULL, 'Efectivo', '0201202001179251089900110010010000000122809280918', NULL, '100.00', '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', '12.00', NULL, NULL, '112.00', '2020-01-02 17:44:13', '2020-01-02 17:44:13', NULL, NULL, 9, 9, 1, 1, 1),
(16, 'Error', 1, 1, 1, '2020-01-02', NULL, NULL, 'Cheque', '0201202001179251089900110010010000000132809280913', NULL, '400.00', '400.00', '0.00', '0.00', '40.00', '0.00', '0.00', '48.00', '2.00', NULL, '450.00', '2020-01-02 18:11:45', '2020-01-02 18:11:45', NULL, NULL, 9, 6, 1, 1, 1),
(17, 'Error', 1, 1, 1, '2020-01-03', NULL, NULL, 'Efectivo', '0301202001179251089900110010010000000132809280918', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-03 14:05:43', '2020-01-03 14:05:43', NULL, NULL, 9, 6, 1, 1, 1),
(18, 'Error', 1, 1, 1, '2020-01-03', NULL, NULL, 'Tarjeta', '0301202001179251089900110010010000000142809280913', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-03 14:10:02', '2020-01-03 14:10:02', NULL, NULL, 9, 6, 1, 1, 1),
(19, 'Error', 1, 1, 1, '2020-01-03', NULL, NULL, 'Tarjeta', '0301202001179251089900110010010000000142809280913', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-03 14:10:40', '2020-01-03 14:10:40', NULL, NULL, 9, 6, 1, 1, 1),
(20, 'Error', 1, 1, 1, '2020-01-03', NULL, NULL, 'Tarjeta', '0301202001179251089900110010010000000142809280913', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-03 14:10:42', '2020-01-03 14:10:42', NULL, NULL, 9, 6, 1, 1, 1),
(21, 'Error', 1, 1, 1, '2020-01-03', NULL, NULL, 'Tarjeta', '0301202001179251089900110010010000000142809280913', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-03 14:11:52', '2020-01-03 14:11:52', NULL, NULL, 9, 6, 1, 1, 1),
(22, 'Error', 1, 1, 1, '2020-01-03', NULL, NULL, 'Tarjeta', '0301202001179251089900110010010000000142809280913', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-03 14:12:22', '2020-01-03 14:12:22', NULL, NULL, 9, 6, 1, 1, 1),
(23, 'Error', 1, 1, 1, '2020-01-03', NULL, NULL, 'Tarjeta', '0301202001179251089900110010010000000142809280913', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-03 14:16:08', '2020-01-03 14:16:08', NULL, NULL, 9, 6, 1, 1, 1),
(24, 'Error', 1, 1, 1, '2020-01-03', NULL, NULL, 'Tarjeta', '0301202001179251089900110010010000000142809280913', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-03 14:16:29', '2020-01-03 14:16:29', NULL, NULL, 9, 6, 1, 1, 1),
(25, 'Error', 1, 1, 1, '2020-01-02', NULL, NULL, 'Efectivo', '0201202001179251089900110010010000000212809280917', 'sa', '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, NULL, '100.00', '2020-01-03 14:30:18', '2020-01-03 14:30:18', NULL, NULL, 9, 6, 1, 1, 1),
(26, 'Error', 1, 1, 1, '2020-01-03', NULL, NULL, 'Efectivo', '0301202001179251089900110010010000000222809280917', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-03 14:33:52', '2020-01-03 14:33:52', NULL, NULL, 9, 6, 1, 1, 1),
(30, 'Error', 1, 1, 1, '2020-01-02', NULL, NULL, 'Efectivo', '0201202001179251089900110010010000000232809280918', 'sa', '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, NULL, '100.00', '2020-01-03 14:45:46', '2020-01-03 14:45:46', NULL, NULL, 9, 6, 1, 1, 1),
(31, 'Error', 0, 1, 1, '2020-01-03', NULL, 1, NULL, NULL, 'asas', '198.00', '99.00', '0.00', '99.00', '2.00', '0.00', '0.00', '11.88', NULL, NULL, '209.88', '2020-01-03 14:54:55', '2020-01-03 15:51:50', NULL, NULL, 9, 6, 1, 1, 1),
(32, 'Error', 1, 1, 1, '2020-01-03', NULL, NULL, NULL, '0301202001179251089900110010010000000252809280913', NULL, '198.00', '99.00', '0.00', '99.00', '2.00', '0.00', '0.00', '11.88', NULL, NULL, '209.88', '2020-01-03 15:00:15', '2020-01-03 15:00:15', NULL, NULL, 9, 6, 1, 1, 1),
(33, 'Error', 1, 1, 1, '2020-01-02', NULL, NULL, 'Cheque', '0201202001179251089900110010010000000262809280914', NULL, '360.00', '180.00', '0.00', '180.00', '40.00', '0.00', '0.00', '21.60', NULL, NULL, '381.60', '2020-01-03 15:04:12', '2020-01-03 15:04:12', NULL, NULL, 9, 6, 1, 1, 1),
(34, 'Error', 1, 1, 1, '2019-12-30', NULL, NULL, NULL, '3012201901179251089900110010010000000272809280910', NULL, '13.00', '0.00', '0.00', '13.00', '0.00', '0.00', '0.00', '0.00', NULL, NULL, '13.00', '2020-01-03 15:05:39', '2020-01-03 15:05:39', NULL, NULL, 9, 6, 1, 1, 1),
(35, 'Error', 1, 1, 1, '2020-01-02', NULL, NULL, 'Efectivo', '0201202001179251089900110010010000000282809280915', 'sa', '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, NULL, '100.00', '2020-01-03 15:17:15', '2020-01-03 15:17:15', NULL, NULL, 9, 6, 1, 1, 1),
(36, 'Error', 1, 1, 1, '2020-01-03', NULL, NULL, NULL, '0301202001179251089900110010010000000292809280915', NULL, '0.50', '0.50', '0.00', '0.00', '0.00', '0.00', '0.00', '0.06', NULL, NULL, '0.56', '2020-01-03 16:52:34', '2020-01-03 16:52:34', NULL, NULL, 9, 6, 1, 1, 1),
(37, 'Error', 1, 1, 1, '2020-01-03', NULL, NULL, 'Tarjeta', '0301202001179251089900110010010000000302809280910', NULL, '0.50', '0.50', '0.00', '0.00', '0.00', '0.00', '0.00', '0.06', NULL, NULL, '0.56', '2020-01-03 17:27:27', '2020-01-03 17:27:27', NULL, NULL, 9, 6, 1, 1, 1),
(38, 'Error', 1, 1, 1, '2020-01-03', NULL, NULL, 'Cheque', '0301202001093008746500110010010000000312809280910', NULL, '0.50', '0.50', '0.00', '0.00', '0.00', '0.00', '0.00', '0.06', NULL, NULL, '0.56', '2020-01-03 17:48:03', '2020-01-03 17:48:03', NULL, NULL, 9, 6, 1, 1, 1),
(39, 'Error', 1, 1, 1, '2020-01-03', NULL, NULL, 'Cheque', '0301202001093008746500110010010000000322809280916', NULL, '0.50', '0.50', '0.00', '0.00', '0.00', '0.00', '0.00', '0.06', NULL, NULL, '0.56', '2020-01-03 18:10:25', '2020-01-03 18:10:25', NULL, NULL, 9, 6, 1, 1, 1),
(40, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, 'Cheque', '0401202001093008746500110010010000000332809280916', NULL, '0.50', '0.50', '0.00', '0.00', '0.00', '0.00', '0.00', '0.06', NULL, NULL, '0.56', '2020-01-04 11:55:25', '2020-01-04 11:55:25', NULL, NULL, 9, 6, 1, 1, 1),
(41, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, NULL, '0401202001093008746500110010010000000342809280911', NULL, '0.50', '0.50', '0.00', '0.00', '0.00', '0.00', '0.00', '0.06', NULL, NULL, '0.56', '2020-01-04 11:59:56', '2020-01-04 11:59:56', NULL, NULL, 9, 6, 1, 1, 1),
(42, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, NULL, '0401202001093008746500110010010000000342809280911', NULL, '0.50', '0.50', '0.00', '0.00', '0.00', '0.00', '0.00', '0.06', NULL, NULL, '0.56', '2020-01-04 12:09:25', '2020-01-04 12:09:25', NULL, NULL, 9, 6, 1, 1, 1),
(43, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, 'Cheque', '0401202001093008746500110010010000000362809280912', NULL, '0.50', '0.50', '0.00', '0.00', '0.00', '0.00', '0.00', '0.06', NULL, NULL, '0.56', '2020-01-04 12:11:54', '2020-01-04 12:11:54', NULL, NULL, 9, 6, 1, 1, 1),
(44, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, 'Efectivo', '0401202001093008746500110010010000000372809280918', NULL, '0.50', '0.50', '0.00', '0.00', '0.00', '0.00', '0.00', '0.06', NULL, NULL, '0.56', '2020-01-04 12:18:02', '2020-01-04 12:18:02', NULL, NULL, 9, 6, 1, 1, 1),
(45, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, 'Cheque', '0401202001093008746500110010010000000382809280913', NULL, '0.50', '0.50', '0.00', '0.00', '0.00', '0.00', '0.00', '0.06', '0.50', NULL, '1.06', '2020-01-04 12:42:22', '2020-01-04 12:42:22', NULL, NULL, 9, 6, 1, 1, 1),
(46, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, 'Efectivo', '0401202001093008746500110010010000000392809280919', NULL, '0.50', '0.50', '0.00', '0.00', '0.00', '0.00', '0.00', '0.06', NULL, NULL, '0.56', '2020-01-04 12:44:20', '2020-01-04 12:44:20', NULL, NULL, 9, 6, 1, 1, 1),
(47, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, 'Cheque', '0401202001093008746500110010010000000402809280914', NULL, '0.50', '0.50', '0.00', '0.00', '0.00', '0.00', '0.00', '0.06', NULL, NULL, '0.56', '2020-01-04 12:48:15', '2020-01-04 12:48:15', NULL, NULL, 9, 6, 1, 1, 1),
(48, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, 'Efectivo', '0401202001093008746500110010010000000412809280911', NULL, '0.50', '0.50', '0.00', '0.00', '0.00', '0.00', '0.00', '0.06', NULL, NULL, '0.56', '2020-01-04 12:50:37', '2020-01-04 12:50:37', NULL, NULL, 9, 6, 1, 1, 1),
(49, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, 'Efectivo', '0401202001093008746500110010010000000422809280915', NULL, '0.50', '0.50', '0.00', '0.00', '0.00', '0.00', '0.00', '0.06', NULL, NULL, '0.56', '2020-01-04 12:52:12', '2020-01-04 12:52:12', NULL, NULL, 9, 6, 1, 1, 1),
(50, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, 'Efectivo', '0401202001093008746500110010010000000422809280915', NULL, '0.50', '0.50', '0.00', '0.00', '0.00', '0.00', '0.00', '0.06', NULL, NULL, '0.56', '2020-01-04 12:55:20', '2020-01-04 12:55:20', NULL, NULL, 9, 6, 1, 1, 1),
(51, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, 'Cheque', '0401202001093008746500110010010000000442809280916', NULL, '0.50', '0.50', '0.00', '0.00', '0.00', '0.00', '0.00', '0.06', NULL, NULL, '0.56', '2020-01-04 12:57:02', '2020-01-04 12:57:02', NULL, NULL, 9, 6, 1, 1, 1),
(52, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, 'Cheque', '0401202001093008746500110010010000000442809280916', NULL, '0.50', '0.50', '0.00', '0.00', '0.00', '0.00', '0.00', '0.06', NULL, NULL, '0.56', '2020-01-04 13:06:05', '2020-01-04 13:06:05', NULL, NULL, 9, 6, 1, 1, 1),
(53, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, 'Cheque', '0401202001093008746500110010010000000442809280916', NULL, '0.50', '0.50', '0.00', '0.00', '0.00', '0.00', '0.00', '0.06', NULL, NULL, '0.56', '2020-01-04 13:06:12', '2020-01-04 13:06:12', NULL, NULL, 9, 6, 1, 1, 1),
(54, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, 'Cheque', '0401202001093008746500110010010000000442809280916', NULL, '0.50', '0.50', '0.00', '0.00', '0.00', '0.00', '0.00', '0.06', NULL, NULL, '0.56', '2020-01-04 13:09:24', '2020-01-04 13:09:24', NULL, NULL, 9, 6, 1, 1, 1),
(55, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, 'Cheque', '0401202001093008746500110010010000000442809280916', NULL, '0.50', '0.50', '0.00', '0.00', '0.00', '0.00', '0.00', '0.06', NULL, NULL, '0.56', '2020-01-04 13:09:25', '2020-01-04 13:09:25', NULL, NULL, 9, 6, 1, 1, 1),
(56, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, 'Tarjeta', '0401202001093008746500110010010000000492809280913', NULL, '0.50', '0.50', '0.00', '0.00', '0.00', '0.00', '0.00', '0.06', NULL, NULL, '0.56', '2020-01-04 13:09:56', '2020-01-04 13:09:56', NULL, NULL, 9, 6, 1, 1, 1),
(57, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, 'Cheque', '0401202001093008746500110010010000000502809280919', NULL, '0.50', '0.50', '0.00', '0.00', '0.00', '0.00', '0.00', '0.06', NULL, NULL, '0.56', '2020-01-04 13:16:46', '2020-01-04 13:16:46', NULL, NULL, 9, 6, 1, 1, 1),
(58, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, NULL, '0401202001093008746500110010010000000512809280914', NULL, '0.50', '0.50', '0.00', '0.00', '0.00', '0.00', '0.00', '0.06', NULL, NULL, '0.56', '2020-01-04 13:20:36', '2020-01-04 13:20:36', NULL, NULL, 9, 6, 1, 1, 1),
(59, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, NULL, '0401202001093008746500110010010000000522809280911', NULL, '0.50', '0.50', '0.00', '0.00', '0.00', '0.00', '0.00', '0.06', NULL, NULL, '0.56', '2020-01-04 13:22:32', '2020-01-04 13:22:32', NULL, NULL, 9, 6, 1, 1, 1),
(60, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, 'Cheque', '0401202001093008746500110010010000000532809280915', NULL, '0.50', '0.50', '0.00', '0.00', '0.00', '0.00', '0.00', '0.06', NULL, NULL, '0.56', '2020-01-04 13:30:53', '2020-01-04 13:30:53', NULL, NULL, 9, 6, 1, 1, 1),
(61, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, 'Cheque', '0401202001093008746500110010010000000532809280915', NULL, '0.50', '0.50', '0.00', '0.00', '0.00', '0.00', '0.00', '0.06', NULL, NULL, '0.56', '2020-01-04 13:34:25', '2020-01-04 13:34:25', NULL, NULL, 9, 6, 1, 1, 1),
(62, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, 'Cheque', '0401202001093008746500110010010000000552809280916', NULL, '0.50', '0.50', '0.00', '0.00', '0.00', '0.00', '0.00', '0.06', NULL, NULL, '0.56', '2020-01-04 13:38:55', '2020-01-04 13:38:55', NULL, NULL, 9, 6, 1, 1, 1),
(63, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, 'Efectivo', '0401202001093008746500110010010000000562809280911', NULL, '0.50', '0.50', '0.00', '0.00', '0.00', '0.00', '0.00', '0.06', NULL, NULL, '0.56', '2020-01-04 13:45:41', '2020-01-04 13:45:41', NULL, NULL, 9, 6, 1, 1, 1),
(64, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, 'Cheque', '0401202001093008746500110010010000000572809280917', NULL, '0.50', '0.50', '0.00', '0.00', '0.00', '0.00', '0.00', '0.06', NULL, NULL, '0.56', '2020-01-04 13:56:49', '2020-01-04 13:56:49', NULL, NULL, 9, 6, 1, 1, 1),
(65, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, 'Efectivo', '0401202001093008746500110010010000000582809280912', NULL, '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, NULL, '1.00', '2020-01-04 14:01:48', '2020-01-04 14:01:48', NULL, NULL, 9, 6, 1, 1, 1),
(66, 'Error', 1, 1, 1, '2019-12-30', NULL, NULL, NULL, '3012201901093008746500110010010000000592809280911', NULL, '10.00', '0.00', '0.00', '10.00', '3.00', '0.00', '0.00', '0.00', NULL, NULL, '10.00', '2020-01-04 14:03:33', '2020-01-04 14:03:33', NULL, NULL, 9, 6, 1, 1, 1),
(67, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, 'Cheque', '0401202001093008746500110010010000000602809280913', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-04 14:17:37', '2020-01-04 14:17:37', NULL, NULL, 9, 6, 1, 1, 1),
(68, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, 'Tarjeta', '0401202001093008746500110010010000000612809280919', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-04 14:23:15', '2020-01-04 14:23:15', NULL, NULL, 9, 6, 1, 1, 1),
(69, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, NULL, '0401202001093008746500110010010000000622809280914', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-04 14:25:02', '2020-01-04 14:25:02', NULL, NULL, 9, 6, 1, 1, 1),
(70, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, 'Efectivo', '0401202001093008746500110010010000000632809280911', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-04 14:28:50', '2020-01-04 14:28:50', NULL, NULL, 9, 6, 1, 1, 1),
(71, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, 'Efectivo', '0401202001093008746500110010010000000632809280911', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-04 14:29:30', '2020-01-04 14:29:30', NULL, NULL, 9, 6, 1, 1, 1),
(72, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, 'Efectivo', '0401202001093008746500110010010000000632809280911', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-04 14:30:23', '2020-01-04 14:30:23', NULL, NULL, 9, 6, 1, 1, 1),
(73, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, 'Tarjeta', '0401202001093008746500110010010000000662809280916', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-04 14:31:19', '2020-01-04 14:31:19', NULL, NULL, 9, 6, 1, 1, 1),
(74, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, 'Tarjeta', '0401202001093008746500110010010000000662809280916', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-04 14:32:01', '2020-01-04 14:32:01', NULL, NULL, 9, 6, 1, 1, 1),
(75, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, 'Tarjeta', '0401202001093008746500110010010000000662809280916', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-04 14:32:37', '2020-01-04 14:32:37', NULL, NULL, 9, 6, 1, 1, 1),
(76, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, NULL, '0401202001093008746500110010010000000692809280912', NULL, '1.00', '0.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, NULL, '1.00', '2020-01-04 14:33:38', '2020-01-04 14:33:38', NULL, NULL, 9, 6, 1, 1, 1),
(77, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, NULL, '0401202001093008746500110010010000000702809280918', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-04 14:34:11', '2020-01-04 14:34:11', NULL, NULL, 9, 6, 1, 1, 1),
(78, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, NULL, '0401202001093008746500110010010000000712809280913', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-04 14:46:59', '2020-01-04 14:46:59', NULL, NULL, 9, 6, 1, 1, 1),
(79, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, NULL, '0401202001093008746500110010010000000722809280919', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-04 14:47:51', '2020-01-04 14:47:51', NULL, NULL, 9, 6, 1, 1, 1),
(80, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, NULL, '0401202001093008746500110010010000000732809280914', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-04 15:32:28', '2020-01-04 15:32:28', NULL, NULL, 9, 6, 1, 1, 1),
(81, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, NULL, '0401202001093008746500110010010000000732809280914', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-04 15:34:51', '2020-01-04 15:34:51', NULL, NULL, 9, 6, 1, 1, 1),
(82, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, NULL, '0401202001093008746500110010010000000732809280914', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-04 15:35:24', '2020-01-04 15:35:24', NULL, NULL, 9, 6, 1, 1, 1),
(83, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, NULL, '0401202001093008746500110010010000000732809280914', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-04 15:35:55', '2020-01-04 15:35:55', NULL, NULL, 9, 6, 1, 1, 1),
(84, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, NULL, '0401202001093008746500110010010000000772809280916', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-04 15:40:28', '2020-01-04 15:40:28', NULL, NULL, 9, 6, 1, 1, 1),
(85, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, NULL, '0401202001093008746500110010010000000782809280911', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-04 15:42:44', '2020-01-04 15:42:44', NULL, NULL, 9, 6, 1, 1, 1),
(86, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, NULL, '0401202001093008746500110010010000000782809280911', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-04 15:44:16', '2020-01-04 15:44:16', NULL, NULL, 9, 6, 1, 1, 1),
(87, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, NULL, '0401202001093008746500110010010000000782809280911', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-04 15:44:58', '2020-01-04 15:44:58', NULL, NULL, 9, 6, 1, 1, 1),
(88, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, NULL, '0401202001093008746500110010010000000782809280911', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-04 15:45:30', '2020-01-04 15:45:30', NULL, NULL, 9, 6, 1, 1, 1),
(89, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, NULL, '0401202001093008746500110010010000000782809280911', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-04 15:47:23', '2020-01-04 15:47:23', NULL, NULL, 9, 6, 1, 1, 1),
(90, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, NULL, '0401202001093008746500110010010000000782809280911', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-04 15:47:45', '2020-01-04 15:47:45', NULL, NULL, 9, 6, 1, 1, 1),
(91, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, NULL, '0401202001093008746500110010010000000842809280914', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-04 15:48:30', '2020-01-04 15:48:30', NULL, NULL, 9, 6, 1, 1, 1),
(92, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, NULL, '0401202001093008746500110010010000000842809280914', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-04 15:49:48', '2020-01-04 15:49:48', NULL, NULL, 9, 6, 1, 1, 1),
(93, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, NULL, '0401202001093008746500110010010000000842809280914', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-04 15:50:00', '2020-01-04 15:50:00', NULL, NULL, 9, 6, 1, 1, 1),
(94, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, NULL, '0401202001093008746500110010010000000842809280914', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-04 15:52:52', '2020-01-04 15:52:52', NULL, NULL, 9, 6, 1, 1, 1),
(95, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, NULL, '0401202001093008746500110010010000000842809280914', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-04 15:53:51', '2020-01-04 15:53:51', NULL, NULL, 9, 6, 1, 1, 1),
(96, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, NULL, '0401202001093008746500110010010000000842809280914', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-04 15:54:04', '2020-01-04 15:54:04', NULL, NULL, 9, 6, 1, 1, 1),
(97, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, NULL, '0401202001093008746500110010010000000842809280914', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-04 15:54:44', '2020-01-04 15:54:44', NULL, NULL, 9, 6, 1, 1, 1),
(98, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, NULL, '0401202001093008746500110010010000000842809280914', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-04 15:57:12', '2020-01-04 15:57:12', NULL, NULL, 9, 6, 1, 1, 1),
(99, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, NULL, '0401202001093008746500110010010000000842809280914', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-04 16:02:41', '2020-01-04 16:02:41', NULL, NULL, 9, 6, 1, 1, 1),
(100, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, NULL, '0401202001093008746500110010010000000842809280914', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-04 16:06:56', '2020-01-04 16:06:56', NULL, NULL, 9, 6, 1, 1, 1),
(101, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, NULL, '0401202001093008746500110010010000000842809280914', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-04 16:16:48', '2020-01-04 16:16:48', NULL, NULL, 9, 6, 1, 1, 1),
(102, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, NULL, '0401202001093008746500110010010000000842809280914', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-04 16:18:35', '2020-01-04 16:18:35', NULL, NULL, 9, 6, 1, 1, 1),
(103, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, 'Cheque', '0401202001093008746500110010010000000962809280911', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', '0.50', NULL, '1.62', '2020-01-04 16:20:43', '2020-01-04 16:20:43', NULL, NULL, 9, 6, 1, 1, 1),
(104, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, 'Cheque', '0401202001093008746500110010010000000962809280911', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', '0.50', NULL, '1.62', '2020-01-04 16:21:25', '2020-01-04 16:21:25', NULL, NULL, 9, 6, 1, 1, 1),
(105, 'Error', 1, 1, 1, '2020-01-04', NULL, NULL, 'Cheque', '0401202001093008746500110010010000000962809280911', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', '0.50', NULL, '1.62', '2020-01-04 16:22:12', '2020-01-04 16:22:12', NULL, NULL, 9, 6, 1, 1, 1),
(106, 'Error', 0, 1, 1, '2020-01-06', '2020-01-06', 1, 'Efectivo', NULL, NULL, '150.00', '150.00', '0.00', '0.00', '0.00', '0.00', '0.00', '18.00', NULL, NULL, '168.00', '2020-01-06 09:56:40', '2020-01-06 09:56:52', NULL, NULL, 9, 6, 1, 1, 1),
(107, 'Error', 1, 1, 1, '2020-01-06', NULL, NULL, 'Efectivo', '0601202001093008746500110010010000001002809280919', NULL, '150.00', '150.00', '0.00', '0.00', '0.00', '0.00', '0.00', '18.00', NULL, NULL, '168.00', '2020-01-06 09:56:52', '2020-01-06 09:56:52', NULL, NULL, 9, 6, 1, 1, 1),
(108, 'Error', 1, 1, 1, '2020-01-06', NULL, NULL, 'Efectivo', '0601202001093008746500110010010000001012809280914', NULL, '150.00', '150.00', '0.00', '0.00', '0.00', '0.00', '0.00', '18.00', NULL, NULL, '168.00', '2020-01-06 09:57:06', '2020-01-06 09:57:06', NULL, NULL, 9, 6, 1, 1, 1),
(109, 'No autorizado', 1, 1, 1, '2020-01-06', NULL, NULL, 'Efectivo', '0601202001093008746500110010010000001022809280911', NULL, '150.00', '150.00', '0.00', '0.00', '0.00', '0.00', '0.00', '18.00', NULL, NULL, '168.00', '2020-01-06 09:57:16', '2020-01-06 15:58:23', NULL, NULL, 9, 6, 1, 1, 1),
(110, 'Enviado', 1, 1, 1, '2020-01-06', NULL, 1, 'Cheque', '0601202001093008746500110010010000001032809280915', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-06 15:56:02', '2020-01-06 15:56:26', NULL, NULL, 9, 6, 1, 1, 1),
(111, 'Enviado', 1, 1, 1, '2020-01-06', '2020-01-07', 0, 'Cheque', '0601202001093008746500110010010000001052809280916', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-06 15:59:16', '2020-01-06 16:00:07', NULL, NULL, 9, 6, 1, 1, 1),
(112, NULL, 1, 1, 1, '2020-01-07', NULL, 1, 'Cheque', '0701202001093008746500110010010000001052809280910', NULL, '672.00', '672.00', '0.00', '0.00', '0.00', '0.00', '0.00', '80.64', NULL, NULL, '752.64', '2020-01-07 10:36:45', '2020-01-07 10:36:45', NULL, NULL, 9, 6, 1, 1, 1),
(113, 'No autorizado', 1, 1, 1, '2020-01-07', NULL, 1, 'Cheque', '0701202001093008746500110010010000001052809280910', NULL, '672.00', '672.00', '0.00', '0.00', '0.00', '0.00', '0.00', '80.64', NULL, NULL, '752.64', '2020-01-07 10:36:54', '2020-01-07 10:52:32', NULL, NULL, 9, 6, 1, 1, 1),
(114, 'No autorizado', 1, 1, 1, '2020-01-07', NULL, 1, 'Cheque', '0701202001093008746500110010010000001052809280910', NULL, '672.00', '672.00', '0.00', '0.00', '0.00', '0.00', '0.00', '80.64', NULL, NULL, '752.64', '2020-01-07 10:39:27', '2020-01-07 10:52:09', NULL, NULL, 9, 6, 1, 1, 1),
(115, 'No autorizado', 1, 1, 1, '2020-01-07', NULL, 1, 'Cheque', '0701202001093008746500110010010000001052809280910', NULL, '672.00', '672.00', '0.00', '0.00', '0.00', '0.00', '0.00', '80.64', NULL, NULL, '752.64', '2020-01-07 10:40:59', '2020-01-07 11:06:50', NULL, NULL, 9, 6, 1, 1, 1),
(116, 'Enviado', 1, 1, 1, '2020-01-07', NULL, 1, 'Cheque', '0701202001093008746500110010010000001092809280912', NULL, '672.00', '672.00', '0.00', '0.00', '0.00', '0.00', '0.00', '80.64', NULL, NULL, '752.64', '2020-01-07 10:49:59', '2020-01-07 10:51:35', NULL, NULL, 9, 6, 1, 1, 1),
(117, 'Enviado', 1, 1, 1, '2020-01-07', NULL, 1, NULL, '0701202001093008746500110010010000001102809280918', NULL, '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.12', NULL, NULL, '1.12', '2020-01-07 11:13:53', '2020-01-07 11:14:27', NULL, NULL, 9, 6, 1, 1, 1),
(118, 'Enviado', 1, 1, 1, '2020-01-07', NULL, 1, NULL, '0701202001093008746500110010010000001112809280913', NULL, '672.00', '672.00', '0.00', '0.00', '0.00', '0.00', '0.00', '80.64', NULL, NULL, '752.64', '2020-01-07 11:18:08', '2020-01-07 11:18:43', NULL, NULL, 9, 6, 1, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_compra`
--

CREATE TABLE `factura_compra` (
  `id_factcompra` int(11) NOT NULL,
  `orden_compra` varchar(50) DEFAULT NULL,
  `destino_pago` varchar(50) DEFAULT NULL,
  `gasto_import` varchar(50) DEFAULT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `fech_emision` date DEFAULT NULL,
  `fech_validez` date DEFAULT NULL,
  `nro_autorizacion` varchar(50) DEFAULT NULL,
  `subtotal_sin_impuesto` decimal(10,2) DEFAULT NULL,
  `subtotal_12` decimal(10,2) DEFAULT NULL,
  `subtotal_0` decimal(10,2) DEFAULT NULL,
  `subtotal_no_obj_iva` decimal(10,2) DEFAULT NULL,
  `descuento` decimal(11,2) DEFAULT NULL,
  `valor_ice` decimal(10,2) DEFAULT NULL,
  `valor_irbpnr` decimal(10,2) DEFAULT NULL,
  `iva_12` decimal(10,2) DEFAULT NULL,
  `total_factura` decimal(10,2) DEFAULT NULL,
  `fcrea` datetime DEFAULT current_timestamp(),
  `fmodifica` datetime DEFAULT NULL,
  `id_sustento` int(11) DEFAULT NULL,
  `id_proveedor` int(11) DEFAULT NULL,
  `id_cliente_asoc` int(11) DEFAULT NULL,
  `id_importacion` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_empresa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `factura_compra`
--

INSERT INTO `factura_compra` (`id_factcompra`, `orden_compra`, `destino_pago`, `gasto_import`, `descripcion`, `fech_emision`, `fech_validez`, `nro_autorizacion`, `subtotal_sin_impuesto`, `subtotal_12`, `subtotal_0`, `subtotal_no_obj_iva`, `descuento`, `valor_ice`, `valor_irbpnr`, `iva_12`, `total_factura`, `fcrea`, `fmodifica`, `id_sustento`, `id_proveedor`, `id_cliente_asoc`, `id_importacion`, `id_user`, `id_empresa`) VALUES
(14, '21221', 'Pago a residentes', '1', 'ecu mamn', '2020-01-07', '2020-01-08', '135464644846644631', '840.00', '647.00', '0.00', '622.00', '75.00', '0.00', '0.00', '77.64', '1346.64', '2020-01-07 21:51:34', '2020-01-07 21:51:34', 6, 50, 9, 2, 6, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupo`
--

CREATE TABLE `grupo` (
  `id_grupo` int(11) NOT NULL,
  `nomb_grupo` varchar(45) DEFAULT NULL,
  `fcrea` datetime DEFAULT NULL,
  `fmodifica` datetime DEFAULT NULL,
  `ucrea` int(11) DEFAULT NULL,
  `umodifica` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `grupo`
--

INSERT INTO `grupo` (`id_grupo`, `nomb_grupo`, `fcrea`, `fmodifica`, `ucrea`, `umodifica`) VALUES
(1, 'GRUPO', NULL, NULL, NULL, NULL),
(2, 'MOVIMIENTO', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupo_cliente`
--

CREATE TABLE `grupo_cliente` (
  `id_grupo_cliente` int(11) NOT NULL,
  `codigo` varchar(45) DEFAULT NULL,
  `nombre_grupo` varchar(150) DEFAULT NULL,
  `cuenta` varchar(150) DEFAULT NULL,
  `cuenta_descuento` varchar(150) DEFAULT NULL,
  `cuenta_anticipo` varchar(150) DEFAULT NULL,
  `cuenta_servicio` varchar(150) DEFAULT NULL,
  `fcrea` datetime DEFAULT NULL,
  `fmodifica` datetime DEFAULT NULL,
  `ucrea` int(11) DEFAULT NULL,
  `umodifica` int(11) DEFAULT NULL,
  `id_empresa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `grupo_cliente`
--

INSERT INTO `grupo_cliente` (`id_grupo_cliente`, `codigo`, `nombre_grupo`, `cuenta`, `cuenta_descuento`, `cuenta_anticipo`, `cuenta_servicio`, `fcrea`, `fmodifica`, `ucrea`, `umodifica`, `id_empresa`) VALUES
(4, NULL, 'General', '1.1.1.01.002', '1.1.1.02.003', '1.1.1.01.001', '1.1.3.01.001', '2019-12-12 16:27:33', '2019-12-17 16:46:20', NULL, NULL, 1),
(7, NULL, 'General 3', '1.1.1.02.', '2.1.5.02.312', '6.4.1.', '6.2.2.03.02', '2019-12-18 16:35:18', '2019-12-19 15:44:46', NULL, NULL, 1),
(9, NULL, 'darwin', '1.1.1.', '1.1.1.01.002', '1.1.1.01.002', '1.1.2.01.001', NULL, NULL, NULL, NULL, 1),
(10, NULL, 'fghfh', '1.1.1.01.001', '1.1.1.02.001', '1.1.1.02.003', '1.1.2.06.', NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupo_ocupacional`
--

CREATE TABLE `grupo_ocupacional` (
  `id_grupo` int(11) NOT NULL,
  `grup_nombre` varchar(100) NOT NULL,
  `grup_estado` varchar(15) NOT NULL,
  `fcrea` date DEFAULT NULL,
  `fmodifica` date DEFAULT NULL,
  `ucrea` int(11) DEFAULT NULL,
  `umodifica` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `grupo_ocupacional`
--

INSERT INTO `grupo_ocupacional` (`id_grupo`, `grup_nombre`, `grup_estado`, `fcrea`, `fmodifica`, `ucrea`, `umodifica`) VALUES
(2, 'Fijo', '1', NULL, NULL, NULL, NULL),
(3, 'Pasante', '1', NULL, NULL, NULL, NULL),
(4, 'Por Horas', '1', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupo_proveedor`
--

CREATE TABLE `grupo_proveedor` (
  `id_grupoprov` int(11) NOT NULL,
  `cod_grupoprov` varchar(10) DEFAULT NULL,
  `nombre_grupoprov` varchar(200) DEFAULT NULL,
  `importador` varchar(45) DEFAULT NULL,
  `cta_contable` varchar(45) DEFAULT NULL,
  `cta_descuento` varchar(45) DEFAULT NULL,
  `cta_anticipo` varchar(45) DEFAULT NULL,
  `fcrea` datetime DEFAULT current_timestamp(),
  `fmodifica` datetime DEFAULT NULL,
  `id_empresa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `grupo_proveedor`
--

INSERT INTO `grupo_proveedor` (`id_grupoprov`, `cod_grupoprov`, `nombre_grupoprov`, `importador`, `cta_contable`, `cta_descuento`, `cta_anticipo`, `fcrea`, `fmodifica`, `id_empresa`) VALUES
(1, '01', 'GENERAL', 'SI', '1.2.', '1.2.', '1.2.', '2019-12-12 11:18:27', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ice`
--

CREATE TABLE `ice` (
  `id_ice` int(11) NOT NULL,
  `nombre` varchar(300) DEFAULT NULL,
  `valor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `ice`
--

INSERT INTO `ice` (`id_ice`, `nombre`, `valor`) VALUES
(1, 'NO GRAVA ICE', 0),
(2, 'ICE - VEHICULOS MOTORIZADOS CUYO PVP SUPERIOR $60,000 USD HASTA $70,000 USD', 0),
(3, 'ICE - VEHICULOS MOTORIZADOS CUYO PVP SUPERIOR $70,000 USD', 0),
(4, 'ICE - AVIONES, AVIONETAS, HELICOPTEROS, MOTOS ACUATICAS, TRICARES, CUADRONES, YATES Y BARCOS RECREO', 0),
(5, 'ICE - SERVICIO DE TELEVISION PAGADA', 0),
(6, 'ICE - VEHICULOS HIBRIDOS O ELECTRICOS CUYO PVP SEA DE HASTA $35,000 USD', 0),
(7, 'ICE - VEHICULOS HIBRIDOS O ELECTRICOS CUYO PVP MAYOR A $35,000 USD HASTA $40,000 USD', 0),
(8, 'ICE - VEHICULOS HIBRIDOS O ELECTRICOS CUYO PVP MAYOR A $40,000 USD HASTA $50,000 USD', 0),
(9, 'ICE - VEHICULOS HIBRIDOS O ELECTRICOS CUYO PVP MAYOR A $50,000 USD HASTA $60,000 USD', 0),
(10, 'ICE - VEHICULOS HIBRIDOS O ELECTRICOS CUYO PVP MAYOR A $60,000 USD HASTA $70,000 USD', 0),
(11, 'ICE - VEHICULOS HIBRIDOS O ELECTRICOS CUYO PVP MAYOR A $70,000 USD', 0),
(12, 'ICE - PERFUME Y AGUAS DE TOCADOR', 0),
(13, 'ICE - VIDEOJUEGOS', 0),
(14, 'ICE - ARMAS DE FUEGO, ARMAS DEPORTIVAS Y MUNICIONES', 0),
(15, 'ICE - FOCOS INCANDESCENTES', 0),
(16, 'ICE - SERVICIOS DE CASINOS, SALAS DE JUEGO (BINGO O MECANICOS) Y OTROS JUEGOS DE AZAR', 0),
(17, 'ICE - CUOTAS, MEMBRECIAS, AFILIACIONES, ACCIONES Y SIMILARES QUE COBREN A SUS MIEMBROS Y USUARIOS', 0),
(18, 'ICE - CIGARRILLOS RUBIOS', 0),
(19, 'ICE - CIGARRILLOS NEGROS', 0),
(20, 'ICE - PRODUCTOS DEL TABACO Y SUCEDANEOS DEL TABACO EXCEPTO CIGARRILLOS', 0),
(21, 'ICE - ALCOHOL Y PRODUCTOS ALCOHOLICOS DISTINTOS A LA CERVEZA', 0),
(22, 'ICE - CERVEZA', 0),
(23, 'ICE - BEBIDAS GASEOSAS', 0),
(24, 'ICE - CAMIONETAS, FURGONETAS, CAMIONES Y VEHICULOS DE RESCATE CUYO PVP SEA HASTA $30,000 USD', 0),
(25, 'ICE - VEHICULOS MOTORIZADOS CUYO PVP SEA HASTA DE $20,000 USD', 0),
(26, 'ICE - VEHICULOS MOTORIZADOS EXCEPTO CAMIONETAS Y FURGONETAS ENTRE $20,000 USD Y $30,000 USD', 0),
(27, 'ICE - VEHICULOS MOTORIZADOS PVP ENTRE $30,000 USD Y $40,000 USD', 0),
(28, 'ICE - VEHICULOS MOTORIZADOS CUYO PVP SUPERIOR $40,00 USD HASTA $50,000 USD', 0),
(29, 'ICE - VEHICULOS MOTORIZADOS CUYO PVP SUPERIOR $50,000 USD HASTA $60,000 USD', 0),
(30, 'BEBIDAS NO ALCOHO?LICAS', 0),
(31, 'SERVICIO DE TELEFON?A FIJA Y PLANES QUE COMERCIALICEN ?NICAMENTE VOZ, DATOS Y SMS DEL SERVICIO M?VIL', 0),
(32, 'ICE - ALCOHOL Y PRODUCTOS ALCOHOLICOS DISTINTOS A LA CERVEZA (750 ML)', 0),
(33, 'ICE - ALCOHOL Y PRODUCTOS ALCOHOLICOS DISTINTOS A LA CERVEZA (9000 ML)', 0),
(34, 'ICE - ALCOHOL Y PRODUCTOS ALCOHOLICOS DISTINTOS A LA CERVEZA (4500 ML)', 0),
(35, 'ICE - ALCOHOL Y PRODUCTOS ALCOHOLICOS DISTINTOS A LA CERVEZA (3000 ML)', 0),
(36, 'ICE - ALCOHOL Y PRODUCTOS ALCOHOLICOS DISTINTOS A LA CERVEZA (2250 ML)', 0),
(37, 'ICE - ALCOHOL Y PRODUCTOS ALCOHOLICOS DISTINTOS A LA CERVEZA (330 ML)', 0),
(38, 'ICE - ALCOHOL Y PRODUCTOS ALCOHOLICOS DISTINTOS A LA CERVEZA (1000 ML)', 0),
(39, 'ICE - ALCOHOL Y PRODUCTOS ALCOHOLICOS DISTINTOS A LA CERVEZA 4000 ML (11% GRADO DE ALCOHOL)', 0),
(40, 'ICE - CERVEZA ARTESANAL 10L (6% GRADOS DE ALCOHOL)', 0),
(41, 'ICE - CERVEZA ARTESANAL 330ML (4.8% GRADOS DE ALCOHOL)', 0),
(42, 'ICE - CERVEZA ARTESANAL 1000ML (3% GRADOS DE ALCOHOL)', 0),
(43, 'ICE - CERVEZA ARTESANAL 20L (4.8% GRADOS DE ALCOHOL)', 0),
(44, 'ICE - ALCOHOL Y PRODUCTOS ALCOHOLICOS DISTINTOS A LA CERVEZA (5000 ML) (15% GRADOS DE ALCOHOL)', 0),
(45, 'ICE - ALCOHOL Y PRODUCTOS ALCOHOLICOS DISTINTOS A LA CERVEZA (1500 ML)', 0),
(46, 'DOYPACK 20 SHOTS X 30ML - 600ML)', 0),
(47, 'ICE - CERVEZA ARTESANAL 20L (5.2% GRADOS DE ALCOHOL)', 0),
(48, 'ICE - CERVEZA ARTESANAL 18L (6% GRADOS DE ALCOHOL)', 0),
(49, 'ICE - CERVEZA ARTESANAL 1000ML (5% GRADOS DE ALCOHOL)', 0),
(50, 'ICE - CERVEZA ARTESANAL 300ML (4.8% GRADOS DE ALCOHOL)', 0),
(51, 'ICE - CERVEZA ARTESANAL 300ML (5.2% GRADOS DE ALCOHOL)', 0),
(52, 'ICE - CERVEZA ARTESANAL 330ML (4.7% GRADOS DE ALCOHOL)', 0),
(53, 'ICE - CERVEZA ARTESANAL 330ML (6% GRADOS DE ALCOHOL)', 0),
(54, 'ICE - CERVEZA ARTESANAL 300ML (5% GRADOS DE ALCOHOL)', 0),
(55, 'ICE - CERVEZA ARTESANAL 336ML (5% GRADOS DE ALCOHOL)', 0),
(56, 'ICE - CERVEZA ARTESANAL 330ML (5% GRADOS DE ALCOHOL)', 0),
(57, 'ICE - CERVEZA ARTESANAL 330ML (3% GRADOS DE ALCOHOL)', 0),
(58, 'ICE - CERVEZA ARTESANAL 20L (4.7% GRADOS DE ALCOHOL)', 0),
(59, 'ICE - CERVEZA ARTESANAL 296ML (5% GRADOS DE ALCOHOL)', 0),
(60, 'ICE - CERVEZA ARTESANAL 1000ML (7% GRADOS DE ALCOHOL)', 0),
(61, 'ICE - CERVEZA ARTESANAL 18LTS (5% GRADOS DE ALCOHOL)', 0),
(62, 'ICE - CERVEZA ARTESANAL 18LTS (4.8% GRADOS DE ALCOHOL)', 0),
(63, 'ICE - ALCOHOL Y PRODUCTOS ALCOHOLICOS DISTINTOS A LA CERVEZA (1000 ML 11% GL)', 0),
(64, 'ICE - CERVEZA ARTESANAL 500ML (5% GRADOS DE ALCOHOL)', 0),
(65, 'ICE - ALCOHOL Y PRODUCTOS ALCOHOLICOS DISTINTOS A LA CERVEZA 750 ML ( 15% GRADOS DE ALCOHOL)', 0),
(66, 'ICE - ALCOHOL Y PRODUCTOS ALCOHOLICOS DISTINTOS A LA CERVEZA 1500 ML ( 15% GRADOS DE ALCOHOL)', 0),
(67, 'ICE - ALCOHOL Y PRODUCTOS ALCOHOLICOS DISTINTOS A LA CERVEZA 4500 ML ( 15% GRADOS DE ALCOHOL)', 0),
(68, 'ICE - ALCOHOL Y PRODUCTOS ALCOHOLICOS DISTINTOS A LA CERVEZA 9000 ML ( 15% GRADOS DE ALCOHOL)', 0),
(69, 'ICE - ALCOHOL Y PRODUCTOS ALCOHOLICOS DISTINTOS A LA CERVEZA 1000 ML (20% GRADO DE ALCOHOL)', 0),
(70, 'ICE - ALCOHOL Y PRODUCTOS ALCOHOLICOS DISTINTOS A LA CERVEZA 6000 ML (15% GRADOS DE ALCOHOL)', 0),
(71, 'ICE - CERVEZA ARTESANAL 330ML (7% GRADOS DE ALCOHOL)', 0),
(72, 'ICE - ALCOHOL Y PRODUCTOS ALCOHOLICOS DISTINTOS A LA CERVEZA 4500 ML (15% GRADO DE ALCOHOL)', 0),
(73, 'ICE - ALCOHOL Y PRODUCTOS ALCOHOLICOS DISTINTOS A LA CERVEZA 9000 ML (15% GRADO DE ALCOHOL)', 0),
(74, 'ICE - ALCOHOL Y PRODUCTOS ALCOHOLICOS DISTINTOS A LA CERVEZA 4000 ML (10% GRADO DE ALCOHOL)', 0),
(75, 'ICE - ALCOHOL Y PRODUCTOS ALCOHOLICOS DISTINTOS A LA CERVEZA 500 ML (10% GRADO DE ALCOHOL)', 0),
(76, 'ICE - ALCOHOL Y PRODUCTOS ALCOHOLICOS DISTINTOS A LA CERVEZA 500 ML (15% GRADO DE ALCOHOL)', 0),
(77, 'ICE - ALCOHOL Y PRODUCTOS ALCOHOLICOS DISTINTOS A LA CERVEZA 4000 ML (15% GRADO DE ALCOHOL)', 0),
(78, 'ICE - ALCOHOL Y PRODUCTOS ALCOHOLICOS DISTINTOS A LA CERVEZA 6000 ML (10% GRADO DE ALCOHOL)', 0),
(79, 'ICE - ALCOHOL Y PRODUCTOS ALCOHOLICOS DISTINTOS A LA CERVEZA 16000 ML (10% GRADO DE ALCOHOL)', 0),
(80, 'ICE - ALCOHOL Y PRODUCTOS ALCOHOLICOS DISTINTOS A LA CERVEZA 16000 ML (15% GRADO DE ALCOHOL)', 0),
(81, 'ICE - ALCOHOL Y PRODUCTOS ALCOHOLICOS DISTINTOS A LA CERVEZA 2000 ML (10% GRADO DE ALCOHOL)', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `importacion`
--

CREATE TABLE `importacion` (
  `id_importacion` int(11) NOT NULL,
  `cod_importacion` varchar(20) DEFAULT NULL,
  `nro_orden` varchar(50) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `fech_inicio` date DEFAULT NULL,
  `fech_fin` date DEFAULT NULL,
  `fech_embarque` date DEFAULT NULL,
  `fech_arribo` date DEFAULT NULL,
  `liquidar` varchar(20) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `costo_unit` decimal(11,2) DEFAULT NULL,
  `costo_total` decimal(11,2) DEFAULT NULL,
  `id_proveedor` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `fcrea` datetime NOT NULL DEFAULT current_timestamp(),
  `fmodifica` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `importacion`
--

INSERT INTO `importacion` (`id_importacion`, `cod_importacion`, `nro_orden`, `estado`, `fech_inicio`, `fech_fin`, `fech_embarque`, `fech_arribo`, `liquidar`, `cantidad`, `costo_unit`, `costo_total`, `id_proveedor`, `id_producto`, `fcrea`, `fmodifica`) VALUES
(1, NULL, '1555', 'Inicial', '2019-12-02', '2019-12-04', '2019-12-23', '2019-12-26', 'Si', 10, '5.00', '15.00', 1, 42, '2019-12-23 14:56:19', '2019-12-23 15:14:33'),
(2, NULL, '55', 'Inicial', '2019-12-18', '2019-12-20', '2019-12-02', '2019-12-26', 'Si', 10, '5.00', '15.00', 1, 44, '2019-12-23 15:15:14', '2019-12-23 15:15:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impuesto`
--

CREATE TABLE `impuesto` (
  `id_imp` int(11) NOT NULL,
  `cod_imp` varchar(20) DEFAULT NULL,
  `descrip_imp` varchar(200) DEFAULT NULL,
  `tipo_imp` varchar(50) DEFAULT NULL,
  `porcen_imp` decimal(11,2) DEFAULT NULL,
  `fcrea` datetime DEFAULT NULL,
  `fmodifica` datetime DEFAULT NULL,
  `id_empresa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `impuesto`
--

INSERT INTO `impuesto` (`id_imp`, `cod_imp`, `descrip_imp`, `tipo_imp`, `porcen_imp`, `fcrea`, `fmodifica`, `id_empresa`) VALUES
(2, '304', 'Servicios predomina el intelecto no relacionados con el título profesional', 'Fuente', '8.00', '2019-12-18 15:36:14', NULL, 1),
(3, '304A', 'Comisiones y demás pagos por servicios predomina intelecto no relacionados con el título profesional', 'Fuente', '8.00', '2019-12-18 15:36:14', NULL, 1),
(4, '304B', 'Pagos a notarios y registradores de la propiedad y mercantil por sus actividades ejercidas como tales', 'Fuente', '8.00', '2019-12-18 15:36:14', NULL, 1),
(5, '304C', 'Pagos a deportistas, entrenadores, árbitros, miembros del cuerpo técnico por sus actividades ejercidas como tales', 'Fuente', '8.00', '2019-12-18 15:36:14', NULL, 1),
(6, '304D', 'Pagos a artistas por sus actividades ejercidas como tales', 'Fuente', '8.00', '2019-12-18 15:36:14', NULL, 1),
(7, '304E', 'Honorarios y demás pagos por servicios de docencia', 'Fuente', '8.00', '2019-12-18 15:36:14', NULL, 1),
(8, '307', 'Servicios predomina la mano de obra', 'Fuente', '2.00', '2019-12-18 15:36:14', NULL, 1),
(9, '308', 'Utilización o aprovechamiento de la imagen o renombre', 'Fuente', '10.00', '2019-12-18 15:36:14', NULL, 1),
(10, '309', 'Servicios prestados por medios de comunicación y agencias de publicidad', 'Fuente', '1.00', '2019-12-18 15:36:14', NULL, 1),
(11, '310', 'Servicio de transporte privado de pasajeros o transporte público o privado de carga', 'Fuente', '1.00', '2019-12-18 15:36:14', NULL, 1),
(12, '311', 'Por pagos a través de liquidación de compra (nivel cultural o rusticidad)', 'Fuente', '2.00', '2019-12-18 15:36:14', NULL, 1),
(13, '312', 'Transferencia de bienes muebles de naturaleza corporal', 'Fuente', '1.00', '2019-12-18 15:36:14', NULL, 1),
(14, '312A', 'Compra de bienes de origen agrícola, avícola, pecuario, apícola, cunícula, bioacuático, y forestal', 'Fuente', '1.00', '2019-12-18 15:36:14', NULL, 1),
(15, '314A', 'Regalías por concepto de franquicias de acuerdo a Ley de Propiedad Intelectual - pago a personas naturales', 'Fuente', '8.00', '2019-12-18 15:36:14', NULL, 1),
(16, '314B', 'Cánones, derechos de autor,  marcas, patentes y similares de acuerdo a Ley de Propiedad Intelectual – pago a personas naturales', 'Fuente', '8.00', '2019-12-18 15:36:14', NULL, 1),
(17, '314C', 'Regalías por concepto de franquicias de acuerdo a Ley de Propiedad Intelectual  - pago a sociedades', 'Fuente', '8.00', '2019-12-18 15:36:14', NULL, 1),
(18, '314D', 'Cánones, derechos de autor,  marcas, patentes y similares de acuerdo a Ley de Propiedad Intelectual – pago a sociedades', 'Fuente', '8.00', '2019-12-18 15:36:14', NULL, 1),
(19, '319', 'Cuotas de arrendamiento mercantil, inclusive la de opción de compra', 'Fuente', '1.00', '2019-12-18 15:36:14', NULL, 1),
(20, '320', 'Por arrendamiento bienes inmuebles', 'Fuente', '8.00', '2019-12-18 15:36:14', NULL, 1),
(21, '322', 'Seguros y reaseguros (primas y cesiones)', 'Fuente', '0.00', '2019-12-18 15:36:14', NULL, 1),
(22, '323', 'Por rendimientos financieros pagados a naturales y sociedades  (No a IFIs)', 'Fuente', '2.00', '2019-12-18 15:36:14', NULL, 1),
(23, '323 M', 'Por RF: Inversiones en títulos valores en renta fija gravados ', 'Fuente', '2.00', '2019-12-18 15:36:14', NULL, 1),
(24, '323 N', 'Por RF: Inversiones en títulos valores en renta fija exentos', 'Fuente', '0.00', '2019-12-18 15:36:14', NULL, 1),
(25, '323 O', 'Por RF: Intereses pagados a bancos y otras entidades sometidas al control de la Superintendencia de Bancos y de la Economía Popular y Solidaria', 'Fuente', '0.00', '2019-12-18 15:36:14', NULL, 1),
(26, '323 P', 'Por RF: Intereses pagados por entidades del sector público a favor de sujetos pasivos', 'Fuente', '2.00', '2019-12-18 15:36:14', NULL, 1),
(27, '323A', 'Por RF: depósitos Cta. Corriente', 'Fuente', '2.00', '2019-12-18 15:36:14', NULL, 1),
(28, '323B1', 'Por RF:  depósitos Cta. Ahorros Sociedades', 'Fuente', '2.00', '2019-12-18 15:36:14', NULL, 1),
(29, '323E', 'Por RF: depósito a plazo fijo  gravados', 'Fuente', '2.00', '2019-12-18 15:36:14', NULL, 1),
(30, '323E2', 'Por RF: depósito a plazo fijo exentos', 'Fuente', '0.00', '2019-12-18 15:36:14', NULL, 1),
(31, '323F', 'Por rendimientos financieros: operaciones de reporto - repos', 'Fuente', '2.00', '2019-12-18 15:36:14', NULL, 1),
(32, '323G', 'Por RF: inversiones (captaciones) rendimientos distintos de aquellos pagados a IFIs', 'Fuente', '2.00', '2019-12-18 15:36:14', NULL, 1),
(33, '323H', 'Por RF: obligaciones', 'Fuente', '2.00', '2019-12-18 15:36:14', NULL, 1),
(34, '323I', 'Por RF: bonos convertible en acciones', 'Fuente', '2.00', '2019-12-18 15:36:14', NULL, 1),
(35, '323Q', 'Por RF: Otros intereses y rendimientos financieros gravados ', 'Fuente', '2.00', '2019-12-18 15:36:14', NULL, 1),
(36, '323R', 'Por RF: Otros intereses y rendimientos financieros exentos', 'Fuente', '0.00', '2019-12-18 15:36:14', NULL, 1),
(37, '324A', 'Por RF: Intereses en operaciones de crédito entre instituciones del sistema financiero y entidades economía popular y solidaria. ', 'Fuente', '1.00', '2019-12-18 15:36:14', NULL, 1),
(38, '324B', 'Por RF: Por inversiones entre instituciones del sistema financiero y entidades economía popular y solidaria. ', 'Fuente', '1.00', '2019-12-18 15:36:14', NULL, 1),
(39, '325', 'Anticipo dividendos', 'Fuente', '22.00', '2019-12-18 15:36:14', NULL, 1),
(40, '325A', 'Dividendos anticipados préstamos accionistas, beneficiarios o partícipes', 'Fuente', '22.00', '2019-12-18 15:36:14', NULL, 1),
(41, '326', 'Dividendos distribuidos que correspondan al impuesto a la renta único establecido en el art. 27 de la lrti', 'Fuente', '0.00', '2019-12-18 15:36:14', NULL, 1),
(42, '327', 'Dividendos distribuidos a personas naturales residentes cuando la sociedad que distribuye aplicó tarifa del 22% IR', 'Fuente', '0.00', '2019-12-18 15:36:14', NULL, 1),
(43, '328', 'Dividendos distribuidos a sociedades residentes', 'Fuente', '0.00', '2019-12-18 15:36:14', NULL, 1),
(44, '329', 'dividendos distribuidos a fideicomisos residentes', 'Fuente', '0.00', '2019-12-18 15:36:14', NULL, 1),
(45, '330', 'Dividendos gravados distribuidos en acciones (reinversión de utilidades sin derecho a reducción tarifa IR) cuando la sociedad que distribuye aplicó tarifa del 22% IR', 'Fuente', '0.00', '2019-12-18 15:36:14', NULL, 1),
(46, '331', 'Dividendos exentos distribuidos en acciones (reinversión de utilidades con derecho a reducción tarifa IR) ', 'Fuente', '0.00', '2019-12-18 15:36:14', NULL, 1),
(47, '332', 'Otras compras de bienes y servicios no sujetas a retención', 'Fuente', '0.00', '2019-12-18 15:36:14', NULL, 1),
(48, '332A', 'Por la enajenación ocasional de acciones o participaciones y títulos valores', 'Fuente', '0.00', '2019-12-18 15:36:14', NULL, 1),
(49, '332B', 'Compra de bienes inmuebles', 'Fuente', '0.00', '2019-12-18 15:36:14', NULL, 1),
(50, '332C', 'Transporte público de pasajeros', 'Fuente', '0.00', '2019-12-18 15:36:14', NULL, 1),
(51, '332D', 'Pagos en el país por transporte de pasajeros o transporte internacional de carga, a compañías nacionales o extranjeras de aviación o marítimas', 'Fuente', '0.00', '2019-12-18 15:36:14', NULL, 1),
(52, '332E', 'Valores entregados por las cooperativas de transporte a sus socios', 'Fuente', '0.00', '2019-12-18 15:36:14', NULL, 1),
(53, '332F', 'Compraventa de divisas distintas al dólar de los Estados Unidos de América', 'Fuente', '0.00', '2019-12-18 15:36:14', NULL, 1),
(54, '332G', 'Pagos con tarjeta de crédito ', 'Fuente', '0.00', '2019-12-18 15:36:14', NULL, 1),
(55, '332H', 'Pago al exterior tarjeta de crédito reportada por la Emisora de tarjeta de crédito, solo recap', 'Fuente', '0.00', '2019-12-18 15:36:14', NULL, 1),
(56, '332I', 'Pago a través de convenio de debito (Clientes IFI`s)', 'Fuente', '0.00', '2019-12-18 15:36:14', NULL, 1),
(57, '333', 'Enajenación de derechos representativos de capital y otros derechos cotizados en bolsa ecuatoriana', 'Fuente', '0.00', '2019-12-18 15:36:14', NULL, 1),
(58, '334', 'Enajenación de derechos representativos de capital y otros derechos no cotizados en bolsa ecuatoriana', 'Fuente', '1.00', '2019-12-18 15:36:14', NULL, 1),
(59, '335', 'Por loterías, rifas, apuestas y similares', 'Fuente', '15.00', '2019-12-18 15:36:14', NULL, 1),
(60, '336', 'Por venta de combustibles a comercializadoras', 'Fuente', '0.00', '2019-12-18 15:36:14', NULL, 1),
(61, '337', 'Por venta de combustibles a distribuidores', 'Fuente', '0.00', '2019-12-18 15:36:14', NULL, 1),
(62, '338', 'Compra local de banano a productor', 'Fuente', '0.00', '2019-12-18 15:36:14', NULL, 1),
(63, '339', 'Liquidación impuesto único a la venta local de banano de producción propia', 'Fuente', '100.00', '2019-12-18 15:36:14', NULL, 1),
(64, '340', 'Impuesto único a la exportación de banano de producción propia - componente 1', 'Fuente', '0.00', '2019-12-18 15:36:14', NULL, 1),
(65, '341', 'Impuesto único a la exportación de banano de producción propia - componente 2', 'Fuente', '2.00', '2019-12-18 15:36:14', NULL, 1),
(66, '342', 'Impuesto único a la exportación de banano producido por terceros', 'Fuente', '2.00', '2019-12-18 15:36:14', NULL, 1),
(67, '342A', 'Impuesto único a la exportación de banano producido por terceros de Asociaciones de micro y pequeños productores hasta 1000 cajas por semana por cada socio.', 'Fuente', '2.00', '2019-12-18 15:36:14', NULL, 1),
(68, '342B', 'Impuesto único a la exportación de banano producido por terceros de Asociaciones de micro, pequeños y medianos productores', 'Fuente', '1.00', '2019-12-18 15:36:14', NULL, 1),
(69, '343A', 'Por energía eléctrica', 'Fuente', '1.00', '2019-12-18 15:36:14', NULL, 1),
(70, '343B', 'Por actividades de construcción de obra material inmueble, urbanización, lotización o actividades similares', 'Fuente', '1.00', '2019-12-18 15:36:14', NULL, 1),
(71, '344', 'Otras retenciones aplicables el 2%', 'Fuente', '2.00', '2019-12-18 15:36:14', NULL, 1),
(72, '344A', 'Pago local tarjeta de crédito reportada por la Emisora de tarjeta de crédito, solo recap', 'Fuente', '2.00', '2019-12-18 15:36:14', NULL, 1),
(73, '346A', 'Ganancias de capital', 'Fuente', '10.00', '2019-12-18 15:36:14', NULL, 1),
(74, '500', 'Pago al exterior - Rentas Inmobiliarias', 'Fuente', '22.00', '2019-12-18 15:36:14', NULL, 1),
(75, '501', 'Pago al exterior - Beneficios Empresariales', 'Fuente', '22.00', '2019-12-18 15:36:14', NULL, 1),
(76, '502', 'Pago al exterior - Servicios Empresariales', 'Fuente', '22.00', '2019-12-18 15:36:14', NULL, 1),
(77, '503', 'Pago al exterior - Navegación Marítima y/o aérea', 'Fuente', '22.00', '2019-12-18 15:36:14', NULL, 1),
(78, '504', 'Pago al exterior- Dividendos distribuidos a personas naturales', 'Fuente', '0.00', '2019-12-18 15:36:14', NULL, 1),
(79, '504A', 'Pago al exterior - Dividendos a sociedades no paraíso fiscal cuyo beneficiario efectivo sea una PN residente en el Ecuador- Cuando la sociedad que distribuye aplicó tarifa del 22% IR', 'Fuente', '0.00', '2019-12-18 15:36:14', NULL, 1),
(80, '504B', 'Pago al exterior - Anticipo dividendos', 'Fuente', '22.00', '2019-12-18 15:36:14', NULL, 1),
(81, '504C', 'Pago al exterior - Dividendos anticipados préstamos accionistas, beneficiarios o partícipes', 'Fuente', '22.00', '2019-12-18 15:36:14', NULL, 1),
(82, '504D', 'Pago al exterior - Dividendos a fideicomisos no paraíso fiscal cuyo beneficiario efectivo sea una PN residente en el Ecuador- Cuando la sociedad que distribuye aplicó tarifa del 22% IR', 'Fuente', '0.00', '2019-12-18 15:36:14', NULL, 1),
(83, '504E', 'Pago al exterior- Dividendos distribuidos a personas naturales (paraísos fiscales)', 'Fuente', '0.00', '2019-12-18 15:36:14', NULL, 1),
(84, '504F', 'Pago al exterior - Dividendos a sociedades  (paraísos fiscales)', 'Fuente', '13.00', '2019-12-18 15:36:14', NULL, 1),
(85, '504G', 'Pago al exterior - Anticipo dividendos  (paraísos fiscales)', 'Fuente', '25.00', '2019-12-18 15:36:14', NULL, 1),
(86, '504H', 'Pago al exterior - Dividendos a fideicomisos  (paraísos fiscales)', 'Fuente', '13.00', '2019-12-18 15:36:14', NULL, 1),
(87, '505', 'Pago al exterior - Rendimientos financieros', 'Fuente', '22.00', '2019-12-18 15:36:14', NULL, 1),
(88, '505A', 'Pago al exterior – Intereses de créditos de Instituciones Financieras del exterior', 'Fuente', '22.00', '2019-12-18 15:36:14', NULL, 1),
(89, '505B', 'Pago al exterior – Intereses de créditos de gobierno a gobierno', 'Fuente', '22.00', '2019-12-18 15:36:14', NULL, 1),
(90, '505C', 'Pago al exterior – Intereses de créditos de organismos multilaterales', 'Fuente', '22.00', '2019-12-18 15:36:14', NULL, 1),
(91, '505D', 'Pago al exterior - Intereses por financiamiento de proveedores externos', 'Fuente', '22.00', '2019-12-18 15:36:14', NULL, 1),
(92, '505E', 'Pago al exterior - Intereses de otros créditos externos', 'Fuente', '22.00', '2019-12-18 15:36:14', NULL, 1),
(93, '505F', 'Pago al exterior - Otros Intereses y Rendimientos Financieros', 'Fuente', '35.00', '2019-12-18 15:36:14', NULL, 1),
(94, '509', 'Pago al exterior - Cánones, derechos de autor,  marcas, patentes y similares', 'Fuente', '22.00', '2019-12-18 15:36:14', NULL, 1),
(95, '509A', 'Pago al exterior - Regalías por concepto de franquicias', 'Fuente', '22.00', '2019-12-18 15:36:14', NULL, 1),
(96, '510', 'Pago al exterior - Ganancias de capital', 'Fuente', '22.00', '2019-12-18 15:36:14', NULL, 1),
(97, '511', 'Pago al exterior - Servicios profesionales independientes', 'Fuente', '22.00', '2019-12-18 15:36:14', NULL, 1),
(98, '512', 'Pago al exterior - Servicios profesionales dependientes', 'Fuente', '22.00', '2019-12-18 15:36:14', NULL, 1),
(99, '513', 'Pago al exterior - Artistas', 'Fuente', '22.00', '2019-12-18 15:36:14', NULL, 1),
(100, '513A', 'Pago al exterior - Deportistas', 'Fuente', '22.00', '2019-12-18 15:36:14', NULL, 1),
(101, '514', 'Pago al exterior - Participación de consejeros', 'Fuente', '22.00', '2019-12-18 15:36:14', NULL, 1),
(102, '515', 'Pago al exterior - Entretenimiento Público', 'Fuente', '22.00', '2019-12-18 15:36:14', NULL, 1),
(103, '516', 'Pago al exterior - Pensiones', 'Fuente', '22.00', '2019-12-18 15:36:14', NULL, 1),
(104, '517', 'Pago al exterior - Reembolso de Gastos', 'Fuente', '22.00', '2019-12-18 15:36:14', NULL, 1),
(105, '518', 'Pago al exterior - Funciones Públicas', 'Fuente', '22.00', '2019-12-18 15:36:14', NULL, 1),
(106, '519', 'Pago al exterior - Estudiantes', 'Fuente', '22.00', '2019-12-18 15:36:14', NULL, 1),
(107, '520', 'Pago al exterior - Otros conceptos de ingresos gravados', 'Fuente', '22.00', '2019-12-18 15:36:14', NULL, 1),
(108, '520A', 'Pago al exterior - Pago a proveedores de servicios hoteleros y turísticos en el exterior', 'Fuente', '22.00', '2019-12-18 15:36:14', NULL, 1),
(109, '520B', 'Pago al exterior - Arrendamientos mercantil internacional', 'Fuente', '22.00', '2019-12-18 15:36:14', NULL, 1),
(110, '520D', 'Pago al exterior - Comisiones por exportaciones y por promoción de turismo receptivo', 'Fuente', '22.00', '2019-12-18 15:36:14', NULL, 1),
(111, '520E', 'Pago al exterior - Por las empresas de transporte marítimo o aéreo y por empresas pesqueras de alta mar, por su actividad.', 'Fuente', '22.00', '2019-12-18 15:36:14', NULL, 1),
(112, '520F', 'Pago al exterior - Por las agencias internacionales de prensa', 'Fuente', '22.00', '2019-12-18 15:36:14', NULL, 1),
(113, '520G', 'Pago al exterior - Contratos de fletamento de naves para empresas de transporte aéreo o marítimo internacional', 'Fuente', '22.00', '2019-12-18 15:36:14', NULL, 1),
(114, '521', 'Pago al exterior - Enajenación de derechos representativos de capital y otros derechos ', 'Fuente', '5.00', '2019-12-18 15:36:14', NULL, 1),
(115, '522A', 'Pago al exterior - Servicios técnicos, administrativos o de consultoría y regalías con convenio de doble tributación', 'Fuente', '100.00', '2019-12-18 15:36:14', NULL, 1),
(116, '522B', 'Pago al exterior - Servicios técnicos, administrativos o de consultoría y regalías sin convenio de doble tributación', 'Fuente', '22.00', '2019-12-18 15:36:14', NULL, 1),
(117, '522C', 'Pago al exterior - Servicios técnicos, administrativos o de consultoría y regalías en paraísos fiscales', 'Fuente', '35.00', '2019-12-18 15:36:14', NULL, 1),
(118, '523A', 'Pago al exterior - Seguros y reaseguros (primas y cesiones)  con convenio de doble tributación', 'Fuente', '100.00', '2019-12-18 15:36:14', NULL, 1),
(119, '523B', 'Pago al exterior - Seguros y reaseguros (primas y cesiones) sin convenio de doble tributación', 'Fuente', '22.00', '2019-12-18 15:36:14', NULL, 1),
(120, '523C', 'Pago al exterior - Seguros y reaseguros (primas y cesiones) en paraísos fiscales', 'Fuente', '35.00', '2019-12-18 15:36:14', NULL, 1),
(121, '524', 'Pago al exterior - Otros pagos al exterior no sujetos a retención ', 'Fuente', '0.00', '2019-12-18 15:36:14', NULL, 1),
(122, '721', 'Retención del 10%', 'Iva', '10.00', '2019-12-18 15:36:14', NULL, 1),
(123, '723', 'Retención del 20%', 'Iva', '20.00', '2019-12-18 15:36:14', NULL, 1),
(124, '725', 'Retención del 30%', 'Iva', '30.00', '2019-12-18 15:36:14', NULL, 1),
(125, '727', 'Retención del 50%', 'Iva', '50.00', '2019-12-18 15:36:14', NULL, 1),
(126, '729', 'Retención del 70%', 'Iva', '70.00', '2019-12-18 15:36:14', NULL, 1),
(127, '731', 'Retención del 100%', 'Iva', '100.00', '2019-12-18 15:36:14', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impuesto_retencion`
--

CREATE TABLE `impuesto_retencion` (
  `id_impret` int(11) NOT NULL,
  `id_retencion` int(11) DEFAULT NULL,
  `id_impuesto` int(11) DEFAULT NULL,
  `porcentaje` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `impuesto_retencion`
--

INSERT INTO `impuesto_retencion` (`id_impret`, `id_retencion`, `id_impuesto`, `porcentaje`) VALUES
(1, 6, 63, 100),
(2, 6, 115, 100),
(3, 6, 118, 100),
(4, 6, 127, 100),
(5, 13, 63, 100),
(6, 13, 115, 100),
(7, 13, 118, 100),
(8, 13, 127, 100),
(9, 5, 126, 70),
(10, 12, 126, 70),
(11, 1, 10, 1),
(12, 1, 11, 1),
(13, 1, 13, 1),
(14, 1, 14, 1),
(15, 1, 19, 1),
(16, 1, 37, 1),
(17, 1, 38, 1),
(18, 1, 58, 1),
(19, 1, 68, 1),
(20, 1, 69, 1),
(21, 1, 70, 1),
(22, 2, 8, 2),
(23, 2, 12, 2),
(24, 2, 22, 2),
(25, 2, 23, 2),
(26, 2, 26, 2),
(27, 2, 27, 2),
(32, 2, 28, 2),
(33, 2, 29, 2),
(34, 2, 31, 2),
(35, 2, 32, 2),
(36, 2, 33, 2),
(37, 2, 34, 2),
(38, 2, 35, 2),
(39, 2, 65, 2),
(40, 2, 66, 2),
(41, 2, 67, 2),
(42, 2, 71, 2),
(43, 2, 72, 2),
(44, 3, 2, 8),
(45, 3, 3, 8),
(46, 3, 4, 8),
(47, 3, 5, 8),
(48, 3, 6, 8),
(49, 3, 7, 8),
(50, 3, 15, 8),
(51, 3, 16, 8),
(52, 3, 17, 8),
(53, 3, 18, 8),
(54, 3, 20, 8),
(55, 17, 2, 8),
(56, 17, 3, 8),
(57, 17, 4, 8),
(58, 17, 5, 8),
(59, 17, 6, 8),
(60, 17, 7, 8),
(61, 17, 15, 8),
(62, 17, 16, 8),
(63, 17, 17, 8),
(64, 17, 18, 8),
(65, 17, 20, 8),
(66, 15, 9, 10),
(67, 15, 73, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `iva`
--

CREATE TABLE `iva` (
  `id_iva` int(11) NOT NULL,
  `nombre` varchar(300) DEFAULT NULL,
  `valor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `iva`
--

INSERT INTO `iva` (`id_iva`, `nombre`, `valor`) VALUES
(1, '0%', 0),
(2, '12%', 12),
(3, 'No objeto de Impuesto', 0),
(4, 'Exento de IVA', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `linea_producto`
--

CREATE TABLE `linea_producto` (
  `id_linea_producto` int(11) NOT NULL,
  `codigo` varchar(100) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `cta_compras_iva` varchar(45) DEFAULT NULL,
  `cta_compras_iva_0` varchar(45) DEFAULT NULL,
  `cta_ventas_iva` varchar(45) DEFAULT NULL,
  `cta_ventas_iva_0` varchar(45) DEFAULT NULL,
  `cuenta_costo` varchar(100) DEFAULT NULL,
  `fcrea` datetime DEFAULT NULL,
  `fmodifica` datetime DEFAULT NULL,
  `ucrea` int(11) DEFAULT NULL,
  `umodifica` int(11) DEFAULT NULL,
  `id_empresa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `linea_producto`
--

INSERT INTO `linea_producto` (`id_linea_producto`, `codigo`, `nombre`, `cta_compras_iva`, `cta_compras_iva_0`, `cta_ventas_iva`, `cta_ventas_iva_0`, `cuenta_costo`, `fcrea`, `fmodifica`, `ucrea`, `umodifica`, `id_empresa`) VALUES
(1, '13', 'linea1', '1214', '23424', '242431', '2432', '32432', '0000-00-00 00:00:00', NULL, NULL, NULL, 1),
(3, '123r54', 'linea3', '1247', '27347367', '3457395723', '23427347', '2374374367', '2019-11-19 18:08:05', '2019-11-19 18:08:05', NULL, NULL, 1),
(6, 'ejemplo 1', 'linea 2', NULL, NULL, NULL, NULL, NULL, '2019-12-12 13:15:36', '2019-12-12 13:15:36', NULL, NULL, 1),
(7, 'ejemplo 32', 'dddddd', NULL, NULL, NULL, NULL, NULL, '2019-12-12 13:16:01', '2019-12-12 13:16:01', NULL, NULL, 1),
(8, 'ejemplo 3', 'prueba1111', NULL, NULL, NULL, NULL, NULL, '2019-12-12 13:16:21', '2019-12-12 13:16:21', NULL, NULL, 1),
(9, '55', 'dfgdgh', '55', '55', '55', '55', '525', '2020-01-03 17:08:35', '2020-01-03 17:08:35', NULL, NULL, 1),
(10, 'kk', '57', '555', '55', '55', '55', '55', '2020-01-03 17:09:05', '2020-01-03 17:09:05', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marca`
--

CREATE TABLE `marca` (
  `id_marca` int(11) NOT NULL,
  `nombre` varchar(300) DEFAULT NULL,
  `descripcion` varchar(300) DEFAULT NULL,
  `fcrea` datetime DEFAULT NULL,
  `fmodifica` datetime DEFAULT NULL,
  `ucrea` int(11) DEFAULT NULL,
  `umodifica` int(11) DEFAULT NULL,
  `id_empresa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `marca`
--

INSERT INTO `marca` (`id_marca`, `nombre`, `descripcion`, `fcrea`, `fmodifica`, `ucrea`, `umodifica`, `id_empresa`) VALUES
(1, 'marca1', 'mskjfkd', NULL, NULL, NULL, NULL, NULL),
(2, 'marca2', NULL, NULL, NULL, NULL, NULL, 1),
(4, 'marca10', 'hhh', '2019-12-18 13:25:05', '2019-12-18 13:25:05', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modelo`
--

CREATE TABLE `modelo` (
  `id_modelo` int(11) NOT NULL,
  `nombre` varchar(300) DEFAULT NULL,
  `descripcion` varchar(300) DEFAULT NULL,
  `fcrea` datetime DEFAULT NULL,
  `fmodifica` datetime DEFAULT NULL,
  `ucrea` int(11) DEFAULT NULL,
  `umodifica` int(11) DEFAULT NULL,
  `id_empresa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `modelo`
--

INSERT INTO `modelo` (`id_modelo`, `nombre`, `descripcion`, `fcrea`, `fmodifica`, `ucrea`, `umodifica`, `id_empresa`) VALUES
(1, 'modelo112', 'dhsdh', NULL, '2019-12-18 13:25:52', NULL, NULL, 1),
(2, 'modelo2', 'sasa', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulos`
--

CREATE TABLE `modulos` (
  `id_modulos` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `value` int(11) DEFAULT NULL,
  `ver` int(11) DEFAULT NULL,
  `editar` int(11) DEFAULT NULL,
  `crear` int(11) DEFAULT NULL,
  `eliminar` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `fcrea` datetime DEFAULT NULL,
  `fmodifica` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `modulos`
--

INSERT INTO `modulos` (`id_modulos`, `nombre`, `value`, `ver`, `editar`, `crear`, `eliminar`, `id_user`, `fcrea`, `fmodifica`) VALUES
(1, 'Empresa', 1, 1, 1, 1, 1, 6, '2020-01-09 22:50:19', '2020-01-09 22:50:19'),
(2, 'Establecimiento', 2, 1, 1, 1, 1, 6, '2020-01-09 22:50:19', '2020-01-09 22:50:19'),
(3, 'Punto Emision', 3, 1, 1, 1, 1, 6, '2020-01-09 22:50:19', '2020-01-09 22:50:19'),
(4, 'Usuarios', 4, 1, 1, 1, 1, 6, '2020-01-09 22:50:19', '2020-01-09 22:50:19'),
(5, 'Codigo Impuestos', 5, 1, 1, 1, 1, 6, '2020-01-09 22:50:19', '2020-01-09 22:50:19'),
(6, 'Tipo Comprobante', 6, 1, 1, 1, 1, 6, '2020-01-09 22:50:19', '2020-01-09 22:50:19'),
(7, 'Retenciones', 7, 1, 1, 1, 1, 6, '2020-01-09 22:50:19', '2020-01-09 22:50:19'),
(8, 'Tipo Sustento', 8, 1, 1, 1, 1, 6, '2020-01-09 22:50:19', '2020-01-09 22:50:19'),
(9, 'Plan Cuentas', 9, 1, 1, 1, 1, 6, '2020-01-09 22:50:19', '2020-01-09 22:50:19'),
(10, 'Asientos Contables', 10, 1, 1, 1, 1, 6, '2020-01-09 22:50:19', '2020-01-09 22:50:19'),
(11, 'Nota credito', 11, 1, 1, 1, 1, 6, '2020-01-09 22:50:19', '2020-01-09 22:50:19'),
(12, 'Factura Venta', 12, 1, 1, 1, 1, 6, '2020-01-09 22:50:19', '2020-01-09 22:50:19'),
(13, 'Proforma ', 13, 1, 1, 1, 1, 6, '2020-01-09 22:50:19', '2020-01-09 22:50:19'),
(14, 'Pago Cliente', 14, 1, 1, 1, 1, 6, '2020-01-09 22:50:19', '2020-01-09 22:50:19'),
(15, 'Clientes', 15, 1, 1, 1, 1, 6, '2020-01-09 22:50:19', '2020-01-09 22:50:19'),
(16, 'Vendedor', 16, 1, 1, 1, 1, 6, '2020-01-09 22:50:19', '2020-01-09 22:50:19'),
(17, 'Bodega', 17, 1, 1, 1, 1, 6, '2020-01-09 22:50:19', '2020-01-09 22:50:19'),
(18, 'Catalogo', 18, 1, 1, 1, 1, 6, '2020-01-09 22:50:19', '2020-01-09 22:50:19'),
(19, 'Empleados', 19, 1, 1, 1, 1, 6, '2020-01-09 22:50:19', '2020-01-09 22:50:19'),
(20, 'Proveedor', 20, 1, 1, 1, 1, 6, '2020-01-09 22:50:19', '2020-01-09 22:50:19'),
(21, 'Factura Compra', 21, 1, 1, 1, 1, 6, '2020-01-09 22:50:19', '2020-01-09 22:50:19'),
(22, 'Importacion', 22, 1, 1, 1, 1, 6, '2020-01-09 22:50:19', '2020-01-09 22:50:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `moneda`
--

CREATE TABLE `moneda` (
  `id_moneda` int(11) NOT NULL,
  `nomb_moneda` varchar(45) DEFAULT NULL,
  `valor` decimal(11,2) DEFAULT NULL,
  `fcrea` datetime DEFAULT NULL,
  `fmodifica` datetime DEFAULT NULL,
  `ucrea` int(11) DEFAULT NULL,
  `umodifica` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `moneda`
--

INSERT INTO `moneda` (`id_moneda`, `nomb_moneda`, `valor`, `fcrea`, `fmodifica`, `ucrea`, `umodifica`) VALUES
(1, 'Dolar', '0.00', NULL, NULL, NULL, NULL),
(2, 'Extranjera', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nacionalidad`
--

CREATE TABLE `nacionalidad` (
  `id_nacionalidad` int(11) NOT NULL,
  `PAIS_NAC` varchar(25) DEFAULT NULL,
  `GENTILICIO_NAC` varchar(45) DEFAULT NULL,
  `ISO_NAC` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `nacionalidad`
--

INSERT INTO `nacionalidad` (`id_nacionalidad`, `PAIS_NAC`, `GENTILICIO_NAC`, `ISO_NAC`) VALUES
(1, 'Afganistán', 'AFGANA', 'AFG'),
(2, 'Albania', 'ALBANESA', 'ALB'),
(3, 'Alemania', 'ALEMANA', 'DEU'),
(4, 'Andorra', 'ANDORRANA', 'AND'),
(5, 'Angola', 'ANGOLEÑA', 'AGO'),
(6, 'AntiguayBarbuda', 'ANTIGUANA', 'ATG'),
(7, 'ArabiaSaudita', 'SAUDÍ', 'SAU'),
(8, 'Argelia', 'ARGELINA', 'DZA'),
(9, 'Argentina', 'ARGENTINA', 'ARG'),
(10, 'Armenia', 'ARMENIA', 'ARM'),
(11, 'Aruba', 'ARUBEÑA', 'ABW'),
(12, 'Australia', 'AUSTRALIANA', 'AUS'),
(13, 'Austria', 'AUSTRIACA', 'AUT'),
(14, 'Azerbaiyán', 'AZERBAIYANA', 'AZE'),
(15, 'Bahamas', 'BAHAMEÑA', 'BHS'),
(16, 'Bangladés', 'BANGLADESÍ', 'BGD'),
(17, 'Barbados', 'BARBADENSE', 'BRB'),
(18, 'Baréin', 'BAREINÍ', 'BHR'),
(19, 'Bélgica', 'BELGA', 'BEL'),
(20, 'Belice', 'BELICEÑA', 'BLZ'),
(21, 'Benín', 'BENINÉSA', 'BEN'),
(22, 'Bielorrusia', 'BIELORRUSA', 'BLR'),
(23, 'Birmania', 'BIRMANA', 'MMR'),
(24, 'Bolivia', 'BOLIVIANA', 'BOL'),
(25, 'BosniayHerzegovina', 'BOSNIA', 'BIH'),
(26, 'Botsuana', 'BOTSUANA', 'BWA'),
(27, 'Brasil', 'BRASILEÑA', 'BRA'),
(28, 'Brunéi', 'BRUNEANA', 'BRN'),
(29, 'Bulgaria', 'BÚLGARA', 'BGR'),
(30, 'BurkinaFaso', 'BURKINÉS', 'BFA'),
(31, 'Burundi', 'BURUNDÉSA', 'BDI'),
(32, 'Bután', 'BUTANÉSA', 'BTN'),
(33, 'CaboVerde', 'CABOVERDIANA', 'CPV'),
(34, 'Camboya', 'CAMBOYANA', 'KHM'),
(35, 'Camerún', 'CAMERUNESA', 'CMR'),
(36, 'Canadá', 'CANADIENSE', 'CAN'),
(37, 'Catar', 'CATARÍ', 'QAT'),
(38, 'Chad', 'CHADIANA', 'TCD'),
(39, 'Chile', 'CHILENA', 'CHL'),
(40, 'China', 'CHINA', 'CHN'),
(41, 'Chipre', 'CHIPRIOTA', 'CYP'),
(42, 'CiudaddelVaticano', 'VATICANA', 'VAT'),
(43, 'Colombia', 'COLOMBIANA', 'COL'),
(44, 'Comoras', 'COMORENSE', 'COM'),
(45, 'CoreadelNorte', 'NORCOREANA', 'PRK'),
(46, 'CoreadelSur', 'SURCOREANA', 'KOR'),
(47, 'CostadeMarfil', 'MARFILEÑA', 'CIV'),
(48, 'CostaRica', 'COSTARRICENSE', 'CRI'),
(49, 'Croacia', 'CROATA', 'HRV'),
(50, 'Cuba', 'CUBANA', 'CUB'),
(51, 'Dinamarca', 'DANÉSA', 'DNK'),
(52, 'Dominica', 'DOMINIQUÉS', 'DMA'),
(53, 'Ecuador', 'ECUATORIANA', 'ECU'),
(54, 'Egipto', 'EGIPCIA', 'EGY'),
(55, 'ElSalvador', 'SALVADOREÑA', 'SLV'),
(56, 'EmiratosÁrabesUnidos', 'EMIRATÍ', 'ARE'),
(57, 'Eritrea', 'ERITREA', 'ERI'),
(58, 'Eslovaquia', 'ESLOVACA', 'SVK'),
(59, 'Eslovenia', 'ESLOVENA', 'SVN'),
(60, 'España', 'ESPAÑOLA', 'ESP'),
(61, 'EstadosUnidos', 'ESTADOUNIDENSE', 'USA'),
(62, 'Estonia', 'ESTONIA', 'EST'),
(63, 'Etiopía', 'ETÍOPE', 'ETH'),
(64, 'Filipinas', 'FILIPINA', 'PHL'),
(65, 'Finlandia', 'FINLANDÉSA', 'FIN'),
(66, 'Fiyi', 'FIYIANA', 'FJI'),
(67, 'Francia', 'FRANCÉSA', 'FRA'),
(68, 'Gabón', 'GABONÉSA', 'GAB'),
(69, 'Gambia', 'GAMBIANA', 'GMB'),
(70, 'Georgia', 'GEORGIANA', 'GEO'),
(71, 'Gibraltar', 'GIBRALTAREÑA', 'GIB'),
(72, 'Ghana', 'GHANÉSA', 'GHA'),
(73, 'Granada', 'GRANADINA', 'GRD'),
(74, 'Grecia', 'GRIEGA', 'GRC'),
(75, 'Groenlandia', 'GROENLANDÉSA', 'GRL'),
(76, 'Guatemala', 'GUATEMALTECA', 'GTM'),
(77, 'Guineaecuatorial', 'ECUATOGUINEANA', 'GNQ'),
(78, 'Guinea', 'GUINEANA', 'GIN'),
(79, 'Guinea-Bisáu', 'GUINEANA', 'GNB'),
(80, 'Guyana', 'GUYANESA', 'GUY'),
(81, 'Haití', 'HAITIANA', 'HTI'),
(82, 'Honduras', 'HONDUREÑA', 'HND'),
(83, 'Hungría', 'HÚNGARA', 'HUN'),
(84, 'India', 'HINDÚ', 'IND'),
(85, 'Indonesia', 'INDONESIA', 'IDN'),
(86, 'Irak', 'IRAQUÍ', 'IRQ'),
(87, 'Irán', 'IRANÍ', 'IRN'),
(88, 'Irlanda', 'IRLANDÉSA', 'IRL'),
(89, 'Islandia', 'ISLANDÉSA', 'ISL'),
(90, 'IslasCook', 'COOKIANA', 'COK'),
(91, 'IslasMarshall', 'MARSHALÉSA', 'MHL'),
(92, 'IslasSalomón', 'SALOMONENSE', 'SLB'),
(93, 'Israel', 'ISRAELÍ', 'ISR'),
(94, 'Italia', 'ITALIANA', 'ITA'),
(95, 'Jamaica', 'JAMAIQUINA', 'JAM'),
(96, 'Japón', 'JAPONÉSA', 'JPN'),
(97, 'Jordania', 'JORDANA', 'JOR'),
(98, 'Kazajistán', 'KAZAJA', 'KAZ'),
(99, 'Kenia', 'KENIATA', 'KEN'),
(100, 'Kirguistán', 'KIRGUISA', 'KGZ'),
(101, 'Kiribati', 'KIRIBATIANA', 'KIR'),
(102, 'Kuwait', 'KUWAITÍ', 'KWT'),
(103, 'Laos', 'LAOSIANA', 'LAO'),
(104, 'Lesoto', 'LESOTENSE', 'LSO'),
(105, 'Letonia', 'LETÓNA', 'LVA'),
(106, 'Líbano', 'LIBANÉSA', 'LBN'),
(107, 'Liberia', 'LIBERIANA', 'LBR'),
(108, 'Libia', 'LIBIA', 'LBY'),
(109, 'Liechtenstein', 'LIECHTENSTEINIANA', 'LIE'),
(110, 'Lituania', 'LITUANA', 'LTU'),
(111, 'Luxemburgo', 'LUXEMBURGUÉSA', 'LUX'),
(112, 'Madagascar', 'MALGACHE', 'MDG'),
(113, 'Malasia', 'MALASIA', 'MYS'),
(114, 'Malaui', 'MALAUÍ', 'MWI'),
(115, 'Maldivas', 'MALDIVA', 'MDV'),
(116, 'Malí', 'MALIENSE', 'MLI'),
(117, 'Malta', 'MALTÉSA', 'MLT'),
(118, 'Marruecos', 'MARROQUÍ', 'MAR'),
(119, 'Martinica', 'MARTINIQUÉS', 'MTQ'),
(120, 'Mauricio', 'MAURICIANA', 'MUS'),
(121, 'Mauritania', 'MAURITANA', 'MRT'),
(122, 'México', 'MEXICANA', 'MEX'),
(123, 'Micronesia', 'MICRONESIA', 'FSM'),
(124, 'Moldavia', 'MOLDAVA', 'MDA'),
(125, 'Mónaco', 'MONEGASCA', 'MCO'),
(126, 'Mongolia', 'MONGOLA', 'MNG'),
(127, 'Montenegro', 'MONTENEGRINA', 'MNE'),
(128, 'Mozambique', 'MOZAMBIQUEÑA', 'MOZ'),
(129, 'Namibia', 'NAMIBIA', 'NAM'),
(130, 'Nauru', 'NAURUANA', 'NRU'),
(131, 'Nepal', 'NEPALÍ', 'NPL'),
(132, 'Nicaragua', 'NICARAGÜENSE', 'NIC'),
(133, 'Níger', 'NIGERINA', 'NER'),
(134, 'Nigeria', 'NIGERIANA', 'NGA'),
(135, 'Noruega', 'NORUEGA', 'NOR'),
(136, 'NuevaZelanda', 'NEOZELANDÉSA', 'NZL'),
(137, 'Omán', 'OMANÍ', 'OMN'),
(138, 'PaísesBajos', 'NEERLANDÉSA', 'NLD'),
(139, 'Pakistán', 'PAKISTANÍ', 'PAK'),
(140, 'Palaos', 'PALAUANA', 'PLW'),
(141, 'Palestina', 'PALESTINA', 'PSE'),
(142, 'Panamá', 'PANAMEÑA', 'PAN'),
(143, 'PapúaNuevaGuinea', 'PAPÚ', 'PNG'),
(144, 'Paraguay', 'PARAGUAYA', 'PRY'),
(145, 'Perú', 'PERUANA', 'PER'),
(146, 'Polonia', 'POLACA', 'POL'),
(147, 'Portugal', 'PORTUGUÉSA', 'PRT'),
(148, 'PuertoRico', 'PUERTORRIQUEÑA', 'PRI'),
(149, 'ReinoUnido', 'BRITÁNICA', 'GBR'),
(150, 'RepúblicaCentroafricana', 'CENTROAFRICANA', 'CAF'),
(151, 'RepúblicaCheca', 'CHECA', 'CZE'),
(152, 'RepúblicadeMacedonia', 'MACEDONIA', 'MKD'),
(153, 'RepúblicadelCongo', 'CONGOLEÑA', 'COG'),
(154, 'RepúblicaDemocráticadelCo', 'CONGOLEÑA', 'COD'),
(155, 'RepúblicaDominicana', 'DOMINICANA', 'DOM'),
(156, 'RepúblicaSudafricana', 'SUDAFRICANA', 'ZAF'),
(157, 'Ruanda', 'RUANDÉSA', 'RWA'),
(158, 'Rumanía', 'RUMANA', 'ROU'),
(159, 'Rusia', 'RUSA', 'RUS'),
(160, 'Samoa', 'SAMOANA', 'WSM'),
(161, 'SanCristóbalyNieves', 'CRISTOBALEÑA', 'KNA'),
(162, 'SanMarino', 'SANMARINENSE', 'SMR'),
(163, 'SanVicenteylasGranadinas', 'SANVICENTINA', 'VCT'),
(164, 'SantaLucía', 'SANTALUCENSE', 'LCA'),
(165, 'SantoToméyPríncipe', 'SANTOTOMENSE', 'STP'),
(166, 'Senegal', 'SENEGALÉSA', 'SEN'),
(167, 'Serbia', 'SERBIA', 'SRB'),
(168, 'Seychelles', 'SEYCHELLENSE', 'SYC'),
(169, 'SierraLeona', 'SIERRALEONÉSA', 'SLE'),
(170, 'Singapur', 'SINGAPURENSE', 'SGP'),
(171, 'Siria', 'SIRIA', 'SYR'),
(172, 'Somalia', 'SOMALÍ', 'SOM'),
(173, 'SriLanka', 'CEILANÉSA', 'LKA'),
(174, 'Suazilandia', 'SUAZI', 'SWZ'),
(175, 'SudándelSur', 'SURSUDANÉSA', 'SSD'),
(176, 'Sudán', 'SUDANÉSA', 'SDN'),
(177, 'Suecia', 'SUECA', 'SWE'),
(178, 'Suiza', 'SUIZA', 'CHE'),
(179, 'Surinam', 'SURINAMESA', 'SUR'),
(180, 'Tailandia', 'TAILANDÉSA', 'THA'),
(181, 'Tanzania', 'TANZANA', 'TZA'),
(182, 'Tayikistán', 'TAYIKA', 'TJK'),
(183, 'TimorOriental', 'TIMORENSE', 'TLS'),
(184, 'Togo', 'TOGOLÉSA', 'TGO'),
(185, 'Tonga', 'TONGANA', 'TON'),
(186, 'TrinidadyTobago', 'TRINITENSE', 'TTO'),
(187, 'Túnez', 'TUNECINA', 'TUN'),
(188, 'Turkmenistán', 'TURCOMANA', 'TKM'),
(189, 'Turquía', 'TURCA', 'TUR'),
(190, 'Tuvalu', 'TUVALUANA', 'TUV'),
(191, 'Ucrania', 'UCRANIANA', 'UKR'),
(192, 'Uganda', 'UGANDÉSA', 'UGA'),
(193, 'Uruguay', 'URUGUAYA', 'URY'),
(194, 'Uzbekistán', 'UZBEKA', 'UZB'),
(195, 'Vanuatu', 'VANUATUENSE', 'VUT'),
(196, 'Venezuela', 'VENEZOLANA', 'VEN'),
(197, 'Vietnam', 'VIETNAMITA', 'VNM'),
(198, 'Yemen', 'YEMENÍ', 'YEM'),
(199, 'Yibuti', 'YIBUTIANA', 'DJI'),
(200, 'Zambia', 'ZAMBIANA', 'ZMB'),
(201, 'Zimbabue', 'ZIMBABUENSE', 'ZWE');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id_pagos` int(11) NOT NULL,
  `metodo_pago` varchar(200) DEFAULT NULL,
  `banco` varchar(100) DEFAULT NULL,
  `tarjeta` varchar(100) DEFAULT NULL,
  `cuenta_contable` varchar(100) DEFAULT NULL,
  `cantidad` decimal(11,2) DEFAULT NULL,
  `comentario` varchar(300) DEFAULT NULL,
  `fcrea` datetime DEFAULT NULL,
  `fmodifica` datetime DEFAULT NULL,
  `id_empresa` int(11) DEFAULT NULL,
  `id_factura` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`id_pagos`, `metodo_pago`, `banco`, `tarjeta`, `cuenta_contable`, `cantidad`, `comentario`, `fcrea`, `fmodifica`, `id_empresa`, `id_factura`) VALUES
(4, '2', NULL, NULL, NULL, '50.00', 'ejemplo', '2019-12-31 01:06:41', '2019-12-31 01:06:41', NULL, 3),
(5, '4', NULL, NULL, NULL, '50.00', 'ejemplo1', '2019-12-31 01:06:41', '2019-12-31 01:06:41', NULL, 3),
(6, '1', NULL, NULL, NULL, '10.00', NULL, '2019-12-31 17:01:57', '2019-12-31 17:01:57', NULL, 6),
(7, '1', NULL, NULL, NULL, '10.00', NULL, '2019-12-31 17:04:13', '2019-12-31 17:04:13', NULL, 7),
(8, '1', NULL, NULL, NULL, '1.00', 'as', '2020-01-02 13:32:10', '2020-01-02 13:32:10', NULL, 7),
(9, '1', NULL, NULL, NULL, '5.00', 'as', '2020-01-02 17:27:27', '2020-01-02 17:27:27', NULL, 12),
(10, '1', NULL, NULL, NULL, '5.00', 'as', '2020-01-02 17:27:40', '2020-01-02 17:27:40', NULL, 13),
(11, '1', NULL, NULL, NULL, '5.00', 'as', '2020-01-02 17:28:54', '2020-01-02 17:28:54', NULL, 14),
(12, 'Deposito', NULL, NULL, NULL, '33.00', '1123', '2020-01-02 17:44:13', '2020-01-02 17:44:13', NULL, 15),
(13, 'Deposito', NULL, NULL, NULL, '200.00', 'ejemplo', '2020-01-02 18:11:46', '2020-01-02 18:11:46', NULL, 16),
(14, 'Cheque', NULL, NULL, NULL, '30.00', 'ejemplo2', '2020-01-02 18:11:46', '2020-01-02 18:11:46', NULL, 16),
(15, 'Deposito', NULL, NULL, NULL, '1.00', 'ejemplo', '2020-01-03 14:05:43', '2020-01-03 14:05:43', NULL, 17),
(16, 'Efectivo', NULL, NULL, NULL, '1.12', 'ejemplo', '2020-01-03 14:10:02', '2020-01-03 14:10:02', NULL, 18),
(17, 'Efectivo', NULL, NULL, NULL, '1.12', 'ejemplo', '2020-01-03 14:10:40', '2020-01-03 14:10:40', NULL, 19),
(18, 'Efectivo', NULL, NULL, NULL, '1.12', 'ejemplo', '2020-01-03 14:10:42', '2020-01-03 14:10:42', NULL, 20),
(19, 'Efectivo', NULL, NULL, NULL, '1.12', 'ejemplo', '2020-01-03 14:11:52', '2020-01-03 14:11:52', NULL, 21),
(20, 'Efectivo', NULL, NULL, NULL, '1.12', 'ejemplo', '2020-01-03 14:12:22', '2020-01-03 14:12:22', NULL, 22),
(21, 'Efectivo', NULL, NULL, NULL, '1.12', 'ejemplo', '2020-01-03 14:16:08', '2020-01-03 14:16:08', NULL, 23),
(22, 'Efectivo', NULL, NULL, NULL, '1.12', 'ejemplo', '2020-01-03 14:16:29', '2020-01-03 14:16:29', NULL, 24),
(23, 'Efectivo', NULL, NULL, NULL, '1.12', 'ejemplo', '2020-01-03 14:33:52', '2020-01-03 14:33:52', NULL, 26);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago_fact_compra`
--

CREATE TABLE `pago_fact_compra` (
  `id_pagfactcompra` int(11) NOT NULL,
  `metodo_pago` varchar(50) DEFAULT NULL,
  `tarjeta` varchar(50) DEFAULT NULL,
  `cantidad_pago` decimal(11,2) DEFAULT NULL,
  `comentario_pago` varchar(150) DEFAULT NULL,
  `fcrea` datetime DEFAULT current_timestamp(),
  `fmodifica` datetime DEFAULT NULL,
  `id_factcompra` int(11) DEFAULT NULL,
  `banco_pago` int(11) DEFAULT NULL,
  `id_empresa` int(11) DEFAULT NULL,
  `cuenta` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pago_fact_compra`
--

INSERT INTO `pago_fact_compra` (`id_pagfactcompra`, `metodo_pago`, `tarjeta`, `cantidad_pago`, `comentario_pago`, `fcrea`, `fmodifica`, `id_factcompra`, `banco_pago`, `id_empresa`, `cuenta`) VALUES
(1, 'Efectivo', NULL, '1.00', 'nose', '2020-01-03 20:29:39', '2020-01-03 20:29:39', 10, 1, 1, NULL),
(2, 'Deposito', NULL, '1.00', 'nose2', '2020-01-03 20:29:39', '2020-01-03 20:29:39', 10, 2, 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parroquia`
--

CREATE TABLE `parroquia` (
  `id_parroquia` int(11) NOT NULL,
  `codigo_parroquia` varchar(10) NOT NULL,
  `nombre_parroquia` varchar(200) NOT NULL,
  `codigo_ciudad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `parroquia`
--

INSERT INTO `parroquia` (`id_parroquia`, `codigo_parroquia`, `nombre_parroquia`, `codigo_ciudad`) VALUES
(1, '10101', 'BELLAVISTA', 1),
(2, '10102', 'CAÑARIBAMBA', 1),
(3, '10103', 'EL BATAN', 1),
(4, '10104', 'EL SAGRARIO', 1),
(5, '10105', 'EL VECINO', 1),
(6, '10106', 'GIL RAMIREZ DAVALOS', 1),
(7, '10107', 'HUAYNACAPAC', 1),
(8, '10108', 'MACHANGARA', 1),
(9, '10109', 'MONAY', 1),
(10, '10110', 'SAN BLAS', 1),
(11, '10111', 'SAN SEBASTIAN', 1),
(12, '10112', 'SUCRE', 1),
(13, '10113', 'TOTORACOCHA', 1),
(14, '10114', 'YANUNCAY', 1),
(15, '10115', 'HERMANO MIGUEL', 1),
(16, '10150', 'CUENCA', 1),
(17, '10151', 'BAÑOS', 1),
(18, '10152', 'CUMBE', 1),
(19, '10153', 'CHAUCHA', 1),
(20, '10154', 'CHECA (JIDCAY)', 1),
(21, '10155', 'CHIQUINTAD', 1),
(22, '10156', 'LLACAO', 1),
(23, '10157', 'MOLLETURO', 1),
(24, '10158', 'NULTI', 1),
(25, '10159', 'OCTAVIO CORDERO PALACIOS (SANTA ROSA)', 1),
(26, '10160', 'PACCHA', 1),
(27, '10161', 'QUINGEO', 1),
(28, '10162', 'RICAURTE', 1),
(29, '10163', 'SAN JOAQUIN', 1),
(30, '10164', 'SANTA ANA', 1),
(31, '10165', 'SAYAUSI', 1),
(32, '10166', 'SIDCAY', 1),
(33, '10167', 'SININCAY', 1),
(34, '10168', 'TARQUI', 1),
(35, '10169', 'TURI', 1),
(36, '10170', 'VALLE', 1),
(37, '10171', 'VICTORIA DEL PORTETE (IRQUIS)', 1),
(38, '10250', 'GIRÒN', 2),
(39, '10251', 'ASUNCIÒN', 2),
(40, '10252', 'SAN GERARDO', 2),
(41, '10350', 'GUALACEO', 3),
(42, '10351', 'CHORDELEG', 3),
(43, '10352', 'DANIEL CÒRDOVA TORAL (EL ORIENTE)', 3),
(44, '10353', 'JADAN', 3),
(45, '10354', 'MARIANO MORENO', 3),
(46, '10355', 'PRINCIPAL', 3),
(47, '10356', 'REMIGIO CRESPO TORAL ', 3),
(48, '10357', 'SAN JUAN', 3),
(49, '10358', 'ZHIDMAD', 3),
(50, '10359', 'LUIS CORDERO VEGA', 3),
(51, '10360', 'SIMON BOLIVAR (CAB. EN GAÑANZOL)', 3),
(52, '10450', 'NABON', 4),
(53, '10451', 'COCHAPATA', 4),
(54, '10452', 'EL PROGRESO (CAB.EN ZHOTA)', 4),
(55, '10453', 'LAS NIEVES (CHAYA)', 4),
(56, '10454', 'OÑA', 4),
(57, '10550', 'PAUTE', 5),
(58, '10551', 'AMALUZA', 5),
(59, '10552', 'BULAN (JOSE VICTOR IZQUIERDO)', 5),
(60, '10553', 'CHICAN (GUILLERMO ORTEGA)', 5),
(61, '10554', 'EL CABO', 5),
(62, '10555', 'GUACHAPALA', 5),
(63, '10556', 'GUARAINAG', 5),
(64, '10557', 'PALMAS', 5),
(65, '10558', 'PAN', 5),
(66, '10559', 'SAN CRISTOBAL (CARLOS ORDOÑEZ LAZO)', 5),
(67, '10560', 'SEVILLA DE ORO', 5),
(68, '10561', 'TOMEBAMBA', 5),
(69, '10562', 'DUG DUG', 5),
(70, '10650', 'PUCARA', 6),
(71, '10651', 'CAMILO PONCE ENRIQUEZ ', 6),
(72, '10652', 'SAN RAFAEL DE SHARUG', 6),
(73, '10750', 'SAN FERNANDO', 7),
(74, '10751', 'CHUMBLIN', 7),
(75, '10850', 'SANTA ISABEL (CHAGUARURCO)', 8),
(76, '10851', 'ABDON CALDERON (LA UNION)', 8),
(77, '10852', 'EL CARMEN DE PIJILI', 8),
(78, '10853', 'ZHAGLLI (SHAGLLI)', 8),
(79, '10854', 'SAN SALVADOR DE CAÑARIBAMBA', 8),
(80, '10950', 'SIGSIG', 9),
(81, '10951', 'CUCHIL (CUTCHIL)', 9),
(82, '10952', 'GIMA', 9),
(83, '10953', 'GUEL', 9),
(84, '10954', 'LUDO', 9),
(85, '10955', 'SAN BARTOLOME', 9),
(86, '10956', 'SAN JOSE DE RARANGA', 9),
(87, '11050', 'SAN FELIPE DE OÑA CABECERA CANTONAL', 10),
(88, '11051', 'SUSUDEL', 10),
(89, '11150', 'CHORDELEG', 11),
(90, '11151', 'PRINCIPAL', 11),
(91, '11152', 'LA UNION', 11),
(92, '11153', 'LUIS GALARZA ORELLANA (CAB.EN DELEGSOL)', 11),
(93, '11154', 'SAN MARTIN DE PUZHIO', 11),
(94, '11250', 'EL PAN', 12),
(95, '11251', 'AMALUZA', 12),
(96, '11252', 'PALMAS', 12),
(97, '11253', 'SAN VICENTE', 12),
(98, '11350', 'SEVILLA DE ORO', 13),
(99, '11351', 'AMALUZA', 13),
(100, '11352', 'PALMAS', 13),
(101, '11450', 'GUACHAPALA', 14),
(102, '11550', 'CAMILO PONCE ENRIQUEZ', 15),
(103, '11551', 'EL CARMEN DE PIJILI', 15),
(104, '20101', 'ANGEL POLIBIO CHAVES', 16),
(105, '20102', 'GABRIEL IGNACIO VEINTIMILLA', 16),
(106, '20103', 'GUANUJO', 16),
(107, '20150', 'GUARANDA', 16),
(108, '20151', 'FACUNDO VELA', 16),
(109, '20152', 'GUANUJO', 16),
(110, '20153', 'JULIO E. MORENO (CATANAHUAN GRANDE)', 16),
(111, '20154', 'LAS NAVES', 16),
(112, '20155', 'SALINAS', 16),
(113, '20156', 'SAN LORENZO', 16),
(114, '20157', 'SAN SIMON (YACOTO)', 16),
(115, '20158', 'SANTA FE (SANTA FE)', 16),
(116, '20159', 'SIMIATUG', 16),
(117, '20160', 'SAN LUIS DE PAMBIL', 16),
(118, '20250', 'CHILLANES', 17),
(119, '20251', 'SAN JOSE DEL TAMBO (TAMBOPAMBA)', 17),
(120, '20350', 'SAN JOSE DE CHIMBO', 18),
(121, '20351', 'ASUNCION (ASANCOTO)', 18),
(122, '20352', 'CALUMA', 18),
(123, '20353', 'MAGDALENA (CHAPACOTO)', 18),
(124, '20354', 'SAN SEBASTIAN', 18),
(125, '20355', 'TELIMBELA', 18),
(126, '20450', 'ECHEANDIA', 19),
(127, '20550', 'SAN MIGUEL', 20),
(128, '20551', 'BALSAPAMBA', 20),
(129, '20552', 'BILOVAN', 20),
(130, '20553', 'REGULO DE MORA', 20),
(131, '20554', 'SAN PABLO (SAN PABLO DE ATENAS)', 20),
(132, '20555', 'SANTIAGO', 20),
(133, '20556', 'SAN VICENTE', 20),
(134, '20650', 'CALUMA', 21),
(135, '20701', 'LAS MERCEDES', 22),
(136, '20702', 'LAS NAVES', 22),
(137, '20750', 'LAS NAVES', 22),
(138, '30101', 'AURELIO BAYAS MARTINEZ', 23),
(139, '30102', 'AZOGUES', 23),
(140, '30103', 'BORRERO', 23),
(141, '30104', 'SAN FRANCISCO', 23),
(142, '30150', 'AZOGUES', 23),
(143, '30151', 'COJITAMBO', 23),
(144, '30152', 'DELEG', 23),
(145, '30153', 'GUAPAN', 23),
(146, '30154', 'JAVIER LOYOLA (CHUQUIPATA)', 23),
(147, '30155', 'LUIS CORDERO', 23),
(148, '30156', 'PINDILIG', 23),
(149, '30157', 'RIVERA', 23),
(150, '30158', 'SAN MIGUEL', 23),
(151, '30159', 'SOLANO', 23),
(152, '30160', 'TADAY', 23),
(153, '30250', 'BIBLIAN', 24),
(154, '30251', 'NAZON (CAB. EN PAMPA DE DOMINGUEZ)', 24),
(155, '30252', 'SAN FRANCISCO DE SAGEO', 24),
(156, '30253', 'TURUPAMBA', 24),
(157, '30254', 'JERUSALEN', 24),
(158, '30350', 'CAÑAR', 25),
(159, '30351', 'CHONTAMARCA', 25),
(160, '30352', 'CHOROCOPTE', 25),
(161, '30353', 'GENERAL MORALES (SOCARTE)', 25),
(162, '30354', 'GUALLETURO', 25),
(163, '30355', 'HONORATO VASQUEZ (TAMBO VIEJO)', 25),
(164, '30356', 'INGAPIRCA', 25),
(165, '30357', 'JUNCAL', 25),
(166, '30358', 'SAN ANTONIO', 25),
(167, '30359', 'SUSCAL', 25),
(168, '30360', 'TAMBO', 25),
(169, '30361', 'ZHUD', 25),
(170, '30362', 'VENTURA', 25),
(171, '30363', 'DUCUR', 25),
(172, '30450', 'LA TRONCAL', 26),
(173, '30451', 'MANUEL J. CALLE', 26),
(174, '30452', 'PANCHO NEGRO', 26),
(175, '30550', 'EL TAMBO', 27),
(176, '30650', 'DELEG', 28),
(177, '30651', 'SOLANO', 28),
(178, '30750', 'SUSCAL', 29),
(179, '40101', 'GONZALEZ SUAREZ', 30),
(180, '40102', 'TULCAN', 30),
(181, '40150', 'TULCAN', 30),
(182, '40151', 'EL CARMELO (EL PUN)', 30),
(183, '40152', 'HUACA', 30),
(184, '40153', 'JULIO ANDRADE (OREJUELA)', 30),
(185, '40154', 'MALDONADO', 30),
(186, '40155', 'PIOTER', 30),
(187, '40156', 'TOBAR DONOSO (LA BOCANA DE CAMUMBI)', 30),
(188, '40157', 'TUFIÑO', 30),
(189, '40158', 'URBINA (TAYA)', 30),
(190, '40159', 'EL CHICAL', 30),
(191, '40160', 'MARISCAL SUCRE', 30),
(192, '40161', 'SANTA MARTHA DE CUBA', 30),
(193, '40250', 'BOLIVAR', 31),
(194, '40251', 'GARCIA MORENO', 31),
(195, '40252', 'LOS ANDES', 31),
(196, '40253', 'MONTE OLIVO', 31),
(197, '40254', 'SAN VICENTE DE PUSIR', 31),
(198, '40255', 'SAN RAFAEL', 31),
(199, '40301', 'EL ANGEL', 32),
(200, '40302', '27 DE SEPTIEMBRE', 32),
(201, '40350', 'EL ANGEL', 32),
(202, '40351', 'EL GOALTAL', 32),
(203, '40352', 'LA LIBERTAD (ALIZO)', 32),
(204, '40353', 'SAN ISIDRO', 32),
(205, '40450', 'MIRA (CHONTAHUASI)', 33),
(206, '40451', 'CONCEPCION', 33),
(207, '40452', 'JIJON Y CAMAÑO (CAB. EN RIO BLANCO)', 33),
(208, '40453', 'JUAN MONTALVO (SAN IGNACIO DE QUIL)', 33),
(209, '40501', 'GONZALEZ SUAREZ', 34),
(210, '40502', 'SAN JOSE', 34),
(211, '40550', 'SAN GABRIEL', 34),
(212, '40551', 'CRISTOBAL COLON', 34),
(213, '40552', 'CHITAN DE NAVARRETE', 34),
(214, '40553', 'FERNANDEZ SALVADOR', 34),
(215, '40554', 'LA PAZ', 34),
(216, '40555', 'PIARTAL', 34),
(217, '40650', 'HUACA', 35),
(218, '40651', 'MARISCAL SUCRE', 35),
(219, '50101', 'ELOY ALFARO (SAN FELIPE)', 36),
(220, '50102', 'IGNACIO FLORES (PARQUE FLORES)', 36),
(221, '50103', 'JUAN MONTALVO (SAN SEBASTIAN)', 36),
(222, '50104', 'LA MATRIZ', 36),
(223, '50105', 'SAN BUENAVENTURA', 36),
(224, '50150', 'LATACUNGA', 36),
(225, '50151', 'ALAQUES (ALAQUEZ)', 36),
(226, '50152', 'BELISARIO QUEVEDO (GUANAILIN)', 36),
(227, '50153', 'GUAITACAMA (GUAYTACAMA)', 36),
(228, '50154', 'JOSEGUANGO BAJO', 36),
(229, '50155', 'LAS PAMPAS', 36),
(230, '50156', 'MULALO', 36),
(231, '50157', '11 DE NOVIEMBRE (ILINCHISI)', 36),
(232, '50158', 'POALO', 36),
(233, '50159', 'SAN JUAN DE PASTOCALLE', 36),
(234, '50160', 'SIGCHOS', 36),
(235, '50161', 'TANICUCHI', 36),
(236, '50162', 'TOACASO', 36),
(237, '50163', 'PALO QUEMADO', 36),
(238, '50201', 'EL CARMEN', 37),
(239, '50202', 'LA MANA', 37),
(240, '50203', 'EL TRIUNFO', 37),
(241, '50250', 'LA MANA', 37),
(242, '50251', 'GUASAGANDA (CAB.EN GUASAGANDA', 37),
(243, '50252', 'PUCAYACU', 37),
(244, '50350', 'EL CORAZON', 38),
(245, '50351', 'MORASPUNGO', 38),
(246, '50352', 'PINLLOPATA', 38),
(247, '50353', 'RAMON CAMPAÑA', 38),
(248, '50450', 'PUJILI', 39),
(249, '50451', 'ANGAMARCA', 39),
(250, '50452', 'CHUCCHILAN (CHUGCHILAN)', 39),
(251, '50453', 'GUANGAJE', 39),
(252, '50454', 'ISINLIBI (ISINLIVI)', 39),
(253, '50455', 'LA VICTORIA', 39),
(254, '50456', 'PILALO', 39),
(255, '50457', 'TINGO', 39),
(256, '50458', 'ZUMBAHUA', 39),
(257, '50550', 'SAN MIGUEL', 40),
(258, '50551', 'ANTONIO JOSE HOLGUIN (SANTA LUCIA)', 40),
(259, '50552', 'CUSUBAMBA', 40),
(260, '50553', 'MULALILLO', 40),
(261, '50554', 'MULLIQUINDIL (SANTA ANA)', 40),
(262, '50555', 'PANSALEO', 40),
(263, '50650', 'SAQUISILI', 41),
(264, '50651', 'CANCHAGUA', 41),
(265, '50652', 'CHANTILIN', 41),
(266, '50653', 'COCHAPAMBA', 41),
(267, '50750', 'SIGCHOS', 42),
(268, '50751', 'CHUGCHILLAN', 42),
(269, '50752', 'ISINLIVI', 42),
(270, '50753', 'LAS PAMPAS', 42),
(271, '50754', 'PALO QUEMADO', 42),
(272, '60101', 'LIZARZABURU', 43),
(273, '60102', 'MALDONADO', 43),
(274, '60103', 'VELASCO', 43),
(275, '60104', 'VELOZ', 43),
(276, '60105', 'YARUQUIES', 43),
(277, '60150', 'RIOBAMBA', 43),
(278, '60151', 'CACHA (CAB. EN MACHANGARA)', 43),
(279, '60152', 'CALPI', 43),
(280, '60153', 'CUBIJIES', 43),
(281, '60154', 'FLORES', 43),
(282, '60155', 'LICAN', 43),
(283, '60156', 'LICTO', 43),
(284, '60157', 'PUNGALA', 43),
(285, '60158', 'PUNIN', 43),
(286, '60159', 'QUIMIAG', 43),
(287, '60160', 'SAN JUAN', 43),
(288, '60161', 'SAN LUIS', 43),
(289, '60250', 'ALAUSI', 44),
(290, '60251', 'ACHUPALLAS', 44),
(291, '60252', 'CUMANDA', 44),
(292, '60253', 'GUASUNTOS', 44),
(293, '60254', 'HUIGRA', 44),
(294, '60255', 'MULTITUD', 44),
(295, '60256', 'PISTISHI (NARIZ DEL DIABLO)', 44),
(296, '60257', 'PUMALLACTA', 44),
(297, '60258', 'SEVILLA', 44),
(298, '60259', 'SIBAMBE', 44),
(299, '60260', 'TIXAN', 44),
(300, '60301', 'CAJABAMBA', 45),
(301, '60302', 'SICALPA', 45),
(302, '60350', 'VILLA LA UNION (CAJABAMBA)', 45),
(303, '60351', 'CAÑI', 45),
(304, '60352', 'COLUMBE', 45),
(305, '60353', 'JUAN DE VELASCO (PANGOR)', 45),
(306, '60354', 'SANTIAGO DE QUITO (CAB. EN SAN ANTONIO DE QUITO)', 45),
(307, '60450', 'CHAMBO', 46),
(308, '60550', 'CHUNCHI', 47),
(309, '60551', 'CAPZOL', 47),
(310, '60552', 'COMPUD', 47),
(311, '60553', 'GONZOL', 47),
(312, '60554', 'LLAGOS', 47),
(313, '60650', 'GUAMOTE', 48),
(314, '60651', 'CEBADAS', 48),
(315, '60652', 'PALMIRA', 48),
(316, '60701', 'EL ROSARIO', 49),
(317, '60702', 'LA MATRIZ', 49),
(318, '60750', 'GUANO', 49),
(319, '60751', 'GUANANDO', 49),
(320, '60752', 'ILAPO', 49),
(321, '60753', 'LA PROVIDENCIA', 49),
(322, '60754', 'SAN ANDRES', 49),
(323, '60755', 'SAN GERARDO DE PACAICAGUAN', 49),
(324, '60756', 'SAN ISIDRO DE PATULE', 49),
(325, '60757', 'SAN JOSE DEL CHAZO', 49),
(326, '60758', 'SANTA FE DE GALAN', 49),
(327, '60759', 'VALPARAISO', 49),
(328, '60850', 'PALLATANGA', 50),
(329, '60950', 'PENIPE', 51),
(330, '60951', 'EL ALTAR', 51),
(331, '60952', 'MATUS', 51),
(332, '60953', 'PUELA', 51),
(333, '60954', 'SAN ANTONIO DE BAYUSHIG', 51),
(334, '60955', 'LA CANDELARIA', 51),
(335, '60956', 'BILBAO (CAB.EN QUILLUYACU)', 51),
(336, '61050', 'CUMANDA', 52),
(337, '70101', 'LA PROVIDENCIA', 53),
(338, '70102', 'MACHALA', 53),
(339, '70103', 'PUERTO BOLIVAR', 53),
(340, '70104', 'NUEVE DE MAYO', 53),
(341, '70105', 'EL CAMBIO', 53),
(342, '70150', 'MACHALA', 53),
(343, '70151', 'EL CAMBIO', 53),
(344, '70152', 'EL RETIRO', 53),
(345, '70250', 'ARENILLAS', 54),
(346, '70251', 'CHACRAS', 54),
(347, '70252', 'LA LIBERTAD', 54),
(348, '70253', 'LAS LAJAS (CAB. EN LA VICTORIA)', 54),
(349, '70254', 'PALMALES', 54),
(350, '70255', 'CARCABON', 54),
(351, '70350', 'PACCHA', 55),
(352, '70351', 'AYAPAMBA', 55),
(353, '70352', 'CORDONCILLO', 55),
(354, '70353', 'MILAGRO', 55),
(355, '70354', 'SAN JOSE', 55),
(356, '70355', 'SAN JUAN DE CERRO AZUL', 55),
(357, '70450', 'BALSAS', 56),
(358, '70451', 'BELLAMAREA', 56),
(359, '70550', 'CHILLA', 57),
(360, '70650', 'EL GUABO', 58),
(361, '70651', 'BARBONES (SUCRE)', 58),
(362, '70652', 'LA IBERIA', 58),
(363, '70653', 'TENDALES (CAB.EN PUERTO TENDALES)', 58),
(364, '70654', 'RIO BONITO', 58),
(365, '70701', 'ECUADOR', 59),
(366, '70702', 'EL PARAISO', 59),
(367, '70703', 'HUALTACO', 59),
(368, '70704', 'MILTON REYES', 59),
(369, '70705', 'UNION LOJANA', 59),
(370, '70750', 'HUAQUILLAS', 59),
(371, '70850', 'MARCABELI', 60),
(372, '70851', 'EL INGENIO', 60),
(373, '70901', 'BOLIVAR', 61),
(374, '70902', 'LOMA DE FRANCO', 61),
(375, '70903', 'OCHOA LEON (MATRIZ)', 61),
(376, '70904', 'TRES CERRITOS', 61),
(377, '70950', 'PASAJE', 61),
(378, '70951', 'BUENAVISTA', 61),
(379, '70952', 'CASACAY', 61),
(380, '70953', 'LA PEAÑA', 61),
(381, '70954', 'PROGRESO', 61),
(382, '70955', 'UZHCURRUMI', 61),
(383, '70956', 'CAÑAQUEMADA', 61),
(384, '71001', 'LA MATRIZ', 62),
(385, '71002', 'LA SUSAYA', 62),
(386, '71003', 'PIÑAS GRANDE', 62),
(387, '71050', 'PIÑAS', 62),
(388, '71051', 'CAPIRO (CAB. EN LA CAPILLA DE CAPIRO)', 62),
(389, '71052', 'LA BOCANA', 62),
(390, '71053', 'MOROMORO (CAB. EN EL VADO)', 62),
(391, '71054', 'PIEDRAS', 62),
(392, '71055', 'SAN ROQUE (AMBROSIO MALDONADO)', 62),
(393, '71056', 'SARACAY', 62),
(394, '71150', 'PORTOVELO', 63),
(395, '71151', 'CURTINCAPA', 63),
(396, '71152', 'MORALES', 63),
(397, '71153', 'SALATI', 63),
(398, '71201', 'SANTA ROSA', 64),
(399, '71202', 'PUERTO JELI', 64),
(400, '71203', 'BALNEARIO JAMBELI (SATELITE)', 64),
(401, '71204', 'JUMON (SATELITE)', 64),
(402, '71205', 'NUEVO SANTA ROSA', 64),
(403, '71250', 'SANTA ROSA', 64),
(404, '71251', 'BELLAVISTA', 64),
(405, '71252', 'JAMBELI', 64),
(406, '71253', 'LA AVANZADA', 64),
(407, '71254', 'SAN ANTONIO', 64),
(408, '71255', 'TORATA', 64),
(409, '71256', 'VICTORIA', 64),
(410, '71257', 'BELLAMARIA', 64),
(411, '71350', 'ZARUMA', 65),
(412, '71351', 'ABAÑIN', 65),
(413, '71352', 'ARCAPAMBA', 65),
(414, '71353', 'GUANAZAN', 65),
(415, '71354', 'GUIZHAGUIÑA', 65),
(416, '71355', 'HUERTAS', 65),
(417, '71356', 'MALVAS', 65),
(418, '71357', 'MULUNCAY GRANDE', 65),
(419, '71358', 'SINSAO', 65),
(420, '71359', 'SALVIAS', 65),
(421, '71401', 'LA VICTORIA', 66),
(422, '71402', 'PLATANILLOS', 66),
(423, '71403', 'VALLE HERMOSO', 66),
(424, '71450', 'LA VICTORIA', 66),
(425, '71451', 'LA LIBERTAD', 66),
(426, '71452', 'EL PARAISO', 66),
(427, '71453', 'SAN ISIDRO', 66),
(428, '80101', 'BARTOLOME RUIZ (CESAR FRANCO CARRION)', 67),
(429, '80102', '5 DE AGOSTO', 67),
(430, '80103', 'ESMERALDAS', 67),
(431, '80104', 'LUIS TELLO (LAS PALMAS)', 67),
(432, '80105', 'SIMàN PLATA TORRES', 67),
(433, '80150', 'ESMERALDAS', 67),
(434, '80151', 'ATACAMES', 67),
(435, '80152', 'CAMARONES (CAB. EN SAN VICENTE)', 67),
(436, '80153', 'CRNEL. CARLOS CONCHA TORRES (CAB.EN HUELE)', 67),
(437, '80154', 'CHINCA', 67),
(438, '80155', 'CHONTADURO', 67),
(439, '80156', 'CHUMUNDE', 67),
(440, '80157', 'LAGARTO', 67),
(441, '80158', 'LA UNION', 67),
(442, '80159', 'MAJUA', 67),
(443, '80160', 'MONTALVO (CAB. EN HORQUETA)', 67),
(444, '80161', 'RIO VERDE', 67),
(445, '80162', 'ROCAFUERTE', 67),
(446, '80163', 'SAN MATEO', 67),
(447, '80164', 'SUA (CAB. EN LA BOCANA)', 67),
(448, '80165', 'TABIAZO', 67),
(449, '80166', 'TACHINA', 67),
(450, '80167', 'TONCHIGšE', 67),
(451, '80168', 'VUELTA LARGA', 67),
(452, '80250', 'VALDEZ (LIMONES)', 68),
(453, '80251', 'ANCHAYACU', 68),
(454, '80252', 'ATAHUALPA (CAB. EN CAMARONES)', 68),
(455, '80253', 'BORBON', 68),
(456, '80254', 'LA TOLA', 68),
(457, '80255', 'LUIS VARGAS TORRES (CAB. EN PLAYA DE ORO)', 68),
(458, '80256', 'MALDONADO', 68),
(459, '80257', 'PAMPANAL DE BOLIVAR', 68),
(460, '80258', 'SAN FRANCISCO DE ONZOLE', 68),
(461, '80259', 'SANTO DOMINGO DE ONZOLE', 68),
(462, '80260', 'SELVA ALEGRE', 68),
(463, '80261', 'TELEMBI', 68),
(464, '80262', 'COLIN ELOY DEL MARIA', 68),
(465, '80263', 'SAN JOSE DE CAYAPAS', 68),
(466, '80264', 'TIMBIRE', 68),
(467, '80350', 'MUISNE', 69),
(468, '80351', 'BOLIVAR', 69),
(469, '80352', 'DAULE', 69),
(470, '80353', 'GALERA', 69),
(471, '80354', 'QUINGUE (OLMEDO PERDOMO FRANCO)', 69),
(472, '80355', 'SALIMA', 69),
(473, '80356', 'SAN FRANCISCO', 69),
(474, '80357', 'SAN GREGORIO', 69),
(475, '80358', 'SAN JOSE DE CHAMANGA (CAB.EN CHAMANGA)', 69),
(476, '80450', 'ROSA ZARATE (QUININDE)', 70),
(477, '80451', 'CUBE', 70),
(478, '80452', 'CHURA (CHANCAMA) (CAB. EN EL YERBERO)', 70),
(479, '80453', 'MALIMPIA', 70),
(480, '80454', 'VICHE', 70),
(481, '80455', 'LA UNION', 70),
(482, '80550', 'SAN LORENZO', 71),
(483, '80551', 'ALTO TAMBO (CAB. EN GUADUAL)', 71),
(484, '80552', 'ANCON (PICHANGAL) (CAB. EN PALMA REAL)', 71),
(485, '80553', 'CALDERON', 71),
(486, '80554', 'CARONDELET', 71),
(487, '80555', '5 DE JUNIO (CAB. EN UIMBI)', 71),
(488, '80556', 'CONCEPCION', 71),
(489, '80557', 'MATAJE (CAB. EN SANTANDER)', 71),
(490, '80558', 'SAN JAVIER DE CACHAVI (CAB. EN SAN JAVIER)', 71),
(491, '80559', 'SANTA RITA', 71),
(492, '80560', 'TAMBILLO', 71),
(493, '80561', 'TULULBI (CAB. EN RICAURTE)', 71),
(494, '80562', 'URBINA', 71),
(495, '80650', 'ATACAMES', 72),
(496, '80651', 'LA UNION', 72),
(497, '80652', 'SUA (CAB. EN LA BOCANA)', 72),
(498, '80653', 'TONCHIGšE', 72),
(499, '80654', 'TONSUPA', 72),
(500, '80750', 'RIOVERDE', 73),
(501, '80751', 'CHONTADURO', 73),
(502, '80752', 'CHUMUNDE', 73),
(503, '80753', 'LAGARTO', 73),
(504, '80754', 'MONTALVO (CAB. EN HORQUETA)', 73),
(505, '80755', 'ROCAFUERTE', 73),
(506, '80850', 'LA CONCORDIA', 74),
(507, '80851', 'MONTERREY', 74),
(508, '80852', 'LA VILLEGAS', 74),
(509, '80853', 'PLAN PILOTO', 74),
(510, '90101', 'AYACUCHO', 75),
(511, '90102', 'BOLIVAR (SAGRARIO)', 75),
(512, '90103', 'CARBO (CONCEPCION)', 75),
(513, '90104', 'FEBRES CORDERO', 75),
(514, '90105', 'GARCIA MORENO', 75),
(515, '90106', 'LETAMENDI', 75),
(516, '90107', 'NUEVE DE OCTUBRE', 75),
(517, '90108', 'OLMEDO (SAN ALEJO)', 75),
(518, '90109', 'ROCA', 75),
(519, '90110', 'ROCAFUERTE', 75),
(520, '90111', 'SUCRE', 75),
(521, '90112', 'TARQUI', 75),
(522, '90113', 'URDANETA', 75),
(523, '90114', 'XIMENA', 75),
(524, '90115', 'PASCUALES', 75),
(525, '90150', 'GUAYAQUIL', 75),
(526, '90151', 'CHONGàN', 75),
(527, '90152', 'JUAN GOMEZ RENDON (PROGRESO)', 75),
(528, '90153', 'MORRO', 75),
(529, '90154', 'PASCUALES', 75),
(530, '90155', 'PLAYAS (GRAL. VILLAMIL)', 75),
(531, '90156', 'POSORJA', 75),
(532, '90157', 'PUNA', 75),
(533, '90158', 'TENGUEL', 75),
(534, '90250', 'ALFREDO BAQUERIZO MORENO (JUJAN)', 76),
(535, '90350', 'BALAO', 77),
(536, '90450', 'BALZAR', 78),
(537, '90550', 'COLIMES', 79),
(538, '90551', 'SAN JACINTO', 79),
(539, '90601', 'DAULE', 80),
(540, '90602', 'LA AURORA (SATELITE)', 80),
(541, '90603', 'BANIFE', 80),
(542, '90604', 'EMILIANO CAICEDO MARCOS', 80),
(543, '90605', 'MAGRO', 80),
(544, '90606', 'PADRE JUAN BAUTISTA AGUIRRE', 80),
(545, '90607', 'SANTA CLARA', 80),
(546, '90608', 'VICENTE PIEDRAHITA', 80),
(547, '90650', 'DAULE', 80),
(548, '90651', 'ISIDRO AYORA (SOLEDAD)', 80),
(549, '90652', 'JUAN BAUTISTA AGUIRRE (LOS TINTOS)', 80),
(550, '90653', 'LAUREL', 80),
(551, '90654', 'LIMONAL', 80),
(552, '90655', 'LOMAS DE SARGENTILLO', 80),
(553, '90656', 'LOS LOJAS (ENRIQUE BAQUERIZO MORENO)', 80),
(554, '90657', 'PIEDRAHITA (NOBOL)', 80),
(555, '90701', 'ELOY ALFARO (DURAN)', 81),
(556, '90702', 'EL RECREO', 81),
(557, '90750', 'ELOY ALFARO (DURAN)', 81),
(558, '90850', 'VELASCO IBARRA (EL EMPALME)', 82),
(559, '90851', 'GUAYAS (PUEBLO NUEVO)', 82),
(560, '90852', 'EL ROSARIO', 82),
(561, '90950', 'EL TRIUNFO', 83),
(562, '91050', 'MILAGRO', 84),
(563, '91051', 'CHOBO', 84),
(564, '91052', 'GENERAL ELIZALDE (BUCAY)', 84),
(565, '91053', 'MARISCAL SUCRE (HUAQUES)', 84),
(566, '91054', 'ROBERTO ASTUDILLO (CAB. EN CRUCE DE VENECIA)', 84),
(567, '91150', 'NARANJAL', 85),
(568, '91151', 'JESUS MARIA', 85),
(569, '91152', 'SAN CARLOS', 85),
(570, '91153', 'SANTA ROSA DE FLANDES', 85),
(571, '91154', 'TAURA', 85),
(572, '91250', 'NARANJITO', 86),
(573, '91350', 'PALESTINA', 87),
(574, '91450', 'PEDRO CARBO', 88),
(575, '91451', 'VALLE DE LA VIRGEN', 88),
(576, '91452', 'SABANILLA', 88),
(577, '91601', 'SAMBORONDàN', 89),
(578, '91602', 'LA PUNTILLA (SATELITE)', 89),
(579, '91650', 'SAMBORONDàN', 89),
(580, '91651', 'TARIFA', 89),
(581, '91850', 'SANTA LUCIA', 90),
(582, '91901', 'BOCANA', 91),
(583, '91902', 'CANDILEJOS', 91),
(584, '91903', 'CENTRAL', 91),
(585, '91904', 'PARAISO', 91),
(586, '91905', 'SAN MATEO', 91),
(587, '91950', 'EL SALITRE (LAS RAMAS)', 91),
(588, '91951', 'GRAL. VERNAZA (DOS ESTEROS)', 91),
(589, '91952', 'LA VICTORIA (ÑAUZA)', 91),
(590, '91953', 'JUNQUILLAL', 91),
(591, '92050', 'SAN JACINTO DE YAGUACHI', 92),
(592, '92051', 'CRNEL. LORENZO DE GARAICOA (PEDREGAL)', 92),
(593, '92052', 'CRNEL. MARCELINO MARIDUEÑA (SAN CARLOS)', 92),
(594, '92053', 'GRAL. PEDRO J. MONTERO (BOLICHE)', 92),
(595, '92054', 'SIMIN BOLIVAR', 92),
(596, '92055', 'YAGUACHI VIEJO (CONE)', 92),
(597, '92056', 'VIRGEN DE FATIMA', 92),
(598, '92150', 'GENERAL VILLAMIL (PLAYAS)', 93),
(599, '92250', 'SIMIN BOLIVAR', 94),
(600, '92251', 'CRNEL.LORENZO DE GARAICOA (PEDREGAL)', 94),
(601, '92350', 'CORONEL MARCELINO MARIDUEÑA (SAN CARLOS)', 95),
(602, '92450', 'LOMAS DE SARGENTILLO', 96),
(603, '92451', 'ISIDRO AYORA (SOLEDAD)', 96),
(604, '92550', 'NARCISA DE JESUS', 97),
(605, '92750', 'GENERAL ANTONIO ELIZALDE (BUCAY)', 98),
(606, '92850', 'ISIDRO AYORA', 99),
(607, '100101', 'CARANQUI', 100),
(608, '100102', 'GUAYAQUIL DE ALPACHACA', 100),
(609, '100103', 'SAGRARIO', 100),
(610, '100104', 'SAN FRANCISCO', 100),
(611, '100105', 'LA DOLOROSA DEL PRIORATO', 100),
(612, '100150', 'SAN MIGUEL DE IBARRA', 100),
(613, '100151', 'AMBUQUI', 100),
(614, '100152', 'ANGOCHAGUA', 100),
(615, '100153', 'CAROLINA', 100),
(616, '100154', 'LA ESPERANZA', 100),
(617, '100155', 'LITA', 100),
(618, '100156', 'SALINAS', 100),
(619, '100157', 'SAN ANTONIO', 100),
(620, '100201', 'ANDRADE MARIN (LOURDES)', 101),
(621, '100202', 'ATUNTAQUI', 101),
(622, '100250', 'ATUNTAQUI', 101),
(623, '100251', 'IMBAYA (SAN LUIS DE COBUENDO)', 101),
(624, '100252', 'SAN FRANCISCO DE NATABUELA', 101),
(625, '100253', 'SAN JOSE DE CHALTURA', 101),
(626, '100254', 'SAN ROQUE', 101),
(627, '100301', 'SAGRARIO', 102),
(628, '100302', 'SAN FRANCISCO', 102),
(629, '100350', 'COTACACHI', 102),
(630, '100351', 'APUELA', 102),
(631, '100352', 'GARCIA MORENO (LLURIMAGUA)', 102),
(632, '100353', 'IMANTAG', 102),
(633, '100354', 'PEÑAHERRERA', 102),
(634, '100355', 'PLAZA GUTIERREZ (CALVARIO)', 102),
(635, '100356', 'QUIROGA', 102),
(636, '100357', '6 DE JULIO DE CUELLAJE (CAB. EN CUELLAJE)', 102),
(637, '100358', 'VACAS GALINDO (EL CHURO) (CAB.EN SAN MIGUEL ALTO', 102),
(638, '100401', 'JORDAN', 103),
(639, '100402', 'SAN LUIS', 103),
(640, '100450', 'OTAVALO', 103),
(641, '100451', 'DR. MIGUEL EGAS CABEZAS (PEGUCHE)', 103),
(642, '100452', 'EUGENIO ESPEJO (CALPAQUI)', 103),
(643, '100453', 'GONZALEZ SUAREZ', 103),
(644, '100454', 'PATAQUI', 103),
(645, '100455', 'SAN JOSE DE QUICHINCHE', 103),
(646, '100456', 'SAN JUAN DE ILUMAN', 103),
(647, '100457', 'SAN PABLO', 103),
(648, '100458', 'SAN RAFAEL', 103),
(649, '100459', 'SELVA ALEGRE (CAB.EN SAN MIGUEL DE PAMPLONA)', 103),
(650, '100550', 'PIMAMPIRO', 104),
(651, '100551', 'CHUGA', 104),
(652, '100552', 'MARIANO ACOSTA', 104),
(653, '100553', 'SAN FRANCISCO DE SIGSIPAMBA', 104),
(654, '100650', 'URCUQUI CABECERA CANTONAL', 105),
(655, '100651', 'CAHUASQUI', 105),
(656, '100652', 'LA MERCED DE BUENOS AIRES', 105),
(657, '100653', 'PABLO ARENAS', 105),
(658, '100654', 'SAN BLAS', 105),
(659, '100655', 'TUMBABIRO', 105),
(660, '110101', 'EL SAGRARIO', 106),
(661, '110102', 'SAN SEBASTIAN', 106),
(662, '110103', 'SUCRE', 106),
(663, '110104', 'VALLE', 106),
(664, '110150', 'LOJA', 106),
(665, '110151', 'CHANTACO', 106),
(666, '110152', 'CHUQUIRIBAMBA', 106),
(667, '110153', 'EL CISNE', 106),
(668, '110154', 'GUALEL', 106),
(669, '110155', 'JIMBILLA', 106),
(670, '110156', 'MALACATOS (VALLADOLID)', 106),
(671, '110157', 'SAN LUCAS', 106),
(672, '110158', 'SAN PEDRO DE VILCABAMBA', 106),
(673, '110159', 'SANTIAGO', 106),
(674, '110160', 'TAQUIL (MIGUEL RIO FRIO)', 106),
(675, '110161', 'VILCABAMBA (VICTORIA)', 106),
(676, '110162', 'YANGANA (ARSENIO CASTILLO)', 106),
(677, '110163', 'QUINARA', 106),
(678, '110201', 'CARIAMANGA', 107),
(679, '110202', 'CHILE', 107),
(680, '110203', 'SAN VICENTE', 107),
(681, '110250', 'CARIAMANGA', 107),
(682, '110251', 'COLAISACA', 107),
(683, '110252', 'EL LUCERO', 107),
(684, '110253', 'UTUANA', 107),
(685, '110254', 'SANGUILLIN', 107),
(686, '110301', 'CATAMAYO', 108),
(687, '110302', 'SAN JOSE', 108),
(688, '110350', 'CATAMAYO (LA TOMA)', 108),
(689, '110351', 'EL TAMBO', 108),
(690, '110352', 'GUAYQUICHUMA', 108),
(691, '110353', 'SAN PEDRO DE LA BENDITA', 108),
(692, '110354', 'ZAMBI', 108),
(693, '110450', 'CELICA', 109),
(694, '110451', 'CRUZPAMBA (CAB. EN CARLOS BUSTAMANTE)', 109),
(695, '110452', 'CHAQUINAL', 109),
(696, '110453', '12 DE DICIEMBRE (CAB. EN ACHIOTES)', 109),
(697, '110454', 'PINDAL (FEDERICO PAEZ)', 109),
(698, '110455', 'POZUL (SAN JUAN DE POZUL)', 109),
(699, '110456', 'SABANILLA', 109),
(700, '110457', 'TNTE. MAXIMILIANO RODRIGUEZ LOAIZA', 109),
(701, '110550', 'CHAGUARPAMBA', 110),
(702, '110551', 'BUENAVISTA', 110),
(703, '110552', 'EL ROSARIO', 110),
(704, '110553', 'SANTA RUFINA', 110),
(705, '110554', 'AMARILLOS', 110),
(706, '110650', 'AMALUZA', 111),
(707, '110651', 'BELLAVISTA', 111),
(708, '110652', 'JIMBURA', 111),
(709, '110653', 'SANTA TERESITA', 111),
(710, '110654', '27 DE ABRIL (CAB. EN LA NARANJA)', 111),
(711, '110655', 'EL INGENIO', 111),
(712, '110656', 'EL AIRO', 111),
(713, '110750', 'GONZANAMA', 112),
(714, '110751', 'CHANGAIMINA (LA LIBERTAD)', 112),
(715, '110752', 'FUNDOCHAMBA', 112),
(716, '110753', 'NAMBACOLA', 112),
(717, '110754', 'PURUNUMA (EGUIGUREN)', 112),
(718, '110755', 'QUILANGA (LA PAZ)', 112),
(719, '110756', 'SACAPALCA', 112),
(720, '110757', 'SAN ANTONIO DE LAS ARADAS (CAB. EN LAS ARADAS)', 112),
(721, '110801', 'GENERAL ELOY ALFARO (SAN SEBASTIAN)', 113),
(722, '110802', 'MACARA (MANUEL ENRIQUE RENGEL SUQUILANDA)', 113),
(723, '110850', 'MACARA', 113),
(724, '110851', 'LARAMA', 113),
(725, '110852', 'LA VICTORIA', 113),
(726, '110853', 'SABIANGO (LA CAPILLA)', 113),
(727, '110901', 'CATACOCHA', 114),
(728, '110902', 'LOURDES', 114),
(729, '110950', 'CATACOCHA', 114),
(730, '110951', 'CANGONAMA', 114),
(731, '110952', 'GUACHANAMA', 114),
(732, '110953', 'LA TINGUE', 114),
(733, '110954', 'LAURO GUERRERO', 114),
(734, '110955', 'OLMEDO (SANTA BARBARA)', 114),
(735, '110956', 'ORIANGA', 114),
(736, '110957', 'SAN ANTONIO', 114),
(737, '110958', 'CASANGA', 114),
(738, '110959', 'YAMANA', 114),
(739, '111050', 'ALAMOR', 115),
(740, '111051', 'CIANO', 115),
(741, '111052', 'EL ARENAL', 115),
(742, '111053', 'EL LIMO (MARIANA DE JESUS)', 115),
(743, '111054', 'MERCADILLO', 115),
(744, '111055', 'VICENTINO', 115),
(745, '111150', 'SARAGURO', 116),
(746, '111151', 'EL PARAISO DE CELEN', 116),
(747, '111152', 'EL TABLON', 116),
(748, '111153', 'LLUZHAPA', 116),
(749, '111154', 'MANU', 116),
(750, '111155', 'SAN ANTONIO DE QUMBE (CUMBE)', 116),
(751, '111156', 'SAN PABLO DE TENTA', 116),
(752, '111157', 'SAN SEBASTIAN DE YULUC', 116),
(753, '111158', 'SELVA ALEGRE', 116),
(754, '111159', 'URDANETA (PAQUISHAPA)', 116),
(755, '111160', 'SUMAYPAMBA', 116),
(756, '111250', 'SOZORANGA', 117),
(757, '111251', 'NUEVA FATIMA', 117),
(758, '111252', 'TACAMOROS', 117),
(759, '111350', 'ZAPOTILLO', 118),
(760, '111351', 'MANGAHURCO (CAZADEROS)', 118),
(761, '111352', 'GARZAREAL', 118),
(762, '111353', 'LIMONES', 118),
(763, '111354', 'PALETILLAS', 118),
(764, '111355', 'BOLASPAMBA', 118),
(765, '111450', 'PINDAL', 119),
(766, '111451', 'CHAQUINAL', 119),
(767, '111452', '12 DE DICIEMBRE (CAB.EN ACHIOTES)', 119),
(768, '111453', 'MILAGROS', 119),
(769, '111550', 'QUILANGA', 120),
(770, '111551', 'FUNDOCHAMBA', 120),
(771, '111552', 'SAN ANTONIO DE LAS ARADAS (CAB. EN LAS ARADAS)', 120),
(772, '111650', 'OLMEDO', 121),
(773, '111651', 'LA TINGUE', 121),
(774, '120101', 'CLEMENTE BAQUERIZO', 122),
(775, '120102', 'DR. CAMILO PONCE', 122),
(776, '120103', 'BARREIRO', 122),
(777, '120104', 'EL SALTO', 122),
(778, '120150', 'BABAHOYO', 122),
(779, '120151', 'BARREIRO (SANTA RITA)', 122),
(780, '120152', 'CARACOL', 122),
(781, '120153', 'FEBRES CORDERO (LAS JUNTAS)', 122),
(782, '120154', 'PIMOCHA', 122),
(783, '120155', 'LA UNIàN', 122),
(784, '120250', 'BABA', 123),
(785, '120251', 'GUARE', 123),
(786, '120252', 'ISLA DE BEJUCAL', 123),
(787, '120350', 'MONTALVO', 124),
(788, '120450', 'PUEBLOVIEJO', 125),
(789, '120451', 'PUERTO PECHICHE', 125),
(790, '120452', 'SAN JUAN', 125),
(791, '120501', 'QUEVEDO', 126),
(792, '120502', 'SAN CAMILO', 126),
(793, '120503', 'SAN JOSE', 126),
(794, '120504', 'GUAYACAN', 126),
(795, '120505', 'NICOLAS INFANTE DIAZ', 126),
(796, '120506', 'SAN CRISTàBAL', 126),
(797, '120507', 'SIETE DE OCTUBRE', 126),
(798, '120508', '24 DE MAYO', 126),
(799, '120509', 'VENUS DEL RIO QUEVEDO', 126),
(800, '120510', 'VIVA ALFARO', 126),
(801, '120550', 'QUEVEDO', 126),
(802, '120551', 'BUENA FE', 126),
(803, '120552', 'MOCACHE', 126),
(804, '120553', 'SAN CARLOS', 126),
(805, '120554', 'VALENCIA', 126),
(806, '120555', 'LA ESPERANZA', 126),
(807, '120650', 'CATARAMA', 127),
(808, '120651', 'RICAURTE', 127),
(809, '120701', '10 DE NOVIEMBRE', 128),
(810, '120750', 'VENTANAS', 128),
(811, '120751', 'QUINSALOMA', 128),
(812, '120752', 'ZAPOTAL', 128),
(813, '120753', 'CHACARITA', 128),
(814, '120754', 'LOS ANGELES', 128),
(815, '120850', 'VINCES', 129),
(816, '120851', 'ANTONIO SOTOMAYOR (CAB. EN PLAYAS DE VINCES)', 129),
(817, '120852', 'PALENQUE', 129),
(818, '120950', 'PALENQUE', 130),
(819, '121001', 'SAN JACINTO DE BUENA FE', 131),
(820, '121002', '7 DE AGOSTO', 131),
(821, '121003', '11 DE OCTUBRE', 131),
(822, '121050', 'SAN JACINTO DE BUENA FE', 131),
(823, '121051', 'PATRICIA PILAR', 131),
(824, '121150', 'VALENCIA', 132),
(825, '121250', 'MOCACHE', 133),
(826, '121350', 'QUINSALOMA', 134),
(827, '130101', 'PORTOVIEJO', 135),
(828, '130102', '12 DE MARZO', 135),
(829, '130103', 'COLON', 135),
(830, '130104', 'PICOAZA', 135),
(831, '130105', 'SAN PABLO', 135),
(832, '130106', 'ANDRES DE VERA', 135),
(833, '130107', 'FRANCISCO PACHECO', 135),
(834, '130108', '18 DE OCTUBRE', 135),
(835, '130109', 'SIMON BOLIVAR', 135),
(836, '130150', 'PORTOVIEJO', 135),
(837, '130151', 'ABDON CALDERON (SAN FRANCISCO)', 135),
(838, '130152', 'ALHAJUELA (BAJO GRANDE)', 135),
(839, '130153', 'CRUCITA', 135),
(840, '130154', 'PUEBLO NUEVO', 135),
(841, '130155', 'RIOCHICO (RIO CHICO)', 135),
(842, '130156', 'SAN PLACIDO', 135),
(843, '130157', 'CHIRIJOS', 135),
(844, '130250', 'CALCETA', 136),
(845, '130251', 'MEMBRILLO', 136),
(846, '130252', 'QUIROGA', 136),
(847, '130301', 'CHONE', 137),
(848, '130302', 'SANTA RITA', 137),
(849, '130350', 'CHONE', 0),
(850, '130351', 'BOYACA', 137),
(851, '130352', 'CANUTO', 137),
(852, '130353', 'CONVENTO', 137),
(853, '130354', 'CHIBUNGA', 137),
(854, '130355', 'ELOY ALFARO', 137),
(855, '130356', 'RICAURTE', 137),
(856, '130357', 'SAN ANTONIO', 137),
(857, '130401', 'EL CARMEN', 137),
(858, '130402', '4 DE DICIEMBRE', 138),
(859, '130450', 'EL CARMEN', 138),
(860, '130451', 'WILFRIDO LOOR MOREIRA (MAICITO)', 138),
(861, '130452', 'SAN PEDRO DE SUMA', 138),
(862, '130550', 'FLAVIO ALFARO', 138),
(863, '130551', 'SAN FRANCISCO DE NOVILLO (CAB. EN', 139),
(864, '130552', 'ZAPALLO', 139),
(865, '130601', 'DR. MIGUEL MORAN LUCIO', 139),
(866, '130602', 'MANUEL INOCENCIO PARRALES Y GUALE', 140),
(867, '130603', 'SAN LORENZO DE JIPIJAPA', 140),
(868, '130650', 'JIPIJAPA', 140),
(869, '130651', 'AMERICA', 140),
(870, '130652', 'EL ANEGADO (CAB. EN ELOY ALFARO)', 140),
(871, '130653', 'JULCUY', 140),
(872, '130654', 'LA UNION', 140),
(873, '130655', 'MACHALILLA', 140),
(874, '130656', 'MEMBRILLAL', 140),
(875, '130657', 'PEDRO PABLO GOMEZ', 140),
(876, '130658', 'PUERTO DE CAYO', 140),
(877, '130659', 'PUERTO LOPEZ', 140),
(878, '130750', 'JUNIN', 140),
(879, '130801', 'LOS ESTEROS', 141),
(880, '130802', 'MANTA', 142),
(881, '130803', 'SAN MATEO', 142),
(882, '130804', 'TARQUI', 142),
(883, '130805', 'ELOY ALFARO', 142),
(884, '130850', 'MANTA', 142),
(885, '130851', 'SAN LORENZO', 142),
(886, '130852', 'SANTA MARIANITA (BOCA DE PACOCHE)', 142),
(887, '130901', 'ANIBAL SAN ANDRES', 142),
(888, '130902', 'MONTECRISTI', 143),
(889, '130903', 'EL COLORADO', 143),
(890, '130904', 'GENERAL ELOY ALFARO', 143),
(891, '130905', 'LEONIDAS PROAÑO', 143),
(892, '130950', 'MONTECRISTI', 143),
(893, '130951', 'JARAMIJà', 143),
(894, '130952', 'LA PILA', 143),
(895, '131050', 'PAJAN', 143),
(896, '131051', 'CAMPOZANO (LA PALMA DE PAJAN)', 144),
(897, '131052', 'CASCOL', 144),
(898, '131053', 'GUALE', 144),
(899, '131054', 'LASCANO', 144),
(900, '131150', 'PICHINCHA', 144),
(901, '131151', 'BARRAGANETE', 145),
(902, '131152', 'SAN SEBASTIAN', 145),
(903, '131250', 'ROCAFUERTE', 145),
(904, '131301', 'SANTA ANA', 146),
(905, '131302', 'LODANA', 147),
(906, '131350', 'SANTA ANA DE VUELTA LARGA', 147),
(907, '131351', 'AYACUCHO', 147),
(908, '131352', 'HONORATO VASQUEZ (CAB. EN VASQUEZ)', 147),
(909, '131353', 'LA UNION', 147),
(910, '131354', 'OLMEDO', 147),
(911, '131355', 'SAN PABLO (CAB. EN PUEBLO NUEVO)', 147),
(912, '131401', 'BAHIA DE CARAQUEZ', 147),
(913, '131402', 'LEONIDAS PLAZA GUTIERREZ', 148),
(914, '131450', 'BAHIA DE CARAQUEZ', 148),
(915, '131451', 'CANOA', 148),
(916, '131452', 'COJIMIES', 148),
(917, '131453', 'CHARAPOTO', 148),
(918, '131454', '10 DE AGOSTO', 148),
(919, '131455', 'JAMA', 148),
(920, '131456', 'PEDERNALES', 148),
(921, '131457', 'SAN ISIDRO', 148),
(922, '131458', 'SAN VICENTE', 148),
(923, '131550', 'TOSAGUA', 148),
(924, '131551', 'BACHILLERO', 149),
(925, '131552', 'ANGEL PEDRO GILER (LA ESTANCILLA)', 149),
(926, '131650', 'SUCRE', 149),
(927, '131651', 'BELLAVISTA', 150),
(928, '131652', 'NOBOA', 150),
(929, '131653', 'ARQ. SIXTO DURAN BALLEN', 150),
(930, '131750', 'PEDERNALES', 150),
(931, '131751', 'COJIMIES', 151),
(932, '131752', '10 DE AGOSTO', 151),
(933, '131753', 'ATAHUALPA', 151),
(934, '131850', 'OLMEDO', 151),
(935, '131950', 'PUERTO LOPEZ', 152),
(936, '131951', 'MACHALILLA', 153),
(937, '131952', 'SALANGO', 153),
(938, '132050', 'JAMA', 153),
(939, '132150', 'JARAMIJO', 154),
(940, '132250', 'SAN VICENTE', 155),
(941, '132251', 'CANOA', 156),
(942, '140150', 'MACAS', 156),
(943, '140151', 'ALSHI (CAB. EN 9 DE OCTUBRE)', 157),
(944, '140152', 'CHIGUAZA', 157),
(945, '140153', 'GENERAL PROAÑO', 157),
(946, '140154', 'HUASAGA (CAB.EN WAMPUIK)', 157),
(947, '140155', 'MACUMA', 157),
(948, '140156', 'SAN ISIDRO', 157),
(949, '140157', 'SEVILLA DON BOSCO', 157),
(950, '140158', 'SINAI', 157),
(951, '140159', 'TAISHA', 157),
(952, '140160', 'ZUÑA (ZUÑAC)', 157),
(953, '140161', 'TUUTINENTZA', 157),
(954, '140162', 'CUCHAENTZA', 157),
(955, '140163', 'SAN JOSE DE MORONA', 157),
(956, '140164', 'RIO BLANCO', 157),
(957, '140201', 'GUALAQUIZA', 157),
(958, '140202', 'MERCEDES MOLINA', 158),
(959, '140250', 'GUALAQUIZA', 158),
(960, '140251', 'AMAZONAS (ROSARIO DE CUYES)', 158),
(961, '140252', 'BERMEJOS', 158),
(962, '140253', 'BOMBOIZA', 158),
(963, '140254', 'CHIGSINDA', 158),
(964, '140255', 'EL ROSARIO', 158),
(965, '140256', 'NUEVA TARQUI', 158),
(966, '140257', 'SAN MIGUEL DE CUYES', 158),
(967, '140258', 'EL IDEAL', 158),
(968, '140350', 'GENERAL LEONIDAS PLAZA GUTIERREZ (LIMON)', 158),
(969, '140351', 'INDANZA', 159),
(970, '140352', 'PAN DE AZUCAR', 159),
(971, '140353', 'SAN ANTONIO (CAB. EN SAN ANTONIO CENTRO', 159),
(972, '140354', 'SAN CARLOS DE LIMàN (SAN CARLOS DEL', 159),
(973, '140355', 'SAN JUAN BOSCO', 159),
(974, '140356', 'SAN MIGUEL DE CONCHAY', 159),
(975, '140357', 'SANTA SUSANA DE CHIVIAZA (CAB. EN CHIVIAZA)', 159),
(976, '140358', 'YUNGANZA (CAB. EN EL ROSARIO)', 159),
(977, '140450', 'PALORA (METZERA)', 159),
(978, '140451', 'ARAPICOS', 160),
(979, '140452', 'CUMANDA (CAB. EN COLONIA AGRICOLA SEVILLA DEL ORO)', 160),
(980, '140453', 'HUAMBOYA', 160),
(981, '140454', 'SANGAY (CAB. EN NAYAMANACA)', 160),
(982, '140550', 'SANTIAGO DE MENDEZ', 160),
(983, '140551', 'COPAL', 161),
(984, '140552', 'CHUPIANZA', 161),
(985, '140553', 'PATUCA', 161),
(986, '140554', 'SAN LUIS DE EL ACHO (CAB. EN EL ACHO)', 161),
(987, '140555', 'SANTIAGO', 161),
(988, '140556', 'TAYUZA', 161),
(989, '140557', 'SAN FRANCISCO DE CHINIMBIMI', 161),
(990, '140650', 'SUCUA', 161),
(991, '140651', 'ASUNCION', 162),
(992, '140652', 'HUAMBI', 162),
(993, '140653', 'LOGROÑO', 162),
(994, '140654', 'YAUPI', 162),
(995, '140655', 'SANTA MARIANITA DE JESUS', 162),
(996, '140750', 'HUAMBOYA', 162),
(997, '140751', 'CHIGUAZA', 163),
(998, '140752', 'PABLO SEXTO', 163),
(999, '140850', 'SAN JUAN BOSCO', 163),
(1000, '140851', 'PAN DE AZUCAR', 164),
(1001, '140852', 'SAN CARLOS DE LIMON', 164),
(1002, '140853', 'SAN JACINTO DE WAKAMBEIS', 164),
(1003, '140854', 'SANTIAGO DE PANANZA', 164),
(1004, '140950', 'TAISHA', 164),
(1005, '140951', 'HUASAGA (CAB. EN WAMPUIK)', 165),
(1006, '140952', 'MACUMA', 165),
(1007, '140953', 'TUUTINENTZA', 165),
(1008, '140954', 'PUMPUENTSA', 165),
(1009, '141050', 'LOGROÑO', 165),
(1010, '141051', 'YAUPI', 166),
(1011, '141052', 'SHIMPIS', 166),
(1012, '141150', 'PABLO SEXTO', 166),
(1013, '141250', 'SANTIAGO', 167),
(1014, '141251', 'SAN JOSE DE MORONA', 168),
(1015, '150150', 'TENA', 168),
(1016, '150151', 'AHUANO', 169),
(1017, '150152', 'CARLOS JULIO AROSEMENA TOLA (ZATZA-YACU)', 169),
(1018, '150153', 'CHONTAPUNTA', 169),
(1019, '150154', 'PANO', 169),
(1020, '150155', 'PUERTO MISAHUALLI', 169),
(1021, '150156', 'PUERTO NAPO', 169),
(1022, '150157', 'TALAG', 169),
(1023, '150158', 'SAN JUAN DE MUYUNA', 169),
(1024, '150350', 'ARCHIDONA', 169),
(1025, '150351', 'AVILA', 170),
(1026, '150352', 'COTUNDO', 170),
(1027, '150353', 'LORETO', 170),
(1028, '150354', 'SAN PABLO DE USHPAYACU', 170),
(1029, '150355', 'PUERTO MURIALDO', 170),
(1030, '150450', 'EL CHACO', 170),
(1031, '150451', 'GONZALO D¡AZ DE PINEDA (EL BOMBON)', 171),
(1032, '150452', 'LINARES', 171),
(1033, '150453', 'OYACACHI', 171),
(1034, '150454', 'SANTA ROSA', 171),
(1035, '150455', 'SARDINAS', 171),
(1036, '150750', 'BAEZA', 171),
(1037, '150751', 'COSANGA', 172),
(1038, '150752', 'CUYUJA', 172),
(1039, '150753', 'PAPALLACTA', 172),
(1040, '150754', 'SAN FRANCISCO DE BORJA (VIRGILIO DAVILA)', 172),
(1041, '150755', 'SAN JOSE DEL PAYAMINO', 172),
(1042, '150756', 'SUMACO', 172),
(1043, '150950', 'CARLOS JULIO AROSEMENA TOLA', 172),
(1044, '160150', 'PUYO', 173),
(1045, '160151', 'ARAJUNO', 174),
(1046, '160152', 'CANELOS', 174),
(1047, '160153', 'CURARAY', 174),
(1048, '160154', 'DIEZ DE AGOSTO', 174),
(1049, '160155', 'FATIMA', 174),
(1050, '160156', 'MONTALVO (ANDOAS)', 174),
(1051, '160157', 'POMONA', 174),
(1052, '160158', 'RIO CORRIENTES', 174),
(1053, '160159', 'RIO TIGRE', 174),
(1054, '160160', 'SANTA CLARA', 174),
(1055, '160161', 'SARAYACU', 174),
(1056, '160162', 'SIMON BOLIVAR (CAB. EN MUSHULLACTA)', 174),
(1057, '160163', 'TARQUI', 174),
(1058, '160164', 'TENIENTE HUGO ORTIZ', 174),
(1059, '160165', 'VERACRUZ (INDILLAMA) (CAB. EN INDILLAMA)', 174),
(1060, '160166', 'EL TRIUNFO', 174),
(1061, '160250', 'MERA', 174),
(1062, '160251', 'MADRE TIERRA', 175),
(1063, '160252', 'SHELL', 175),
(1064, '160350', 'SANTA CLARA', 175),
(1065, '160351', 'SAN JOSE', 176),
(1066, '160450', 'ARAJUNO', 176),
(1067, '160451', 'CURARAY', 177),
(1068, '170101', 'BELISARIO QUEVEDO', 177),
(1069, '170102', 'CARCELEN', 178),
(1070, '170103', 'CENTRO HISTàRICO', 178),
(1071, '170104', 'COCHAPAMBA', 178),
(1072, '170105', 'COMITE DEL PUEBLO', 178),
(1073, '170106', 'COTOCOLLAO', 178),
(1074, '170107', 'CHILIBULO', 178),
(1075, '170108', 'CHILLOGALLO', 178),
(1076, '170109', 'CHIMBACALLE', 178),
(1077, '170110', 'EL CONDADO', 178),
(1078, '170111', 'GUAMANI', 178),
(1079, '170112', 'IÑAQUITO', 178),
(1080, '170113', 'ITCHIMBIA', 178),
(1081, '170114', 'JIPIJAPA', 178),
(1082, '170115', 'KENNEDY', 178),
(1083, '170116', 'LA ARGELIA', 178),
(1084, '170117', 'LA CONCEPCION', 178),
(1085, '170118', 'LA ECUATORIANA', 178),
(1086, '170119', 'LA FERROVIARIA', 178),
(1087, '170120', 'LA LIBERTAD', 178),
(1088, '170121', 'LA MAGDALENA', 178),
(1089, '170122', 'LA MENA', 178),
(1090, '170123', 'MARISCAL SUCRE', 178),
(1091, '170124', 'PONCEANO', 178),
(1092, '170125', 'PUENGASI', 178),
(1093, '170126', 'QUITUMBE', 178),
(1094, '170127', 'RUMIPAMBA', 178),
(1095, '170128', 'SAN BARTOLO', 178),
(1096, '170129', 'SAN ISIDRO DEL INCA', 178),
(1097, '170130', 'SAN JUAN', 178),
(1098, '170131', 'SOLANDA', 178),
(1099, '170132', 'TURUBAMBA', 178),
(1100, '170150', 'QUITO DISTRITO METROPOLITANO', 178),
(1101, '170151', 'ALANGASI', 178),
(1102, '170152', 'AMAGUAÑA', 178),
(1103, '170153', 'ATAHUALPA', 178),
(1104, '170154', 'CALACALI', 178),
(1105, '170155', 'CALDERON', 178),
(1106, '170156', 'CONOCOTO', 178),
(1107, '170157', 'CUMBAYA', 178),
(1108, '170158', 'CHAVEZPAMBA', 178),
(1109, '170159', 'CHECA', 178),
(1110, '170160', 'EL QUINCHE', 178),
(1111, '170161', 'GUALEA', 178),
(1112, '170162', 'GUANGOPOLO', 178),
(1113, '170163', 'GUAYLLABAMBA', 178),
(1114, '170164', 'LA MERCED', 178),
(1115, '170165', 'LLANO CHICO', 178),
(1116, '170166', 'LLOA', 178),
(1117, '170167', 'MINDO', 178),
(1118, '170168', 'NANEGAL', 178),
(1119, '170169', 'NANEGALITO', 178),
(1120, '170170', 'NAYON', 178),
(1121, '170171', 'NONO', 178),
(1122, '170172', 'PACTO', 178),
(1123, '170173', 'PEDRO VICENTE MALDONADO', 178),
(1124, '170174', 'PERUCHO', 178),
(1125, '170175', 'PIFO', 178),
(1126, '170176', 'PINTAG', 178),
(1127, '170177', 'POMASQUI', 178),
(1128, '170178', 'PUELLARO', 178),
(1129, '170179', 'PUEMBO', 178),
(1130, '170180', 'SAN ANTONIO', 178),
(1131, '170181', 'SAN JOSE DE MINAS', 178),
(1132, '170182', 'SAN MIGUEL DE LOS BANCOS', 178),
(1133, '170183', 'TABABELA', 178),
(1134, '170184', 'TUMBACO', 178),
(1135, '170185', 'YARUQUI', 178),
(1136, '170186', 'ZAMBIZA', 178),
(1137, '170187', 'PUERTO QUITO', 178),
(1138, '170201', 'AYORA', 178),
(1139, '170202', 'CAYAMBE', 179),
(1140, '170203', 'JUAN MONTALVO', 179),
(1141, '170250', 'CAYAMBE', 179),
(1142, '170251', 'ASCAZUBI', 179),
(1143, '170252', 'CANGAHUA', 179),
(1144, '170253', 'OLMEDO (PESILLO)', 179),
(1145, '170254', 'OTON', 179),
(1146, '170255', 'SANTA ROSA DE CUZUBAMBA', 179),
(1147, '170350', 'MACHACHI', 179),
(1148, '170351', 'ALOAG', 180),
(1149, '170352', 'ALOASI', 180),
(1150, '170353', 'CUTUGLAHUA', 180),
(1151, '170354', 'EL CHAUPI', 180),
(1152, '170355', 'MANUEL CORNEJO ASTORGA (TANDAPI)', 180),
(1153, '170356', 'TAMBILLO', 180),
(1154, '170357', 'UYUMBICHO', 180),
(1155, '170450', 'TABACUNDO', 180),
(1156, '170451', 'LA ESPERANZA', 181),
(1157, '170452', 'MALCHINGUI', 181),
(1158, '170453', 'TOCACHI', 181),
(1159, '170454', 'TUPIGACHI', 181),
(1160, '170501', 'SANGOLQUI', 181),
(1161, '170502', 'SAN PEDRO DE TABOADA', 182),
(1162, '170503', 'SAN RAFAEL', 182),
(1163, '170550', 'SANGOLQUI', 182),
(1164, '170551', 'COTOGCHOA', 182),
(1165, '170552', 'RUMIPAMBA', 182),
(1166, '170750', 'SAN MIGUEL DE LOS BANCOS', 182),
(1167, '170751', 'MINDO', 183),
(1168, '170752', 'PEDRO VICENTE MALDONADO', 183),
(1169, '170753', 'PUERTO QUITO', 183),
(1170, '170850', 'PEDRO VICENTE MALDONADO', 183),
(1171, '170950', 'PUERTO QUITO', 184),
(1172, '180101', 'ATOCHA  FICOA', 185),
(1173, '180102', 'CELIANO MONGE', 186),
(1174, '180103', 'HUACHI CHICO', 186),
(1175, '180104', 'HUACHI LORETO', 186),
(1176, '180105', 'LA MERCED', 186),
(1177, '180106', 'LA PENINSULA', 186),
(1178, '180107', 'MATRIZ', 186),
(1179, '180108', 'PISHILATA', 186),
(1180, '180109', 'SAN FRANCISCO', 186),
(1181, '180150', 'AMBATO', 186),
(1182, '180151', 'AMBATILLO', 186),
(1183, '180152', 'ATAHUALPA (CHISALATA)', 186),
(1184, '180153', 'AUGUSTO N. MARTINEZ (MUNDUGLEO)', 186),
(1185, '180154', 'CONSTANTINO FERNANDEZ (CAB. EN CULLITAHUA)', 186),
(1186, '180155', 'HUACHI GRANDE', 186),
(1187, '180156', 'IZAMBA', 186),
(1188, '180157', 'JUAN BENIGNO VELA', 186),
(1189, '180158', 'MONTALVO', 186),
(1190, '180159', 'PASA', 186),
(1191, '180160', 'PICAIGUA', 186),
(1192, '180161', 'PILAGSIN (PILAHSIN)', 186),
(1193, '180162', 'QUISAPINCHA (QUIZAPINCHA)', 186),
(1194, '180163', 'SAN BARTOLOME DE PINLLOG', 186),
(1195, '180164', 'SAN FERNANDO (PASA SAN FERNANDO)', 186),
(1196, '180165', 'SANTA ROSA', 186),
(1197, '180166', 'TOTORAS', 186),
(1198, '180167', 'CUNCHIBAMBA', 186),
(1199, '180168', 'UNAMUNCHO', 186),
(1200, '180250', 'BAÑOS DE AGUA SANTA', 186),
(1201, '180251', 'LLIGUA', 187),
(1202, '180252', 'RIO NEGRO', 187),
(1203, '180253', 'RIO VERDE', 187),
(1204, '180254', 'ULBA', 187),
(1205, '180350', 'CEVALLOS', 187),
(1206, '180450', 'MOCHA', 188),
(1207, '180451', 'PINGUILI', 189),
(1208, '180550', 'PATATE', 189),
(1209, '180551', 'EL TRIUNFO', 190),
(1210, '180552', 'LOS ANDES (CAB. EN POATUG)', 190),
(1211, '180553', 'SUCRE (CAB. EN SUCRE-PATATE URCU)', 190),
(1212, '180650', 'QUERO', 190),
(1213, '180651', 'RUMIPAMBA', 191),
(1214, '180652', 'YANAYACU - MOCHAPATA (CAB. EN YANAYACU)', 191),
(1215, '180701', 'PELILEO', 191),
(1216, '180702', 'PELILEO GRANDE', 192),
(1217, '180750', 'PELILEO', 192),
(1218, '180751', 'BENITEZ (PACHANLICA)', 192),
(1219, '180752', 'BOLIVAR', 192),
(1220, '180753', 'COTALO', 192),
(1221, '180754', 'CHIQUICHA (CAB. EN CHIQUICHA GRANDE)', 192),
(1222, '180755', 'EL ROSARIO (RUMICHACA)', 192),
(1223, '180756', 'GARCIA MORENO (CHUMAQUI)', 192),
(1224, '180757', 'GUAMBALO (HUAMBALO)', 192),
(1225, '180758', 'SALASACA', 192),
(1226, '180801', 'CIUDAD NUEVA', 192),
(1227, '180802', 'PILLARO', 193),
(1228, '180850', 'PILLARO', 193),
(1229, '180851', 'BAQUERIZO MORENO', 193),
(1230, '180852', 'EMILIO MARIA TERAN (RUMIPAMBA)', 193),
(1231, '180853', 'MARCOS ESPINEL (CHACATA)', 193),
(1232, '180854', 'PRESIDENTE URBINA (CHAGRAPAMBA -PATZUCUL)', 193),
(1233, '180855', 'SAN ANDRES', 193),
(1234, '180856', 'SAN JOSE DE POALO', 193),
(1235, '180857', 'SAN MIGUELITO', 193),
(1236, '180950', 'TISALEO', 193),
(1237, '180951', 'QUINCHICOTO', 194),
(1238, '190101', 'EL LIMON', 194),
(1239, '190102', 'ZAMORA', 195),
(1240, '190150', 'ZAMORA', 195),
(1241, '190151', 'CUMBARATZA', 195),
(1242, '190152', 'GUADALUPE', 195),
(1243, '190153', 'IMBANA (LA VICTORIA DE IMBANA)', 195),
(1244, '190154', 'PAQUISHA', 195),
(1245, '190155', 'SABANILLA', 195),
(1246, '190156', 'TIMBARA', 195),
(1247, '190157', 'ZUMBI', 195),
(1248, '190158', 'SAN CARLOS DE LAS MINAS', 195),
(1249, '190250', 'ZUMBA', 195),
(1250, '190251', 'CHITO', 196),
(1251, '190252', 'EL CHORRO', 196),
(1252, '190253', 'EL PORVENIR DEL CARMEN', 196),
(1253, '190254', 'LA CHONTA', 196),
(1254, '190255', 'PALANDA', 196),
(1255, '190256', 'PUCAPAMBA', 196),
(1256, '190257', 'SAN FRANCISCO DEL VERGEL', 196),
(1257, '190258', 'VALLADOLID', 196),
(1258, '190259', 'SAN ANDRES', 196),
(1259, '190350', 'GUAYZIMI', 196),
(1260, '190351', 'ZURMI', 197),
(1261, '190352', 'NUEVO PARAISO', 197),
(1262, '190450', '28 DE MAYO (SAN JOSE DE YACUAMBI)', 197),
(1263, '190451', 'LA PAZ', 198),
(1264, '190452', 'TUTUPALI', 198),
(1265, '190550', 'YANTZAZA (YANZATZA)', 198),
(1266, '190551', 'CHICAÑA', 199),
(1267, '190552', 'EL PANGUI', 199),
(1268, '190553', 'LOS ENCUENTROS', 199),
(1269, '190650', 'EL PANGUI', 199),
(1270, '190651', 'EL GUISME', 200),
(1271, '190652', 'PACHICUTZA', 200),
(1272, '190653', 'TUNDAYME', 200),
(1273, '190750', 'ZUMBI', 200),
(1274, '190751', 'PAQUISHA', 201),
(1275, '190752', 'TRIUNFO-DORADO', 201),
(1276, '190753', 'PANGUINTZA', 201),
(1277, '190850', 'PALANDA', 201),
(1278, '190851', 'EL PORVENIR DEL CARMEN', 202),
(1279, '190852', 'SAN FRANCISCO DEL VERGEL', 202),
(1280, '190853', 'VALLADOLID', 202),
(1281, '190854', 'LA CANELA', 202),
(1282, '190950', 'PAQUISHA', 202),
(1283, '190951', 'BELLAVISTA', 203),
(1284, '190952', 'NUEVO QUITO', 203),
(1285, '200150', 'PUERTO BAQUERIZO MORENO', 203),
(1286, '200151', 'EL PROGRESO', 204),
(1287, '200152', 'A SANTA MARIA (FLOREANA) (CAB. EN PTO. VELASCO IBARRA)', 204),
(1288, '200250', 'PUERTO VILLAMIL', 204),
(1289, '200251', 'TOMAS DE BERLANGA (SANTO TOMAS)', 205),
(1290, '200350', 'PUERTO AYORA', 205),
(1291, '200351', 'BELLAVISTA', 206),
(1292, '200352', 'SANTA ROSA (INCLUYE LA ISLA BALTRA)', 206),
(1293, '210150', 'NUEVA LOJA', 206),
(1294, '210151', 'CUYABENO', 207),
(1295, '210152', 'DURENO', 207),
(1296, '210153', 'GENERAL FARFAN', 207),
(1297, '210154', 'TARAPOA', 207),
(1298, '210155', 'EL ENO', 207),
(1299, '210156', 'PACAYACU', 207),
(1300, '210157', 'JAMBELI', 207),
(1301, '210158', 'SANTA CECILIA', 207),
(1302, '210159', 'AGUAS NEGRAS', 207),
(1303, '210250', 'EL DORADO DE CASCALES', 207),
(1304, '210251', 'EL REVENTADOR', 208),
(1305, '210252', 'GONZALO PIZARRO', 208),
(1306, '210253', 'LUMBAQUI', 208),
(1307, '210254', 'PUERTO LIBRE', 208),
(1308, '210255', 'SANTA ROSA DE SUCUMBIOS', 208),
(1309, '210350', 'PUERTO EL CARMEN DEL PUTUMAYO', 208),
(1310, '210351', 'PALMA ROJA', 209),
(1311, '210352', 'PUERTO BOLIVAR (PUERTO MONTUFAR)', 209),
(1312, '210353', 'PUERTO RODRIGUEZ', 209),
(1313, '210354', 'SANTA ELENA', 209),
(1314, '210450', 'SHUSHUFINDI', 209),
(1315, '210451', 'LIMONCOCHA', 210),
(1316, '210452', 'PAÑACOCHA', 210),
(1317, '210453', 'SAN ROQUE (CAB. EN SAN VICENTE)', 210),
(1318, '210454', 'SAN PEDRO DE LOS COFANES', 210),
(1319, '210455', 'SIETE DE JULIO', 210),
(1320, '210550', 'LA BONITA', 210),
(1321, '210551', 'EL PLAYON DE SAN FRANCISCO', 211),
(1322, '210552', 'LA SOFIA', 211),
(1323, '210553', 'ROSA FLORIDA', 211),
(1324, '210554', 'SANTA BARBARA', 211),
(1325, '210650', 'EL DORADO DE CASCALES', 211),
(1326, '210651', 'SANTA ROSA DE SUCUMBIOS', 212),
(1327, '210652', 'SEVILLA', 212),
(1328, '210750', 'TARAPOA', 212),
(1329, '210751', 'CUYABENO', 213),
(1330, '210752', 'AGUAS NEGRAS', 213),
(1331, '220150', 'PUERTO FRANCISCO DE ORELLANA (EL COCA)', 213),
(1332, '220151', 'DAYUMA', 214),
(1333, '220152', 'TARACOA (NUEVA ESPERANZA: YUCA)', 214),
(1334, '220153', 'ALEJANDRO LABAKA', 214),
(1335, '220154', 'EL DORADO', 214),
(1336, '220155', 'EL EDEN', 214),
(1337, '220156', 'GARCIA MORENO', 214),
(1338, '220157', 'INES ARANGO (CAB. EN WESTERN)', 214),
(1339, '220158', 'LA BELLEZA', 214),
(1340, '220159', 'NUEVO PARAISO (CAB. EN UNION)', 214),
(1341, '220160', 'SAN JOSE DE GUAYUSA', 214),
(1342, '220161', 'SAN LUIS DE ARMENIA', 214),
(1343, '220201', 'TIPITINI', 214),
(1344, '220250', 'NUEVO ROCAFUERTE', 215),
(1345, '220251', 'CAPITAN AUGUSTO RIVADENEYRA', 215),
(1346, '220252', 'CONONACO', 215),
(1347, '220253', 'SANTA MARIA DE HUIRIRIMA', 215),
(1348, '220254', 'TIPUTINI', 215),
(1349, '220255', 'YASUNI', 215),
(1350, '220350', 'LA JOYA DE LOS SACHAS', 215),
(1351, '220351', 'ENOKANQUI', 216),
(1352, '220352', 'POMPEYA', 216),
(1353, '220353', 'SAN CARLOS', 216),
(1354, '220354', 'SAN SEBASTIAN DEL COCA', 216),
(1355, '220355', 'LAGO SAN PEDRO', 216),
(1356, '220356', 'RUMIPAMBA', 216),
(1357, '220357', 'TRES DE NOVIEMBRE', 216),
(1358, '220358', 'UNION MILAGREÑA', 216),
(1359, '220450', 'LORETO', 216),
(1360, '220451', 'AVILA (CAB. EN HUIRUNO)', 217),
(1361, '220452', 'PUERTO MURIALDO', 217),
(1362, '220453', 'SAN JOSE DE PAYAMINO', 217),
(1363, '220454', 'SAN JOSE DE DAHUANO', 217),
(1364, '220455', 'SAN VICENTE DE HUATICOCHA', 217),
(1365, '230101', 'ABRAHAM CALAZACON', 217),
(1366, '230102', 'BOMBOLI', 218),
(1367, '230103', 'CHIGUILPE', 218),
(1368, '230104', 'RIO TOACHI', 218),
(1369, '230105', 'RIO VERDE', 218),
(1370, '230106', 'SANTO DOMINGO DE LOS COLORADOS', 218),
(1371, '230107', 'ZARACAY', 218),
(1372, '230150', 'SANTO DOMINGO DE LOS COLORADOS', 218),
(1373, '230151', 'ALLURIQUIN', 218),
(1374, '230152', 'PUERTO LIMàN', 218),
(1375, '230153', 'LUZ DE AMERICA', 218);
INSERT INTO `parroquia` (`id_parroquia`, `codigo_parroquia`, `nombre_parroquia`, `codigo_ciudad`) VALUES
(1376, '230154', 'SAN JACINTO DEL BUA', 218),
(1377, '230155', 'VALLE HERMOSO', 218),
(1378, '230156', 'EL ESFUERZO', 218),
(1379, '230157', 'SANTA MARIA DEL TOACHI', 218),
(1380, '240101', 'BALLENITA', 218),
(1381, '240102', 'SANTA ELENA', 219),
(1382, '240150', 'SANTA ELENA', 219),
(1383, '240151', 'ATAHUALPA', 219),
(1384, '240152', 'COLONCHE', 219),
(1385, '240153', 'CHANDUY', 219),
(1386, '240154', 'MANGLARALTO', 219),
(1387, '240155', 'SIMON BOLIVAR (JULIO MORENO)', 219),
(1388, '240156', 'SAN JOSE DE ANCON', 219),
(1389, '240250', 'LA LIBERTAD', 219),
(1390, '240301', 'CARLOS ESPINOZA LARREA', 220),
(1391, '240302', 'GRAL. ALBERTO ENRIQUEZ GALLO', 221),
(1392, '240303', 'VICENTE ROCAFUERTE', 221),
(1393, '240304', 'SANTA ROSA', 221),
(1394, '240350', 'SALINAS', 221),
(1395, '240351', 'ANCONCITO', 221),
(1396, '240352', 'JOSE LUIS TAMAYO (MUEY)', 221),
(1397, '900151', 'LAS GOLONDRINAS', 221),
(1398, '900351', 'MANGA DEL CURA', 222),
(1399, '900451', 'EL PIEDRERO', 223);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plan_cuentas`
--

CREATE TABLE `plan_cuentas` (
  `id_ctas` int(11) NOT NULL,
  `id_empresa` int(11) DEFAULT NULL,
  `codcta` varchar(12) DEFAULT NULL,
  `nomcta` varchar(45) DEFAULT NULL,
  `id_moneda` int(11) DEFAULT NULL,
  `refcon` varchar(255) DEFAULT NULL,
  `bansel` varchar(12) DEFAULT NULL,
  `fcrea` datetime DEFAULT current_timestamp(),
  `fmodifica` datetime DEFAULT NULL,
  `id_grupo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `plan_cuentas`
--

INSERT INTO `plan_cuentas` (`id_ctas`, `id_empresa`, `codcta`, `nomcta`, `id_moneda`, `refcon`, `bansel`, `fcrea`, `fmodifica`, `id_grupo`) VALUES
(1, 1, '1.', 'ACTIVO', 1, NULL, NULL, '2019-11-26 16:29:16', '2019-12-23 18:13:44', 1),
(2, 1, '1.1.', 'ACTIVO CORRIENTE', 1, NULL, NULL, '2019-11-26 16:29:16', '2019-12-23 18:14:46', 1),
(3, 1, '1.1.1.', 'EFECTIVO Y EQUIVALENTES AL EFECTIVO', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(4, 1, '1.1.1.01.', 'CAJA', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(5, 1, '1.1.1.01.001', 'Caja general', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(6, 1, '1.1.1.01.002', 'Caja Chica', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(7, 1, '1.1.1.02.', 'BANCOS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(8, 1, '1.1.1.02.001', 'Mutualista Pichincha', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(9, 1, '1.1.1.02.002', 'Cooperativa 9 de Octubre', 1, NULL, 'C', '2019-11-26 16:29:16', NULL, 2),
(10, 1, '1.1.1.02.003', 'Banco del Pichincha', 1, NULL, 'C', '2019-11-26 16:29:16', NULL, 2),
(11, 1, '1.1.1.03.', 'BANCOS DEL EXTERIOR', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(12, 1, '1.1.1.04.', 'INVERSIONES LOCALES', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(13, 1, '1.1.1.05.', 'IVNERSIONES DEL EXTERIOR', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(14, 1, '1.1.2.', 'ACTIVOS FINANCIEROS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(15, 1, '1.1.2.01.', 'CUENTAS POR COBRAR CLIENTES NO RELACIONADOS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(16, 1, '1.1.2.01.001', 'Clientes Locales', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(17, 1, '1.1.2.01.002', 'Clientes del Exterior', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(18, 1, '1.1.2.02.', 'TERJETAS DE CREDITO', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(19, 1, '1.1.2.02.001', 'Visa Pichincha', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(20, 1, '1.1.2.02.002', 'Pacificard', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(21, 1, '1.1.2.06.', 'DOCUMENTOS POR COBRAR CLIENTES RELACIONADOS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(22, 1, '1.1.2.06.001', 'Jorge Sol¡s', 1, NULL, 'C', '2019-11-26 16:29:16', NULL, 2),
(23, 1, '1.1.2.07.', 'OTRAS CUENTAS POR COBRAR RELACIONADOS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(24, 1, '1.1.2.09.', 'CUENTAS POR COBRAR EMPLEADOS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(25, 1, '1.1.2.09.001', 'Anticipos a empleados', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(26, 1, '1.1.2.09.002', 'Pr‚stamos a empleados', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(27, 1, '1.1.2.09.003', 'Anticipos gastos de viaje', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(28, 1, '1.1.2.09.004', 'Anticipos a varios', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(29, 1, '1.1.2.09.005', 'Anticipos a proveedores', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(30, 1, '1.1.2.10.', 'OTRAS CUENTAS POR COBRAR', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(31, 1, '1.1.2.10.001', 'Otras Cuentas por Cobrar', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(32, 1, '1.1.2.19.', 'PROVISIàN CUENTAS INCOBRABLES', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(33, 1, '1.1.2.19.001', 'Provisi¢n cuentas incobrables', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(34, 1, '1.1.3.', 'ACTIVO REALIZABLE', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(35, 1, '1.1.3.01.', 'PRODUCTO PARA LA VENTA', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(36, 1, '1.1.3.01.001', 'Inventario bodega principal', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(37, 1, '1.1.4.', 'SERVICIOS Y PAGOS ANTICIPADOS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(38, 1, '1.1.4.01.', 'SEGUROS PAGADOS POR ANTICIPADO', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(39, 1, '1.1.4.01.001', 'Seguros generales', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(40, 1, '1.1.4.01.002', 'Seguros edificios', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(41, 1, '1.1.4.01.003', 'Seguros equipos', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(42, 1, '1.1.5.', 'ACTIVOS POR IMPUESTOS CORRIENTES', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(43, 1, '1.1.5.01.', 'CREDITO TRIBUTARIO DE I.V.A.', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(44, 1, '1.1.5.01.001', '12% iva compra bienes y servicios', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(45, 1, '1.1.5.01.002', '12% iva compra de servicios', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(46, 1, '1.1.5.01.003', '12% iva compra de activos fijos', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(47, 1, '1.1.5.01.004', '12% iva importaci¢n de bienes', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(48, 1, '1.1.5.01.005', 'Retenciones de IVA (nos retienen)', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(49, 1, '1.1.5.01.006', 'Cr‚dito tributario de IVA', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(50, 1, '1.1.5.02.', 'CRDITO TRIBUTARIO IMPUESTO A LA RENTA', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(51, 1, '1.1.5.02.001', 'Retenciones ejercicio corriente', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(52, 1, '1.1.5.02.002', 'Anticipo impuesto ejercicio corriente', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(53, 1, '1.1.5.02.003', 'Retenciones ejercicios anteriores', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(54, 1, '1.1.5.02.004', 'Saldo a favor del contribuyente', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(55, 1, '1.2.', 'ACTIVO NO CORRIENTE', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(56, 1, '1.2.1.', 'PROPIEDAD PLANTA Y EQUIPO', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(57, 1, '1.2.1.01.', 'ACTIVOS FIJOS COSTO HISTàRICO', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(58, 1, '1.2.1.01.001', 'Terrenos', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(59, 1, '1.2.1.01.002', 'Edificios', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(60, 1, '1.2.1.01.003', 'Veh¡culos', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(61, 1, '1.2.1.01.004', 'Maquinaria y equipo', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(62, 1, '1.2.1.01.005', 'Equipo de computaci¢n y Software', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(63, 1, '1.2.1.01.006', 'Equipo de cafeter¡a', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(64, 1, '1.2.1.01.007', 'Muebles y enseres', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(65, 1, '1.2.1.01.008', 'Activos fijos en construcci¢n', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(66, 1, '1.2.1.02.', 'DEPRECIACIàN ACUMULADA COSTO HISTàRICO', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(67, 1, '1.2.1.02.001', 'Depr. Acum. Edificios', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(68, 1, '1.2.1.02.002', 'Depr. Acum. Veh¡culos', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(69, 1, '1.2.1.02.003', 'Depr. Acum. Maquinaria y equipo', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(70, 1, '1.2.1.02.004', 'Depr. Acum. Equipo de computaci¢n', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(71, 1, '1.2.1.02.005', 'Depr. Acum. Equipo de cafeter¡a', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(72, 1, '1.2.1.02.006', 'Depr. Acum. Muebles y enseres', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(73, 1, '1.2.1.02.007', 'Depr. Acum.  Otros activos', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(74, 1, '1.2.1.03.', 'ACTIVOS FIJOS COSTO REVALUADO', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(75, 1, '1.2.1.03.001', 'Terrenos', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(76, 1, '1.2.1.03.002', 'Edificios', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(77, 1, '1.2.1.03.003', 'Veh¡culos', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(78, 1, '1.2.1.03.004', 'Maquinaria y equipo', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(79, 1, '1.2.1.03.005', 'Equipo de computaci¢n', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(80, 1, '1.2.1.03.006', 'Equipo de cafeter¡a', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(81, 1, '1.2.1.03.007', 'Muebles y enseres', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(82, 1, '1.2.1.04.', 'DEPREC. ACUM. COSTO REVALUADO', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(83, 1, '1.2.2.', 'ACTIVO DIFERIDO', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(84, 1, '1.2.2.02.', 'ACTIVO DIFERIDO', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(85, 1, '1.2.2.02.001', 'Marcas, Patentes y otros similares', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(86, 1, '1.2.2.02.002', 'Gastos de organizaci¢n y constitucion', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(87, 1, '1.2.2.02.003', 'Instalaciones Prepagados', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(88, 1, '1.2.2.03.', 'AMORTIZACION GASTOS DIFERIDOS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(89, 1, '1.2.2.03.001', 'Amortizaci¢n acumulada activos diferidos', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(90, 1, '1.2.3.07.', 'CTAS Y DOC. POR COBRAR CLIENTES', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(91, 1, '1.2.3.08.', 'OTROS ACTIVOS DIFERIDO', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(92, 1, '1.2.3.08.001', 'Impuestos Diferidos', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(93, 1, '1.2.7.', 'ACTIVOS A LARGO PLAZO', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(94, 1, '1.2.7.04.', 'INVERSIONES EN ACCIONES', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(95, 1, '1.2.7.05.', 'PROVISION VALUACION INVERSIONES', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(96, 1, '1.2.7.05.001', 'Provisi¢n valuaci¢n de inversiones', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(97, 1, '1.2.7.06.', 'OTROS ACTIVOS NO CORRIENTES', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(98, 1, '1.2.7.06.001', 'Otros activos no corrientes', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(99, 1, '1.2.7.06.002', 'Garat¡a ', 1, NULL, 'C', '2019-11-26 16:29:16', NULL, 2),
(100, 1, '2.', 'PASIVO', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(101, 1, '2.1.', 'PASIVO CORRIENTE', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(102, 1, '2.1.3.', 'CUENTAS Y DOCUMENTOS POR PAGAR', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(103, 1, '2.1.3.01.', 'PROVEEDORES LOCALES', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(104, 1, '2.1.3.01.001', 'Cuentas por Pagar Proveedores', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(105, 1, '2.1.3.01.002', 'Visa Banco Pichincha', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(106, 1, '2.1.3.01.003', 'Pacificard', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(107, 1, '2.1.3.01.004', 'American Express', 1, NULL, 'C', '2019-11-26 16:29:16', NULL, 2),
(108, 1, '2.1.3.02.', 'PROVEEDORES DEL EXTERIOR', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(109, 1, '2.1.4.', 'OBLIGACIONES INSTITUCIONES FINANCIERAS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(110, 1, '2.1.4.01.', 'INSTITUCIONES FINANCIERAS LOCALES', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(111, 1, '2.1.4.01.001', 'Sobregiros bancarios ', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(112, 1, '2.1.4.01.002', 'Sobregiros Bancarios ', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(113, 1, '2.1.4.01.003', 'Pr‚stamos banco', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(114, 1, '2.1.4.01.004', 'Pr‚stamos banco', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(115, 1, '2.1.5.', 'OTRAS OBLIGACIONES CORRIENTES', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(116, 1, '2.1.5.01.', 'RETENCIONES DE I.V.A.', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(117, 1, '2.1.5.01.721', '30% retenci¢n iva bienes', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(118, 1, '2.1.5.01.723', '70% retenci¢n iva servicios', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(119, 1, '2.1.5.01.725', '100% Honorarios y liquidaciones de compras', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(120, 1, '2.1.5.02.', 'RETENCION IMPUESTO A LA RENTA', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(121, 1, '2.1.5.02.302', 'En relaci¢n de dependencia', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(122, 1, '2.1.5.02.303', 'Honorarios profesionales', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(123, 1, '2.1.5.02.304', 'Pagos a notarios y registradores de la proied', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(124, 1, '2.1.5.02.307', 'Servicios de mano de obra', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(125, 1, '2.1.5.02.308', 'Utilizacion / aprovechamiento de la imagen', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(126, 1, '2.1.5.02.309', 'Servicios de publicidad y comunicaci¢n', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(127, 1, '2.1.5.02.310', 'Servicios de transporte personas y carga', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(128, 1, '2.1.5.02.311', 'Retenciones por liquidaciones de compras', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(129, 1, '2.1.5.02.312', 'Retenciones de bienes muebles', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(130, 1, '2.1.5.02.319', 'Arrendamiento mercantil', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(131, 1, '2.1.5.02.320', 'Arrendamiento bienes inmuebles', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(132, 1, '2.1.5.02.322', 'Seguros y reaseguros (10% de la prima)', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(133, 1, '2.1.5.02.323', 'Retenci¢n rendimientos financieros', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(134, 1, '2.1.5.02.324', 'Retenciones por dividendos', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(135, 1, '2.1.5.02.332', 'No sujeto a retencion', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(136, 1, '2.1.5.02.340', 'Otras retenciones aplicables 1%', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(137, 1, '2.1.5.02.341', 'Otras retenciones aplicables 2%', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(138, 1, '2.1.5.02.342', 'Otras retenciones aplicables 8%', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(139, 1, '2.1.5.02.343', 'Otras retenciones aplicables 25%', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(140, 1, '2.1.5.02.344', 'Aplicables otros porcentajes', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(141, 1, '2.1.5.02.421', 'Sin convenio de doble tributaci¢n', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(142, 1, '2.1.5.03.', 'IMPUESTO IVA EN VENTAS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(143, 1, '2.1.5.03.001', '12% IVA en ventas', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(144, 1, '2.1.5.04.', 'IMPUESTOS POR LIQUIDAR', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(145, 1, '2.1.5.05.', 'IMPUESTO A LA RENTA', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(146, 1, '2.1.5.05.001', 'Impuesto a la renta del ejercicio', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(147, 1, '2.1.5.06.', 'OBLIGACIONES CON EL IESS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(148, 1, '2.1.5.06.001', 'Aportes IESS por pagar', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(149, 1, '2.1.5.06.002', 'Fondos de reserva', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(150, 1, '2.1.5.06.003', 'Pr‚stamos quirografarios', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(151, 1, '2.1.5.06.004', 'Pr‚stamos hipotecarios', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(152, 1, '2.1.5.07.', 'OBLIGACIONES CON EMPLEADOS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(153, 1, '2.1.5.07.001', 'Remuneraciones por pagar', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(154, 1, '2.1.5.07.002', 'Descuentos comisariato', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(155, 1, '2.1.5.07.003', 'D‚cimo tercer sueldo', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(156, 1, '2.1.5.07.004', 'D‚cimo cuerto sueldo', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(157, 1, '2.1.5.07.005', 'Vacaciones por pagar', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(158, 1, '2.1.5.07.006', 'Finiquitos por pagar', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(159, 1, '2.1.5.08.', 'PARTICIPACION TRABAJADORES', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(160, 1, '2.1.5.08.001', '15% participaci¢n trabajadores', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(161, 1, '2.1.5.09.', 'DIVIDENDOS POR PAGAR', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(162, 1, '2.1.5.09.001', 'Jorge Sol¡s', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(163, 1, '2.1.5.10.', 'CUENTAS POR PAGAR RELACIONADAS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(164, 1, '2.1.5.10.001', 'Jorge Sol¡s', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(165, 1, '2.1.5.11.', 'ANTICIPOS RECIBIDOS DE CLIENTES', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(166, 1, '2.1.5.11.001', 'Anticipos de clientes', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(167, 1, '2.1.9.', 'PASIVOS ASO. CON ACTIVOS NO CORRIENTES', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(168, 1, '2.1.9.02.', 'PORCION CORRIENTE PROVISIONES BENEFICIOS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(169, 1, '2.1.9.02.001', 'Provisi¢n jubilaci¢n patronal', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(170, 1, '2.1.9.02.002', 'provisi¢n para desahucio', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(171, 1, '2.2.', 'PASIVO NO CORRIENTE', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(172, 1, '2.2.4.', 'CUENTAS POR PAGAR DIVERSAS RELACIONADAS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(173, 1, '2.2.4.01.', 'CUENTAS POR PAGAR ACCIONISTAS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(174, 1, '2.2.4.01.001', 'Jorge Sol¡s', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(175, 1, '2.2.7.', 'PROVISIONES POR BENEFICIOS EMPLEADOS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(176, 1, '2.2.7.01.', 'PROVISION JUBILACION', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(177, 1, '2.2.7.01.001', 'Provisi¢n jubilaci¢n patronal', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(178, 1, '2.2.7.01.002', 'Provisi¢n por desahucio', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(179, 1, '2.2.8.', 'PASIVO DIFERIDO', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(180, 1, '2.2.8.01.', 'PASIVOS POR IMPUESTOS DIFERIDOS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(181, 1, '2.2.8.01.001', 'pasivos por impuestos diferidos ejer.01', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(182, 1, '3.', 'PATRIMONIO', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(183, 1, '3.1.', 'CAPITAL', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(184, 1, '3.1.1.', 'CAPITAL SUSCRITO O ASIGNADO', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(185, 1, '3.1.1.01.', 'CAPITAL SUSCRITO O ASIGNADO', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(186, 1, '3.1.1.01.001', 'Capital Suscrito y Asignado', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(187, 1, '3.2.1.', 'APORTES A FUTURA CAPITALIZACION', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(188, 1, '3.2.1.01.', 'APORTES A FUTURA CAPITALIZACION', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(189, 1, '3.2.1.01.001', 'Aportes a Futura Capitalizaci¢n', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(190, 1, '3.4.', 'RESERVAS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(191, 1, '3.4.1.', 'RESERVA LEGAL', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(192, 1, '3.4.1.01.', 'RESERVA LEGAL', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(193, 1, '3.4.1.01.001', 'Reservas legales', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(194, 1, '3.4.3.', 'RESERVA DE CAPITAL', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(195, 1, '3.4.3.01.', 'RESERVA DE CAPITAL (BRECHA NEC 17)', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(196, 1, '3.4.3.01.001', 'Reserva de capital (ajuste brecha NEC 17)', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(197, 1, '3.4.3.01.002', 'Reservas de capital', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(198, 1, '3.5.', 'OTROS RESULTADOS INTEGRALES', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(199, 1, '3.5.1.', 'OTROS RESULTADOS INTEGRALES', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(200, 1, '3.5.1.01.', 'OTROS RESULTADOS INTEGRALES', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(201, 1, '3.5.1.01.001', 'Superavit activos financieros (venta)', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(202, 1, '3.5.1.01.002', 'Superavit reval. Propiedad planta y equipo', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(203, 1, '3.5.1.01.003', 'Superavit reval. Activos intangibles', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(204, 1, '3.5.1.01.004', 'Otros superavits por revaluacion', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(205, 1, '3.6.', 'RESULTADOS ACUMULADOS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(206, 1, '3.6.1.', 'RESULTADOS ACUMULADOS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(207, 1, '3.6.1.01.', 'GANANCIAS ACUMULADAS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(208, 1, '3.6.1.01.001', 'Ganancias acumuladas', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(209, 1, '3.6.1.02.', 'PERDIDAS ACUMULADAS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(210, 1, '3.6.1.02.001', 'Perdidas acumuladas', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(211, 1, '3.6.1.03.', 'RESULTADOS ACUM. ADPCION NIIF\'s', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(212, 1, '3.6.1.03.001', 'Resultados acum. adopcion 1ra.vez', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(213, 1, '3.6.1.03.002', 'Resultados acum. NIIF\'s transicion', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(214, 1, '3.7.', 'RESULTADOS DEL EJERCICIO', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(215, 1, '3.7.1.', 'RESULTADOS DEL EJERCICIO', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(216, 1, '3.7.1.01.', 'RESULTADO NETO DEL EJERCICIO', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(217, 1, '3.7.1.01.001', 'Utilidad neta del ejercicio', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(218, 1, '3.7.1.02.', 'PERDIDA NETA DEL EJERCICIO', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(219, 1, '3.7.1.02.001', 'Perdida neta del ejercicio', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(220, 1, '4.', 'INGRESOS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(221, 1, '4.1.', 'INGRESOS DE ACTIVIDADES ORDINARIAS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(222, 1, '4.1.1.', 'VENTAS DE BIENES', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(223, 1, '4.1.1.01.', 'VENTAS BRUTAS LOCALES', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(224, 1, '4.1.1.01.01', 'Venta Servicios Tarifa 0%', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(225, 1, '4.1.1.01.02', 'Venta Servicios Tarifa 14%', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(226, 1, '4.1.1.02.', 'DEVOLUCIONES EN VENTAS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(227, 1, '4.1.1.02.01', 'Devoluciones ventas tarifa 0%', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(228, 1, '4.1.1.02.02', 'Devoluciones ventas tarifa 14%', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(229, 1, '4.1.1.03.', 'DESCUENTOS EN VENTAS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(230, 1, '4.1.1.03.01', 'Descuentos ventas tarifa 0%', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(231, 1, '4.1.1.03.02', 'Descuentos ventas tarifa 14%', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(232, 1, '4.1.2.', 'PRESTACION DE SERVICIOS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(233, 1, '4.1.2.01.', 'PRESTACION DE SERVICIOS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(234, 1, '4.1.2.01.01', 'Servicios Contables', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(235, 1, '4.1.2.01.02', 'Cierre de Ejercicios Contables', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(236, 1, '4.1.2.01.03', 'Comisi¢n SIACI', 1, NULL, 'C', '2019-11-26 16:29:16', NULL, 2),
(237, 1, '4.1.2.01.04', 'Administraci¢n Conjuntos', 1, NULL, 'C', '2019-11-26 16:29:16', NULL, 2),
(238, 1, '4.1.2.01.05', 'Sistema Contable', 1, NULL, 'C', '2019-11-26 16:29:16', NULL, 2),
(239, 1, '4.1.2.01.06', 'Firma Electronica', 1, NULL, 'C', '2019-11-26 16:29:16', NULL, 2),
(240, 1, '4.1.6.', 'INTERESES RECIBIDOS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(241, 1, '4.1.6.01.', 'INTERESES GANADOS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(242, 1, '4.1.6.01.01', 'Intereses financieros', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(243, 1, '4.1.8.', 'OTROS INGRESOS ACTIVIDADES ORDINARIAS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(244, 1, '4.1.8.01.', 'OTROS INGRESOS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(245, 1, '4.1.8.01.01', 'Otros ingresos envases', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(246, 1, '4.1.8.01.02', 'Otros ingresos Guayaquil', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(247, 1, '4.1.8.01.03', 'Descuentos en compras', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(248, 1, '4.1.8.01.04', 'Ajustes por diferencias en precios', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(249, 1, '4.1.8.01.05', 'Diferencias en caja', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(250, 1, '5.', 'COSTOS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(251, 1, '5.1.', 'COSTOS Y COMISIONES', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(252, 1, '5.1.1.', 'COSTOS DE VENTAS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(253, 1, '5.1.1.01.', 'COSTOS DE VENTAS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(254, 1, '5.1.1.01.01', 'Costo de Ventas', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(255, 1, '5.1.1.01.02', 'Diferencia en toma fisica', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(256, 1, '5.1.1.01.03', 'Ajuste inventarios siniestro', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(257, 1, '5.1.1.01.04', 'Costos por ajustes de inventarios', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(258, 1, '5.1.1.01.05', 'Costo productos panaderia', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(259, 1, '5.1.2.', 'GASTOS EN COMPRAS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(260, 1, '5.1.2.01.', 'COMPRAS Y COMISIONES', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(261, 1, '5.1.2.01.01', 'Comisiones a terceros', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(262, 1, '5.1.2.01.02', 'Fletes en barco', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(263, 1, '5.1.2.01.03', 'Fletes en avion', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(264, 1, '5.1.2.01.04', 'Impuestos Sicgal', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(265, 1, '5.1.2.01.05', 'Gastos estibaje', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(266, 1, '5.1.2.01.06', 'Gastos muellaje', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(267, 1, '5.1.2.01.07', 'Servicios de descarga', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(268, 1, '5.1.2.01.08', 'Transporte en desembarque', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(269, 1, '5.1.2.01.09', 'Servicios de logistica', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(270, 1, '5.1.2.01.10', 'Estibaje (Guayaquil)', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(271, 1, '5.1.2.01.11', 'Transporte en (Guayaquil)', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(272, 1, '5.1.2.01.12', 'Gastos varios en (Guayaquil)', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(273, 1, '5.5.', 'COSTOS DE OPERACIàN', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(274, 1, '5.5.1.', 'GASTOS DE PERSONAL', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(275, 1, '5.5.1.01.', 'GASTOS DE REMUNERACIONES', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(276, 1, '5.5.1.01.01', 'Sueldos', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(277, 1, '5.5.1.01.02', 'Horas extras', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(278, 1, '5.5.1.01.03', 'Adicional horas extras', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(279, 1, '5.5.1.01.04', 'Incentivos', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(280, 1, '5.5.1.01.05', 'Subsidio de antiguedad', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(281, 1, '5.5.1.02.', 'APORTES SEGURIDAD SOCIAL', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(282, 1, '5.5.1.02.01', 'Aporte patronal', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(283, 1, '5.5.1.02.02', 'Fondos de reserva', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(284, 1, '5.5.1.03.', 'BENEFICIOS SOCIALES E INDEMNIZACIONES', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(285, 1, '5.5.1.03.01', 'Decimo tercer sueldo', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(286, 1, '5.5.1.03.02', 'Decimo cuarto sueldo', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(287, 1, '5.5.1.03.03', 'Vacaciones', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(288, 1, '5.5.1.03.04', 'Gastos medicos personal', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(289, 1, '5.5.1.03.05', 'Bonificacines', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(290, 1, '5.5.1.03.06', 'Capacitacion', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(291, 1, '5.5.1.03.07', 'Uniformes', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(292, 1, '5.5.1.03.08', 'Jubilacion patronal', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(293, 1, '5.5.1.03.09', 'Indemnizaciones - desahucios', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(294, 1, '5.5.1.04.', 'OTROS GASTOS DE PERSONAL', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(295, 1, '5.5.1.04.01', 'Trabajos ocasionales', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(296, 1, '5.5.1.04.02', 'Agasajos trabajadores', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(297, 1, '5.5.1.04.03', 'Pasajes aereos', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(298, 1, '5.5.1.04.04', 'Gastos de viaje y alimentacion', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(299, 1, '6.', 'GASTOS DE OPERACION', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(300, 1, '6.1.', 'GASTOS DE VENTAS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(301, 1, '6.1.1.', 'GASTOS DE PERSONAL', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(302, 1, '6.1.1.01.', 'GASTOS DE REMUNERACIONES', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(303, 1, '6.1.1.01.01', 'Sueldos', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(304, 1, '6.1.1.01.02', 'Horas extras', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(305, 1, '6.1.1.01.03', 'Adicional horas extras', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(306, 1, '6.1.1.01.04', 'Incentivos', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(307, 1, '6.1.1.01.05', 'Subsidio de antiguedad', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(308, 1, '6.1.1.02.', 'APORTES SEGURIDAD SOCIAL', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(309, 1, '6.1.1.02.01', 'Aporte patronal', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(310, 1, '6.1.1.02.02', 'Fondos de reserva', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(311, 1, '6.1.1.03.', 'BENEFICIOS SOCIALES E INDEMNIZACIONES', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(312, 1, '6.1.1.03.01', 'Decimo tercer sueldo', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(313, 1, '6.1.1.03.02', 'Decimo cuarto sueldo', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(314, 1, '6.1.1.03.03', 'Vacaciones', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(315, 1, '6.1.1.03.04', 'Gastos medicos personal', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(316, 1, '6.1.1.03.05', 'Bonificacines', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(317, 1, '6.1.1.03.06', 'Capacitacion', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(318, 1, '6.1.1.03.07', 'Uniformes', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(319, 1, '6.1.1.03.08', 'Jubilacion patronal', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(320, 1, '6.1.1.03.09', 'Indemnizaciones - desahucios', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(321, 1, '6.1.1.04.', 'OTROS GASTOS DE PERSONAL', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(322, 1, '6.1.1.04.01', 'Trabajos ocasionales', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(323, 1, '6.1.1.04.02', 'Agasajos trabajadores', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(324, 1, '6.1.1.04.03', 'Pasajes aereos', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(325, 1, '6.1.1.04.04', 'Gastos de viaje y alimentacion', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(326, 1, '6.1.1.05.', 'HONORARIOS A PERSONAS NATURALES', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(327, 1, '6.1.1.05.01', 'Honorarios profesionales', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(328, 1, '6.1.2.', 'OTROS GASTOS OPERACIONALES', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(329, 1, '6.1.2.01.', 'SERVICIOS DE TERCEROS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(330, 1, '6.1.2.01.01', 'Arrendamiento Oficina', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(331, 1, '6.1.2.01.02', 'Formularios Preimpresos', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(332, 1, '6.1.2.01.03', 'Gastos de publicidad', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(333, 1, '6.1.2.01.04', 'Monitoreo y vigilancia', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(334, 1, '6.1.2.01.05', 'Gastos de Representaci¢n', 1, NULL, 'C', '2019-11-26 16:29:16', NULL, 2),
(335, 1, '6.1.2.01.06', 'Firma Electronica', 1, NULL, 'C', '2019-11-26 16:29:16', NULL, 2),
(336, 1, '6.1.2.03.', 'GASTOS BANCARIOS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(337, 1, '6.1.2.03.02', 'Comisiones tarejtas', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(338, 1, '6.1.2.03.03', 'Gastos bancarios', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(339, 1, '6.1.2.04.', 'GASTOS DE MANTENIMIENTO', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(340, 1, '6.1.2.04.01', 'Mantenimiento equipo de computaci¢n', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(341, 1, '6.1.2.06.', 'AMORTIZACIONES / PROVISIONES', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(342, 1, '6.1.2.06.01', 'Gasto seguros generales', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(343, 1, '6.1.2.06.02', 'Gastos cuentas incobralbes', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(344, 1, '6.1.2.07.', 'GASTOS POR DETERIORO', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(345, 1, '6.1.2.07.01', 'Gastos siniestro de inventarios', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(346, 1, '6.1.2.07.02', 'Gastos deterioro de inventarios', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(347, 1, '6.1.2.07.03', 'Gastos deterioro cuentas por cobrar', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(348, 1, '6.1.2.07.04', 'Gastos deterioro propiedad, planta y equipo', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(349, 1, '6.2.', 'GASTOS DE ADMINISTRACION', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(350, 1, '6.2.1.', 'GASTOS DE PERSONAL', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(351, 1, '6.2.1.01.', 'GASTOS DE REMUNERACIONES', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(352, 1, '6.2.1.01.01', 'Sueldos', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(353, 1, '6.2.1.01.02', 'Horas extras', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(354, 1, '6.2.1.01.03', 'Adicional horas extras', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(355, 1, '6.2.1.01.04', 'Incentivos', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(356, 1, '6.2.1.01.05', 'Subsidio de antiguedad', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(357, 1, '6.2.1.02.', 'APORTES SEGURIDAD SOCIAL', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(358, 1, '6.2.1.02.01', 'Aporte patronal', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(359, 1, '6.2.1.02.02', 'Fondos de reserva', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(360, 1, '6.2.1.03.', 'BENEFICIOS SOCIALES E INDEMNIZACIONES', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(361, 1, '6.2.1.03.01', 'Decimo tercer sueldo', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(362, 1, '6.2.1.03.02', 'Decimo cuarto sueldo', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(363, 1, '6.2.1.03.03', 'Vacaciones', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(364, 1, '6.2.1.03.04', 'Gastos medicos personal', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(365, 1, '6.2.1.03.05', 'Bonificacines', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(366, 1, '6.2.1.03.06', 'Capacitacion', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(367, 1, '6.2.1.03.07', 'Uniformes', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(368, 1, '6.2.1.03.08', 'Jubilacion patronal', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(369, 1, '6.2.1.03.09', 'Indemnizaciones - desahucios', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(370, 1, '6.2.1.04.', 'OTROS GASTOS DE PERSONAL', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(371, 1, '6.2.1.04.01', 'Trabajos ocasionales', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(372, 1, '6.2.1.04.02', 'Agasajos trabajadores', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(373, 1, '6.2.1.04.03', 'Pasajes aereos', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(374, 1, '6.2.1.04.04', 'Gastos de viaje y alimentacion', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(375, 1, '6.2.1.05.', 'HONORARIOS A PERSONAS NATURALES', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(376, 1, '6.2.1.05.01', 'Honorarios profesionales', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(377, 1, '6.2.1.05.02', 'Honorarios Contabilidad', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(378, 1, '6.2.2.', 'OTROS GASTOS OPERACIONALES', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(379, 1, '6.2.2.01.', 'SERVICIOS DE TERCEROS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(380, 1, '6.2.2.01.01', 'Combustible', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(381, 1, '6.2.2.01.02', 'Lubricantes', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(382, 1, '6.2.2.01.03', 'Gastos de Gesti¢n', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(383, 1, '6.2.2.01.04', 'Utiles de oficina', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(384, 1, '6.2.2.01.05', 'Movilizacion', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(385, 1, '6.2.2.01.06', 'Gastos varios', 1, NULL, 'C', '2019-11-26 16:29:16', NULL, 2),
(386, 1, '6.2.2.01.07', 'Transporte', 1, NULL, 'C', '2019-11-26 16:29:16', NULL, 2),
(387, 1, '6.2.2.02.', 'AGUA ENERGIA Y TELECOMUNICACIONES', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(388, 1, '6.2.2.02.01', 'Gastos de agua', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(389, 1, '6.2.2.02.02', 'Energia electrica', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(390, 1, '6.2.2.02.03', 'Telecomunicaciones', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(391, 1, '6.2.2.02.04', 'Servicio de internet', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(392, 1, '6.2.2.03.', 'IMPUESTOS Y CONTRIBUCIONES', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(393, 1, '6.2.2.03.01', 'Impuestos municipales', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(394, 1, '6.2.2.03.02', 'Cuotas varias', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(395, 1, '6.2.2.03.03', 'Gastos bancarios', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(396, 1, '6.2.2.04.', 'GASTOS DE MANTENIMIENTO', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(397, 1, '6.2.2.04.01', 'Mantenimiento Vehiculos', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(398, 1, '6.2.2.04.02', 'Mantenimiento equipo de computaci¢n', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(399, 1, '6.2.2.04.03', 'Mantenimiento muebles', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(400, 1, '6.2.2.05.', 'DEPRECIACIONES', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(401, 1, '6.2.2.05.01', 'Depreciaci¢n edificio', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(402, 1, '6.2.2.05.02', 'Depreciaci¢n veh¡culos', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(403, 1, '6.2.2.05.03', 'Depreciaci¢n maquinaria', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(404, 1, '6.2.2.05.04', 'Depreciaci¢n equipo de computaci¢n', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(405, 1, '6.2.2.05.05', 'Depreciaci¢n equipo de cafeter¡a', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(406, 1, '6.2.2.05.06', 'Depreciaci¢n muebles y enseres', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(407, 1, '6.2.2.05.07', 'Depreciaci¢n otros activos', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(408, 1, '6.2.2.07.', 'GASTOS POR DETERIORO', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(409, 1, '6.2.2.07.01', 'Gastos deterioro propiedad, planta y equipo', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(410, 1, '6.3.', 'OTROS GASTOS OPERACIONALES', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(411, 1, '6.3.1.', 'OTROS GASTOS OPERACIONALES', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(412, 1, '6.3.1.01.', 'GASTOS NO DEDUCIBLES', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(413, 1, '6.3.1.01.01', 'Impuestos asumidos', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(414, 1, '6.3.1.01.02', 'Multas tributarias', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(415, 1, '6.3.1.01.03', 'Multas y contravenciones', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(416, 1, '6.3.2.', 'GASTOS FINANCIEROS', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(417, 1, '6.3.2.01.', 'GASTOS DE INTERESES', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(418, 1, '6.3.2.01.01', 'Intereses pr‚stamos bancarios', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(419, 1, '6.3.2.01.02', 'Intereses pr‚stamos terceros', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(420, 1, '6.3.2.02.', 'GASTOS DE FINANCIAMIENTO', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(421, 1, '6.3.2.02.01', 'Comisiones bancarias', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 2),
(422, 1, '6.4.', 'OTROS GASTOS NO OPERACIONALES', 1, NULL, NULL, '2019-11-26 16:29:16', NULL, 1),
(423, NULL, '6.4.1.', 'OTROS GASTOS NO OPERACIONALESS', 1, NULL, NULL, '2019-11-26 16:29:16', '2019-12-23 18:20:07', 1),
(424, NULL, '6.4.1.01.', 'PERDIDA EN INVERSIONES ASOCIADASS', 1, NULL, NULL, '2019-11-26 16:29:16', '2019-12-23 18:19:55', 1),
(427, 1, NULL, NULL, NULL, NULL, NULL, '2019-12-17 15:13:58', '2019-12-17 15:13:58', NULL),
(429, NULL, NULL, '1', NULL, NULL, NULL, '2019-12-23 18:11:54', '2019-12-23 18:11:54', 1),
(431, NULL, '9999', '9999', 1, NULL, '1', '2019-12-23 18:21:23', '2019-12-23 18:21:23', 1),
(432, NULL, NULL, NULL, NULL, NULL, NULL, '2019-12-23 18:35:42', '2019-12-23 18:35:42', NULL),
(433, NULL, NULL, NULL, NULL, NULL, NULL, '2019-12-23 18:35:43', '2019-12-23 18:35:43', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presentacion`
--

CREATE TABLE `presentacion` (
  `id_presentacion` int(11) NOT NULL,
  `nombre` varchar(300) DEFAULT NULL,
  `descripcion` varchar(300) DEFAULT NULL,
  `fcrea` datetime DEFAULT NULL,
  `fmodifica` datetime DEFAULT NULL,
  `ucrea` int(11) DEFAULT NULL,
  `umodifica` int(11) DEFAULT NULL,
  `id_empresa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `presentacion`
--

INSERT INTO `presentacion` (`id_presentacion`, `nombre`, `descripcion`, `fcrea`, `fmodifica`, `ucrea`, `umodifica`, `id_empresa`) VALUES
(1, 'present1', 'jdjsh', NULL, NULL, NULL, NULL, 1),
(2, 'present2', 'sdjsjn', NULL, NULL, NULL, NULL, 1),
(4, NULL, NULL, '2019-12-12 15:47:12', '2019-12-12 15:47:12', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id_producto` int(11) NOT NULL,
  `cod_principal` varchar(300) DEFAULT NULL,
  `cod_alterno` varchar(300) DEFAULT NULL,
  `imagen` text DEFAULT NULL,
  `nombre` varchar(300) DEFAULT NULL,
  `codigo_barras` varchar(300) DEFAULT NULL,
  `cuenta_contable` varchar(300) DEFAULT NULL,
  `form_prod` varchar(300) DEFAULT NULL,
  `descripcion` varchar(300) DEFAULT NULL,
  `caracteristicas` varchar(300) DEFAULT NULL,
  `normativa` varchar(300) DEFAULT NULL,
  `uso` varchar(300) DEFAULT NULL,
  `nombrec` varchar(300) DEFAULT NULL,
  `sector` varchar(300) DEFAULT NULL,
  `ubicacion_fisica` varchar(300) DEFAULT NULL,
  `unidad_entrada` int(11) DEFAULT NULL,
  `unidad_salida` int(11) DEFAULT NULL,
  `vencimiento` date DEFAULT NULL,
  `existencia_maxima` int(11) DEFAULT NULL,
  `existencia_minima` int(11) DEFAULT NULL,
  `numero_unidad` varchar(300) DEFAULT NULL,
  `estado` varchar(300) DEFAULT NULL,
  `vehiculo` tinyint(1) DEFAULT NULL,
  `placa` varchar(300) DEFAULT NULL,
  `pais_origen` varchar(300) DEFAULT NULL,
  `ano_fabricacionv` varchar(4) DEFAULT NULL,
  `color` varchar(300) DEFAULT NULL,
  `carroceria` varchar(300) DEFAULT NULL,
  `combustible` varchar(300) DEFAULT NULL,
  `motor` varchar(300) DEFAULT NULL,
  `cilindraje` varchar(300) DEFAULT NULL,
  `chasis` varchar(300) DEFAULT NULL,
  `clase` varchar(300) DEFAULT NULL,
  `subclase` varchar(300) DEFAULT NULL,
  `numero_pasajeros` int(11) DEFAULT NULL,
  `iva` varchar(300) DEFAULT NULL,
  `ice` varchar(300) DEFAULT NULL,
  `arancel_advalorem` decimal(10,2) DEFAULT NULL,
  `arancel_especifico` decimal(10,2) DEFAULT NULL,
  `arancel_fodinfa` decimal(10,2) DEFAULT NULL,
  `comision` decimal(10,2) DEFAULT NULL,
  `salvaguardia` decimal(10,2) DEFAULT NULL,
  `pvp_precio1` decimal(10,2) DEFAULT NULL,
  `precio2` decimal(10,2) DEFAULT NULL,
  `precio3` decimal(10,2) DEFAULT NULL,
  `precio4` decimal(10,2) DEFAULT NULL,
  `precio5` decimal(10,2) DEFAULT NULL,
  `descuento` decimal(10,2) DEFAULT NULL,
  `utilidad` decimal(10,2) DEFAULT NULL,
  `fecha_fabricacion` date DEFAULT NULL,
  `ultimo_costo` decimal(10,2) DEFAULT NULL,
  `costo_promedio` decimal(10,2) DEFAULT NULL,
  `costo_total` decimal(10,2) DEFAULT NULL,
  `existencia_total` decimal(10,2) DEFAULT NULL,
  `fcrea` datetime DEFAULT NULL,
  `fmodifica` datetime DEFAULT NULL,
  `ucrea` int(11) DEFAULT NULL,
  `umodifica` int(11) DEFAULT NULL,
  `id_linea_producto` int(11) DEFAULT NULL,
  `id_tipo_producto` int(11) DEFAULT NULL,
  `id_marca` int(11) DEFAULT NULL,
  `id_modelo` int(11) DEFAULT NULL,
  `id_presentacion` int(11) DEFAULT NULL,
  `id_tipo_medida` int(11) DEFAULT NULL,
  `id_unidad_medida` int(11) DEFAULT NULL,
  `id_empresa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id_producto`, `cod_principal`, `cod_alterno`, `imagen`, `nombre`, `codigo_barras`, `cuenta_contable`, `form_prod`, `descripcion`, `caracteristicas`, `normativa`, `uso`, `nombrec`, `sector`, `ubicacion_fisica`, `unidad_entrada`, `unidad_salida`, `vencimiento`, `existencia_maxima`, `existencia_minima`, `numero_unidad`, `estado`, `vehiculo`, `placa`, `pais_origen`, `ano_fabricacionv`, `color`, `carroceria`, `combustible`, `motor`, `cilindraje`, `chasis`, `clase`, `subclase`, `numero_pasajeros`, `iva`, `ice`, `arancel_advalorem`, `arancel_especifico`, `arancel_fodinfa`, `comision`, `salvaguardia`, `pvp_precio1`, `precio2`, `precio3`, `precio4`, `precio5`, `descuento`, `utilidad`, `fecha_fabricacion`, `ultimo_costo`, `costo_promedio`, `costo_total`, `existencia_total`, `fcrea`, `fmodifica`, `ucrea`, `umodifica`, `id_linea_producto`, `id_tipo_producto`, `id_marca`, `id_modelo`, `id_presentacion`, `id_tipo_medida`, `id_unidad_medida`, `id_empresa`) VALUES
(42, '001', NULL, 'prod.jpg', 'producto1', NULL, '1.1.', '1234', NULL, '12', '12', '12', '', 'Servicio', NULL, NULL, 7, NULL, 1, 1, '123', '1', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '3', '2', '123.00', '123.00', '123.00', '123.00', '123.00', '13.00', NULL, NULL, NULL, NULL, NULL, NULL, '2019-12-10', NULL, NULL, '14.00', NULL, '2019-12-10 19:36:51', '2020-01-07 16:10:08', NULL, NULL, 1, 3, 1, 1, 1, 2, 7, 1),
(43, '002', NULL, 'product.png', 'producto2', NULL, '1.1.1.01.001', NULL, NULL, NULL, NULL, NULL, '0', 'Servicio', NULL, NULL, 17, NULL, 1, 1, '12', '1', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2', '1', '12.00', NULL, '1234.00', '123456.00', '1234567.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00', '0.00', NULL, '12.56', NULL, '2019-12-10 19:38:19', '2019-12-25 21:25:01', NULL, NULL, 1, 3, 1, 2, 1, 4, 17, 1),
(44, '003', NULL, NULL, 'a', NULL, '2.', '1234', NULL, NULL, NULL, NULL, '', 'Producto', NULL, NULL, 10, NULL, 1, 1, '123', '1', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '4', '6', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-12-11', NULL, NULL, '23.00', NULL, '2019-12-11 12:24:16', '2019-12-30 00:12:18', NULL, NULL, 1, 3, 1, 1, 1, 2, 11, 1),
(103, 'aaa', 'bb', 'aa', 's', 'd', '1.1.', 'gg', 'hh', NULL, NULL, NULL, 'jj', 'dfgfg', '12', 12, 12, NULL, 1, 1, 'ffgg', '1', 0, 'ff', 'ff', '2013', 'ff', 'ff', 'ff', 'ff', 'ff', 'ff', 'ff', 'ff', 10, '1', 'ff', '123.00', '123.00', '123.00', '123.00', '123.00', '123.00', '123.00', '123.00', '123.00', '123.00', '123.00', '123.00', NULL, '123.00', '123.00', '123.00', '123.00', '2020-01-03 17:05:21', '2020-01-03 17:05:21', NULL, NULL, 1, 1, 1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_bodega`
--

CREATE TABLE `producto_bodega` (
  `id_producto_bodega` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `id_producto` int(11) NOT NULL,
  `id_bodega` int(11) NOT NULL,
  `id_empresa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `producto_bodega`
--

INSERT INTO `producto_bodega` (`id_producto_bodega`, `cantidad`, `id_producto`, `id_bodega`, `id_empresa`) VALUES
(10, 33, 43, 11, 1),
(11, 61, 43, 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_bodega_trans`
--

CREATE TABLE `producto_bodega_trans` (
  `id_producto_bodega_trans` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cant_env` int(11) DEFAULT NULL,
  `cant_recib` int(11) DEFAULT NULL,
  `id_bodega_transferencia` int(11) DEFAULT NULL,
  `id_empresa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `producto_bodega_trans`
--

INSERT INTO `producto_bodega_trans` (`id_producto_bodega_trans`, `id_producto`, `cant_env`, `cant_recib`, `id_bodega_transferencia`, `id_empresa`) VALUES
(5, 43, 10, 10, 16, 1),
(7, 43, 10, 5, 18, 1),
(8, 44, 10, 5, 18, 1),
(9, 42, 10, 10, 19, 1),
(10, 44, 10, 10, 19, 1),
(11, 43, 5, 3, 20, 1),
(12, 43, 1, NULL, 21, 1),
(13, 44, 1, NULL, 21, 1),
(14, 43, 20, NULL, 22, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_factura`
--

CREATE TABLE `producto_factura` (
  `id_prodfact` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` decimal(11,2) DEFAULT NULL,
  `descuento` decimal(11,2) DEFAULT NULL,
  `subtotal` decimal(11,2) DEFAULT NULL,
  `fcrea` datetime DEFAULT current_timestamp(),
  `fmodifica` datetime DEFAULT NULL,
  `id_factcompra` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `iva` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `producto_factura`
--

INSERT INTO `producto_factura` (`id_prodfact`, `nombre`, `cantidad`, `precio`, `descuento`, `subtotal`, `fcrea`, `fmodifica`, `id_factcompra`, `id_producto`, `iva`) VALUES
(11, 's', 1, '20.00', NULL, NULL, '2020-01-03 20:29:39', '2020-01-03 20:29:39', 10, 103, 1),
(12, 'producto1', 1, '20.00', NULL, NULL, '2020-01-06 11:01:48', '2020-01-06 11:01:48', 13, 42, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `id_proveedor` int(11) NOT NULL,
  `cod_proveedor` varchar(20) DEFAULT NULL,
  `grupo` varchar(50) DEFAULT NULL,
  `nombre_proveedor` varchar(200) DEFAULT NULL,
  `tipo_identificacion` varchar(80) DEFAULT NULL,
  `identif_proveedor` varchar(20) DEFAULT NULL,
  `contribuyente` varchar(20) DEFAULT NULL,
  `beneficiario` varchar(150) DEFAULT NULL,
  `contacto` varchar(150) DEFAULT NULL,
  `direccion_prov` varchar(200) DEFAULT NULL,
  `nrcasa` varchar(20) DEFAULT NULL,
  `telefono_prov` int(15) DEFAULT NULL,
  `estado_prov` varchar(20) DEFAULT NULL,
  `tipo_cuenta` varchar(50) DEFAULT NULL,
  `cta_banco` int(20) DEFAULT NULL,
  `id` int(20) DEFAULT NULL,
  `pagos` int(11) DEFAULT NULL,
  `plazo` int(11) DEFAULT NULL,
  `dias_pago` int(11) DEFAULT NULL,
  `tip_comprob` varchar(100) DEFAULT NULL,
  `serie` varchar(20) DEFAULT NULL,
  `fvalidez` date DEFAULT NULL,
  `comentario` varchar(200) DEFAULT NULL,
  `rangomax` varchar(20) DEFAULT NULL,
  `rangomin` varchar(20) DEFAULT NULL,
  `nrautorizacion` varchar(45) DEFAULT NULL,
  `contribuye_sri` varchar(20) DEFAULT NULL,
  `tip_electronico` varchar(50) DEFAULT NULL,
  `imp_retencion` varchar(100) DEFAULT NULL,
  `codsri_imp` varchar(100) DEFAULT NULL,
  `retencion_iva` varchar(100) DEFAULT NULL,
  `codsri_iva` varchar(100) DEFAULT NULL,
  `cta_contable` varchar(50) DEFAULT NULL,
  `id_provincia` int(11) DEFAULT NULL,
  `id_ciudad` int(11) DEFAULT NULL,
  `id_banco` int(11) DEFAULT NULL,
  `id_empresa` int(11) DEFAULT NULL,
  `fcrea` date DEFAULT current_timestamp(),
  `fmodifica` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`id_proveedor`, `cod_proveedor`, `grupo`, `nombre_proveedor`, `tipo_identificacion`, `identif_proveedor`, `contribuyente`, `beneficiario`, `contacto`, `direccion_prov`, `nrcasa`, `telefono_prov`, `estado_prov`, `tipo_cuenta`, `cta_banco`, `id`, `pagos`, `plazo`, `dias_pago`, `tip_comprob`, `serie`, `fvalidez`, `comentario`, `rangomax`, `rangomin`, `nrautorizacion`, `contribuye_sri`, `tip_electronico`, `imp_retencion`, `codsri_imp`, `retencion_iva`, `codsri_iva`, `cta_contable`, `id_provincia`, `id_ciudad`, `id_banco`, `id_empresa`, `fcrea`, `fmodifica`) VALUES
(49, 'cris', NULL, 'cris', NULL, '1726290289', NULL, NULL, NULL, 'reer', NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, 61, NULL, 1, '2019-12-26', '2019-12-28'),
(50, 'cris', NULL, 'cris', NULL, '1726290289001', NULL, NULL, NULL, 'AAA', NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5, 36, NULL, 1, '2019-12-26', '2019-12-28'),
(51, 'cris', NULL, 'cris', NULL, '1723986806001', NULL, NULL, NULL, '999', NULL, 999, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 44, NULL, NULL, '2019-12-26', '2019-12-26'),
(52, 'cris', NULL, 'cris', NULL, '1726290289', NULL, NULL, NULL, 'ddas', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2019-12-26', '2019-12-26'),
(53, 'cris', NULL, 'cris', NULL, '1726290289001', NULL, NULL, NULL, 'sss', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2019-12-26', '2019-12-26'),
(54, 'COM00-1', NULL, 'cris', NULL, '1726290289', NULL, NULL, NULL, 'san carlos', NULL, 254785, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2019-12-26', '2019-12-26'),
(55, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-12-26', '2019-12-26'),
(56, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-12-26', '2019-12-26'),
(59, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-12-26', '2019-12-26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `provincia`
--

CREATE TABLE `provincia` (
  `id_provincia` int(11) NOT NULL,
  `codigo` varchar(45) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `fcrea` datetime DEFAULT NULL,
  `fmodifica` datetime DEFAULT NULL,
  `ucrea` int(11) DEFAULT NULL,
  `umodifica` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `provincia`
--

INSERT INTO `provincia` (`id_provincia`, `codigo`, `nombre`, `fcrea`, `fmodifica`, `ucrea`, `umodifica`) VALUES
(1, '01', 'AZUAY', NULL, NULL, NULL, NULL),
(2, '02', 'BOLIVAR', NULL, NULL, NULL, NULL),
(3, '03', 'CAÑAR', NULL, NULL, NULL, NULL),
(4, '04', 'CARCHI', NULL, NULL, NULL, NULL),
(5, '05', 'COTOPAXI', NULL, NULL, NULL, NULL),
(6, '06', 'CHIMBORAZO', NULL, NULL, NULL, NULL),
(7, '07', 'EL ORO', NULL, NULL, NULL, NULL),
(8, '08', 'ESMERALDAS', NULL, NULL, NULL, NULL),
(9, '09', 'GUAYAS', NULL, NULL, NULL, NULL),
(10, '10', 'IMBABURA', NULL, NULL, NULL, NULL),
(11, '11', 'LOJA', NULL, NULL, NULL, NULL),
(12, '12', 'LOS RIOS', NULL, NULL, NULL, NULL),
(13, '13', 'MANABI', NULL, NULL, NULL, NULL),
(14, '14', 'MORONA SANTIAGO', NULL, NULL, NULL, NULL),
(15, '15', 'NAPO', NULL, NULL, NULL, NULL),
(16, '16', 'PASTAZA', NULL, NULL, NULL, NULL),
(17, '17', 'PICHINCHA', NULL, NULL, NULL, NULL),
(18, '18', 'TUNGURAHUA', NULL, NULL, NULL, NULL),
(19, '19', 'ZAMORA CHINCHIPE', NULL, NULL, NULL, NULL),
(20, '20', 'GALAPAGOS', NULL, NULL, NULL, NULL),
(21, '21', 'SUCUMBIOS', NULL, NULL, NULL, NULL),
(22, '22', 'ORELLANA', NULL, NULL, NULL, NULL),
(23, '23', 'SANTO DOMINGO DE LOS TSACHILAS', NULL, NULL, NULL, NULL),
(24, '24', 'SANTA ELENA', NULL, NULL, NULL, NULL),
(25, '90', 'ZONAS NO DELIMITADAS', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `punto_emision`
--

CREATE TABLE `punto_emision` (
  `id_punto_emision` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `codigo` varchar(10) DEFAULT NULL,
  `secuencial_factura` varchar(100) DEFAULT NULL,
  `secuencial_nota_credito` varchar(100) DEFAULT NULL,
  `secuencial_nota_debito` varchar(100) DEFAULT NULL,
  `secuencial_guia_remision` varchar(100) DEFAULT NULL,
  `secuencial_retencion` varchar(100) DEFAULT NULL,
  `secuencial_liquidacion_compra` varchar(200) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT NULL,
  `fcrea` datetime DEFAULT NULL,
  `fmodifica` datetime DEFAULT NULL,
  `umodifica` int(11) DEFAULT NULL,
  `ucrea` int(11) DEFAULT NULL,
  `id_empresa` int(11) NOT NULL,
  `id_establecimiento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `punto_emision`
--

INSERT INTO `punto_emision` (`id_punto_emision`, `nombre`, `codigo`, `secuencial_factura`, `secuencial_nota_credito`, `secuencial_nota_debito`, `secuencial_guia_remision`, `secuencial_retencion`, `secuencial_liquidacion_compra`, `activo`, `fcrea`, `fmodifica`, `umodifica`, `ucrea`, `id_empresa`, `id_establecimiento`) VALUES
(1, 'SOLMOCONTADORES', '001', '000000001', '1', '1', '1', '1', '1', 1, '2019-12-17 18:00:00', '2019-12-23 11:38:05', NULL, NULL, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `retencion`
--

CREATE TABLE `retencion` (
  `id_retencion` int(11) NOT NULL,
  `cod_retencion` varchar(45) DEFAULT NULL,
  `descrip_retencion` varchar(100) DEFAULT NULL,
  `porcen_retencion` varchar(45) DEFAULT NULL,
  `tipo_retencion` varchar(80) DEFAULT NULL,
  `tipoiva_retencion` varchar(45) DEFAULT NULL,
  `cta_contable` varchar(45) DEFAULT NULL,
  `id_moneda` int(11) DEFAULT NULL,
  `id_impuesto` int(11) DEFAULT NULL,
  `id_empresa` int(11) DEFAULT NULL,
  `fcrea` datetime DEFAULT current_timestamp(),
  `fmodifica` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `retencion`
--

INSERT INTO `retencion` (`id_retencion`, `cod_retencion`, `descrip_retencion`, `porcen_retencion`, `tipo_retencion`, `tipoiva_retencion`, `cta_contable`, `id_moneda`, `id_impuesto`, `id_empresa`, `fcrea`, `fmodifica`) VALUES
(1, '1', 'I.R.F. Por  Pagar (1%)', '1.00', 'Retencion Fuente Compras', 'Credito Tributario', '2.1.5.02.343', 1, 13, 1, '2019-12-20 12:25:09', NULL),
(2, '2', 'I.R.F. Por  Pagar (2%)', '2.00', 'Retencion Fuente Compras', 'Credito Tributario', '2.1.5.02.344', 1, 71, 1, '2019-12-20 12:25:09', NULL),
(3, '3', 'I.R.F. Por  Pagar (8%)', '8.00', 'Retencion Fuente Compras', 'Credito Tributario', '2.1.5.02.304', 1, 2, 1, '2019-12-20 12:25:09', NULL),
(4, '4', 'I.V.A. Retenido por Pagar (30%)', '30.00', 'Retencion IVA Compras', 'Credito Tributario', '2.1.5.01.721', 1, 0, 1, '2019-12-20 12:25:09', NULL),
(5, '5', 'I.V.A. Retenido por Pagar (70%)', '70.00', 'Retencion IVA Compras', 'Credito Tributario', '2.1.5.01.723', 1, 126, 1, '2019-12-20 12:25:09', NULL),
(6, '6', 'I.V.A. Retenido por Pagar (100%)', '100.00', 'Retencion IVA Compras', 'Credito Tributario', '2.1.5.01.725', 1, 127, 1, '2019-12-20 12:25:09', NULL),
(7, '7', 'IVA. en Compras', '12.00', 'Retencion IVA Compras', 'Credito Tributario', '1.1.5.01.001', 1, NULL, 1, '2019-12-20 12:25:09', NULL),
(8, '8', 'I.R.F. por Cobrar (1%)', '1.00', 'Retencion Fuente Ventas', 'Credito Tributario', '1.1.5.02.001', 1, NULL, 1, '2019-12-20 12:25:09', NULL),
(9, '9', 'I.R.F. por Cobrar (2%)', '2.00', 'Retencion Fuente Ventas', 'Credito Tributario', '1.1.5.02.001', 1, NULL, 1, '2019-12-20 12:25:09', NULL),
(10, '10', 'I.R.F. por Cobrar (8%)', '8.00', 'Retencion Fuente Ventas', 'Credito Tributario', '1.1.5.02.001', 1, 20, 1, '2019-12-20 12:25:09', NULL),
(11, '11', 'IVA. Retenido por Cobrar (30%)', '30.00', 'Retencion IVA Ventas', 'Credito Tributario', '1.01.05.05', 1, NULL, 1, '2019-12-20 12:25:09', NULL),
(12, '12', 'IVA. Retenido por Cobrar (70%)', '70.00', 'Retencion IVA Ventas', 'Credito Tributario', '1.01.05.06', 1, NULL, 1, '2019-12-20 12:25:09', NULL),
(13, '13', 'I.V.A. Retenido por Cobrar(100%)', '100.00', 'Retencion IVA Ventas', 'Credito Tributario', '1.1.5.01.005', 1, NULL, 1, '2019-12-20 12:25:09', NULL),
(14, '15', 'IVA. en Ventas', '12.00', 'Retencion IVA Ventas', 'Credito Tributario', '2.1.5.03.001', 1, NULL, 1, '2019-12-20 12:25:09', NULL),
(15, '17', 'I.R.F. Por  Pagar (10%) Honorarios', '10.00', 'Retencion Fuente Compras', 'Credito Tributario', '2.1.5.02.303', 1, 122, 1, '2019-12-20 12:25:09', NULL),
(16, '18', 'Transacciones sin retenci¢n fuente', '0.00', 'Retencion Fuente Compras', 'Credito Tributario', '2.01.07.01.12', 1, 60, 1, '2019-12-20 12:25:09', NULL),
(17, '19', 'I.R.F. Por  Pagar (8%) Arriendos', '8.00', 'Retencion Fuente Compras', 'Credito Tributario', '2.1.5.02.320', 1, 2, 1, '2019-12-20 12:25:09', NULL),
(18, '20', 'Otras Retenciones', '0.00', 'Retencion Fuente Compras', 'Credito Tributario', '2.01.07.01.10', 1, NULL, 1, '2019-12-20 12:25:09', NULL),
(20, '99', '99', '8.00', 'Retencion Fuente Compra', NULL, '1.1.1.01.', 1, 2, 1, '2019-12-23 17:26:49', '2019-12-24 10:31:35');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `retencion_factura`
--

CREATE TABLE `retencion_factura` (
  `id_retencion_factura` int(11) NOT NULL,
  `id_factura` int(11) NOT NULL,
  `id_retencion_iva` int(11) DEFAULT NULL,
  `id_retencion_renta` int(11) DEFAULT NULL,
  `porcentajeiva` varchar(50) DEFAULT NULL,
  `cantidadiva` decimal(11,2) DEFAULT NULL,
  `baserenta` decimal(11,2) NOT NULL,
  `porcentajerenta` varchar(50) DEFAULT NULL,
  `cantidadrenta` decimal(11,2) DEFAULT NULL,
  `fcrea` datetime DEFAULT NULL,
  `fmodifica` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `retencion_factura`
--

INSERT INTO `retencion_factura` (`id_retencion_factura`, `id_factura`, `id_retencion_iva`, `id_retencion_renta`, `porcentajeiva`, `cantidadiva`, `baserenta`, `porcentajerenta`, `cantidadrenta`, `fcrea`, `fmodifica`) VALUES
(1, 112, 13, 10, '100.00%', '80.64', '672.00', '8.00%', '53.76', '2020-01-07 10:36:45', '2020-01-07 10:36:45'),
(2, 113, 13, 10, '100.00%', '80.64', '672.00', '8.00%', '53.76', '2020-01-07 10:36:54', '2020-01-07 10:36:54'),
(3, 114, 13, 10, '100.00%', '80.64', '672.00', '8.00%', '53.76', '2020-01-07 10:39:27', '2020-01-07 10:39:27'),
(4, 115, 13, 10, '100.00%', '80.64', '672.00', '8.00%', '53.76', '2020-01-07 10:41:00', '2020-01-07 10:41:00'),
(5, 116, 13, 10, '100.00%', '80.64', '500.00', '8.00%', '40.00', '2020-01-07 10:50:00', '2020-01-07 10:50:00'),
(6, 117, 11, 10, '30.00%', '0.04', '1.00', '8.00%', '0.08', '2020-01-07 11:13:54', '2020-01-07 11:13:54'),
(7, 118, 12, 10, '70.00%', '56.45', '500.00', '8.00%', '40.00', '2020-01-07 11:18:08', '2020-01-07 11:18:08'),
(8, 118, 11, 10, '30.00%', '24.19', '172.00', '8.00%', '13.76', '2020-01-07 11:18:08', '2020-01-07 11:18:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `retencion_factura_comp`
--

CREATE TABLE `retencion_factura_comp` (
  `id_retfactcompra` int(11) NOT NULL,
  `id_factura` int(11) DEFAULT NULL,
  `id_retencion_iva` int(11) DEFAULT NULL,
  `id_retencion_renta` int(11) DEFAULT NULL,
  `porcentajeiva` varchar(50) DEFAULT NULL,
  `cantidadiva` decimal(11,2) DEFAULT NULL,
  `baserenta` decimal(11,2) DEFAULT NULL,
  `porcentajerenta` varchar(50) DEFAULT NULL,
  `cantidadrenta` decimal(11,2) DEFAULT NULL,
  `fcrea` datetime DEFAULT NULL,
  `fmodifica` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `retencion_factura_comp`
--

INSERT INTO `retencion_factura_comp` (`id_retfactcompra`, `id_factura`, `id_retencion_iva`, `id_retencion_renta`, `porcentajeiva`, `cantidadiva`, `baserenta`, `porcentajerenta`, `cantidadrenta`, `fcrea`, `fmodifica`) VALUES
(13, 14, 13, 10, '100.00%', '79.44', '604.00', '8.00%', '48.32', '2020-01-08 00:29:18', '2020-01-08 00:29:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  `fcrea` datetime DEFAULT NULL,
  `fmodifica` datetime DEFAULT NULL,
  `ucrea` int(11) DEFAULT NULL,
  `umodifica` int(11) DEFAULT NULL,
  `id_empresa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `nombre`, `descripcion`, `fcrea`, `fmodifica`, `ucrea`, `umodifica`, `id_empresa`) VALUES
(1, 'Administrador', 'Administrador', '2019-11-05 14:43:00', '2019-11-05 14:43:00', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_cliente`
--

CREATE TABLE `tipo_cliente` (
  `id_tipo_cliente` int(11) NOT NULL,
  `descripcion_tipo_cliente` varchar(100) DEFAULT NULL,
  `fcrea` datetime DEFAULT NULL,
  `fmodifica` datetime DEFAULT NULL,
  `ucrea` int(11) DEFAULT NULL,
  `umodifica` int(11) DEFAULT NULL,
  `id_empresa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tipo_cliente`
--

INSERT INTO `tipo_cliente` (`id_tipo_cliente`, `descripcion_tipo_cliente`, `fcrea`, `fmodifica`, `ucrea`, `umodifica`, `id_empresa`) VALUES
(7, 'morosos', '2019-12-18 16:04:16', '2019-12-18 16:12:19', NULL, NULL, 1),
(19, 'Puntuales', '2019-12-18 16:16:05', '2019-12-18 16:16:05', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_comprobante`
--

CREATE TABLE `tipo_comprobante` (
  `id_tipcomprobante` int(11) NOT NULL,
  `cod_tipcomprob` varchar(20) DEFAULT NULL,
  `descrip_tipcomprob` varchar(200) DEFAULT NULL,
  `venta_tipcomprob` varchar(50) DEFAULT NULL,
  `fmodifica` datetime DEFAULT NULL,
  `fcrea` datetime DEFAULT NULL,
  `id_empresa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tipo_comprobante`
--

INSERT INTO `tipo_comprobante` (`id_tipcomprobante`, `cod_tipcomprob`, `descrip_tipcomprob`, `venta_tipcomprob`, `fmodifica`, `fcrea`, `id_empresa`) VALUES
(2, '01', 'Facturas', NULL, '2019-12-23 16:14:35', NULL, 1),
(3, '02', 'Nota o boleta de venta', NULL, '2019-12-18 15:51:44', NULL, 1),
(4, '03', 'Liquidación de compra de Bienes o Prestación de servicios', NULL, '2019-12-18 12:23:35', '2019-12-18 12:23:35', 1),
(5, '04', 'Nota de crédito', NULL, '2019-12-18 15:51:44', NULL, 1),
(6, '05', 'Nota de débito', NULL, '2019-12-18 15:51:44', NULL, 1),
(7, '07', 'Comprobante de Retención', NULL, '2019-12-18 15:51:44', NULL, 1),
(8, '08', 'Boletos o entradas a espectáculos públicos', NULL, '2019-12-18 15:51:44', NULL, 1),
(9, '09', 'Tiquetes o vales emitidos por máquinas registradoras', NULL, '2019-12-18 15:51:44', NULL, 1),
(10, '11', 'Pasajes expedidos por empresas de aviación', NULL, '2019-12-18 15:51:44', NULL, 1),
(11, '12', 'Documentos emitidos por instituciones financieras', NULL, '2019-12-18 15:51:44', NULL, 1),
(12, '15', 'Comprobante de venta emitido en el Exterior', NULL, '2019-12-18 15:51:44', NULL, 1),
(13, '16', 'Formulario Único de Exportación (FUE) o Declaración Aduanera Única (DAU) o Declaración Andina de Valor (DAV)', NULL, '2019-12-18 15:51:44', NULL, 1),
(14, '18', 'Documentos autorizados utilizados en ventas excepto N/C N/D ', NULL, '2019-12-18 15:51:44', NULL, 1),
(15, '19', 'Comprobantes de Pago de Cuotas o Aportes', NULL, '2019-12-18 15:51:44', NULL, 1),
(16, '20', 'Documentos por Servicios Administrativos emitidos por Inst. del Estado', NULL, '2019-12-18 15:51:44', NULL, 1),
(17, '21', 'Carta de Porte Aéreo', NULL, '2019-12-18 15:51:44', NULL, 1),
(18, '22', 'RECAP', NULL, '2019-12-18 15:51:44', NULL, 1),
(19, '23', 'Nota de Crédito TC', NULL, '2019-12-18 15:51:44', NULL, 1),
(20, '24', 'Nota de Débito TC', NULL, '2019-12-18 15:51:44', NULL, 1),
(21, '41', 'Comprobante de venta emitido por reembolso', NULL, '2019-12-18 15:51:44', NULL, 1),
(22, '42', 'Documento agente de retención Combustibles', NULL, '2019-12-18 15:51:44', NULL, 1),
(23, '43', 'Liquidacion para Explotacion y Exploracion de Hidrocarburos', NULL, '2019-12-18 15:51:44', NULL, 1),
(24, '44', 'Comprobante de Contribuciones y Aportes', NULL, '2019-12-18 15:51:44', NULL, 1),
(25, '45', 'Liquidación por reclamos de aseguradoras', NULL, '2019-12-18 15:51:44', NULL, 1),
(26, '47', 'Nota de Crédito por Reembolso Emitida por Intermediario', NULL, '2019-12-18 15:51:44', NULL, 1),
(27, '48', 'Nota de Débito por Reembolso Emitida por Intermediario', NULL, '2019-12-18 15:51:44', NULL, 1),
(28, '49', 'Proveedor Directo de Exportador Bajo Régimen Especial', NULL, '2019-12-18 15:51:44', NULL, 1),
(29, '50', 'A Inst. Estado y Empr. Públicas que percibe ingreso exento de Imp. Renta', NULL, '2019-12-18 15:51:44', NULL, 1),
(30, '51', 'N/C A Inst. Estado y Empr. Públicas que percibe ingreso exento de Imp. Renta', NULL, '2019-12-18 15:51:44', NULL, 1),
(31, '52', 'N/D A Inst. Estado y Empr. Públicas que percibe ingreso exento de Imp. Renta', NULL, '2019-12-18 15:51:44', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_medida`
--

CREATE TABLE `tipo_medida` (
  `id_tipo_medida` int(11) NOT NULL,
  `nombre` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tipo_medida`
--

INSERT INTO `tipo_medida` (`id_tipo_medida`, `nombre`) VALUES
(1, 'Talla'),
(2, 'Masa'),
(3, 'Longitud'),
(4, 'Volumen'),
(5, 'Superficie'),
(6, 'Otro');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_producto`
--

CREATE TABLE `tipo_producto` (
  `id_tipo_producto` int(11) NOT NULL,
  `codigo` varchar(45) DEFAULT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `utilidad` varchar(45) DEFAULT NULL,
  `fcrea` datetime DEFAULT NULL,
  `fmodifica` datetime DEFAULT NULL,
  `ucrea` int(11) DEFAULT NULL,
  `umodifica` int(11) DEFAULT NULL,
  `id_linea_producto` int(11) NOT NULL,
  `id_empresa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipo_producto`
--

INSERT INTO `tipo_producto` (`id_tipo_producto`, `codigo`, `nombre`, `utilidad`, `fcrea`, `fmodifica`, `ucrea`, `umodifica`, `id_linea_producto`, `id_empresa`) VALUES
(1, '123', 'tipo1', 'asyfy', NULL, NULL, NULL, NULL, 3, 1),
(2, '12354', 'tipo2', 'sasd', NULL, NULL, NULL, NULL, 3, 1),
(3, '12455', 'otro2', 'dasdas', '2019-11-19 18:06:43', '2019-12-18 14:20:22', NULL, NULL, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_sustento`
--

CREATE TABLE `tipo_sustento` (
  `id_sustento` int(11) NOT NULL,
  `cod_sustento` varchar(12) DEFAULT NULL,
  `descrip_sustento` varchar(255) DEFAULT NULL,
  `fcrea` datetime DEFAULT current_timestamp(),
  `fmodifica` datetime DEFAULT NULL,
  `id_empresa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tipo_sustento`
--

INSERT INTO `tipo_sustento` (`id_sustento`, `cod_sustento`, `descrip_sustento`, `fcrea`, `fmodifica`, `id_empresa`) VALUES
(1, '00', ' No aplica', '2019-12-20 14:38:47', NULL, 1),
(2, '01', 'Crédito Tributario para declaración de IVA (servicios y bienes distintos de inventarios y activos fijos)', '2019-12-20 14:38:47', NULL, 1),
(3, '02', 'Costo o Gasto para declaración de IR (servicios y bienes distintos de inventarios y activos fijos)', '2019-12-20 14:38:47', NULL, 1),
(4, '03', ' Activo Fijo - Crédito Tributario para declaración de IVA', '2019-12-20 14:38:47', NULL, 1),
(5, '04', ' Activo Fijo - Costo o Gasto para declaración de IR', '2019-12-20 14:38:47', NULL, 1),
(6, '05', 'Liquidación Gastos de Viaje, hospedaje y alimentación Gastos IR (a nombre de empleados y no de la empresa)', '2019-12-20 14:38:47', NULL, 1),
(7, '06', ' Inventario - Crédito Tributario para declaración de IVA', '2019-12-20 14:38:47', NULL, 1),
(8, '07', ' Inventario - Costo o Gasto para declaración de IR', '2019-12-20 14:38:47', NULL, 1),
(9, '08', ' Valor pagado para solicitar Reembolso de Gasto (intermediario)', '2019-12-20 14:38:47', NULL, 1),
(10, '09', 'Reembolso por SiniestrossS', '2019-12-20 14:38:47', '2019-12-23 18:22:12', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubicacion_fisica`
--

CREATE TABLE `ubicacion_fisica` (
  `id_ubicacion_fisica` int(11) NOT NULL,
  `nombre` varchar(300) DEFAULT NULL,
  `descripcion` varchar(300) DEFAULT NULL,
  `id_bodega` int(11) NOT NULL,
  `fcrea` datetime DEFAULT NULL,
  `fmodifica` datetime DEFAULT NULL,
  `ucrea` int(11) DEFAULT NULL,
  `umodifica` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `ubicacion_fisica`
--

INSERT INTO `ubicacion_fisica` (`id_ubicacion_fisica`, `nombre`, `descripcion`, `id_bodega`, `fcrea`, `fmodifica`, `ucrea`, `umodifica`) VALUES
(1, 'ubicacion1', '1234', 1, NULL, NULL, NULL, NULL),
(2, 'ubicacion2', '1234', 1, NULL, NULL, NULL, NULL),
(3, 'ubicacion3', '1234', 2, NULL, NULL, NULL, NULL),
(4, 'ubicacion4', '123', 2, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidad_medida`
--

CREATE TABLE `unidad_medida` (
  `id_unidad_medida` int(11) NOT NULL,
  `nombre` varchar(300) DEFAULT NULL,
  `id_tipo_medida` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `unidad_medida`
--

INSERT INTO `unidad_medida` (`id_unidad_medida`, `nombre`, `id_tipo_medida`) VALUES
(1, 'Metros', 3),
(2, 'Centímetros', 3),
(3, 'Milímetros', 3),
(4, 'Pies', 3),
(5, 'Pulgadas', 3),
(6, 'Onzas', 2),
(7, 'Libras', 2),
(8, 'Stone', 2),
(9, 'Toneladas cortas', 2),
(10, 'Toneladas largas', 2),
(11, 'Microgramos', 2),
(12, 'Miligramos', 2),
(13, 'Gramos', 2),
(14, 'Kilogramos', 2),
(15, 'Toneladas', 2),
(16, 'Mililitro', 4),
(17, 'Litro', 4),
(18, 'Arrobas', 2),
(19, 'Quintales', 2),
(20, 'Centímetros Cúbicos', 4),
(21, 'Metros Cubicos', 4),
(22, 'Milímetros Cubicos', 4),
(23, 'Galónes', 4),
(24, 'Pulgadas Cuadradas', 5),
(25, 'Pies Cuadrados', 5),
(26, 'Metros Cuadrados', 5),
(27, 'Centímetros Cuadrados', 5),
(28, 'Milímetros Cuadrados', 5),
(29, 'Board Foot', 4),
(30, 'Unidad', 6),
(31, 'Paquete', 6),
(32, 'Par', 6),
(33, 'Unica', 1),
(34, '2 USA', 1),
(35, '4 USA ', 1),
(36, '6 USA', 1),
(37, '8 USA', 1),
(38, '10 USA', 1),
(39, '12 USA', 1),
(40, '14 USA', 1),
(41, '16 USA', 1),
(42, '18 USA', 1),
(43, '20 USA', 1),
(44, '32 / XS', 1),
(45, '34 / XS/S', 1),
(46, '36 / S', 1),
(47, '38 / M', 1),
(48, '40 / M', 1),
(49, '42 / L', 1),
(50, '44 / L/XL', 1),
(51, '46 / XL/1X', 1),
(52, '50 / 1X/2X ', 1),
(53, '52 / 2X', 1),
(54, '6', 1),
(55, '7', 1),
(56, '8', 1),
(57, '9', 1),
(58, '10', 1),
(59, '11', 1),
(60, '12', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nombres` varchar(300) NOT NULL,
  `apellidos` varchar(300) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `fcrea` datetime DEFAULT NULL,
  `fmodifica` datetime DEFAULT NULL,
  `ucrea` int(11) DEFAULT NULL,
  `umodifica` int(11) DEFAULT NULL,
  `id_rol` int(11) NOT NULL,
  `id_empresa` int(11) NOT NULL,
  `id_establecimiento` int(11) NOT NULL,
  `id_punto_emision` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id`, `nombres`, `apellidos`, `password`, `email`, `estado`, `fcrea`, `fmodifica`, `ucrea`, `umodifica`, `id_rol`, `id_empresa`, `id_establecimiento`, `id_punto_emision`) VALUES
(6, 'wilian', 'ponce', '$2y$10$Y2g6wrQgeAFJER9Bi7eVauY7K5Ge6OOk7vBqEOx9.kZQw2Fc5Q3aO', 'wily2809@hotmail.com', 1, '2019-11-05 15:43:55', '2019-11-05 15:43:55', NULL, NULL, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vendedor`
--

CREATE TABLE `vendedor` (
  `id_vendedor` int(11) NOT NULL,
  `codigo_vendedor` varchar(45) DEFAULT NULL,
  `nombre_vendedor` varchar(100) DEFAULT NULL,
  `email_vendedor` varchar(100) DEFAULT NULL,
  `fcrea` datetime DEFAULT NULL,
  `fmodifica` datetime DEFAULT NULL,
  `ucrea` int(11) DEFAULT NULL,
  `umodifica` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `vendedor`
--

INSERT INTO `vendedor` (`id_vendedor`, `codigo_vendedor`, `nombre_vendedor`, `email_vendedor`, `fcrea`, `fmodifica`, `ucrea`, `umodifica`) VALUES
(4, 'v1', 'Andres Gómez', 'andres.@gmail.com', '2019-12-19 13:54:18', '2019-12-19 15:41:09', NULL, NULL),
(5, 'v2', 'darwin 23', 'darwin@gmail.com', '2019-12-19 15:04:24', '2019-12-19 15:41:47', NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `area_trabajo`
--
ALTER TABLE `area_trabajo`
  ADD PRIMARY KEY (`id_area`);

--
-- Indices de la tabla `asientos`
--
ALTER TABLE `asientos`
  ADD PRIMARY KEY (`id_asientos`);

--
-- Indices de la tabla `asientos_detalle`
--
ALTER TABLE `asientos_detalle`
  ADD PRIMARY KEY (`ida_detalle`),
  ADD KEY `id_ctas` (`id_ctas`);

--
-- Indices de la tabla `banco`
--
ALTER TABLE `banco`
  ADD PRIMARY KEY (`id_banco`);

--
-- Indices de la tabla `bodega`
--
ALTER TABLE `bodega`
  ADD PRIMARY KEY (`id_bodega`),
  ADD KEY `fk_bodega_empresa_idx` (`id_empresa`);

--
-- Indices de la tabla `bodega_egreso`
--
ALTER TABLE `bodega_egreso`
  ADD PRIMARY KEY (`id_bodega_egreso`),
  ADD KEY `fk_bodega_egreso_id_producto_bodega_idx` (`id_producto_bodega`),
  ADD KEY `fk_bodega_egreso_empresa_idx` (`id_empresa`);

--
-- Indices de la tabla `bodega_ingreso`
--
ALTER TABLE `bodega_ingreso`
  ADD PRIMARY KEY (`id_bodega_ingreso`),
  ADD KEY `fk_ingreso_bodega_id_producto_bodega_idx` (`id_producto_bodega`),
  ADD KEY `fk_bodega_ingreso_empresa_idx` (`id_empresa`);

--
-- Indices de la tabla `bodega_transferencia`
--
ALTER TABLE `bodega_transferencia`
  ADD PRIMARY KEY (`id_bodega_transferencia`),
  ADD KEY `fk_bodega_transferencia_empresa_idx` (`id_empresa`);

--
-- Indices de la tabla `caja`
--
ALTER TABLE `caja`
  ADD PRIMARY KEY (`id_caja`),
  ADD KEY `id_moneda` (`id_moneda`),
  ADD KEY `id_empresa` (`id_empresa`);

--
-- Indices de la tabla `calendario_empleado`
--
ALTER TABLE `calendario_empleado`
  ADD PRIMARY KEY (`id_calen_emp`),
  ADD KEY `idemplead` (`idemplead`);

--
-- Indices de la tabla `campo_adicional`
--
ALTER TABLE `campo_adicional`
  ADD PRIMARY KEY (`id_campo_adicional`);

--
-- Indices de la tabla `cargas_empleado`
--
ALTER TABLE `cargas_empleado`
  ADD PRIMARY KEY (`id_carga`),
  ADD KEY `idempleado` (`idempleado`);

--
-- Indices de la tabla `cargo`
--
ALTER TABLE `cargo`
  ADD PRIMARY KEY (`id_cargo`),
  ADD KEY `idarea` (`idarea`);

--
-- Indices de la tabla `ciudad`
--
ALTER TABLE `ciudad`
  ADD PRIMARY KEY (`id_ciudad`,`id_provincia`),
  ADD KEY `fk_ciudad_provincia1` (`id_provincia`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id_cliente`),
  ADD KEY `fk_cliente_grupocliente1_idx` (`id_grupo_cliente`),
  ADD KEY `fk_cliente_empresa1_idx` (`id_empresa`),
  ADD KEY `id_tipo_cliente` (`id_tipo_cliente`),
  ADD KEY `id_vendedor` (`id_vendedor`);

--
-- Indices de la tabla `codigo_pais`
--
ALTER TABLE `codigo_pais`
  ADD PRIMARY KEY (`id_codigo_pais`);

--
-- Indices de la tabla `credito_compras`
--
ALTER TABLE `credito_compras`
  ADD PRIMARY KEY (`id_credcompra`),
  ADD KEY `id_factcompra` (`id_factcompra`);

--
-- Indices de la tabla `ctas_cobrar`
--
ALTER TABLE `ctas_cobrar`
  ADD PRIMARY KEY (`id_ctascobrar`);

--
-- Indices de la tabla `ctas_pagar`
--
ALTER TABLE `ctas_pagar`
  ADD PRIMARY KEY (`id_ctaspagar`);

--
-- Indices de la tabla `departamento`
--
ALTER TABLE `departamento`
  ADD PRIMARY KEY (`id_departamento`);

--
-- Indices de la tabla `detalle`
--
ALTER TABLE `detalle`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `fk_detalle_producto1_idx` (`id_producto`),
  ADD KEY `fk_detalle_factura_idx` (`id_factura`);

--
-- Indices de la tabla `detalle_factura_compra`
--
ALTER TABLE `detalle_factura_compra`
  ADD PRIMARY KEY (`id_detalle_factura_compra`);

--
-- Indices de la tabla `documentacion`
--
ALTER TABLE `documentacion`
  ADD PRIMARY KEY (`id_documento`);

--
-- Indices de la tabla `docu_empleado`
--
ALTER TABLE `docu_empleado`
  ADD PRIMARY KEY (`id_docu_emp`),
  ADD KEY `iddocu` (`iddocu`),
  ADD KEY `idemple` (`idemple`);

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`id_empleado`),
  ADD KEY `fk_empleado_id_empresa_idx` (`id_empresa`);

--
-- Indices de la tabla `empleado_cargo`
--
ALTER TABLE `empleado_cargo`
  ADD PRIMARY KEY (`id_emp_cargo`),
  ADD KEY `id_empleado` (`id_empleado`),
  ADD KEY `idcargo` (`id_cargo`),
  ADD KEY `idgrupo` (`id_grupo`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`id_empresa`),
  ADD KEY `id_moneda` (`id_moneda`),
  ADD KEY `fk_empresa_user1` (`id_user`);

--
-- Indices de la tabla `establecimiento`
--
ALTER TABLE `establecimiento`
  ADD PRIMARY KEY (`id_establecimiento`),
  ADD KEY `fk_establecimiento_id_empresa_idx` (`id_empresa`);

--
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`id_factura`),
  ADD KEY `fk_factura_cliente1_idx` (`id_cliente`),
  ADD KEY `fk_factura_id_user_idx` (`id_user`),
  ADD KEY `fk_factura_id_punto_emision_idx` (`id_punto_emision`),
  ADD KEY `fk_factura_id_empresa_idx` (`id_empresa`),
  ADD KEY `fk_factura_id_establecimiento_idx` (`id_establecimiento`);

--
-- Indices de la tabla `factura_compra`
--
ALTER TABLE `factura_compra`
  ADD PRIMARY KEY (`id_factcompra`),
  ADD KEY `id_cliente_asoc` (`id_cliente_asoc`),
  ADD KEY `id_importacion` (`id_importacion`),
  ADD KEY `id_proveedor` (`id_proveedor`),
  ADD KEY `id_sustento` (`id_sustento`);

--
-- Indices de la tabla `grupo`
--
ALTER TABLE `grupo`
  ADD PRIMARY KEY (`id_grupo`);

--
-- Indices de la tabla `grupo_cliente`
--
ALTER TABLE `grupo_cliente`
  ADD PRIMARY KEY (`id_grupo_cliente`),
  ADD KEY `fk_grupo_cliente_empresa_idx` (`id_empresa`);

--
-- Indices de la tabla `grupo_ocupacional`
--
ALTER TABLE `grupo_ocupacional`
  ADD PRIMARY KEY (`id_grupo`);

--
-- Indices de la tabla `grupo_proveedor`
--
ALTER TABLE `grupo_proveedor`
  ADD PRIMARY KEY (`id_grupoprov`),
  ADD KEY `fk_grupo_proveedor_empresa_idx` (`id_empresa`);

--
-- Indices de la tabla `ice`
--
ALTER TABLE `ice`
  ADD PRIMARY KEY (`id_ice`);

--
-- Indices de la tabla `importacion`
--
ALTER TABLE `importacion`
  ADD PRIMARY KEY (`id_importacion`);

--
-- Indices de la tabla `impuesto`
--
ALTER TABLE `impuesto`
  ADD PRIMARY KEY (`id_imp`),
  ADD KEY `fk_impuesto_empresa_idx` (`id_empresa`);

--
-- Indices de la tabla `impuesto_retencion`
--
ALTER TABLE `impuesto_retencion`
  ADD PRIMARY KEY (`id_impret`),
  ADD KEY `id_impuesto` (`id_impuesto`),
  ADD KEY `id_retencion` (`id_retencion`);

--
-- Indices de la tabla `iva`
--
ALTER TABLE `iva`
  ADD PRIMARY KEY (`id_iva`);

--
-- Indices de la tabla `linea_producto`
--
ALTER TABLE `linea_producto`
  ADD PRIMARY KEY (`id_linea_producto`),
  ADD KEY `fk_linea producto_empresa_idx` (`id_empresa`);

--
-- Indices de la tabla `marca`
--
ALTER TABLE `marca`
  ADD PRIMARY KEY (`id_marca`),
  ADD KEY `fk_marca_empresa_idx` (`id_empresa`);

--
-- Indices de la tabla `modelo`
--
ALTER TABLE `modelo`
  ADD PRIMARY KEY (`id_modelo`),
  ADD KEY `fk_modelo_empresa_idx` (`id_empresa`);

--
-- Indices de la tabla `modulos`
--
ALTER TABLE `modulos`
  ADD PRIMARY KEY (`id_modulos`);

--
-- Indices de la tabla `moneda`
--
ALTER TABLE `moneda`
  ADD PRIMARY KEY (`id_moneda`);

--
-- Indices de la tabla `nacionalidad`
--
ALTER TABLE `nacionalidad`
  ADD PRIMARY KEY (`id_nacionalidad`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id_pagos`),
  ADD KEY `fk_pagos_empresa_idx` (`id_empresa`);

--
-- Indices de la tabla `pago_fact_compra`
--
ALTER TABLE `pago_fact_compra`
  ADD PRIMARY KEY (`id_pagfactcompra`);

--
-- Indices de la tabla `parroquia`
--
ALTER TABLE `parroquia`
  ADD PRIMARY KEY (`id_parroquia`);

--
-- Indices de la tabla `plan_cuentas`
--
ALTER TABLE `plan_cuentas`
  ADD PRIMARY KEY (`id_ctas`),
  ADD KEY `codmon` (`id_moneda`),
  ADD KEY `grumov` (`id_grupo`),
  ADD KEY `codemp` (`id_empresa`);

--
-- Indices de la tabla `presentacion`
--
ALTER TABLE `presentacion`
  ADD PRIMARY KEY (`id_presentacion`),
  ADD KEY `fk_presentacion_empresa_idx` (`id_empresa`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `fk_producto_marca1` (`id_marca`),
  ADD KEY `fk_producto_modelo1_idx` (`id_modelo`),
  ADD KEY `fk_producto_presentacion1_idx` (`id_presentacion`),
  ADD KEY `fk_producto_linea_producto1_idx` (`id_linea_producto`),
  ADD KEY `fk_producto_tipo_producto1_idx` (`id_tipo_producto`),
  ADD KEY `fk_producto_tipo_medida1_idx` (`id_tipo_medida`),
  ADD KEY `fk_producto_unidad_medida1_idx` (`id_unidad_medida`),
  ADD KEY `fk_producto_empresa_idx` (`id_empresa`);

--
-- Indices de la tabla `producto_bodega`
--
ALTER TABLE `producto_bodega`
  ADD PRIMARY KEY (`id_producto_bodega`,`id_producto`,`id_bodega`),
  ADD KEY `fk_producto_has_bodega_bodega1_idx` (`id_bodega`),
  ADD KEY `fk_producto_has_bodega_producto1_idx` (`id_producto`),
  ADD KEY `fk_producto_bodega_empresa_idx` (`id_empresa`);

--
-- Indices de la tabla `producto_bodega_trans`
--
ALTER TABLE `producto_bodega_trans`
  ADD PRIMARY KEY (`id_producto_bodega_trans`,`id_producto`),
  ADD KEY `fk_producto_bodega_trans_has_producto_idx` (`id_producto`),
  ADD KEY `fk_id_bodega_transferencia_idx` (`id_bodega_transferencia`),
  ADD KEY `fk_producto_bodega_trans_empresa_idx` (`id_empresa`);

--
-- Indices de la tabla `producto_factura`
--
ALTER TABLE `producto_factura`
  ADD PRIMARY KEY (`id_prodfact`),
  ADD KEY `fk_producto_factura_factura_compra_idx` (`id_factcompra`),
  ADD KEY `fk_producto_factura_producto_idx` (`id_producto`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`id_proveedor`),
  ADD KEY `fk_proveedor_id_empresa_idx` (`id_empresa`);

--
-- Indices de la tabla `provincia`
--
ALTER TABLE `provincia`
  ADD PRIMARY KEY (`id_provincia`);

--
-- Indices de la tabla `punto_emision`
--
ALTER TABLE `punto_emision`
  ADD PRIMARY KEY (`id_punto_emision`),
  ADD KEY `id_empresa` (`id_empresa`),
  ADD KEY `id_establecimientos` (`id_establecimiento`);

--
-- Indices de la tabla `retencion`
--
ALTER TABLE `retencion`
  ADD PRIMARY KEY (`id_retencion`),
  ADD KEY `fk_retencion_empresa_idx` (`id_empresa`);

--
-- Indices de la tabla `retencion_factura`
--
ALTER TABLE `retencion_factura`
  ADD PRIMARY KEY (`id_retencion_factura`);

--
-- Indices de la tabla `retencion_factura_comp`
--
ALTER TABLE `retencion_factura_comp`
  ADD PRIMARY KEY (`id_retfactcompra`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`),
  ADD KEY `fk_rol_empresa_idx` (`id_empresa`);

--
-- Indices de la tabla `tipo_cliente`
--
ALTER TABLE `tipo_cliente`
  ADD PRIMARY KEY (`id_tipo_cliente`),
  ADD KEY `fk_tipo_cliente_empresa_idx` (`id_empresa`);

--
-- Indices de la tabla `tipo_comprobante`
--
ALTER TABLE `tipo_comprobante`
  ADD PRIMARY KEY (`id_tipcomprobante`),
  ADD KEY `fk_empresa_tipo_comprobante_idx` (`id_empresa`);

--
-- Indices de la tabla `tipo_medida`
--
ALTER TABLE `tipo_medida`
  ADD PRIMARY KEY (`id_tipo_medida`);

--
-- Indices de la tabla `tipo_producto`
--
ALTER TABLE `tipo_producto`
  ADD PRIMARY KEY (`id_tipo_producto`),
  ADD KEY `fk_tipo_producto_linea_producto1_idx` (`id_linea_producto`),
  ADD KEY `fk_tipo_producto_empresa_idx` (`id_empresa`);

--
-- Indices de la tabla `tipo_sustento`
--
ALTER TABLE `tipo_sustento`
  ADD PRIMARY KEY (`id_sustento`),
  ADD KEY `fk_tipo_sustento_empresa_idx` (`id_empresa`);

--
-- Indices de la tabla `ubicacion_fisica`
--
ALTER TABLE `ubicacion_fisica`
  ADD PRIMARY KEY (`id_ubicacion_fisica`),
  ADD KEY `fk_ubicacion_fisica_bodega1_idx` (`id_bodega`);

--
-- Indices de la tabla `unidad_medida`
--
ALTER TABLE `unidad_medida`
  ADD PRIMARY KEY (`id_unidad_medida`),
  ADD KEY `fk_unidad_medida_tipo_medida1_idx` (`id_tipo_medida`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_rol1_idx` (`id_rol`),
  ADD KEY `fk_user_empresa_idx` (`id_empresa`),
  ADD KEY `fk_user_id_establecimiento_idx` (`id_establecimiento`),
  ADD KEY `fk_user_id_punto_emision_idx` (`id_punto_emision`);

--
-- Indices de la tabla `vendedor`
--
ALTER TABLE `vendedor`
  ADD PRIMARY KEY (`id_vendedor`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `area_trabajo`
--
ALTER TABLE `area_trabajo`
  MODIFY `id_area` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `asientos`
--
ALTER TABLE `asientos`
  MODIFY `id_asientos` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `asientos_detalle`
--
ALTER TABLE `asientos_detalle`
  MODIFY `ida_detalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `banco`
--
ALTER TABLE `banco`
  MODIFY `id_banco` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `bodega`
--
ALTER TABLE `bodega`
  MODIFY `id_bodega` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `bodega_egreso`
--
ALTER TABLE `bodega_egreso`
  MODIFY `id_bodega_egreso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `bodega_ingreso`
--
ALTER TABLE `bodega_ingreso`
  MODIFY `id_bodega_ingreso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `bodega_transferencia`
--
ALTER TABLE `bodega_transferencia`
  MODIFY `id_bodega_transferencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `caja`
--
ALTER TABLE `caja`
  MODIFY `id_caja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `campo_adicional`
--
ALTER TABLE `campo_adicional`
  MODIFY `id_campo_adicional` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cargas_empleado`
--
ALTER TABLE `cargas_empleado`
  MODIFY `id_carga` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cargo`
--
ALTER TABLE `cargo`
  MODIFY `id_cargo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `ciudad`
--
ALTER TABLE `ciudad`
  MODIFY `id_ciudad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=225;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=238;

--
-- AUTO_INCREMENT de la tabla `codigo_pais`
--
ALTER TABLE `codigo_pais`
  MODIFY `id_codigo_pais` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=246;

--
-- AUTO_INCREMENT de la tabla `credito_compras`
--
ALTER TABLE `credito_compras`
  MODIFY `id_credcompra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `ctas_cobrar`
--
ALTER TABLE `ctas_cobrar`
  MODIFY `id_ctascobrar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT de la tabla `ctas_pagar`
--
ALTER TABLE `ctas_pagar`
  MODIFY `id_ctaspagar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `departamento`
--
ALTER TABLE `departamento`
  MODIFY `id_departamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `detalle`
--
ALTER TABLE `detalle`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT de la tabla `detalle_factura_compra`
--
ALTER TABLE `detalle_factura_compra`
  MODIFY `id_detalle_factura_compra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `documentacion`
--
ALTER TABLE `documentacion`
  MODIFY `id_documento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `docu_empleado`
--
ALTER TABLE `docu_empleado`
  MODIFY `id_docu_emp` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
  MODIFY `id_empleado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `empleado_cargo`
--
ALTER TABLE `empleado_cargo`
  MODIFY `id_emp_cargo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `empresa`
--
ALTER TABLE `empresa`
  MODIFY `id_empresa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `establecimiento`
--
ALTER TABLE `establecimiento`
  MODIFY `id_establecimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `factura`
--
ALTER TABLE `factura`
  MODIFY `id_factura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT de la tabla `factura_compra`
--
ALTER TABLE `factura_compra`
  MODIFY `id_factcompra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `grupo`
--
ALTER TABLE `grupo`
  MODIFY `id_grupo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `grupo_cliente`
--
ALTER TABLE `grupo_cliente`
  MODIFY `id_grupo_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `grupo_ocupacional`
--
ALTER TABLE `grupo_ocupacional`
  MODIFY `id_grupo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `grupo_proveedor`
--
ALTER TABLE `grupo_proveedor`
  MODIFY `id_grupoprov` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `ice`
--
ALTER TABLE `ice`
  MODIFY `id_ice` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT de la tabla `importacion`
--
ALTER TABLE `importacion`
  MODIFY `id_importacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `impuesto`
--
ALTER TABLE `impuesto`
  MODIFY `id_imp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT de la tabla `impuesto_retencion`
--
ALTER TABLE `impuesto_retencion`
  MODIFY `id_impret` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT de la tabla `iva`
--
ALTER TABLE `iva`
  MODIFY `id_iva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `linea_producto`
--
ALTER TABLE `linea_producto`
  MODIFY `id_linea_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `marca`
--
ALTER TABLE `marca`
  MODIFY `id_marca` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `modelo`
--
ALTER TABLE `modelo`
  MODIFY `id_modelo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id_modulos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `moneda`
--
ALTER TABLE `moneda`
  MODIFY `id_moneda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `nacionalidad`
--
ALTER TABLE `nacionalidad`
  MODIFY `id_nacionalidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=202;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id_pagos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `pago_fact_compra`
--
ALTER TABLE `pago_fact_compra`
  MODIFY `id_pagfactcompra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `parroquia`
--
ALTER TABLE `parroquia`
  MODIFY `id_parroquia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1400;

--
-- AUTO_INCREMENT de la tabla `plan_cuentas`
--
ALTER TABLE `plan_cuentas`
  MODIFY `id_ctas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=434;

--
-- AUTO_INCREMENT de la tabla `presentacion`
--
ALTER TABLE `presentacion`
  MODIFY `id_presentacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT de la tabla `producto_bodega`
--
ALTER TABLE `producto_bodega`
  MODIFY `id_producto_bodega` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `producto_bodega_trans`
--
ALTER TABLE `producto_bodega_trans`
  MODIFY `id_producto_bodega_trans` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `producto_factura`
--
ALTER TABLE `producto_factura`
  MODIFY `id_prodfact` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `id_proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT de la tabla `provincia`
--
ALTER TABLE `provincia`
  MODIFY `id_provincia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `punto_emision`
--
ALTER TABLE `punto_emision`
  MODIFY `id_punto_emision` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `retencion`
--
ALTER TABLE `retencion`
  MODIFY `id_retencion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `retencion_factura`
--
ALTER TABLE `retencion_factura`
  MODIFY `id_retencion_factura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `retencion_factura_comp`
--
ALTER TABLE `retencion_factura_comp`
  MODIFY `id_retfactcompra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tipo_cliente`
--
ALTER TABLE `tipo_cliente`
  MODIFY `id_tipo_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `tipo_comprobante`
--
ALTER TABLE `tipo_comprobante`
  MODIFY `id_tipcomprobante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `tipo_medida`
--
ALTER TABLE `tipo_medida`
  MODIFY `id_tipo_medida` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `tipo_producto`
--
ALTER TABLE `tipo_producto`
  MODIFY `id_tipo_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tipo_sustento`
--
ALTER TABLE `tipo_sustento`
  MODIFY `id_sustento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `ubicacion_fisica`
--
ALTER TABLE `ubicacion_fisica`
  MODIFY `id_ubicacion_fisica` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `unidad_medida`
--
ALTER TABLE `unidad_medida`
  MODIFY `id_unidad_medida` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `vendedor`
--
ALTER TABLE `vendedor`
  MODIFY `id_vendedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asientos_detalle`
--
ALTER TABLE `asientos_detalle`
  ADD CONSTRAINT `id_ctas` FOREIGN KEY (`id_ctas`) REFERENCES `plan_cuentas` (`id_ctas`);

--
-- Filtros para la tabla `bodega`
--
ALTER TABLE `bodega`
  ADD CONSTRAINT `fk_bodega_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id_empresa`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `bodega_egreso`
--
ALTER TABLE `bodega_egreso`
  ADD CONSTRAINT `fk_bodega_egreso_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id_empresa`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_bodega_egreso_id_producto_bodega` FOREIGN KEY (`id_producto_bodega`) REFERENCES `producto_bodega` (`id_producto_bodega`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `bodega_ingreso`
--
ALTER TABLE `bodega_ingreso`
  ADD CONSTRAINT `fk_bodega_ingreso_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id_empresa`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ingreso_bodega_id_producto_bodega` FOREIGN KEY (`id_producto_bodega`) REFERENCES `producto_bodega` (`id_producto_bodega`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `bodega_transferencia`
--
ALTER TABLE `bodega_transferencia`
  ADD CONSTRAINT `fk_bodega_transferencia_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id_empresa`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `caja`
--
ALTER TABLE `caja`
  ADD CONSTRAINT `caja_ibfk_1` FOREIGN KEY (`id_moneda`) REFERENCES `moneda` (`id_moneda`),
  ADD CONSTRAINT `caja_ibfk_2` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id_empresa`);

--
-- Filtros para la tabla `calendario_empleado`
--
ALTER TABLE `calendario_empleado`
  ADD CONSTRAINT `idemplead` FOREIGN KEY (`idemplead`) REFERENCES `empleado` (`id_empleado`);

--
-- Filtros para la tabla `cargas_empleado`
--
ALTER TABLE `cargas_empleado`
  ADD CONSTRAINT `idempleado` FOREIGN KEY (`idempleado`) REFERENCES `empleado` (`id_empleado`);

--
-- Filtros para la tabla `cargo`
--
ALTER TABLE `cargo`
  ADD CONSTRAINT `idarea` FOREIGN KEY (`idarea`) REFERENCES `area_trabajo` (`id_area`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `ciudad`
--
ALTER TABLE `ciudad`
  ADD CONSTRAINT `fk_ciudad_provincia1` FOREIGN KEY (`id_provincia`) REFERENCES `provincia` (`id_provincia`);

--
-- Filtros para la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`id_tipo_cliente`) REFERENCES `tipo_cliente` (`id_tipo_cliente`),
  ADD CONSTRAINT `cliente_ibfk_2` FOREIGN KEY (`id_vendedor`) REFERENCES `vendedor` (`id_vendedor`),
  ADD CONSTRAINT `fk_cliente_empresa1` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id_empresa`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_cliente_grupocliente1` FOREIGN KEY (`id_grupo_cliente`) REFERENCES `grupo_cliente` (`id_grupo_cliente`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `credito_compras`
--
ALTER TABLE `credito_compras`
  ADD CONSTRAINT `credito_compras_ibfk_1` FOREIGN KEY (`id_factcompra`) REFERENCES `factura_compra` (`id_factcompra`);

--
-- Filtros para la tabla `detalle`
--
ALTER TABLE `detalle`
  ADD CONSTRAINT `fk_detalle_factura` FOREIGN KEY (`id_factura`) REFERENCES `factura` (`id_factura`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detalle_producto1` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `docu_empleado`
--
ALTER TABLE `docu_empleado`
  ADD CONSTRAINT `iddocu` FOREIGN KEY (`iddocu`) REFERENCES `documentacion` (`id_documento`),
  ADD CONSTRAINT `idemple` FOREIGN KEY (`idemple`) REFERENCES `empleado` (`id_empleado`);

--
-- Filtros para la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD CONSTRAINT `fk_empleado_id_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id_empresa`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `empleado_cargo`
--
ALTER TABLE `empleado_cargo`
  ADD CONSTRAINT `id_empleado` FOREIGN KEY (`id_empleado`) REFERENCES `empleado` (`id_empleado`),
  ADD CONSTRAINT `idcargo` FOREIGN KEY (`id_cargo`) REFERENCES `cargo` (`id_cargo`),
  ADD CONSTRAINT `idgrupo` FOREIGN KEY (`id_grupo`) REFERENCES `grupo_ocupacional` (`id_grupo`);

--
-- Filtros para la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD CONSTRAINT `empresa_ibfk_1` FOREIGN KEY (`id_moneda`) REFERENCES `moneda` (`id_moneda`),
  ADD CONSTRAINT `fk_empresa_user1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `establecimiento`
--
ALTER TABLE `establecimiento`
  ADD CONSTRAINT `fk_establecimiento_id_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id_empresa`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `factura`
--
ALTER TABLE `factura`
  ADD CONSTRAINT `fk_factura_cliente1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_factura_id_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id_empresa`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_factura_id_establecimiento` FOREIGN KEY (`id_establecimiento`) REFERENCES `establecimiento` (`id_establecimiento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_factura_id_punto_emision` FOREIGN KEY (`id_punto_emision`) REFERENCES `punto_emision` (`id_punto_emision`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_factura_id_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `factura_compra`
--
ALTER TABLE `factura_compra`
  ADD CONSTRAINT `factura_compra_ibfk_12` FOREIGN KEY (`id_sustento`) REFERENCES `tipo_sustento` (`id_sustento`),
  ADD CONSTRAINT `factura_compra_ibfk_3` FOREIGN KEY (`id_cliente_asoc`) REFERENCES `cliente` (`id_cliente`),
  ADD CONSTRAINT `factura_compra_ibfk_4` FOREIGN KEY (`id_importacion`) REFERENCES `importacion` (`id_importacion`),
  ADD CONSTRAINT `factura_compra_ibfk_7` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id_proveedor`);

--
-- Filtros para la tabla `grupo_cliente`
--
ALTER TABLE `grupo_cliente`
  ADD CONSTRAINT `fk_grupo_cliente_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id_empresa`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `grupo_proveedor`
--
ALTER TABLE `grupo_proveedor`
  ADD CONSTRAINT `fk_grupo_proveedor_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id_empresa`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `impuesto`
--
ALTER TABLE `impuesto`
  ADD CONSTRAINT `fk_impuesto_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id_empresa`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `impuesto_retencion`
--
ALTER TABLE `impuesto_retencion`
  ADD CONSTRAINT `impuesto_retencion_ibfk_1` FOREIGN KEY (`id_impuesto`) REFERENCES `impuesto` (`id_imp`),
  ADD CONSTRAINT `impuesto_retencion_ibfk_2` FOREIGN KEY (`id_retencion`) REFERENCES `retencion` (`id_retencion`);

--
-- Filtros para la tabla `linea_producto`
--
ALTER TABLE `linea_producto`
  ADD CONSTRAINT `fk_linea producto_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id_empresa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `marca`
--
ALTER TABLE `marca`
  ADD CONSTRAINT `fk_marca_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id_empresa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `modelo`
--
ALTER TABLE `modelo`
  ADD CONSTRAINT `fk_modelo_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id_empresa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `fk_pagos_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id_empresa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `presentacion`
--
ALTER TABLE `presentacion`
  ADD CONSTRAINT `fk_presentacion_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id_empresa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `fk_producto_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id_empresa`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_producto_linea_producto1` FOREIGN KEY (`id_linea_producto`) REFERENCES `linea_producto` (`id_linea_producto`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_producto_marca1` FOREIGN KEY (`id_marca`) REFERENCES `marca` (`id_marca`),
  ADD CONSTRAINT `fk_producto_modelo1` FOREIGN KEY (`id_modelo`) REFERENCES `modelo` (`id_modelo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_producto_presentacion1` FOREIGN KEY (`id_presentacion`) REFERENCES `presentacion` (`id_presentacion`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_producto_tipo_medida1` FOREIGN KEY (`id_tipo_medida`) REFERENCES `tipo_medida` (`id_tipo_medida`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_producto_tipo_producto1` FOREIGN KEY (`id_tipo_producto`) REFERENCES `tipo_producto` (`id_tipo_producto`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_producto_unidad_medida1` FOREIGN KEY (`id_unidad_medida`) REFERENCES `unidad_medida` (`id_unidad_medida`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `producto_bodega`
--
ALTER TABLE `producto_bodega`
  ADD CONSTRAINT `fk_producto_bodega_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id_empresa`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_producto_has_bodega_bodega1` FOREIGN KEY (`id_bodega`) REFERENCES `bodega` (`id_bodega`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_producto_has_bodega_producto1` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `producto_bodega_trans`
--
ALTER TABLE `producto_bodega_trans`
  ADD CONSTRAINT `fk_id_bodega_transferencia` FOREIGN KEY (`id_bodega_transferencia`) REFERENCES `bodega_transferencia` (`id_bodega_transferencia`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_producto_bodega_trans_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id_empresa`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_producto_bodega_trans_has_producto` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `producto_factura`
--
ALTER TABLE `producto_factura`
  ADD CONSTRAINT `fk_producto_factura_factura_compra` FOREIGN KEY (`id_factcompra`) REFERENCES `factura_compra` (`id_factcompra`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_producto_factura_producto` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD CONSTRAINT `fk_proveedor_id_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id_empresa`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `punto_emision`
--
ALTER TABLE `punto_emision`
  ADD CONSTRAINT `id_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id_empresa`),
  ADD CONSTRAINT `id_establecimientos` FOREIGN KEY (`id_establecimiento`) REFERENCES `establecimiento` (`id_establecimiento`);

--
-- Filtros para la tabla `retencion`
--
ALTER TABLE `retencion`
  ADD CONSTRAINT `fk_retencion_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id_empresa`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `rol`
--
ALTER TABLE `rol`
  ADD CONSTRAINT `fk_rol_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id_empresa`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tipo_cliente`
--
ALTER TABLE `tipo_cliente`
  ADD CONSTRAINT `fk_tipo_cliente_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id_empresa`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tipo_comprobante`
--
ALTER TABLE `tipo_comprobante`
  ADD CONSTRAINT `fk_empresa_tipo_comprobante` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id_empresa`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tipo_producto`
--
ALTER TABLE `tipo_producto`
  ADD CONSTRAINT `fk_tipo_producto_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id_empresa`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tipo_producto_linea_producto1` FOREIGN KEY (`id_linea_producto`) REFERENCES `linea_producto` (`id_linea_producto`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tipo_sustento`
--
ALTER TABLE `tipo_sustento`
  ADD CONSTRAINT `fk_tipo_sustento_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id_empresa`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `ubicacion_fisica`
--
ALTER TABLE `ubicacion_fisica`
  ADD CONSTRAINT `fk_ubicacion_fisica_bodega1` FOREIGN KEY (`id_bodega`) REFERENCES `bodega` (`id_bodega`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `unidad_medida`
--
ALTER TABLE `unidad_medida`
  ADD CONSTRAINT `fk_unidad_medida_tipo_medida1` FOREIGN KEY (`id_tipo_medida`) REFERENCES `tipo_medida` (`id_tipo_medida`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id_empresa`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_user_id_establecimiento` FOREIGN KEY (`id_establecimiento`) REFERENCES `establecimiento` (`id_establecimiento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_user_id_punto_emision` FOREIGN KEY (`id_punto_emision`) REFERENCES `punto_emision` (`id_punto_emision`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_user_rol1` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
