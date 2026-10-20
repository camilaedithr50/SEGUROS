
CREATE DATABASE IF NOT EXISTS seguros_vehiculares
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE seguros_vehiculares;

-- =====================================
-- 1. TABLA CLIENTES
-- =====================================

CREATE TABLE clientes (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    dni VARCHAR(15) NOT NULL UNIQUE,
    telefono VARCHAR(20),
    email VARCHAR(100),
    direccion VARCHAR(100),
    usuario VARCHAR(50) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL
);

INSERT INTO clientes
(nombre, apellido, dni, telefono, email, direccion, usuario, contrasena)
VALUES
('Juan','Perez','35123456','3704123001','juan.perez@email.com','San Martin 123','jperez','DemoCli01'),
('Maria','Gomez','36234567','3704123002','maria.gomez@email.com','Belgrano 245','mgomez','DemoCli02'),
('Lucas','Fernandez','37345678','3704123003','lucas.fernandez@email.com','Moreno 350','lfernandez','DemoCli03'),
('Sofia','Rodriguez','38456789','3704123004','sofia.rodriguez@email.com','Rivadavia 410','srodriguez','DemoCli04'),
('Mateo','Lopez','39567890','3704123005','mateo.lopez@email.com','Mitre 525','mlopez','DemoCli05'),
('Valentina','Martinez','40678901','3704123006','valentina.martinez@email.com','Sarmiento 630','vmartinez','DemoCli06'),
('Nicolas','Diaz','41789012','3704123007','nicolas.diaz@email.com','Espana 715','ndiaz','DemoCli07'),
('Camila','Torres','42890123','3704123008','camila.torres@email.com','Paraguay 820','ctorres','DemoCli08'),
('Tomas','Ramirez','43901234','3704123009','tomas.ramirez@email.com','Uriburu 930','tramirez','DemoCli09'),
('Abril','Benitez','44012345','3704123010','abril.benitez@email.com','Junin 1050','abenitez','DemoCli10'),
('Agustin','Cruz','45123456','3704123011','agustin.cruz@email.com','Entre Rios 1100','acruz','DemoCli11'),
('Elena','Sosa','46234567','3704123012','elena.sosa@email.com','9 de Julio 1180','esosa','DemoCli12'),
('Bruno','Maidana','47345678','3704123013','bruno.maidana@email.com','Cordoba 1260','bmaidana','DemoCli13'),
('Florencia','Vega','48456789','3704123014','florencia.vega@email.com','Avellaneda 1325','fvega','DemoCli14'),
('Diego','Navarro','49567890','3704123015','diego.navarro@email.com','La Rioja 1410','dnavarro','DemoCli15');

-- =====================================
-- 2. TABLA EMPLEADOS
-- =====================================

CREATE TABLE empleados (
    id_empleado INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    dni VARCHAR(15) NOT NULL UNIQUE,
    cargo VARCHAR(50) NOT NULL,
    telefono VARCHAR(20),
    email VARCHAR(100),
    usuario VARCHAR(50) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL
);

INSERT INTO empleados
(nombre, apellido, dni, cargo, telefono, email, usuario, contrasena)
VALUES
('Roberto','Sanchez','30111222','Administrador','3704131001','roberto@seguros.com','rsanchez','DemoEmp01'),
('Laura','Acosta','31222333','Vendedor','3704131002','laura@seguros.com','lacosta','DemoEmp02'),
('Martin','Romero','32333444','Cajero','3704131003','martin@seguros.com','mromero','DemoEmp03'),
('Paula','Herrera','33444555','Asesor de seguros','3704131004','paula@seguros.com','pherrera','DemoEmp04'),
('Diego','Castro','34555666','Vendedor','3704131005','diego@seguros.com','dcastro','DemoEmp05'),
('Natalia','Flores','35666777','Atencion al cliente','3704131006','natalia@seguros.com','nflores','DemoEmp06'),
('Gabriel','Silva','36777888','Cajero','3704131007','gabriel@seguros.com','gsilva','DemoEmp07'),
('Andrea','Medina','37888999','Asesor de seguros','3704131008','andrea@seguros.com','amedina','DemoEmp08'),
('Federico','Ortiz','38999000','Supervisor','3704131009','federico@seguros.com','fortiz','DemoEmp09'),
('Julieta','Rojas','39000111','Atencion al cliente','3704131010','julieta@seguros.com','jrojas','DemoEmp10'),
('Carla','Molina','40111222','Vendedor','3704131011','carla@seguros.com','cmolina','DemoEmp11'),
('Gonzalo','Pereyra','41222333','Supervisor','3704131012','gonzalo@seguros.com','gpereyra','DemoEmp12'),
('Marina','Arce','42333444','Asesor de seguros','3704131013','marina@seguros.com','marce','DemoEmp13'),
('Leandro','Aguirre','43444555','Cajero','3704131014','leandro@seguros.com','laguirre','DemoEmp14'),
('Eugenia','Luna','44555666','Atencion al cliente','3704131015','eugenia@seguros.com','eluna','DemoEmp15');

