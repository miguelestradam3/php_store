/*
Crear base de datos
*/
CREATE DATABASE IF NOT EXISTS tienda;

/*
Seleccionar la base de datos para asegurar que las tablas se creen aquí
*/
USE tienda;

/*
Crear tabla de la base de datos
*/
CREATE TABLE IF NOT EXISTS productos (
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
    ('Computadora portátil', 'Computadora portátil de 15.6 pulgadas con 8GB de RAM y 512GB SSD', 'assets/img/computadora.png', 799.99),
    ('Ratón inalámbrico', 'Ratón inalámbrico ergonómico con receptor USB', 'assets/img/mouse.png', 24.99),
    ('Teclado mecánico', 'Teclado mecánico con iluminación RGB y conexión USB', 'assets/img/keyboard.png', 59.99),
    ('Cargador USB-C', 'Cargador de pared USB-C de carga rápida de 65W', 'assets/img/usbc.png', 34.99),
    ('Auriculares Bluetooth', 'Auriculares inalámbricos con aislamiento de ruido', 'assets/img/headphones.png', 89.99),
    ('Teléfono inteligente', 'Teléfono inteligente de 6.5 pulgadas con 128GB de almacenamiento', 'assets/img/iphone.png', 499.99),
    ('Memoria USB', 'Memoria USB 3.0 con capacidad de 64GB', 'assets/img/usb.png', 12.99),
    ('Disco duro externo', 'Disco duro externo portátil USB con capacidad de 1TB', 'assets/img/ssd.png', 69.99),
    ('Cámara web', 'Cámara web Full HD 1080p con micrófono incorporado', 'assets/img/webcamera.png', 49.99),
    ('Monitor', 'Monitor LED de 24 pulgadas con resolución Full HD', 'assets/img/monitor.png', 179.99);

/*
Tabla de contactos
*/

/*1. Asegúramos el usar la base de datos*/

USE tienda;

/*2. Crear la tabla para el formulario de consultas*/

CREATE TABLE IF NOT EXISTS consultas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    telefono VARCHAR(30) NOT NULL,
    correo VARCHAR(150) NOT NULL,
    detalle TEXT NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

/*
EXTRA: Tablas para guardar las compras de cada usuario
*/

USE tienda;

CREATE TABLE IF NOT EXISTS ordenes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(150) NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2) NOT NULL
);

CREATE TABLE IF NOT EXISTS detalle_orden (
    id INT AUTO_INCREMENT PRIMARY KEY,
    orden_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,

    CONSTRAINT fk_detalle_orden
        FOREIGN KEY (orden_id)
        REFERENCES ordenes(id)
        ON DELETE CASCADE
);