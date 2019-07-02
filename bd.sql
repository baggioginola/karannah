DROP DATABASE IF EXISTS `karannah`;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`karannah` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `karannah`;

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nombre` varchar(200) NOT NULL,
  `apellidos` varchar(200) NOT NULL,
  `e_mail` varchar(200) NOT NULL,
  `PASSWORD` varchar(200) NOT NULL,
  `active` boolean DEFAULT TRUE,
  `nivel` int(2) NOT NULL,
  `fecha_alta` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `fecha_modifica` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `usuarios` (`nombre`, `apellidos`, `e_mail`, `PASSWORD`, `active`, `nivel`) VALUES
('Mario', 'Cuevas', 'mariocuevas88@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 1, 0);

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL auto_increment primary KEY,
  `nombre` varchar(200) NOT NULL,
  `titulo` varchar(500) NOT NULL,
  `subtitulo` varchar(500) NOT NULL,
  `descripcion` text NOT NULL,
  `active` boolean DEFAULT TRUE,
  `fecha_alta` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `fecha_modifica` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `productos` (
  `id` int(11) NOT NULL auto_increment primary key,
  `id_categoria` int(11) not null,
  `nombre` varchar(500),
  `descripcion` text,
  `active` boolean DEFAULT TRUE,
  `precio` decimal(10,2) DEFAULT NULL,
  `num_imagenes` int(5) DEFAULT NULL,
  `fecha_alta` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `fecha_modifica` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `iva` decimal(10,2) DEFAULT NULL,
  `codigo_interno` varchar(250),
  `novedades` boolean,
  `promociones` boolean,
  FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `articulos` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`titulo` varchar(255) NOT NULL,
`subtitulo` varchar(255) NOT NULL,
`contenido` text NOT NULL,
`active` boolean DEFAULT TRUE,
`fecha` date,
`fecha_alta` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `fecha_modifica` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY(`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `logs` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
`service` varchar(250),
`info` TEXT,
`expiration_date` TIMESTAMP,
PRIMARY KEY(`id`)
) ENGINE = InnoDB DEFAULT CHARSET = latin1;
