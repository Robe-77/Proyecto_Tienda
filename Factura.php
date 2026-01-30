<?php
include_once 'Conexion.php';
session_start();
if (!isset($_SESSION['id_salida'])) {
    header("Location: InicioCajero.php");
    exit();
}
$id_salida = $_SESSION['id_salida'];
$total = $_SESSION['total_venta'];
$mensaje = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['requiere_factura'])) {
    if ($_POST['requiere_factura'] == '1') {
        if (isset($_POST['cliente_registrado']) && $_POST['cliente_registrado'] == 'si') {
            $rfc = $_POST['rfc_existente'];
            $stmt = $conexion->prepare("SELECT RFC FROM cliente WHERE RFC = ?");
            $stmt->bind_param("s", $rfc);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $stmt = $conexion->prepare("INSERT INTO factura (Fecha, RFC, Id_Salida) VALUES (NOW(), ?, ?)");
                $stmt->bind_param("si", $rfc, $id_salida);
                $stmt->execute();
                $mensaje = "<div class='mensaje-exito'>Factura generada exitosamente.</div>";
                unset($_SESSION['id_salida']);
                unset($_SESSION['total_venta']);
                $_SESSION['factura_data'] = [
                    'id_salida' => $id_salida,
                    'rfc' => $rfc
                ];
                header("refresh:2;url=FacturaGenerada.php");
            } else {
                $mensaje = "<div class='mensaje-error'>El RFC no está registrado en el sistema.</div>";
            }
        } else {
            $datos_factura = [
                'nombre' => $_POST['nombre'],
                'rfc' => $_POST['rfc'],
                'apellido' => $_POST['apellido'],
                'calle' => $_POST['calle'],
                'numero' => $_POST['numero'],
                'colonia' => $_POST['colonia'],
                'ciudad' => $_POST['ciudad'],
                'estado' => $_POST['estado'],
                'codigo_postal' => $_POST['codigo_postal'],
                'telefono' => $_POST['telefono'],
                'correo' => $_POST['correo']
            ];
            $stmt = $conexion->prepare("INSERT INTO cliente (Nombre, RFC, Apellido, Calle, Número, Colonia, Ciudad, Estado, Código_Postal, Teléfono, Correo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param(
                "sssssssssss",
                $datos_factura['nombre'],
                $datos_factura['rfc'],
                $datos_factura['apellido'],
                $datos_factura['calle'],
                $datos_factura['numero'],
                $datos_factura['colonia'],
                $datos_factura['ciudad'],
                $datos_factura['estado'],
                $datos_factura['codigo_postal'],
                $datos_factura['telefono'],
                $datos_factura['correo']
            );
            $stmt->execute();
            $stmt = $conexion->prepare("INSERT INTO factura (Fecha, RFC, Id_Salida) VALUES (NOW(), ?, ?)");
            $stmt->bind_param("si", $datos_factura['rfc'], $id_salida);
            $stmt->execute();
            $mensaje_exito = "<div class='mensaje-exito'>Factura generada exitosamente.</div>";
            unset($_SESSION['id_salida']);
            unset($_SESSION['total_venta']);
            $_SESSION['factura_data'] = [
                'id_salida' => $id_salida,
                'rfc' => $datos_factura['rfc']
            ];
            header("refresh:2;url=FacturaGenerada.php");
        }
    } else {
        unset($_SESSION['id_salida']);
        unset($_SESSION['total_venta']);
        header("Location: InicioCajero.php");
        exit();
    }
}
?>

<html>

<head>
    <title>Facturación</title>
</head>

<body>
    <?php if (!empty($mensaje)) echo $mensaje; ?>
    <div class="resumen-venta">
        <h2>Resumen de Venta</h2>
        <p>Número de Venta: <?php echo $id_salida; ?></p>
        <p>Total: $<?php echo number_format($total, 2); ?></p>
    </div>
    <form method="post">
        <h3>¿Generar factura?</h3>
        <label>
            <input type="radio" name="requiere_factura" id="factura-no" value="0" onchange="toggleDatosFactura()"> No
        </label>
        <label>
            <input type="radio" name="requiere_factura" id="factura-si" value="1" onchange="toggleDatosFactura()"> Sí
        </label>
        <div id="opcion-cliente" style="display: none; margin-top: 10px;">
            <label>¿El cliente ya está dado de alta en el sistema?</label>
            <label>
                <input type="radio" name="cliente_registrado" value="si" onchange="toggleClienteRegistrado()"> Sí
            </label>
            <label>
                <input type="radio" name="cliente_registrado" value="no" onchange="toggleClienteRegistrado()"> No
            </label>
        </div>
        <div id="rfc-existente" style="display: none; margin-top: 10px;">
            <input type="text" name="rfc_existente" placeholder="RFC del cliente registrado" pattern="^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$" title="Ingrese un RFC válido">
        </div>
        <div id="datos-factura" style="display: none;">
            <h4>Datos para Facturación</h4>
            <input type="text" name="rfc" placeholder="RFC" pattern="^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$" title="Ingrese un RFC válido">
            <input type="text" name="nombre" placeholder="Ingrese su nombre" required>
            <input type="text" name="apellido" placeholder="Ingrese su apellido" required>
            <input type="text" name="calle" placeholder="Calle" required>
            <input type="text" name="numero" placeholder="Número" required>
            <input type="text" name="colonia" placeholder="Colonia" required>
            <input type="text" name="ciudad" placeholder="Ciudad" required>
            <input type="text" name="estado" placeholder="Estado" required>
            <input type="text" name="codigo_postal" placeholder="Código Postal" pattern="^\d{5}$" title="Ingrese un código postal válido" required>
            <input type="tel" name="telefono" placeholder="Teléfono" title="Ingrese un número de teléfono válido" required>
            <input type="email" name="correo" placeholder="Correo Electrónico" required>
        </div>
        <button type="submit">Continuar</button>
    </form>
