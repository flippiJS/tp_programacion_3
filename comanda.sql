-- --------------------------------------------------------
-- Host:                         raspiflippi.ddns.net
-- Versión del servidor:         10.1.38-MariaDB-0+deb9u1 - Raspbian 9.0
-- SO del servidor:              debian-linux-gnueabihf
-- HeidiSQL Versión:             10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Volcando estructura de base de datos para comanda
CREATE DATABASE IF NOT EXISTS `comanda` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `comanda`;

-- Volcando estructura para tabla comanda.empleados
CREATE TABLE IF NOT EXISTS `empleados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `sectorId` int(11) NOT NULL,
  `usuarioId` int(11) NOT NULL,
  `estado` int(11) NOT NULL,
  `fechaAlta` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla comanda.empleados: ~5 rows (aproximadamente)
/*!40000 ALTER TABLE `empleados` DISABLE KEYS */;
INSERT INTO `empleados` (`id`, `nombre`, `sectorId`, `usuarioId`, `estado`, `fechaAlta`) VALUES
	(1, 'Carlos Gomez', 1, 1, 1, '2019-07-02'),
	(2, 'Hernan Perez', 5, 2, 1, '2019-07-03'),
	(3, 'Pedro Almendros', 2, 3, 1, '2019-07-03'),
	(4, 'Juan Mieres', 3, 4, 2, '2019-07-04'),
	(5, 'Luciana Pichio', 4, 5, 1, '2019-07-03');
/*!40000 ALTER TABLE `empleados` ENABLE KEYS */;

-- Volcando estructura para tabla comanda.empleados_estados
CREATE TABLE IF NOT EXISTS `empleados_estados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla comanda.empleados_estados: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `empleados_estados` DISABLE KEYS */;
INSERT INTO `empleados_estados` (`id`, `descripcion`) VALUES
	(1, 'Activo'),
	(2, 'Suspendido');
/*!40000 ALTER TABLE `empleados_estados` ENABLE KEYS */;

-- Volcando estructura para tabla comanda.encuestas
CREATE TABLE IF NOT EXISTS `encuestas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pedido` varchar(5) NOT NULL DEFAULT '',
  `puntajeMozo` int(1) NOT NULL,
  `puntajeCocinero` int(1) NOT NULL,
  `puntajeMesa` int(1) NOT NULL,
  `puntajeRestaurante` int(1) NOT NULL,
  `comentario` varchar(66) NOT NULL,
  `fechaAlta` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla comanda.encuestas: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `encuestas` DISABLE KEYS */;
INSERT INTO `encuestas` (`id`, `pedido`, `puntajeMozo`, `puntajeCocinero`, `puntajeMesa`, `puntajeRestaurante`, `comentario`, `fechaAlta`) VALUES
	(1, '66n1n', 6, 7, 5, 4, 'me gusto la atencion', '2019-07-05'),
	(2, '66n1n', 6, 7, 5, 4, 'me gusto la atencion', '2019-07-05');
/*!40000 ALTER TABLE `encuestas` ENABLE KEYS */;

-- Volcando estructura para tabla comanda.logs
CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `empleadoId` int(11) DEFAULT NULL,
  `sectorId` int(11) DEFAULT NULL,
  `path` varchar(50) DEFAULT NULL,
  `method` varchar(50) DEFAULT NULL,
  `fechaAlta` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla comanda.logs: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `logs` DISABLE KEYS */;
