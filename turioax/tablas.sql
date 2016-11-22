DROP TABLE IF EXISTS estado;
CREATE TABLE estado (
    id integer primary key AUTO_INCREMENT,
    nombre varchar(150)
);

DROP TABLE IF EXISTS ciudad;
CREATE TABLE ciudad (
    id integer primary key AUTO_INCREMENT,
    nombre varchar(150),
    estado_id  integer,
    FOREIGN KEY (estado_id) REFERENCES estado(id)
      ON UPDATE CASCADE ON DELETE CASCADE
);


DROP TABLE IF EXISTS region;
CREATE TABLE region (
    id integer primary key AUTO_INCREMENT,
    nombre varchar(150)
);

DROP TABLE IF EXISTS tespecifico;
CREATE TABLE tespecifico (
    id integer primary key AUTO_INCREMENT,
    nombre varchar(150) NOT NULL DEFAULT ""
);

DROP TABLE IF EXISTS tgeneral;
CREATE TABLE tgeneral (
    id integer primary key AUTO_INCREMENT,
    nombre varchar(150) NOT NULL DEFAULT ""
);

DROP TABLE IF EXISTS persona;
-- Tipo de publico al que va orientado.
CREATE TABLE persona (
    id integer primary key AUTO_INCREMENT,
    nombre varchar(150)
);


DROP TABLE IF EXISTS sitio;
CREATE TABLE sitio (
    id integer primary key AUTO_INCREMENT,
    nombre varchar(150) NOT NULL DEFAULT "",
    ciudad_id integer not null,
    region_id integer not null,
    telefono text,
    direccion text,
    correo varchar(255),
    url varchar(255),
    latitud decimal(12,9),
    longitud decimal(12,9),
    rmin decimal(5,1),
    rmax decimal(5,1),
    descripcion text,
    observaciones text,
    referencia text
);

DROP TABLE IF EXISTS sitiotespecifico;
CREATE TABLE sitiotespecifico (
    id integer primary key auto_increment,
    tespecifico_id integer,
    sitio_id integer,
    FOREIGN KEY (tespecifico_id) REFERENCES tespecifico(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    FOREIGN KEY (sitio_id) REFERENCES sitio(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
);

DROP TABLE IF EXISTS sitiotgeneral;
CREATE TABLE sitiotgeneral (
    id integer primary key auto_increment,
    tgeneral_id integer,
    sitio_id integer,
    FOREIGN KEY (tgeneral_id) REFERENCES tgeneral(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    FOREIGN KEY (sitio_id) REFERENCES sitio(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
);


DROP TABLE IF EXISTS sitiopersona;
CREATE TABLE sitiopersona (
    id integer primary key AUTO_INCREMENT,
    persona_id integer,
    sitio_id integer,
    FOREIGN KEY (persona_id) REFERENCES persona(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    FOREIGN KEY (sitio_id) REFERENCES sitio(id)
        ON UPDATE CASCADE ON DELETE RESTRICT

);

DROP TABLE IF EXISTS temporada;
CREATE TABLE temporada (
    id integer primary key AUTO_INCREMENT,
    nombre varchar(150),
    sitio_id integer,
    FOREIGN KEY (sitio_id) REFERENCES sitio(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    inicia_el date,
    termina_el date
    );

DROP TABLE IF EXISTS horario;
CREATE TABLE horario (
    id integer primary key AUTO_INCREMENT,
    temporada_id integer,
    FOREIGN KEY (temporada_id) REFERENCES temporada(id)
        ON UPDATE CASCADE ON DELETE CASCADE,

    dia varchar(1),
    horas varchar(50));



