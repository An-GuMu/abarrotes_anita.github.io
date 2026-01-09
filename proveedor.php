<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.html");
    exit;
}
require_once 'db.php';

if (isset($_POST['agregar_prov'])) {
    $id = $_POST['id_proveedor'];
    $dia = $_POST['dia_surtido'];
    $hora = $_POST['hora_surtido'];

    $stmt = $conn->prepare("INSERT INTO Proveedor (Id_Proveedor, dia_surtido, hora_surtido) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $id, $dia, $hora);
    $stmt->execute();
    $stmt->close();
    
    header("Location: proveedor.php");
    exit;
}

if (isset($_POST['agregar_tel'])) {
    $id = $_POST['id_proveedor_tel'];
    $tel = $_POST['telefono'];

    $stmt = $conn->prepare("INSERT INTO Telefono_Proveedor (Id_Proveedor, telefono) VALUES (?, ?)");
    $stmt->bind_param("ss", $id, $tel);
    $stmt->execute();
    $stmt->close();
    
    header("Location: proveedor.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Proveedores</title>
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
        <h1>Gestionar Proveedores</h1>

        <h2>Agregar Nuevo Proveedor</h2>
        <form action="proveedor.php" method="POST">
            <input type="text" name="id_proveedor" placeholder="ID Proveedor (ej: PROV-001)" required>
            <input type="date" name="dia_surtido" title="Día de surtido" required>
            <input type="time" name="hora_surtido" title="Hora de surtido" required>
            <button type="submit" name="agregar_prov">Agregar Proveedor</button>
        </form>

        <h2>Lista de Proveedores</h2>
        <table>
            <thead>
                <tr><th>ID</th><th>Día Surtido</th><th>Hora Surtido</th></tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM Proveedor");
                while($row = $result->fetch_assoc()) {
                    echo "<tr><td>{$row['Id_Proveedor']}</td><td>{$row['dia_surtido']}</td><td>{$row['hora_surtido']}</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <hr style="margin: 30px 0;">

        <h2>Agregar Teléfono a Proveedor</h2>
        <form action="proveedor.php" method="POST">
             <select name="id_proveedor_tel" required>
                <option value="">-- Seleccionar Proveedor --</option>
                <?php
                $result_prov = $conn->query("SELECT Id_Proveedor FROM Proveedor");
                while($row_prov = $result_prov->fetch_assoc()) {
                    echo "<option value='{$row_prov['Id_Proveedor']}'>{$row_prov['Id_Proveedor']}</option>";
                }
                ?>
            </select>
            <input type="text" name="telefono" placeholder="Número de Teléfono" required>
            <button type="submit" name="agregar_tel">Agregar Teléfono</button>
        </form>

        <h2>Lista de Teléfonos</h2>
        <table>
            <thead>
                <tr><th>ID Proveedor</th><th>Teléfono</th></tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM Telefono_Proveedor");
                while($row = $result->fetch_assoc()) {
                    echo "<tr><td>{$row['Id_Proveedor']}</td><td>{$row['telefono']}</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </main>

</body>
</html>