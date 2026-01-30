<?php
include_once 'Conexion.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $fecha_ingreso = $_POST['fecha_ingreso'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $calle = $_POST['calle'];
    $numero = $_POST['numero'];
    $colonia = $_POST['colonia'];
    $ciudad = $_POST['ciudad'];
    $estado = $_POST['estado'];
    $codigo_postal = $_POST['codigo_postal'];
    $telefono = $_POST['telefono'];
    $rol = $_POST['rol'];
    $stmt = $conexion->prepare("INSERT INTO empleado (Contraseña, Nombre, Apellido, Fecha_Ingreso, Fecha_Nacimiento, Calle, Número, Colonia, Ciudad, Estado, Código_Postal, Teléfono, Rol) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssisssiss", $contraseña, $nombre, $apellido, $fecha_ingreso, $fecha_nacimiento, $calle, $numero, $colonia, $ciudad, $estado, $codigo_postal, $telefono, $rol);

    if ($stmt->execute()) {
        echo "Empleado registrado con éxito.";
        header("Location: InicioAdmin.php");
        exit();
    } else {
        echo "Error al registrar empleado.";
    }
}
?>

<html>

<head>
    <title>Registrar Empleado</title>
</head>

<body>
    <form method="post">
        <input type="password" name="contraseña" placeholder="Contraseña" required />
        <input type="text" name="nombre" placeholder="Nombre" required />
        <input type="text" name="apellido" placeholder="Apellido" required />
        <label>Fecha de Ingreso:</label>
        <input type="date" name="fecha_ingreso" required />
        <label>Fecha de Nacimiento:</label>
        <input type="date" name="fecha_nacimiento" required />
        <input type="text" name="calle" placeholder="Calle" required />
        <input type="text" name="numero" placeholder="Número" required />
        <input type="text" name="colonia" placeholder="Colonia" required />
        <input type="text" name="ciudad" placeholder="Ciudad" required />
        <input type="text" name="estado" placeholder="Estado" required />
        <input type="text" name="codigo_postal" placeholder="Código Postal" required />
        <input type="text" name="telefono" placeholder="Teléfono" required />
        <label>Selecciona el Rol:</label>
        <select name="rol">
            <option value="Administrador">Administrador</option>
            <option value="Cajero">Cajero</option>
        </select>
        <button type="submit">Registrar Empleado</button>
        <a href="InicioAdmin.php" class="btn-regresar">Regresar al Panel de Administración</a>
    </form>
</body>

</html>

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
</style>