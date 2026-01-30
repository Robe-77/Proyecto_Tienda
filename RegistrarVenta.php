<?php
include_once 'Conexion.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productos = $_POST['productos'];
    $cantidades = $_POST['cantidades'];
    $total = 0;
    $id_empleado = $_SESSION['id'];
    $stmt = $conexion->prepare("INSERT INTO salida (Fecha, Id_Empleado, Total) VALUES (NOW(), ?, 0)");
    $stmt->bind_param("i", $id_empleado);
    $stmt->execute();
    $id_salida = $conexion->insert_id;
    for ($i = 0; $i < count($productos); $i++) {
        $codigo = $productos[$i];
        $cantidad = $cantidades[$i];
        $stmt_precio = $conexion->prepare("SELECT Precio_Público FROM producto WHERE Código_Producto = ?");
        $stmt_precio->bind_param("i", $codigo);
        $stmt_precio->execute();
        $stmt_precio->bind_result($precio_unitario);
        $stmt_precio->fetch();
        $stmt_precio->close();
        $subtotal = $cantidad * $precio_unitario;
        $total += $subtotal;
        $stmt_detalle = $conexion->prepare("INSERT INTO detalle_salida (Id_Salida, Código_Producto, Cantidad) VALUES (?, ?, ?)");
        $stmt_detalle->bind_param("iii", $id_salida, $codigo, $cantidad);
        $stmt_detalle->execute();
        $stmt2 = $conexion->prepare("UPDATE producto SET Existencia = Existencia - ? WHERE Código_Producto = ?");
        $stmt2->bind_param("ii", $cantidad, $codigo);
        $stmt2->execute();
    }
    $stmt_update = $conexion->prepare("UPDATE salida SET Total = ? WHERE Id_Salida = ?");
    $stmt_update->bind_param("di", $total, $id_salida);
    $stmt_update->execute();
    $_SESSION['id_salida'] = $id_salida;
    $_SESSION['total_venta'] = $total;
    header("Location: Factura.php");
    exit();
}
?>

<html>

<head>
    <title>Registrar Venta</title>
</head>

<body>
    <form method="post" id="form-venta">
        <div id="productos-container">
            <div class="producto-row">
                <input type="text" name="productos[]" placeholder="Código del producto" required />
                <input type="number" name="cantidades[]" placeholder="Cantidad" min="1" required />
                <button type="button" onclick="eliminarProducto(this)">Eliminar</button>
            </div>
        </div>
        <button type="button" onclick="agregarProducto()">Agregar otro producto</button>
        <button type="submit">Registrar Venta</button>
        <a href="InicioCajero.php" class="btn-regresar">Regresar al Panel de Cajero</a>
    </form>
</body>

</html>

<script>
    function agregarProducto() {
        const container = document.getElementById('productos-container');
        const row = document.createElement('div');
        row.className = 'producto-row';
        row.innerHTML = `
        <input type="text" name="productos[]" placeholder="Código del producto" required />
        <input type="number" name="cantidades[]" placeholder="Cantidad" min="1" required />
        <button type="button" onclick="eliminarProducto(this)">Eliminar</button>
    `;
        container.appendChild(row);
    }

    function eliminarProducto(btn) {
        btn.parentElement.remove();
    }
</script>

<style>
    @import url(https://fonts.googleapis.com/css?family=Open+Sans);

    body {
        width: 100%;
        min-height: 100vh;
        margin: 0;
        font-family: "Open Sans", sans-serif;
        background: #092756;
        background: -moz-radial-gradient(0% 100%,
                ellipse cover,
                rgba(104, 128, 138, 0.4) 10%,
                rgba(138, 114, 76, 0) 40%),
            -moz-linear-gradient(top, rgba(57, 173, 219, 0.25) 0%, rgba(42, 60, 87, 0.4) 100%),
            -moz-linear-gradient(-45deg, #670d10 0%, #092756 100%);
        background: -webkit-radial-gradient(0% 100%,
                ellipse cover,
                rgba(104, 128, 138, 0.4) 10%,
                rgba(138, 114, 76, 0) 40%),
            -webkit-linear-gradient(top, rgba(57, 173, 219, 0.25) 0%, rgba(42, 60, 87, 0.4) 100%),
            -webkit-linear-gradient(-45deg, #670d10 0%, #092756 100%);
        background: -o-radial-gradient(0% 100%,
                ellipse cover,
                rgba(104, 128, 138, 0.4) 10%,
                rgba(138, 114, 76, 0) 40%),
            -o-linear-gradient(top, rgba(57, 173, 219, 0.25) 0%, rgba(42, 60, 87, 0.4) 100%),
            -o-linear-gradient(-45deg, #670d10 0%, #092756 100%);
        background: linear-gradient(to bottom,
                rgba(57, 173, 219, 0.25) 0%,
                rgba(42, 60, 87, 0.4) 100%),
            linear-gradient(135deg, #670d10 0%, #092756 100%);
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }

    form {
        background: rgba(0, 0, 0, 0.3);
        padding: 30px 30px 20px 30px;
        border-radius: 10px;
        box-shadow: 0 1px 10px rgba(0, 0, 0, 0.2);
        margin-top: 40px;
        width: 350px;
    }

    form input,
    form select,
    form button,
    form label {
        width: 100%;
        margin-bottom: 12px;
        background: rgba(0, 0, 0, 0.3);
        border: none;
        outline: none;
        padding: 10px;
        font-size: 14px;
        color: #fff;
        border-radius: 4px;
        box-shadow: inset 0 -5px 45px rgba(100, 100, 100, 0.2),
            0 1px 1px rgba(255, 255, 255, 0.2);
        -webkit-transition: box-shadow 0.5s ease;
        -moz-transition: box-shadow 0.5s ease;
        -o-transition: box-shadow 0.5s ease;
        -ms-transition: box-shadow 0.5s ease;
        transition: box-shadow 0.5s ease;
    }

    form input:focus,
    form select:focus {
        box-shadow: inset 0 -5px 45px rgba(100, 100, 100, 0.4),
            0 1px 1px rgba(255, 255, 255, 0.2);
    }

    form button {
        background-color: #4a77d4;
        color: #fff;
        font-weight: bold;
        border: none;
        cursor: pointer;
        margin-top: 10px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        transition: background 0.2s;
    }

    form button:hover {
        background-color: #3762bc;
    }

    form label {
        background: none;
        color: #fff;
        font-size: 13px;
        margin-bottom: 4px;
        padding: 0;
        box-shadow: none;
    }

    .mensaje-exito {
        color: #4caf50;
        background: rgba(0, 0, 0, 0.2);
        padding: 10px;
        border-radius: 5px;
        margin: 10px auto;
        text-align: center;
        width: 350px;
    }

    .mensaje-error {
        color: #ff4d4d;
        background: rgba(0, 0, 0, 0.2);
        padding: 10px;
        border-radius: 5px;
        margin: 10px auto;
        text-align: center;
        width: 350px;
    }

    .btn-regresar {
        display: block;
        width: 85%;
        text-align: center;
        padding: 10px 24px;
        background-color: red;
        color: #fff;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        transition: background 0.2s;
        margin: 0 auto;
        margin-top: 10px;
    }

    .btn-regresar:hover {
        background-color: #c0392b;
    }
</style>