-- =====================================
-- 3. TABLA VEHICULOS
-- =====================================

CREATE TABLE vehiculos (
    id_vehiculo INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    tipo_vehiculo ENUM('Auto','Moto') NOT NULL,
    marca VARCHAR(50) NOT NULL,
    modelo VARCHAR(50) NOT NULL,
    anio YEAR NOT NULL,
    patente VARCHAR(10) NOT NULL UNIQUE,
    numero_chasis VARCHAR(30) NOT NULL UNIQUE,
    FOREIGN KEY (id_cliente)
        REFERENCES clientes(id_cliente)
);

INSERT INTO vehiculos
(id_cliente, tipo_vehiculo, marca, modelo, anio, patente, numero_chasis)
VALUES
(1,'Auto','Toyota','Corolla',2022,'AB123CD','CHASIS001'),
(2,'Moto','Honda','Wave 110',2023,'A123BCD','CHASIS002'),
(3,'Auto','Volkswagen','Gol Trend',2020,'AC234DE','CHASIS003'),
(4,'Moto','Yamaha','FZ 150',2022,'B234CDE','CHASIS004'),
(5,'Auto','Chevrolet','Onix',2024,'AD345EF','CHASIS005'),
(6,'Moto','Motomel','Blitz 110',2021,'C345DEF','CHASIS006'),
(7,'Auto','Ford','Ka',2019,'AE456FG','CHASIS007'),
(8,'Moto','Honda','GLH 150',2024,'D456EFG','CHASIS008'),
(9,'Auto','Fiat','Cronos',2023,'AF567GH','CHASIS009'),
(10,'Moto','Bajaj','Rouser 200',2022,'E567FGH','CHASIS010'),
(11,'Auto','Renault','Clio',2021,'AF678GH','CHASIS011'),
(12,'Moto','Suzuki','GSX 150',2024,'F678GHI','CHASIS012'),
(13,'Auto','Peugeot','208',2020,'AG789HI','CHASIS013'),
(14,'Moto','Kawasaki','Ninja 300',2023,'G789HIJ','CHASIS014'),
(15,'Auto','Citroen','C3',2022,'AH890IJ','CHASIS015');

-- =====================================
-- 4. TABLA TIPOS DE SEGUROS
-- =====================================

CREATE TABLE tipos_seguros (
    id_tipo_seguro INT AUTO_INCREMENT PRIMARY KEY,
    nombre_seguro VARCHAR(80) NOT NULL,
    descripcion VARCHAR(200),
    cobertura VARCHAR(80),
    precio_base_mensual DECIMAL(12,2) NOT NULL
);

INSERT INTO tipos_seguros
(nombre_seguro, descripcion, cobertura, precio_base_mensual)
VALUES
('Responsabilidad civil auto','Danos a terceros','Terceros',18000),
('Terceros completo auto','Cobertura ampliada','Terceros completo',28000),
('Todo riesgo auto','Cobertura amplia con franquicia segun plan','Todo riesgo',55000),
('Responsabilidad civil moto','Danos a terceros','Terceros',9000),
('Robo e incendio moto','Proteccion contra robo e incendio','Robo e incendio',14000),
('Terceros completo moto','Cobertura ampliada','Terceros completo',18000),
('Robo e incendio auto','Proteccion contra robo e incendio','Robo e incendio',24000),
('Todo riesgo moto','Cobertura amplia segun plan','Todo riesgo',30000),
('Auto basico','Proteccion basica a terceros','Terceros',16000),
('Moto basica','Proteccion basica a terceros','Terceros',8000),
('Cobertura integral auto','Proteccion completa para auto','Integral',62000),
('Cobertura integral moto','Proteccion completa para moto','Integral',34000),
('Asistencia vial auto','Auxilio por accidente y grua','Asistencia',12000),
('Asistencia vial moto','Auxilio por accidente y grua','Asistencia',9500),
('Proteccion juridica auto','Cobertura legal por incidentes','Juridica',11000);

