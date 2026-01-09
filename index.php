<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="admin-page">

    <header class="main-header">
        <nav class="main-nav">
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="producto.php">Producto</a></li>
                <li><a href="proveedor.php">Proveedor</a></li>
                <li><a href="comprar.php">Compras</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h1 style="text-align: center; margin-bottom: 40px;">
            Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?>
        </h1>

        <div class="dashboard-menu">
            <a href="producto.php" class="dashboard-button">
                Gestionar Productos
            </a>
            <a href="proveedor.php" class="dashboard-button">
                Gestionar Proveedores
            </a>
            <a href="comprar.php" class="dashboard-button">
                Ver Historial de Compras
            </a>
        </div>
    </main>

    <footer class="main-footer">
    </footer>

</body>
</html>