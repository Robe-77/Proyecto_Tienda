<?php
include_once 'Conexion.php';
session_start();
if (!isset($_SESSION['factura_data'])) {
    header("Location: InicioCajero.php");
    exit();
}
$facturaData = $_SESSION['factura_data'];
$id_salida = $facturaData['id_salida'];
$rfc_cliente = $facturaData['rfc'];
$stmtCliente = $conexion->prepare("SELECT * FROM cliente WHERE RFC = ?");
$stmtCliente->bind_param("s", $rfc_cliente);
$stmtCliente->execute();
$cliente = $stmtCliente->get_result()->fetch_assoc();
$stmtFactura = $conexion->prepare("SELECT * FROM factura WHERE Id_Salida = ?");
$stmtFactura->bind_param("i", $id_salida);
$stmtFactura->execute();
$factura = $stmtFactura->get_result()->fetch_assoc();
$stmtVenta = $conexion->prepare("
    SELECT p.Nombre_Comercial, ds.Cantidad, p.Precio_Público, (ds.Cantidad * p.Precio_Público) AS Subtotal 
    FROM detalle_salida ds
    JOIN producto p ON ds.Código_Producto = p.Código_Producto
    WHERE ds.Id_Salida = ?
");
$stmtVenta->bind_param("i", $id_salida);
$stmtVenta->execute();
$productos = $stmtVenta->get_result();
$stmtTotal = $conexion->prepare("SELECT Total FROM salida WHERE Id_Salida = ?");
$stmtTotal->bind_param("i", $id_salida);
$stmtTotal->execute();
$total = $stmtTotal->get_result()->fetch_assoc()['Total'];
unset($_SESSION['factura_data']);
?>

<html>

<head>
    <meta charset="UTF-8">
    <title>Factura Generada</title>
</head>

<body>
    <div class="factura-container">
        <div class="header">
            <h1>FACTURA</h1>
            <p>N° <?php echo $factura['Id_Factura']; ?></p>
            <p>Fecha: <?php echo date('d/m/Y', strtotime($factura['Fecha'])); ?></p>
        </div>
        <div class="datos-empresa">
            <h3>Datos de la Empresa</h3>
            <p><strong>Nombre:</strong> Tienda de Conveniencia</p>
            <p><strong>RFC:</strong> XYZ123456ABC</p>
            <p><strong>Dirección:</strong> Av. Principal 123, Centro, Ciudad, Estado, C.P. 12345</p>
            <p><strong>Teléfono:</strong> 555-123-4567</p>
        </div>
        <div class="datos-cliente">
            <h3>Datos del Cliente</h3>
            <p><strong>Nombre:</strong> <?php echo $cliente['Nombre'] . ' ' . $cliente['Apellido']; ?></p>
            <p><strong>RFC:</strong> <?php echo $cliente['RFC']; ?></p>
            <p><strong>Dirección:</strong> <?php echo $cliente['Calle'] . ' ' . $cliente['Número'] . ', ' . $cliente['Colonia'] . ', ' . $cliente['Ciudad'] . ', ' . $cliente['Estado'] . ', C.P. ' . $cliente['Código_Postal']; ?></p>
            <p><strong>Teléfono:</strong> <?php echo $cliente['Teléfono']; ?></p>
            <p><strong>Email:</strong> <?php echo $cliente['Correo']; ?></p>
        </div>
        <div class="datos-factura">
            <div>
                <p><strong>Folio de Venta:</strong> <?php echo $id_salida; ?></p>
            </div>
            <div>
                <p><strong>Fecha de Venta:</strong> <?php echo date('d/m/Y H:i:s'); ?></p>
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Cantidad</th>
                    <th>Descripción</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($producto = $productos->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $producto['Cantidad']; ?></td>
                        <td><?php echo $producto['Nombre_Comercial']; ?></td>
                        <td>$<?php echo number_format($producto['Precio_Público'], 2); ?></td>
                        <td>$<?php echo number_format($producto['Subtotal'], 2); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="total">
            <p>TOTAL: $<?php echo number_format($total, 2); ?></p>
        </div>
        <div class="footer">
            <p>¡Gracias por su compra!</p>
            <p>Esta factura es válida como comprobante fiscal</p>
            <button class="btn-imprimir" onclick="window.print()">Imprimir Factura</button>
            <a href="InicioCajero.php" class="btn-regresar">Regresar al Panel</a>
        </div>
    </div>
</body>

</html>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
    }

    .factura-container {
        max-width: 800px;
        margin: 0 auto;
        border: 1px solid #ddd;
        padding: 20px;
    }

    .header {
        text-align: center;
        margin-bottom: 30px;
    }

    .logo {
        max-width: 150px;
    }

    .datos-empresa,
    .datos-cliente {
        margin-bottom: 20px;
    }

    .datos-factura {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    .total {
        text-align: right;
        font-weight: bold;
        font-size: 1.2em;
    }

    .footer {
        margin-top: 30px;
        text-align: center;
        font-size: 0.9em;
        color: #666;
    }

    .btn-imprimir {
        padding: 10px 20px;
        background: #4CAF50;
        color: white;
        border: none;
        cursor: pointer;
    }
</style>