</body>

</html>

<script>
    function toggleDatosFactura() {
        const facturaSi = document.getElementById('factura-si').checked;
        document.getElementById('opcion-cliente').style.display = facturaSi ? 'block' : 'none';
        document.getElementById('rfc-existente').style.display = 'none';
        document.getElementById('datos-factura').style.display = 'none';
        if (!facturaSi) {
            document.querySelectorAll('#datos-factura input, #rfc-existente input').forEach(input => {
                input.required = false;
            });
        }
        if (facturaSi) {
            toggleClienteRegistrado();
        }
    }

    function toggleClienteRegistrado() {
        const clienteRegistrado = document.querySelector('input[name="cliente_registrado"]:checked');
        if (!clienteRegistrado) return;
        if (clienteRegistrado.value === 'si') {
            document.getElementById('rfc-existente').style.display = 'block';
            document.getElementById('datos-factura').style.display = 'none';
            document.querySelectorAll('#datos-factura input').forEach(input => input.required = false);
            document.querySelector('input[name="rfc_existente"]').required = true;
        } else {
            document.getElementById('rfc-existente').style.display = 'none';
            document.getElementById('datos-factura').style.display = 'block';
            document.querySelectorAll('#datos-factura input').forEach(input => input.required = true);
            document.querySelector('input[name="rfc_existente"]').required = false;
        }
    }
    window.onload = function() {
        toggleDatosFactura();
    };
</script>

<style>
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
        display: flex;
        justify-content: center;
        align-items: flex-start;
        flex-direction: column;
        padding: 0;
    }

    h2,
    h3,
    h4 {
        color: #fff;
        text-align: center;
        margin-top: 30px;
        margin-bottom: 20px;
        text-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        letter-spacing: 1px;
    }

    p {
        color: #fff;
        text-align: center;
        font-size: 18px;
    }

    form {
        background: rgba(0, 0, 0, 0.3);
        padding: 30px 30px 20px 30px;
        border-radius: 10px;
        box-shadow: 0 1px 10px rgba(0, 0, 0, 0.2);
        margin: 30px auto 0 auto;
        width: 380px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    form label {
        color: #fff;
        font-size: 15px;
        margin-bottom: 8px;
        width: 100%;
        text-align: left;
        background: none;
        padding: 0;
        box-shadow: none;
    }

    form input[type="text"],
    form input[type="number"],
    form input[type="tel"],
    form input[type="email"] {
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

    form button[type="submit"] {
        background-color: #4a77d4;
        color: #fff;
        font-weight: bold;
        border: none;
        cursor: pointer;
        margin-top: 10px;
        border-radius: 5px;
        padding: 12px 30px;
        font-size: 16px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        transition: background 0.2s;
        width: 100%;
    }

    form button[type="submit"]:hover {
        background-color: #3762bc;
    }

    #datos-factura {
        margin-top: 20px;
        width: 100%;
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

    @media (max-width: 500px) {
        form {
            width: 95%;
            padding: 15px;
        }

        .mensaje-exito,
        .mensaje-error {
            width: 95%;
        }
    }

    .resumen-venta {
        background: rgba(0, 0, 0, 0.3);
        padding: 30px 30px 20px 30px;
        border-radius: 10px;
        box-shadow: 0 1px 10px rgba(0, 0, 0, 0.2);
        margin: 30px auto 0 auto;
        width: 380px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .resumen-venta h2 {
        color: #fff;
        margin-bottom: 10px;
        text-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    }

    .resumen-venta p {
        color: #fff;
        font-size: 18px;
        margin: 6px 0;
        text-align: center;
    }

    @media (max-width: 500px) {
        .resumen-venta {
            width: 95%;
            padding: 15px;
        }
    }
</style>