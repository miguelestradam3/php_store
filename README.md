# 🛒 Sistema de carrito de compras en PHP

Aplicación web desarrollada con **PHP, MySQL, HTML, CSS y Bootstrap** que permite mostrar productos desde una base de datos, agregarlos a un carrito de compras y registrar pedidos con los datos de contacto del cliente.

El proyecto utiliza sesiones de PHP para mantener temporalmente el carrito y MySQL para almacenar las órdenes y sus detalles.

## ✨ Características

- Mostrar productos almacenados en MySQL.
- Presentar el nombre, la descripción, la imagen y el precio de cada producto.
- Agregar productos al carrito.
- Mantener el carrito mediante sesiones de PHP.
- Consultar los productos seleccionados, las cantidades, los subtotales y el total.
- Actualizar cantidades y eliminar productos del carrito.
- Vaciar el carrito completo.
- Solicitar un correo electrónico de contacto durante el proceso de compra.
- Guardar los pedidos y sus productos en tablas relacionadas de MySQL.
- Mostrar una página de confirmación con el número de pedido, el correo de contacto y el total.
- Utilizar Bootstrap para una interfaz adaptable a distintos tamaños de pantalla.
- Usar consultas preparadas para las operaciones SQL que reciben datos del usuario.

## 🧰 Tecnologías utilizadas

- **PHP** — lógica del servidor y manejo de sesiones.
- **MySQL** — almacenamiento de productos, pedidos y detalles de pedidos.
- **MySQLi** — conexión y consultas a la base de datos.
- **HTML5** — estructura de las páginas.
- **CSS3** — estilos de la interfaz.
- **Bootstrap** — diseño adaptable y componentes visuales.
- **Bootstrap Icons** — iconografía.

## 📁 Estructura del proyecto

La estructura puede variar según los archivos y recursos del proyecto. Una organización de referencia es:

```text
proyecto/
├── index.php                 # Catálogo de productos
├── connection.php            # Conexión con MySQL
├── cart.php                  # Vista del carrito
├── add_to_cart.php           # Agrega un producto al carrito
├── update_cart.php            # Actualiza la cantidad
├── remove_from_cart.php      # Elimina un producto
├── clear_cart.php            # Vacía el carrito
├── checkout.php              # Formulario para finalizar la compra
├── process_order.php         # Valida y guarda el pedido
├── order_confirmation.php    # Confirmación del pedido
└── assets/
    ├── css/
    ├── img/
    ├── javascript/
    └── vendor/
        ├── bootstrap/
        └── bootstrap-icons/
```

> Los nombres del árbol son una referencia basada en el flujo desarrollado. Verifica que los archivos estén presentes en tu proyecto y ajusta la lista si tu estructura es diferente.

## 💻 Requisitos

Antes de ejecutar el proyecto, asegúrate de contar con:

- PHP 8.x o una versión compatible con el código.
- MySQL o MariaDB.
- Un servidor web local, por ejemplo Apache mediante XAMPP, WAMP o Laragon.
- Un navegador web.
- Las extensiones de PHP `mysqli` y `session` habilitadas.

## ⚙️ Configuración e instalación

### 1. Descargar o clonar el proyecto

Coloca los archivos dentro del directorio público de tu servidor local. Por ejemplo, con XAMPP:

```text
C:\xampp\htdocs\proyecto
```

### 2. Crear la base de datos

Abre phpMyAdmin o tu cliente MySQL y crea una base de datos:

```sql
CREATE DATABASE tienda
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
```

### 3. Crear las tablas

El catálogo necesita una tabla `productos` con los campos que utiliza el código, incluyendo `codigo`, `nombre`, `detalle`, `imagen` y `precio`.

Ejemplo de estructura:

```sql
USE tienda;

CREATE TABLE IF NOT EXISTS productos (
    codigo INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    detalle TEXT,
    imagen VARCHAR(255),
    precio DECIMAL(10,2) NOT NULL
);
```

Para registrar los `contactos` o `consultas` de los clientes:

```sql
USE tienda;

CREATE TABLE IF NOT EXISTS consultas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    telefono VARCHAR(30) NOT NULL,
    correo VARCHAR(150) NOT NULL,
    detalle TEXT NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

Para registrar pedidos, crea las tablas `ordenes` y `detalle_orden`:

```sql
CREATE TABLE IF NOT EXISTS ordenes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(150) NOT NULL,
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2) NOT NULL
);

