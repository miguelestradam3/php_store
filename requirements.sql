/*
Crear base de datos
*/

CREATE DATABASE tienda;

/*
Crear tabla de la base de datos
*/


CREATE TABLE productos (
    codigo INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    detalle TEXT NOT NULL,
    imagen VARCHAR(255) NOT NULL,
    precio DOUBLE NOT NULL
);

/*
Contenido de la tabla de la base de datos
*/

INSERT INTO productos
    (nombre, detalle, imagen, precio)
VALUES
    ('Computadora portátil', 'Computadora portátil de 15.6 pulgadas con 8GB de RAM y 512GB SSD', 'computadora-portatil.jpg', 799.99),
    ('Ratón inalámbrico', 'Ratón inalámbrico ergonómico con receptor USB', 'raton-inalambrico.jpg', 24.99),
    ('Teclado mecánico', 'Teclado mecánico con iluminación RGB y conexión USB', 'teclado-mecanico.jpg', 59.99),
    ('Cargador USB-C', 'Cargador de pared USB-C de carga rápida de 65W', 'cargador-usb-c.jpg', 34.99),
    ('Auriculares Bluetooth', 'Auriculares inalámbricos con aislamiento de ruido', 'auriculares-bluetooth.jpg', 89.99),
    ('Teléfono inteligente', 'Teléfono inteligente de 6.5 pulgadas con 128GB de almacenamiento', 'telefono-inteligente.jpg', 499.99),
    ('Memoria USB', 'Memoria USB 3.0 con capacidad de 64GB', 'memoria-usb.jpg', 12.99),
    ('Disco duro externo', 'Disco duro externo portátil USB con capacidad de 1TB', 'disco-duro-externo.jpg', 69.99),
    ('Cámara web', 'Cámara web Full HD 1080p con micrófono incorporado', 'camara-web.jpg', 49.99),
    ('Monitor', 'Monitor LED de 24 pulgadas con resolución Full HD', 'monitor.jpg', 179.99);