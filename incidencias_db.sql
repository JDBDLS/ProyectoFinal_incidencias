
CREATE TABLE `provincias` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



CREATE TABLE `municipios` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(50) NOT NULL,
  `provincia_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `provincia_id` (`provincia_id`),
  CONSTRAINT `fk_municipios_provincias` FOREIGN KEY (`provincia_id`) REFERENCES `provincias` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



CREATE TABLE `barrios` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(50) NOT NULL,
  `municipio_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `municipio_id` (`municipio_id`),
  CONSTRAINT `fk_barrios_municipios` FOREIGN KEY (`municipio_id`) REFERENCES `municipios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



CREATE TABLE `tipos_incidencia` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(50) NOT NULL,
  `icono` VARCHAR(100) NOT NULL, 
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO `tipos_incidencia` (`nombre`, `icono`) VALUES
('Accidente de Tránsito', 'car-crash'),
('Pelea/Agresión', 'hand-fist'),
('Robo', 'thief'),
('Desastre Natural', 'cloud-bolt'),
('Incendio', 'fire-flame-curved'),
('Inundación', 'house-flood'),
('Emergencia Médica', 'briefcase-medical'),
('Otros', 'info-circle');



CREATE TABLE `reporteros` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `nombre` VARCHAR(100) DEFAULT NULL,
  `fecha_registro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



CREATE TABLE `validadores` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `usuario` VARCHAR(50) NOT NULL UNIQUE,
  `contrasena` VARCHAR(255) NOT NULL, -- Aquí guardaremos el hash de la contraseña
  `rol` VARCHAR(20) NOT NULL, -- 'validador' o 'administrador'
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



CREATE TABLE `incidencias` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(255) NOT NULL,
  `descripcion` TEXT NOT NULL,
  `latitud` DECIMAL(10,8) NOT NULL,
  `longitud` DECIMAL(11,8) NOT NULL,
  `muertos` INT(11) DEFAULT 0,
  `heridos` INT(11) DEFAULT 0,
  `perdida_estimada` DECIMAL(15,2) DEFAULT 0.00,
  `link_social` VARCHAR(255) DEFAULT NULL,
  `foto_url` VARCHAR(255) DEFAULT NULL,
  `fecha_creacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` VARCHAR(20) NOT NULL DEFAULT 'pendiente', -- 'pendiente', 'aprobado', 'rechazado', 'validado'
  `reportero_id` INT(11) DEFAULT NULL,
  `tipo_id` INT(11) NOT NULL,
  `provincia_id` INT(11) DEFAULT NULL,
  `municipio_id` INT(11) DEFAULT NULL,
  `barrio_id` INT(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reportero_id` (`reportero_id`),
  KEY `tipo_id` (`tipo_id`),
  KEY `provincia_id` (`provincia_id`),
  KEY `municipio_id` (`municipio_id`),
  KEY `barrio_id` (`barrio_id`),
  CONSTRAINT `fk_incidencias_reporteros` FOREIGN KEY (`reportero_id`) REFERENCES `reporteros` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_incidencias_tipos` FOREIGN KEY (`tipo_id`) REFERENCES `tipos_incidencia` (`id`),
  CONSTRAINT `fk_incidencias_provincias` FOREIGN KEY (`provincia_id`) REFERENCES `provincias` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_incidencias_municipios` FOREIGN KEY (`municipio_id`) REFERENCES `municipios` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_incidencias_barrios` FOREIGN KEY (`barrio_id`) REFERENCES `barrios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



CREATE TABLE `comentarios` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `incidencia_id` INT(11) NOT NULL,
  `reportero_id` INT(11) NOT NULL,
  `comentario` TEXT NOT NULL,
  `fecha_creacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `incidencia_id` (`incidencia_id`),
  KEY `reportero_id` (`reportero_id`),
  CONSTRAINT `fk_comentarios_incidencias` FOREIGN KEY (`incidencia_id`) REFERENCES `incidencias` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_comentarios_reporteros` FOREIGN KEY (`reportero_id`) REFERENCES `reporteros` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



CREATE TABLE `correcciones` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `incidencia_id` INT(11) NOT NULL,
  `reportero_id` INT(11) NOT NULL,
  `campo` VARCHAR(50) NOT NULL,
  `valor_sugerido` TEXT NOT NULL,
  `fecha_sugerencia` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estado_validacion` VARCHAR(20) NOT NULL DEFAULT 'pendiente', -- 'pendiente', 'aprobado', 'rechazado'
  PRIMARY KEY (`id`),
  KEY `incidencia_id` (`incidencia_id`),
  KEY `reportero_id` (`reportero_id`),
  CONSTRAINT `fk_correcciones_incidencias` FOREIGN KEY (`incidencia_id`) REFERENCES `incidencias` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_correcciones_reporteros` FOREIGN KEY (`reportero_id`) REFERENCES `reporteros` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO `provincias` (`nombre`) VALUES
('Azua'),
('Bahoruco'),
('Barahona'),
('Dajabón'),
('Distrito Nacional'),
('Duarte'),
('El Seibo'),
('Elías Piña'),
('Espaillat'),
('Hato Mayor'),
('Hermanas Mirabal'),
('Independencia'),
('La Altagracia'),
('La Romana'),
('La Vega'),
('María Trinidad Sánchez'),
('Monseñor Nouel'),
('Monte Cristi'),
('Monte Plata'),
('Pedernales'),
('Peravia'),
('Puerto Plata'),
('Samaná'),
('San Cristóbal'),
('San José de Ocoa'),
('San Juan'),
('San Pedro de Macorís'),
('Sánchez Ramírez'),
('Santiago'),
('Santiago Rodríguez'),
('Santo Domingo'),
('Valverde');


INSERT INTO `municipios` (`nombre`, `provincia_id`) VALUES
('Azua', 1),
('Villa Jaragua', 2),
('Barahona', 3),
('Dajabón', 4),
('Santo Domingo de Guzmán', 5),
('San Francisco de Macorís', 6),
('El Seibo', 7),
('Comendador', 8),
('Moca', 9),
('Hato Mayor del Rey', 10),
('Salcedo', 11),
('Jimaní', 12),
('Higüey', 13),
('La Romana', 14),
('La Vega', 15),
('Nagua', 16),
('Bonao', 17),
('Monte Cristi', 18),
('Monte Plata', 19),
('Pedernales', 20),
('Baní', 21),
('Puerto Plata', 22),
('Samaná', 23),
('San Cristóbal', 24),
('San José de Ocoa', 25),
('San Juan de la Maguana', 26),
('San Pedro de Macorís', 27),
('Cotuí', 28),
('Santiago de los Caballeros', 29),
('San Ignacio de Sabaneta', 30),
('Santo Domingo Este', 31),
('Mao', 32);


INSERT INTO `barrios` (`nombre`, `municipio_id`) VALUES
('Ensanche La Fe', 5),
('Los Girasoles', 5),
('Los Tres Brazos', 31),
('Manoguayabo', 31),
('Bella Vista', 29),
('Los Jardines Metropolitanos', 29),
('Cienfuegos', 29);