CREATE TABLE IF NOT EXISTS detalle_orden (
    id INT AUTO_INCREMENT PRIMARY KEY,
    orden_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,

    CONSTRAINT fk_detalle_orden_orden
        FOREIGN KEY (orden_id)
        REFERENCES ordenes(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_detalle_orden_producto
        FOREIGN KEY (producto_id)
        REFERENCES productos(codigo)
);
```

> Si ya creaste estas tablas, no vuelvas a ejecutar los comandos `CREATE TABLE`. Comprueba que los nombres y tipos de las columnas coincidan con los que utiliza tu código PHP.

### 4. Configurar la conexión

Edita `connection.php` con los datos de tu entorno local. Ejemplo con MySQLi:

```php
<?php
    // 1. Configuramos MySQLi para que lance excepciones ante cualquier fallo
    mysqli_report(MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ERROR);

    // Variables de conexión
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "tienda";

    try {
        // 2. Intentamos realizar la conexión dentro del bloque try
        $conn = new mysqli($servername, $username, $password, $dbname);
        
        // Configuramos el juego de caracteres de forma segura
        mysqli_set_charset($conn, "utf8mb4");
        
        // Verificamos explícitamente y lanzamos la excepción si hay un error de conexión
        if ($conn->connect_error != null) {
            throw new Exception("Error de conexión: " . $conn->connect_error);
        }

    } catch (Exception $e) {
        // 3. Registramos el error real en el archivo de log del servidor de forma silenciosa
        error_log("Fallo crítico de conexión a MySQL: " . $e->getMessage());

        // 4. Mostramos un mensaje genérico y seguro para el usuario final
        http_response_code(500);
        die("Ocurrió un error técnico al intentar conectar con la base de datos. Por favor, inténtalo más tarde.");
    }
```

Cambia el usuario, la contraseña y el nombre de la base de datos según tu configuración. No publiques credenciales reales en un repositorio público.

### 5. Agregar productos

Inserta algunos productos en la tabla `productos`. Por ejemplo:

```sql
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
```

Asegúrate de que las rutas de las imágenes correspondan a archivos existentes.

## 🛍️ Flujo de compra

1. El cliente entra al catálogo en `index.php`.
2. Agrega uno o varios productos al carrito.
3. El carrito mantiene los identificadores y las cantidades en `$_SESSION['cart']`.
4. En `cart.php`, el cliente revisa cantidades, subtotales y el total.
5. Selecciona **Finalizar compra** y proporciona su correo electrónico.
6. `process_order.php` valida la solicitud, recupera los precios desde MySQL y calcula el total en el servidor.
7. El sistema guarda la orden en `ordenes` y cada producto comprado en `detalle_orden`.
8. Si todas las inserciones funcionan, se confirma la transacción y se limpia el carrito.
9. `order_confirmation.php` muestra la información básica del pedido.

## 🗃️ Modelo de datos

### `productos`

Almacena el catálogo de artículos disponibles.

| Campo | Descripción |
|---|---|
| `codigo` | Identificador único del producto |
| `nombre` | Nombre del producto |
| `detalle` | Descripción del producto |
| `imagen` | Ruta de la imagen |
| `precio` | Precio actual del producto |

### `consultas`

Almacena el catálogo de artículos disponibles.

| Campo | Descripción |
|---|---|
| `id` | Identificador único del producto |
| `nombre` | Nombre del producto |
| `telefono` | Descripción del producto |
| `correo` | Ruta de la imagen |
| `detalle` | Precio actual del producto |
| `fecha_creacion` | Precio actual del producto |

### `ordenes`

Almacena la información general de cada pedido.

| Campo | Descripción |
|---|---|
| `id` | Identificador único del pedido |
| `email` | Correo electrónico de contacto del cliente |
| `fecha` | Fecha y hora de creación |
| `total` | Total del pedido |

### `detalle_orden`

Almacena los productos incluidos en cada pedido.

| Campo | Descripción |
|---|---|
| `id` | Identificador del detalle |
| `orden_id` | Pedido al que pertenece |
| `producto_id` | Producto comprado |
| `cantidad` | Unidades compradas |
| `precio` | Precio unitario guardado al comprar |
| `subtotal` | Precio unitario multiplicado por la cantidad |

La relación entre `ordenes` y `detalle_orden` es de **uno a muchos**: una orden puede contener varios productos. `producto_id` relaciona cada detalle con el catálogo.

## 🔐 Consideraciones importantes

- **Validación en el servidor:** no confíes en los precios ni en los totales enviados desde el navegador. Recupera los precios desde la base de datos y vuelve a calcular el total en PHP.
- **Consultas preparadas:** utiliza sentencias preparadas para las consultas que incluyan datos recibidos de formularios.
- **Transacciones:** registra la orden y sus detalles dentro de una transacción para evitar pedidos incompletos si una operación falla.
- **Protección CSRF:** añade tokens CSRF a los formularios que modifican el carrito o crean pedidos.
- **Acceso a pedidos:** en una tienda real, no basta con mostrar un número de pedido en la URL. Protege la página de confirmación para que otros usuarios no puedan consultar pedidos ajenos.
- **Correo electrónico:** el sistema guarda el correo de contacto. Esto no significa que envíe automáticamente un mensaje; para enviar confirmaciones se necesita configurar un servicio de correo.
- **Pagos:** la confirmación de un pedido no equivale a un pago procesado. Integra una pasarela de pago si necesitas aceptar pagos en línea.
- **Moneda:** los ejemplos muestran importes con el símbolo `$`. Ajusta el formato a la moneda que utilice la tienda.
- **Entorno de producción:** utiliza credenciales seguras, mensajes de error que no expongan detalles internos y copias de seguridad de la base de datos.

