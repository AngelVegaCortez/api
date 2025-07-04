CREATE DATABASE IF NOT EXISTS polleria;
USE polleria;

DROP TABLE IF EXISTS Usuario, Cliente, Administrador, Producto, MateriaPrima, Compra, DetalleCompra;

CREATE TABLE Usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellidoPaterno VARCHAR(100) NOT NULL,
    apellidoMaterno VARCHAR(100),
    correo VARCHAR(255) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    estatus BOOLEAN DEFAULT 1,
    direccion VARCHAR(255),
    nombreUsuario VARCHAR(100) UNIQUE NOT NULL,
    tipoUsuario ENUM('Cliente', 'Administrador') NOT NULL,
    fechaAlta DATETIME DEFAULT CURRENT_TIMESTAMP,
    fechaModificacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    fechaBaja DATETIME NULL,
    fotoPerfil VARCHAR(255)
);

CREATE TABLE Cliente (
    id INT PRIMARY KEY,
    telefono VARCHAR(20),
    codigoActivacion VARCHAR(50),
    activado BOOLEAN DEFAULT 0,
    FOREIGN KEY (id) REFERENCES Usuario(id) ON DELETE CASCADE
);

CREATE TABLE Administrador (
    id INT PRIMARY KEY,
    privilegios VARCHAR(255),
    FOREIGN KEY (id) REFERENCES Usuario(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE Producto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT,
    stock INT DEFAULT 0,
    precio DECIMAL(10,2) NOT NULL,
    descuento DECIMAL(5,2) DEFAULT 0,
    categoria VARCHAR(100),
    activo BOOLEAN DEFAULT 1,
    idAdministrador INT NULL,
    FOREIGN KEY (idAdministrador) REFERENCES Administrador(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE MateriaPrima (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    cantidad DECIMAL(10,2) NOT NULL,
    cantidadMax DECIMAL(10,2) NOT NULL,
    umbral DECIMAL(10,2) NOT NULL,
    unidadMedida VARCHAR(50),
    consumoPromedioDiario DECIMAL (10,2) DEFAULT 1,
    idAdministrador INT NULL,
    FOREIGN KEY (idAdministrador) REFERENCES Administrador(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE Compra (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    confirmacion BOOLEAN DEFAULT 0,
    medioPago VARCHAR(50),
    total DECIMAL(10,2) NOT NULL,
    idCliente INT NOT NULL,
    FOREIGN KEY (idCliente) REFERENCES Cliente(id) ON DELETE CASCADE
);

CREATE TABLE DetalleCompra (
    idCompra INT,
    idProducto INT,
    cantidad INT NOT NULL,
    precioUnitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (idCompra, idProducto),
    FOREIGN KEY (idCompra) REFERENCES Compra(id) ON DELETE CASCADE,
    FOREIGN KEY (idProducto) REFERENCES Producto(id) ON DELETE CASCADE
);
-- INSERT INTO Compra (fecha, confirmacion, medioPago, total, idCliente) VALUE (timestamp '2023-10-01 12:00:00', 0, 'Tarjeta de Crédito', 150.00, 1);

-- Insertar usuarios administradores
INSERT INTO Usuario (nombre, apellidoPaterno, apellidoMaterno, correo, contrasena, direccion, nombreUsuario, tipoUsuario)
VALUES
-- Admins
('Jose Angel', 'Vega', 'Cortez', 'jvegac2001@alumno.ipn.mx', 'admin123', 'SEXCOM', 'stailz', 'Administrador'),
('Cinthya', 'Jiménez', 'García', 'cjimenezg2000@alumno.ipn.mx', 'admin123', 'ESCOM', 'zzzinthya', 'Administrador'),
('Omar Jesús', 'Vazquez', 'Sanchez', 'ovazquezs2000@alumno.ipn.mx', 'admin123', 'ESCOM', 'boringchange', 'Administrador'),
('Joshua Iván', 'Zavaleta', 'Guerrero', 'jzavaletag2000@alumno.ipn.mx', 'admin123', 'ESCOM', 'diverzg5', 'Administrador'),
('Kitzia María', 'Araujo', 'Pérez', 'karaujop2000@alumno.ipn.mx', 'admin123', 'ESCOM', 'kira', 'Administrador'),
('Axel Eduardo', 'Martínez', 'Granados', 'amartinezg2026@alumno.ipn.mx', 'admin123', 'ESCOM', 'zalgock', 'Administrador'),
('Luis Axel', 'Zarate', 'Lozano', 'lzaratel2000@alumno.ipn.mx', 'admin123', 'ESCOM', 'batcom', 'Administrador'),
-- Clientes
('Sara', 'Lagunas', 'Orduña', 'slagunaso2000@alumno.ipn.mx', 'cliente123', 'ESM', 'sarabola', 'Cliente'),
('Ana', 'Ramírez', 'Torres', 'ana@correo.com', 'cliente123', 'Calle 1', 'anaram', 'Cliente'),
('Luis', 'Hernández', 'Pérez', 'luis@correo.com', 'cliente123', 'Calle 2', 'luisito', 'Cliente'),
('María', 'López', 'Gómez', 'maria@correo.com', 'cliente123', 'Calle 3', 'marylg', 'Cliente'),
('Pedro', 'Sánchez', 'Rodríguez', 'pedro@correo.com', 'cliente123', 'Calle 4', 'pedro_s', 'Cliente'),
('Lucía', 'García', 'Martínez', 'lucia@correo.com', 'cliente123', 'Calle 5', 'lucym', 'Cliente'),
('Jorge', 'Morales', 'Núñez', 'jorge@correo.com', 'cliente123', 'Calle 6', 'jorgem', 'Cliente'),
('Sandra', 'Reyes', 'Díaz', 'sandra@correo.com', 'cliente123', 'Calle 7', 'sandyd', 'Cliente'),
('Ricardo', 'Ortiz', 'Flores', 'ricardo@correo.com', 'cliente123', 'Calle 8', 'rickyf', 'Cliente'),
('Carmen', 'Torres', 'Castillo', 'carmen@correo.com', 'cliente123', 'Calle 9', 'carment', 'Cliente'),
('David', 'Mendoza', 'Ruiz', 'david@correo.com', 'cliente123', 'Calle 10', 'davidr', 'Cliente')
;

-- Insertar administradores
INSERT INTO Administrador (id, privilegios)
VALUES 
(1, 'Gestión completa'),
(2, 'Gestión de productos y materia prima');

-- Insertar productos
INSERT INTO Producto (nombre, descripcion, stock, precio, descuento, categoria, activo, idAdministrador)
VALUES
-- Tacos
('Taco de pechuga asada', 'Taco de tortilla caliente con pechuga de pollo asada y especias.', 100, 15.00, 0.00, 'Tacos', 1, 1),
('Taco de pollo al pastor', 'Taco de pollo adobado estilo pastor con piña y cebolla.', 100, 15.00, 0.00, 'Tacos', 1, 2),
('Taco de pollo BBQ', 'Taco de pollo con salsa BBQ dulce y ahumada.', 100, 15.00, 0.00, 'Tacos', 1, 1),
('Taco de boneless con aderezo ranch', 'Taco de boneless crujiente con aderezo ranch.', 100, 18.00, 0.00, 'Tacos', 1, 2),
('Taco de Pollo a la Parrilla', 'Tortilla de maíz con pollo marinado a la parrilla, cebolla, cilantro y limón.', 50, 22.00, 0.00, 'Tacos', 1, 2),
('Taco de Pollo con Queso Fundido', 'Pollo deshebrado con mezcla de quesos derretidos en tortilla de harina.', 50, 28.00, 0.00, 'Tacos', 1, 2),

-- Pollos Enteros y Medios
('Pollo rostizado entero', 'Pollo entero rostizado con especias especiales, ideal para compartir.', 30, 150.00, 0.00, 'Pollos', 1, 1),
('Pollo al carbón entero', 'Pollo entero al carbón con sabor ahumado tradicional.', 30, 160.00, 0.00, 'Pollos', 1, 2),
('Medio pollo rostizado', 'Mitad de pollo rostizado, jugoso y especiado.', 40, 85.00, 0.00, 'Pollos', 1, 1),
('Medio pollo BBQ', 'Mitad de pollo rostizado con salsa BBQ.', 40, 90.00, 0.00, 'Pollos', 1, 2),
('Pollo Asado Tradicional', 'Pollo entero marinado en especias caseras y asado lentamente al carbón.', 50, 180.00, 0.00, 'Pollos', 1, 1),
('Pollo Picoso al Chipotle', 'Pollo entero con salsa de chipotle y ajo rostizado. Ideal para los amantes del picante.', 50, 190.00, 0.00, 'Pollos', 1, 1),

-- Alitas
('6 Alitas clásicas', 'Orden de 6 alitas crujientes con salsa a elegir.', 60, 55.00, 0.00, 'Alitas', 1, 1),
('12 Alitas variadas', 'Orden de 12 alitas con hasta dos sabores.', 40, 100.00, 0.00, 'Alitas', 1, 2),
('Alitas Búfalo', '8 piezas con salsa búfalo casera, ligeramente picante.', 50, 90.00, 0.00, 'Alitas', 1, 1),
('Alitas Mango Habanero', 'Perfecto balance entre dulce y picante, glaseadas al momento. 8 piezas incluidas.', 50, 95.00, 0.00, 'Alitas', 1, 2),
('Alitas Ajo Parmesano', 'Sabor intenso a mantequilla, ajo y queso parmesano rallado. 8 piezas incluidas.', 50, 100.00, 0.00, 'Alitas', 1, 1),

-- Boneless
('6 Boneless BBQ', 'Boneless de pollo con salsa BBQ.', 60, 60.00, 10.00, 'Boneless', 1, 1),
('6 Boneless Búfalo', 'Boneless de pollo con salsa búfalo.', 60, 60.00, 5.00, 'Boneless', 1, 2),
('6 Boneless Mango Habanero', 'Boneless con salsa mango habanero.', 60, 60.00, 15.00, 'Boneless', 1, 1),
('12 Boneless mixtos', 'Boneless surtidos con dos tipos de salsa.', 40, 110.00, 9.99, 'Boneless', 1, 2),
('Boneless Clásicos (10 pzas)', 'Cubos de pechuga empanizados, crujientes por fuera y jugosos por dentro.', 50, 85.00, 3.00, 'Boneless', 1, 2),
('Boneless con Salsa Búfalo (10 pzas)', 'Empanizados y bañados en salsa búfalo, servidos con aderezo ranch.', 50, 90.00, 7.00, 'Boneless', 1, 1),
('Boneless BBQ (10 pzas)', 'Cubiertos con una capa de BBQ dulce y ahumada.', 50, 90.00, 25.00, 'Boneless', 1, 2),

-- Combos
('Combo Estudiantil', '3 tacos surtidos de pollo con refresco.', 50, 50.00, 0.00, 'Combos', 1, 1),
('Combo Familiar', 'Pollo entero, arroz y tortillas para compartir.', 30, 180.00, 0.00, 'Combos', 1, 2),
('Combo Alitas', '6 alitas, papas gajo y refresco.', 40, 75.00, 0.00, 'Combos', 1, 1),
('Combo Familiar "El pollo pollon"', '1 pollo entero, 1 porción de papas, tortillas, salsas y 1 litro de agua fresca.', 50, 270.00, 0.00, 'Combos', 1, 1),
('Combo Alitas y Boneless', '6 alitas + 6 boneless + papas gajo + aderezo a elegir.', 50, 145.00, 0.00, 'Combos', 1, 2),
('Combo Tacos & Refresco', '3 tacos de pollo a elegir + 1 bebida 355 ml.', 50, 85.00, 0.00, 'Combos', 1, 1),

-- Complementos
('Arroz rojo', 'Arroz rojo casero con verduritas.', 100, 15.00, 0.00, 'Complementos', 1, 1),
('Arroz blanco', 'Arroz blanco suave y esponjoso.', 100, 15.00, 0.00, 'Complementos', 1, 2),
('Papas gajo', 'Papas al horno en corte gajo, sazonadas.', 80, 20.00, 0.00, 'Complementos', 1, 1),
('Ensalada fresca', 'Ensalada con lechuga, jitomate y aderezo.', 60, 25.00, 0.00, 'Complementos', 1, 2),
('Tortillas', 'Orden de tortillas recién hechas.', 100, 10.00, 0.00, 'Complementos', 1, 1),
('Tortillas de Maíz Azul', '1 KG de tortillas hechas a mano con maíz criollo azul. ', 50, 30.00, 0.00, 'Complementos', 1, 2),
('Papas Gajo con Especias', 'Papas crujientes sazonadas con especias de la casa.', 50, 45.00, 0.00, 'Complementos', 1, 1),
('Arroz a la Mexicana', 'Arroz rojo con verduras, ideal para acompañar el pollo.', 50, 35.00, 0.00, 'Complementos', 1, 2),
('Ensalada Fresca de Col', 'Con zanahoria, col morada y aderezo ligero.', 50, 30.00, 0.00, 'Complementos', 1, 1),

-- Aderezos
('Aderezo ranch', 'Aderezo ranch cremoso y fresco.', 100, 5.00, 0.00, 'Aderezos', 1, 1),
('Aderezo búfalo', 'Salsa tipo búfalo, picante y ácida.', 100, 5.00, 0.00, 'Aderezos', 1, 2),
('Aderezo BBQ', 'Salsa BBQ dulce y ahumada.', 100, 5.00, 0.00, 'Aderezos', 1, 1),
('Aderezo mango habanero', 'Salsa frutal con toque picante.', 100, 5.00, 0.00, 'Aderezos', 1, 2),
('Guacamole Cremoso', 'Hecho al momento con aguacate, limón y pico de gallo.', 50, 15.00, 0.00, 'Aderezos', 1, 2),

-- Bebidas
('Refresco en lata', 'Refresco en lata frío de varios sabores.', 120, 15.00, 0.00, 'Bebidas', 1, 1),
('Agua de sabor 500ml', 'Agua fresca del día (jamaica, horchata, etc).', 100, 12.00, 0.00, 'Bebidas', 1, 2),
('Agua embotellada', 'Botella de agua purificada 500ml.', 100, 10.00, 0.00, 'Bebidas', 1, 1),
('Agua de Jamaica (1 litro)', 'Refrescante y ligeramente acidita.', 50, 25.00, 0.00, 'Bebidas', 1, 1),
('Agua de Limón con Chía (1 litro)', 'Hidratante y natural, endulzada al gusto.', 50, 25.00, 0.00, 'Bebidas', 1, 2),
('Refresco en Lata (355 ml)', 'Variedad de sabores disponibles.', 50, 20.00, 0.00, 'Bebidas', 1, 1),
('Cerveza Artesanal de la Casa (355 ml)', 'Perfecta para un rico pollo a la parrilla.', 50, 50.00, 0.00, 'Bebidas', 1, 2);

-- Insertar materia prima
INSERT INTO MateriaPrima (nombre, precio, cantidad, cantidadMax, umbral, unidadMedida, consumoPromedioDiario, idAdministrador)
VALUES
    ('Pollo vivo', 40.00, 500, 1000, 200, 'unidades', 5, 1),
    ('Sal', 18.00, 30.00, 200.00, 40.00, 'kg', 1, 2),
    ('Pimienta', 22.00, 50.00, 100.00, 20.00, 'kg', 0.5, 2),
    ('Empanizador', 35.00, 80.00, 150.00, 30.00, 'kg', 0.5, 2),
    ('Aceite vegetal', 28.00, 200.00, 300.00, 60.00, 'litros', 3, 1),
    ('Especias para alitas', 260.00, 30.00, 50.00, 10.00, 'kg', 1.5, 1),
    ('Embalaje para pollo', 4.50, 1000, 2000, 400, 'unidades', 4,2),
    ('Ajo en polvo', 183.00, 40.00, 50.00, 10.00, 'kg', 3,1),
    ('Paprika', 80.00, 25.00, 50.00, 10.00, 'kg', 1, 2),
    ('Comino', 189.00, 20.00, 50.00, 10.00, 'kg', 1, 2),
    ('Tortillas de maíz', 13.50, 1000, 2000, 400, 'unidades', 320, 2),
    ('Arroz', 150.00, 500.00, 500.00, 100.00, 'kg', 5,1),
    ('Pan para torta', 2.50, 50, 100, 20, 'unidades', 10,2),
    ('Chiles secos', 169.00, 35.00, 40.00, 8.00, 'kg', 13,1),
    ('Chipotle adobado', 65.00, 3.00, 40.00, 8.00, 'kg', 1,2),
    ('Salsa BBQ', 55.00, 50.00, 80.00, 16.00, 'litros', 3,1),
    ('Jitomate', 18.00, 80.00, 100.00, 20.00, 'kg', 10,1),
    ('Cebolla', 14.00, 60.00, 60.00, 12.00, 'kg', 3,1),
    ('Chile habanero', 120.00, 2.00, 40.00, 8.00, 'kg', 1,2);

-- Clientes
INSERT INTO Cliente (id, telefono, codigoActivacion, activado) VALUES (8, '5517606318', 'ACT0008', 1);
INSERT INTO Cliente (id, telefono, codigoActivacion, activado) VALUES (9, '5571212002', 'ACT0009', 1);
INSERT INTO Cliente (id, telefono, codigoActivacion, activado) VALUES (10, '5539827672', 'ACT0010', 0);
INSERT INTO Cliente (id, telefono, codigoActivacion, activado) VALUES (11, '5526499788', 'ACT0011', 0);
INSERT INTO Cliente (id, telefono, codigoActivacion, activado) VALUES (12, '5597802279', 'ACT0012', 1);
INSERT INTO Cliente (id, telefono, codigoActivacion, activado) VALUES (13, '5514802608', 'ACT0013', 0);
INSERT INTO Cliente (id, telefono, codigoActivacion, activado) VALUES (14, '5568661324', 'ACT0014', 0);
INSERT INTO Cliente (id, telefono, codigoActivacion, activado) VALUES (15, '5576577432', 'ACT0015', 0);
INSERT INTO Cliente (id, telefono, codigoActivacion, activado) VALUES (16, '5511643113', 'ACT0016', 1);
INSERT INTO Cliente (id, telefono, codigoActivacion, activado) VALUES (17, '5579726997', 'ACT0017', 0);

-- Compras
INSERT INTO Compra (id, fecha, confirmacion, medioPago, total, idCliente) VALUES (1, '2025-06-02 00:00:00', 1, 'Efectivo', 380.76, 9);
INSERT INTO Compra (id, fecha, confirmacion, medioPago, total, idCliente) VALUES (2, '2025-06-03 00:00:00', 1, 'Tarjeta', 345.42, 9);
INSERT INTO Compra (id, fecha, confirmacion, medioPago, total, idCliente) VALUES (3, '2025-06-04 00:00:00', 1, 'Tarjeta', 380.52, 12);
INSERT INTO Compra (id, fecha, confirmacion, medioPago, total, idCliente) VALUES (4, '2025-06-05 00:00:00', 1, 'Transferencia', 192.96, 16);
INSERT INTO Compra (id, fecha, confirmacion, medioPago, total, idCliente) VALUES (5, '2025-06-06 00:00:00', 1, 'Tarjeta', 386.34, 13);
INSERT INTO Compra (id, fecha, confirmacion, medioPago, total, idCliente) VALUES (6, '2025-06-07 00:00:00', 1, 'Transferencia', 222.54, 15);
INSERT INTO Compra (id, fecha, confirmacion, medioPago, total, idCliente) VALUES (7, '2025-06-08 00:00:00', 1, 'Transferencia', 433.18, 8);
INSERT INTO Compra (id, fecha, confirmacion, medioPago, total, idCliente) VALUES (8, '2025-06-02 00:00:00', 1, 'Transferencia', 302.29, 10);
INSERT INTO Compra (id, fecha, confirmacion, medioPago, total, idCliente) VALUES (9, '2025-06-03 00:00:00', 1, 'Transferencia', 279.66, 14);
INSERT INTO Compra (id, fecha, confirmacion, medioPago, total, idCliente) VALUES (10, '2025-06-04 00:00:00', 1, 'Transferencia', 364.08, 9);
INSERT INTO Compra (id, fecha, confirmacion, medioPago, total, idCliente) VALUES (11, '2025-06-05 00:00:00', 1, 'Tarjeta', 410.80, 9);
INSERT INTO Compra (id, fecha, confirmacion, medioPago, total, idCliente) VALUES (12, '2025-06-06 00:00:00', 1, 'Efectivo', 303.10, 13);
INSERT INTO Compra (id, fecha, confirmacion, medioPago, total, idCliente) VALUES (13, '2025-06-07 00:00:00', 1, 'Transferencia', 214.80, 9);
INSERT INTO Compra (id, fecha, confirmacion, medioPago, total, idCliente) VALUES (14, '2025-06-08 00:00:00', 1, 'Transferencia', 418.90, 14);
INSERT INTO Compra (id, fecha, confirmacion, medioPago, total, idCliente) VALUES (15, '2025-06-09 00:00:00', 1, 'Transferencia', 458.09, 13);
INSERT INTO Compra (id, fecha, confirmacion, medioPago, total, idCliente) VALUES (16, '2025-06-10 00:00:00', 1, 'Transferencia', 413.20, 16);
INSERT INTO Compra (id, fecha, confirmacion, medioPago, total, idCliente) VALUES (17, '2025-06-11 00:00:00', 1, 'Tarjeta', 342.75, 8);

-- INSERTS PARA TABLA DETALLECOMPRA
INSERT INTO DetalleCompra (idCompra, idProducto, cantidad, precioUnitario, subtotal) VALUES (1, 6, 1, 66.65, 66.65);
INSERT INTO DetalleCompra (idCompra, idProducto, cantidad, precioUnitario, subtotal) VALUES (2, 19, 5, 76.21, 381.05);
INSERT INTO DetalleCompra (idCompra, idProducto, cantidad, precioUnitario, subtotal) VALUES (3, 49, 1, 67.41, 67.41);
INSERT INTO DetalleCompra (idCompra, idProducto, cantidad, precioUnitario, subtotal) VALUES (4, 6, 2, 117.21, 234.42);
INSERT INTO DetalleCompra (idCompra, idProducto, cantidad, precioUnitario, subtotal) VALUES (4, 36, 4, 35.45, 141.80);
INSERT INTO DetalleCompra (idCompra, idProducto, cantidad, precioUnitario, subtotal) VALUES (5, 2, 3, 82.48, 247.44);
INSERT INTO DetalleCompra (idCompra, idProducto, cantidad, precioUnitario, subtotal) VALUES (6, 40, 5, 108.95, 544.75);
INSERT INTO DetalleCompra (idCompra, idProducto, cantidad, precioUnitario, subtotal) VALUES (7, 43, 5, 82.98, 414.90);
INSERT INTO DetalleCompra (idCompra, idProducto, cantidad, precioUnitario, subtotal) VALUES (7, 46, 5, 101.82, 509.10);
INSERT INTO DetalleCompra (idCompra, idProducto, cantidad, precioUnitario, subtotal) VALUES (8, 25, 1, 69.33, 69.33);
INSERT INTO DetalleCompra (idCompra, idProducto, cantidad, precioUnitario, subtotal) VALUES (9, 9, 1, 149.21, 149.21);
INSERT INTO DetalleCompra (idCompra, idProducto, cantidad, precioUnitario, subtotal) VALUES (9, 49, 1, 29.04, 29.04);
INSERT INTO DetalleCompra (idCompra, idProducto, cantidad, precioUnitario, subtotal) VALUES (9, 28, 2, 107.44, 214.88);
INSERT INTO DetalleCompra (idCompra, idProducto, cantidad, precioUnitario, subtotal) VALUES (10, 31, 1, 125.89, 125.89);
INSERT INTO DetalleCompra (idCompra, idProducto, cantidad, precioUnitario, subtotal) VALUES (10, 15, 5, 59.95, 299.75);
INSERT INTO DetalleCompra (idCompra, idProducto, cantidad, precioUnitario, subtotal) VALUES (10, 39, 5, 64.60, 323.00);
