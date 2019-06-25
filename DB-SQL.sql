/*CREATE TABLE tipo_encuesta(
	id int(10) NOT NULL AUTO_INCREMENT,
	descripcion varchar(100),
	nivel_min int(2) NOT NULL,
	nivel_max int(2) NOT NULL,
	fecha_inicio date NOT NULL,
	fecha_fin date NOT NULL,
	PRIMARY KEY (id)
);
*/
CREATE TABLE periodo_encuesta(
	id int(10) NOT NULL AUTO_INCREMENT,
	periodo int(10),
	descripcion varchar(100),
	fecha_inicio date NOT NULL,
	fecha_fin date NOT NULL,
	PRIMARY KEY (id)
);

CREATE TABLE empleados (
	id int(10) NOT NULL AUTO_INCREMENT,
	nombre varchar(50) NOT NULL,
	appaterno varchar(50) NOT NULL,
	apmaterno varchar(50) NOT NULL,
	num_e varchar(10) NOT NULL,
	pass varchar(20) NOT NULL,
	nivel tinyint(4) NOT NULL,
	departamento varchar(50) NOT NULL,
	tipo varchar(10) NOT NULL,
	PRIMARY KEY (id)
	);

CREATE TABLE empleado_encargado (
	id_encargado int(10) NOT NULL,
	id_empleado int(10) NOT NULL,
	PRIMARY KEY (id_encargado, id_empleado),
	FOREIGN KEY (id_encargado) REFERENCES empleados (id),
	FOREIGN KEY (id_empleado) REFERENCES empleados (id)
	);

CREATE TABLE encuestas(
	id int(10) NOT NULL AUTO_INCREMENT,
	/*tipo_encuesta_id int(10) NOT NULL,*/
	periodo_encuesta_id int(10) NOT NULL,
	descripcion varchar(100) NOT NULL,
	nivel_min int(2) NOT NULL,
	nivel_max int(2) NOT NULL,	
	PRIMARY KEY (id),
	FOREIGN KEY (periodo_encuesta_id) REFERENCES periodo_encuesta (id)
);

CREATE TABLE resultados_posiciones (
	id int(10) NOT NULL AUTO_INCREMENT,
	encuesta_id int(10) NOT NULL,
	pregunta varchar(200) NOT NULL,
	resultado int(10) NOT NULL,
	fecha_captura date NOT NULL,	
	empleado_evaluado_id int(10) NOT NULL,
	empleado_evalua_id int(10) NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (encuesta_id) REFERENCES encuestas (id),
	FOREIGN KEY (empleado_evaluado_id) REFERENCES empleados (id),
	FOREIGN KEY (empleado_evalua_id) REFERENCES empleados (id)
	);

CREATE TABLE resultados_objetivos (
	id int(10) NOT NULL AUTO_INCREMENT,
	encuesta_id int(10) NOT NULL,
	objetivo varchar(200) NOT NULL,
	ponderacion int(10) NOT NULL,
	consecucion int(10) NOT NULL,
	comentarios varchar(250) NOT NULL,
	fecha_captura date NOT NULL,
	empleado_evaluado_id int(10) NOT NULL,
	empleado_evalua_id int(10) NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (encuesta_id) REFERENCES encuestas (id),
	FOREIGN KEY (empleado_evaluado_id) REFERENCES empleados (id),
	FOREIGN KEY (empleado_evalua_id) REFERENCES empleados (id)
	);

