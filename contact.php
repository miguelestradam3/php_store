<?php
    session_start();

    // 1. Bring in the connection file[cite: 3]
    require_once 'connection.php';

    $errores = [];
    $exito = "";
    $enviado_nombre = "";
    $enviado_phone = "";
    $enviado_email = "";

    // 2. RECUPERAR MENSAJES Y DATOS FLASH DE LA SESIÓN (Patrón PRG)
    if (isset($_SESSION['exito'])) {
        $exito = $_SESSION['exito'];
        unset($_SESSION['exito']); 
    }

    if (isset($_SESSION['enviado_nombre'])) {
        $enviado_nombre = $_SESSION['enviado_nombre'];
        unset($_SESSION['enviado_nombre']);
    }

    if (isset($_SESSION['enviado_phone'])) {
        $enviado_phone = $_SESSION['enviado_phone'];
        unset($_SESSION['enviado_phone']);
    }

    if (isset($_SESSION['enviado_email'])) {
        $enviado_email = $_SESSION['enviado_email'];
        unset($_SESSION['enviado_email']);
    }

    if (isset($_SESSION['errores'])) {
        $errores = $_SESSION['errores'];
        unset($_SESSION['errores']);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Recibir y limpiar datos[cite: 3]
        $name = trim($_POST["name"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $phone = trim($_POST["phone"] ?? ""); 
        $subject = trim($_POST["subject"] ?? "");

        // --- VALIDACIONES DE CONTENIDO ---[cite: 3]
        if (empty($name)) {
            $errores[] = "El campo nombre es obligatorio.";
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores[] = "El correo electrónico no es válido.";
        }

        if (empty($phone)) {
            $errores[] = "El campo de contacto/teléfono es obligatorio.";
        }

        if (empty($subject)) {
            $errores[] = "El detalle de la consulta es obligatorio.";
        }

        // --- PROCESAR SI NO HAY ERRORES DE VALIDACIÓN ---[cite: 3]
        if (empty($errores)) {
            try {
                // Preparamos la consulta SQL de forma segura para evitar inyecciones SQL[cite: 3]
                $sql = "INSERT INTO consultas (nombre, correo, telefono, detalle) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                
                // Vinculamos los parámetros (s = string)[cite: 3]
                $stmt->bind_param("ssss", $name, $email, $phone, $subject);
                
                // Ejecutamos la consulta[cite: 3]
                $stmt->execute();
                
                // Cerramos el statement[cite: 3]
                $stmt->close();

                // 3. GUARDAR EN SESIÓN Y REDIRIGIR (Evita el problema del F5 / reenvío de POST)[cite: 3]
                $_SESSION['exito'] = "¡Tu consulta ha sido enviada y guardada correctamente en la base de datos!";
                $_SESSION['enviado_nombre'] = $name;
                $_SESSION['enviado_phone'] = $phone;
                $_SESSION['enviado_email'] = $email;
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();

            } catch (Exception $e) {
                // REGISTRO DE ERROR TÉCNICO: Guardamos el mensaje real en los logs del servidor de forma silenciosa
                error_log("Error crítico al guardar la consulta en la base de datos: " . $e->getMessage());
                // Capturamos cualquier error que ocurra en la base de datos[cite: 3]
                $errores[] = "Ocurrió un error técnico al guardar los datos. Inténtalo más tarde.";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <!-- utf-8 charset -->
        <meta charset="utf-8">

        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <title>PHP Avanzado - Fidelitas</title>

        <!-- Favicons -->
        <link href="assets/img/phpicon.png" rel="icon">
        <link href="assets/img/phpicon.png" rel="apple-touch-icon">

        <!-- Bootstrap -->
        <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

        <!-- Main CSS File -->
        <link href="assets/css/main.css" rel="stylesheet">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com" rel="preconnect">
        <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">

    </head>
    <body class="index-page">
        <header id="header" class="dark-background header d-flex align-items-center sticky-top">
            <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

                <a href="index.php" class="logo d-flex align-items-center">
                    <h1 class="sitename">PHP Avanzado</h1>
                </a>

                <a href="cart.php" class="btn btn-primary">
                    <i class="bi bi-cart"></i>
                    Carrito

                    <?php
                    $cart_count = 0;

                    if (isset($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $quantity) {
                            $cart_count += $quantity;
                        }
                    }

                    echo "($cart_count)";
                    ?>
                </a>
                
                <a
                    href="clear_cart.php"
                    class="btn btn-danger ms-2"
                    onclick="return confirm('¿Estás seguro de que quieres vaciar el carrito?');"
                >
                    <i class="bi bi-trash"></i>
                    Vaciar carrito
                </a>

                <a href="index.php" class="btn btn-light">
                    <i class="bi bi-arrow-left"></i>
                    Seguir comprando
                </a>
            </div>
        </header>
        <main class="main">
            <section class="section">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <?php if (!empty($errores)): ?>
                                <div class="alert alert-danger">
                                    <ul>
                                        <?php foreach ($errores as $error): ?>
                                            <li><?php echo htmlspecialchars($error); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php elseif (!empty($exito)): ?>
                                <div class="alert alert-success">
                                    <?php echo htmlspecialchars($exito); ?>
                                    <p>Saludos, <?php echo htmlspecialchars($enviado_nombre); ?>! Nos pondremos en contacto a su número de teléfono: <?php echo htmlspecialchars($enviado_phone); ?> o a su correo electrónico: <?php echo htmlspecialchars($enviado_email); ?></p>
                                </div>
                            <?php elseif (empty($exito) && empty($errores)): ?>
                                <div class="alert alert-danger">
                                    <?php echo "Para que el envio de la informacion funcione hay que llenar el formulario disponible en la <a href='index.php#contact'><strong>pagina de inicio</strong></a> y en la <a href='cart.php#contact'><strong>pagina del carrito</strong></a>"; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <footer id="footer" class="footer dark-background">
            <div class="container">
                <div class="row g-4">
                    <div class="col-md-4 col-lg-4 mb-3 mb-md-0">
                    <div class="widget">
                        <h3 class="widget-heading">Especialización en Diseño y Desarrollo Web</h3>
                        <p class="mb-4">
                            Aqui aprendere a desarrollar sitios web completos, integrando diseño visual y programación, dos habilidades clave en el entorno digital actual. 
                        </p>
                    </div>
                    </div>
                    <div class="col-md-4 col-lg-4 pl-lg-5">
                        <div class="widget">
                            <h3 class="widget-heading">En constante crecimiento</h3>
                            <ul class="list-unstyled footer-blog-entry">
                            <li>
                                <span class="d-block date">PHP Avanzado</span>
                                <a href="#">
                                    Entre otras cosas, en este curso aprendere a aplicar los métodos que proporciona el protocolo HTTP y tipos de datos en PHP para el envío e implementación de datos en formularios del servidor<br>Asi como el uso de <strong>bases de datos</strong> para el almacenamiento de informacion
                                </a>
                            </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4 pl-lg-5">
                        <div class="widget">
                            <h3 class="widget-heading">Siguenos en Github</h3>
                            <ul class="list-unstyled social-icons light mb-3">
                                <li>
                                    <a href="#"><span class="bi bi-github"></span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- Custom JavaScript --> 
         <script src="assets/javascript/main.js"></script>
    </body>
</html>