INSERT INTO `logs` (`id`, `empleadoId`, `sectorId`, `path`, `method`, `fechaAlta`) VALUES
	(1, 1, 1, '/auth/login', 'POST', '2019-07-05 15:19:39'),
	(2, NULL, NULL, '/clientes/pedido/tiempo', 'POST', '2019-07-05 15:19:43'),
	(3, 1, 1, '/auth/login', 'POST', '2019-07-05 15:19:45'),
	(4, 1, 1, '/listados/empleados', 'GET', '2019-07-05 15:19:58'),
	(5, 1, 1, '/listados/pedidosHora', 'GET', '2019-07-05 15:19:59'),
	(6, 1, 1, '/empleados/estado', 'POST', '2019-07-05 15:20:01'),
	(7, 1, 1, '/listados/pedidosHora', 'GET', '2019-07-05 15:21:03'),
	(8, 1, 1, '/listados/logs', 'GET', '2019-07-05 15:28:03'),
	(9, 1, 1, '/listados/empleados', 'GET', '2019-07-05 15:35:23'),
	(10, 1, 1, '/auth/login', 'POST', '2019-07-05 15:35:34'),
	(11, 1, 1, '/listados/empleados', 'GET', '2019-07-05 15:35:43'),
	(12, 1, 1, '/auth/login', 'POST', '2019-07-05 15:38:26'),
	(13, 2, 5, '/auth/login', 'POST', '2019-07-05 15:44:10'),
	(14, 1, 1, '/auth/login', 'POST', '2019-07-05 15:44:13'),
	(15, 1, 1, '/auth/login', 'POST', '2019-07-05 15:54:51'),
	(16, 2, 5, '/auth/login', 'POST', '2019-07-05 15:56:50'),
	(17, 1, 1, '/listados/empleados', 'GET', '2019-07-05 15:57:28');
/*!40000 ALTER TABLE `logs` ENABLE KEYS */;

-- Volcando estructura para tabla comanda.mesas
CREATE TABLE IF NOT EXISTS `mesas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) NOT NULL DEFAULT '',
  `estadoId` int(11) NOT NULL,
  `fechaBaja` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla comanda.mesas: ~4 rows (aproximadamente)
/*!40000 ALTER TABLE `mesas` DISABLE KEYS */;
INSERT INTO `mesas` (`id`, `codigo`, `estadoId`, `fechaBaja`) VALUES
	(1, 'ewr4a', 1, NULL),
	(2, 'laus9', 1, NULL),
	(3, '9vuau', 1, NULL),
	(4, 'e23x0', 1, NULL);
/*!40000 ALTER TABLE `mesas` ENABLE KEYS */;

-- Volcando estructura para tabla comanda.mesas_estados
CREATE TABLE IF NOT EXISTS `mesas_estados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla comanda.mesas_estados: ~4 rows (aproximadamente)
/*!40000 ALTER TABLE `mesas_estados` DISABLE KEYS */;
INSERT INTO `mesas_estados` (`id`, `descripcion`) VALUES
	(1, 'Cerrada'),
	(2, 'Con clientes esperando pedido'),
	(3, 'Con clientes comiendo'),
	(4, 'Con clientes pagando');
/*!40000 ALTER TABLE `mesas_estados` ENABLE KEYS */;

-- Volcando estructura para tabla comanda.pedidos
CREATE TABLE IF NOT EXISTS `pedidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(5) NOT NULL,
  `estado` int(11) NOT NULL,
  `mesaId` int(11) NOT NULL,
  `mozoId` int(11) NOT NULL,
  `precioTotal` float DEFAULT NULL,
  `fechaAlta` datetime DEFAULT NULL,
  `fechaEntrega` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla comanda.pedidos: ~1 rows (aproximadamente)
/*!40000 ALTER TABLE `pedidos` DISABLE KEYS */;
INSERT INTO `pedidos` (`id`, `codigo`, `estado`, `mesaId`, `mozoId`, `precioTotal`, `fechaAlta`, `fechaEntrega`) VALUES
	(1, '66n1n', 5, 1, 2, 460, '2019-07-05 10:59:06', '2019-07-05 11:54:58');
/*!40000 ALTER TABLE `pedidos` ENABLE KEYS */;

-- Volcando estructura para tabla comanda.pedidos_estados
CREATE TABLE IF NOT EXISTS `pedidos_estados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla comanda.pedidos_estados: ~5 rows (aproximadamente)
/*!40000 ALTER TABLE `pedidos_estados` DISABLE KEYS */;
INSERT INTO `pedidos_estados` (`id`, `descripcion`) VALUES
	(1, 'Iniciado'),
	(2, 'En preparacion'),
	(3, 'Listo para servir'),
	(4, 'Entregado'),
	(5, 'Cobrado');
/*!40000 ALTER TABLE `pedidos_estados` ENABLE KEYS */;