-- =====================================
-- 5. TABLA POLIZAS
-- =====================================

CREATE TABLE polizas (
    id_poliza INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    id_vehiculo INT NOT NULL,
    id_tipo_seguro INT NOT NULL,
    id_empleado INT NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    precio_mensual DECIMAL(12,2) NOT NULL,
    estado ENUM('Activa','Vencida','Cancelada') NOT NULL,
    FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente),
    FOREIGN KEY (id_vehiculo) REFERENCES vehiculos(id_vehiculo),
    FOREIGN KEY (id_tipo_seguro) REFERENCES tipos_seguros(id_tipo_seguro),
    FOREIGN KEY (id_empleado) REFERENCES empleados(id_empleado)
);

INSERT INTO polizas
(id_cliente, id_vehiculo, id_tipo_seguro, id_empleado, fecha_inicio, fecha_fin, precio_mensual, estado)
VALUES
(1,1,2,2,'2026-01-01','2026-12-31',28000,'Activa'),
(2,2,4,4,'2026-01-05','2027-01-04',9000,'Activa'),
(3,3,1,5,'2026-02-01','2027-01-31',18000,'Activa'),
(4,4,6,8,'2026-02-10','2027-02-09',18000,'Activa'),
(5,5,3,2,'2026-03-01','2027-02-28',55000,'Activa'),
(6,6,5,4,'2026-03-15','2027-03-14',14000,'Activa'),
(7,7,9,5,'2026-04-01','2027-03-31',16000,'Activa'),
(8,8,4,8,'2026-04-12','2027-04-11',9000,'Activa'),
(9,9,7,2,'2026-05-01','2027-04-30',24000,'Activa'),
(10,10,10,4,'2026-05-20','2027-05-19',8000,'Activa'),
(11,11,11,11,'2026-06-01','2027-05-31',62000,'Activa'),
(12,12,12,12,'2026-06-10','2027-06-09',34000,'Activa'),
(13,13,1,13,'2026-07-01','2027-06-30',18000,'Activa'),
(14,14,6,14,'2026-07-15','2027-07-14',18000,'Activa'),
(15,15,15,15,'2026-08-01','2027-07-31',11000,'Activa');

-- =====================================
-- 6. TABLA CUOTAS
-- =====================================

CREATE TABLE cuotas (
    id_cuota INT AUTO_INCREMENT PRIMARY KEY,
    id_poliza INT NOT NULL,
    numero_cuota INT NOT NULL,
    fecha_vencimiento DATE NOT NULL,
    importe DECIMAL(12,2) NOT NULL,
    estado ENUM('Pendiente','Pagada','Vencida') NOT NULL,
    FOREIGN KEY (id_poliza) REFERENCES polizas(id_poliza),
    UNIQUE (id_poliza, numero_cuota)
);

INSERT INTO cuotas
(id_poliza, numero_cuota, fecha_vencimiento, importe, estado)
VALUES
(1,1,'2026-02-01',28000,'Pagada'),
(2,1,'2026-02-05',9000,'Pagada'),
(3,1,'2026-03-01',18000,'Pagada'),
(4,1,'2026-03-10',18000,'Pendiente'),
(5,1,'2026-04-01',55000,'Pagada'),
(6,1,'2026-04-15',14000,'Pendiente'),
(7,1,'2026-05-01',16000,'Pagada'),
(8,1,'2026-05-12',9000,'Pendiente'),
(9,1,'2026-06-01',24000,'Pagada'),
(10,1,'2026-06-20',8000,'Pendiente'),
(11,1,'2026-07-01',62000,'Pagada'),
(12,1,'2026-07-10',34000,'Pendiente'),
(13,1,'2026-08-01',18000,'Pagada'),
(14,1,'2026-08-15',18000,'Pendiente'),
(15,1,'2026-09-01',11000,'Pagada');

-- =====================================
-- 7. TABLA PAGOS
-- =====================================

CREATE TABLE pagos (
    id_pago INT AUTO_INCREMENT PRIMARY KEY,
    id_cuota INT NOT NULL,
    fecha_pago DATE NOT NULL,
    medio_pago ENUM('Efectivo','Transferencia','Tarjeta de debito','Tarjeta de credito','Mercado Pago') NOT NULL,
    importe_pagado DECIMAL(12,2) NOT NULL,
    numero_comprobante VARCHAR(30) NOT NULL UNIQUE,
    FOREIGN KEY (id_cuota) REFERENCES cuotas(id_cuota)
);

