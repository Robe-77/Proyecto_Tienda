<?php
include_once 'Conexion.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigo_producto'])) {
    $codigo_producto = $_POST['codigo_producto'];
    $cantidad = $_POST['cantidad'];
    $id_empleado = $_SESSION['id'];
    $id_proveedor = $_POST['id_proveedor'];
    $stmt = $conexion->prepare("INSERT INTO entrada_producto (Fecha, Id_Empleado, Id_Proveedor) VALUES (NOW(), ?, ?)");
    $stmt->bind_param("is", $id_empleado, $id_proveedor);
    if ($stmt->execute()) {
        $id_entrada = $conexion->insert_id;
        $tipo = "Compra";
        $stmt_detalle = $conexion->prepare("INSERT INTO detalle_entrada (Id_Entrada, Código_Producto, Tipo, Cantidad) VALUES (?, ?, ?, ?)");
        $stmt_detalle->bind_param("issi", $id_entrada, $codigo_producto, $tipo, $cantidad);
        $stmt_detalle->execute();
        $stmt2 = $conexion->prepare("UPDATE producto SET Existencia = Existencia + ? WHERE Código_Producto = ?");
        $stmt2->bind_param("is", $cantidad, $codigo_producto);
        $stmt2->execute();
        echo "<div class='mensaje-exito'>Compra registrada con éxito.</div>";
    } else {
        echo "<div class='mensaje-error'>Error al registrar la compra.</div>";
    }
}
?>

<form method="post" id="form-devolucion">
    <input type="text" name="id_proveedor" placeholder="ID del Proveedor" required />
    <input type="text" name="codigo_producto" placeholder="Código del producto" required />
    <input type="number" name="cantidad" placeholder="Cantidad a comprar" min="1" required />
    <button type="submit">Registrar Compra</button>
    <a href="InicioCajero.php" class="btn-regresar">Regresar al Panel de Cajero</a>
</form>

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
    form button {
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
        transition: box-shadow 0.5s ease;
    }

    form input:focus {
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