-- Volcando estructura para tabla comanda.pedidos_productos
CREATE TABLE IF NOT EXISTS `pedidos_productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pedidoId` int(11) NOT NULL,
  `productoId` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `empleadoId` int(11) DEFAULT NULL,
  `tiempoEstimado` int(11) DEFAULT '0',
  `fechaAlta` datetime DEFAULT NULL,
  `fechaEntrega` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla comanda.pedidos_productos: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `pedidos_productos` DISABLE KEYS */;
INSERT INTO `pedidos_productos` (`id`, `pedidoId`, `productoId`, `cantidad`, `empleadoId`, `tiempoEstimado`, `fechaAlta`, `fechaEntrega`) VALUES
	(1, 1, 1, 2, 5, 20, '2019-07-03 17:19:43', '2019-07-05 11:45:26'),
	(2, 1, 4, 1, 5, 15, '2019-07-05 10:41:44', '2019-07-05 11:45:46'),
	(3, 1, 3, 1, 5, 2, '2019-07-03 17:19:54', '2019-07-05 11:48:39');
/*!40000 ALTER TABLE `pedidos_productos` ENABLE KEYS */;

-- Volcando estructura para tabla comanda.productos
CREATE TABLE IF NOT EXISTS `productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) NOT NULL,
  `sectorId` int(11) NOT NULL,
  `precio` float NOT NULL,
  `fechaBaja` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla comanda.productos: ~9 rows (aproximadamente)
/*!40000 ALTER TABLE `productos` DISABLE KEYS */;
INSERT INTO `productos` (`id`, `descripcion`, `sectorId`, `precio`, `fechaBaja`) VALUES
	(1, 'Hamburguesa Completa', 4, 120, NULL),
	(2, 'Martini', 2, 100, NULL),
	(3, 'Pinta IPA', 3, 110, NULL),
	(4, 'Pinta HONEY', 3, 110, NULL),
	(5, 'Pinta APA', 3, 110, NULL),
	(6, 'Milanesa con Fritas', 4, 180, NULL),
	(7, 'Papas con Cheddar', 4, 130, NULL),
	(8, 'Fernet Cola', 2, 145, NULL),
	(9, 'Campari', 2, 155, NULL);
/*!40000 ALTER TABLE `productos` ENABLE KEYS */;

-- Volcando estructura para tabla comanda.sectores
CREATE TABLE IF NOT EXISTS `sectores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) NOT NULL,
  `fechaBaja` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla comanda.sectores: ~5 rows (aproximadamente)
/*!40000 ALTER TABLE `sectores` DISABLE KEYS */;
INSERT INTO `sectores` (`id`, `descripcion`, `fechaBaja`) VALUES
	(1, 'Socios', NULL),
	(2, 'Bartender', NULL),
	(3, 'Cerveceros', NULL),
	(4, 'Cocineros', NULL),
	(5, 'Mozos', NULL);
/*!40000 ALTER TABLE `sectores` ENABLE KEYS */;

-- Volcando estructura para tabla comanda.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(50) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `fechaBaja` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla comanda.usuarios: ~5 rows (aproximadamente)
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` (`id`, `usuario`, `clave`, `fechaBaja`) VALUES
	(1, 'cgomez', '$2y$10$0n86PC2a8EZJqJgwkGrkveigfRyZbdISPW6FdmtUVGiwaLmNsHDJ6', NULL),
	(2, 'hperez', '$2y$10$WP4u9dp4xM/OKhspjEH9W.jKV8GORFwoyCmdsTehYHCDq77Y5VWa6', NULL),
	(3, 'palmendros', '$2y$10$62I9lwt3/dUw5l7CJAnbbeUnPEYi71PM7RGKck3vrIggBTKNqncIK', NULL),
	(4, 'jmieres', '$2y$10$6tIFiKG7Krp79sHRQfLhDOGd9jMyZFG6SYY9W3.7U1FlbTRzh9LAu', NULL),
	(5, 'lpichio', '$2y$10$1Stq8/V7zJViGqRC8M6w8uXmQQEDucNXdIBEkl1q5b1hXGTKxJ7kG', NULL);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