INSERT INTO pagos
(id_cuota, fecha_pago, medio_pago, importe_pagado, numero_comprobante)
VALUES
(1,'2026-02-01','Efectivo',28000,'COMP001'),
(2,'2026-02-05','Transferencia',9000,'COMP002'),
(3,'2026-03-01','Tarjeta de debito',18000,'COMP003'),
(5,'2026-04-01','Mercado Pago',55000,'COMP004'),
(7,'2026-05-01','Tarjeta de credito',16000,'COMP005'),
(1,'2026-03-01','Transferencia',28000,'COMP006'),
(3,'2026-04-01','Efectivo',18000,'COMP007'),
(5,'2026-05-01','Mercado Pago',55000,'COMP008'),
(7,'2026-06-01','Transferencia',16000,'COMP009'),
(9,'2026-06-01','Tarjeta de debito',24000,'COMP010'),
(11,'2026-07-01','Tarjeta de credito',62000,'COMP011'),
(13,'2026-08-01','Transferencia',18000,'COMP012'),
(15,'2026-09-01','Mercado Pago',11000,'COMP013'),
(11,'2026-08-01','Efectivo',62000,'COMP014'),
(13,'2026-09-01','Tarjeta de debito',18000,'COMP015');

-- =====================================
-- 8. TABLA SINIESTROS
-- =====================================

CREATE TABLE siniestros (
    id_siniestro INT AUTO_INCREMENT PRIMARY KEY,
    id_poliza INT NOT NULL,
    id_empleado INT NOT NULL,
    fecha_siniestro DATE NOT NULL,
    tipo_siniestro VARCHAR(50) NOT NULL,
    descripcion VARCHAR(250),
    costo_estimado DECIMAL(12,2),
    estado ENUM('En revision','Aprobado','Cerrado','Rechazado') NOT NULL,
    FOREIGN KEY (id_poliza) REFERENCES polizas(id_poliza),
    FOREIGN KEY (id_empleado) REFERENCES empleados(id_empleado)
);

INSERT INTO siniestros
(id_poliza, id_empleado, fecha_siniestro, tipo_siniestro, descripcion, costo_estimado, estado)
VALUES
(1,6,'2026-02-15','Choque','Colision leve entre vehiculos',150000,'En revision'),
(2,6,'2026-02-20','Caida','Caida de motocicleta',80000,'Aprobado'),
(3,9,'2026-03-05','Robo','Robo parcial de accesorios',120000,'En revision'),
(4,6,'2026-03-12','Choque','Colision en interseccion',95000,'Cerrado'),
(5,9,'2026-04-10','Granizo','Danos en carroceria',250000,'Aprobado'),
(6,6,'2026-04-18','Caida','Caida sin terceros involucrados',60000,'Cerrado'),
(7,9,'2026-05-05','Cristales','Rotura de parabrisas',70000,'En revision'),
(8,6,'2026-05-15','Choque','Danos menores en motocicleta',45000,'Aprobado'),
(9,9,'2026-06-10','Robo','Intento de robo del vehiculo',180000,'En revision'),
(10,6,'2026-06-20','Accidente','Danos materiales leves',50000,'Cerrado'),
(11,11,'2026-07-05','Choque','Impacto frontal con pared',220000,'En revision'),
(12,12,'2026-07-12','Robo','Robo total de motocicleta',175000,'Aprobado'),
(13,13,'2026-08-02','Granizo','Danos en parabrisas y techo',130000,'Cerrado'),
(14,14,'2026-08-20','Accidente','Vuelco de vehiculo en carretera',90000,'En revision'),
(15,15,'2026-09-05','Cristales','Rotura de vidrio lateral',65000,'Aprobado');

-- =====================================
-- CONSULTA DE VERIFICACION
-- =====================================

SHOW TABLES;

SELECT 'clientes' AS tabla, COUNT(*) AS registros FROM clientes
UNION ALL
SELECT 'empleados', COUNT(*) FROM empleados
UNION ALL
SELECT 'vehiculos', COUNT(*) FROM vehiculos
UNION ALL
SELECT 'tipos_seguros', COUNT(*) FROM tipos_seguros
UNION ALL
SELECT 'polizas', COUNT(*) FROM polizas
UNION ALL
SELECT 'cuotas', COUNT(*) FROM cuotas
UNION ALL
SELECT 'pagos', COUNT(*) FROM pagos
UNION ALL
SELECT 'siniestros', COUNT(*) FROM siniestros;