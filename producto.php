<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.html");
    exit;
}
require_once 'db.php';

if (isset($_POST['agregar_prod'])) {
    
    $cod = $_POST['codigo_barras'];
    $precio = $_POST['precio'];
    $cant = $_POST['cantidad'];
    $nom = $_POST['nombre'];
    $prov = $_POST['id_proveedor'];
    $tipo = $_POST['tipo_producto'];

    $conn->query("START TRANSACTION");

    $stmt_prod = $conn->prepare("INSERT INTO Producto (Codigo_Barras, precio, cantidad, nombre, Id_Proveedor) VALUES (?, ?, ?, ?, ?)");
    $stmt_prod->bind_param("sdiss", $cod, $precio, $cant, $nom, $prov);

    if ($stmt_prod->execute()) {
        
        // --- INICIO DE LA CORRECCIÓN ---
        // Si es Duradero (con Garantía), inserta en Producto_Duradero
        if ($tipo == 'duradero') {
            $gar = $_POST['garantia'];
            // La tabla 'Producto_Duradero' es la que tiene 'garantia'
            $stmt_hija = $conn->prepare("INSERT INTO Producto_Duradero (codigo_de_barras, garantia) VALUES (?, ?)");
            $stmt_hija->bind_param("ss", $cod, $gar);
        } else { // Si es No Duradero (con Caducidad), inserta en Producto_No_Duradero
            $cad = $_POST['caducidad'];
            $temp = $_POST['temperatura'];
            // La tabla 'Producto_No_Duradero' es la que tiene 'caducidad' y 'temperatura'
            $stmt_hija = $conn->prepare("INSERT INTO Producto_No_Duradero (codigo_de_barras, caducidad, temperatura) VALUES (?, ?, ?)");
            $stmt_hija->bind_param("ssd", $cod, $cad, $temp);
        }
        // --- FIN DE LA CORRECCIÓN ---

        if ($stmt_hija->execute()) {
            $conn->query("COMMIT");
            $mensaje = "Producto agregado correctamente.";
        } else {
            $conn->query("ROLLBACK");
            $mensaje = "Error: No se pudo agregar el detalle del producto.";
        }
        $stmt_hija->close();

    } else {
        $conn->query("ROLLBACK");
        $mensaje = "Error: No se pudo agregar el producto (verifique Cód. Barras).";
    }
    
    $stmt_prod->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Productos</title>
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
        <h1>Gestionar Productos</h1>
        <?php if(isset($mensaje)) { echo "<p style='font-weight:bold;'>$mensaje</p>"; } ?>
        
        <h2>Agregar Nuevo Producto</h2>
        <form action="producto.php" method="POST">
            <input type="text" name="codigo_barras" placeholder="Código de Barras" required style="width: 100%;">
            <input type="text" name="nombre" placeholder="Nombre Producto" required style="flex-grow: 1;">
            <input type="number" step="0.01" name="precio" placeholder="Precio" required>
            <input type="number" name="cantidad" placeholder="Cantidad (Stock)" required>
            <select name="id_proveedor" required style="flex-grow: 1;">
                <option value="">-- Seleccionar Proveedor --</option>
                <?php
                $result_prov = $conn->query("SELECT Id_Proveedor FROM Proveedor");
                while($row_prov = $result_prov->fetch_assoc()) {
                    echo "<option value='{$row_prov['Id_Proveedor']}'>{$row_prov['Id_Proveedor']}</option>";
                }
                ?>
            </select>
            <select name="tipo_producto" id="tipo_producto" required onchange="mostrarCampos()" style="width: 100%;">
                <option value="">-- Tipo de Producto --</option>
                <option value="duradero">Duradero (con Garantía)</option>
                <option value="no_duradero">No Duradero (con Caducidad)</option>
            </select>
            
            <div id="campos_duradero" class="campo-hijo">
                <input type="text" name="garantia" placeholder="Garantía (ej: 1 año)" style="width: 100%;">
            </div>

            <div id="campos_no_duradero" class="campo-hijo">
                <input type="date" name="caducidad" title="Caducidad">
                <input type="number" step="0.01" name="temperatura" placeholder="Temperatura °C">
            </div>

            <button type="submit" name="agregar_prod" class="full-width">Agregar Producto</button>
        </form>

        <h2>Lista de Productos</h2>
        <table>
            <thead>
                <tr><th>Cód. Barras</th><th>Nombre</th><th>Precio</th><th>Stock</th><th>Proveedor</th></tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM Producto");
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['Codigo_Barras']}</td>
                        <td>{$row['nombre']}</td>
                        <td>\${$row['precio']}</td>
                        <td>{$row['cantidad']}</td>
                        <td>{$row['Id_Proveedor']}</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </main>

    <script>
    function mostrarCampos() {
        var tipo = document.getElementById('tipo_producto').value;
        document.getElementById('campos_duradero').style.display = (tipo === 'duradero') ? 'flex' : 'none';
        document.getElementById('campos_no_duradero').style.display = (tipo === 'no_duradero') ? 'flex' : 'none';
    }
    </script>
</body>
</html>