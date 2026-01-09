<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.html");
    exit;
}
require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Compras</title>
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
        <h1>Historial de Compras</h1>

        <h2>Tickets de Compra</h2>
        <table>
            <thead>
                <tr><th>ID Ticket</th><th>Fecha</th><th>Hora</th><th>Total</th><th>ID Cliente</th></tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM Compra ORDER BY fecha DESC, hora DESC");
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['Id_Ticket']}</td>
                        <td>{$row['fecha']}</td>
                        <td>{$row['hora']}</td>
                        <td>\${$row['total']}</td>
                        <td>{$row['Id_Cliente']}</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>

        <h2>Detalle de Compras</h2>
        <table>
            <thead>
                <tr><th>ID Ticket</th><th>Cód. Barras</th><th>Precio Unit.</th><th>Cantidad</th></tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM Detalle_Compra ORDER BY Id_ticket DESC");
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['Id_ticket']}</td>
                        <td>{$row['Codigo_Barras']}</td>
                        <td>\${$row['Precio_Unitario']}</td>
                        <td>{$row['Cantidad']}</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </main>

</body>
</html>