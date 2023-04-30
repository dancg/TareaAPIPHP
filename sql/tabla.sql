create table articulos(
id int unsigned auto_increment primary key,
nombre varchar(60),
descripcion varchar(250) not null,
pvp float(6,2),
stock smallint unsigned,
disponible enum('SI', 'NO'),
categoria enum('BAZAR', 'ALIMENTACION')
);