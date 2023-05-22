-- Prueba3.areas definition

CREATE TABLE `areas` (
  `cod_area` int(11) NOT NULL AUTO_INCREMENT,
  `nom_area` varchar(100) NOT NULL,
  `imagen` varchar(100) NOT NULL,
  `estado` int(1) NOT NULL,
  `descripcion` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`cod_area`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;


-- Prueba3.usuarios definition

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(40) NOT NULL,
  `clave` varchar(40) NOT NULL,
  `estado` int(11) NOT NULL,
  `nombre` varchar(70) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;


INSERT INTO Prueba3.usuarios (usuario,clave,estado,nombre) VALUES
	 ('Gabriel.CruzS','123456',1,'Gabriel Cruz Soto'),
	 ('ww','www',0,'www');


INSERT INTO Prueba3.areas (nom_area,imagen,estado,descripcion) VALUES
	 ('Administración pública','AdmPublic.jpg',0,'Hola'),
	 ('ddss','descarga.png',1,'<p>sdsd</p>'),
	 ('dd','Administrador-Publico.jpg',1,'<p>dcd</p>');
