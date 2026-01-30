<?php
include_once 'Conexion.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verificar'])) {
    $id_empleado = $_POST['id_empleado'];
    $consulta = $conexion->prepare("SELECT Nombre, Apellido, Rol FROM empleado WHERE Id_Empleado = ?");
    $consulta->bind_param("i", $id_empleado);
    $consulta->execute();
    $resultado = $consulta->get_result();
    if ($resultado->num_rows === 0) {
        echo "<div class='mensaje-error'>Error: Empleado no encontrado.</div>";
        exit();
    }
    $fila = $resultado->fetch_assoc();
    if (strtolower($fila['Rol']) === 'Administrador') {
        echo "<div class='mensaje-error'>No se puede eliminar a un usuario con rol de Administrador.</div>";
        exit();
    }
    $esta_en_uso = false;
    $tablas_referenciadas = [
        ['tabla' => 'salida', 'campo' => 'Id_Empleado'],
        ['tabla' => 'entrada_producto', 'campo' => 'Id_Empleado']
    ];
    foreach ($tablas_referenciadas as $tabla) {
        $verificar = $conexion->prepare("SELECT 1 FROM {$tabla['tabla']} WHERE {$tabla['campo']} = ? LIMIT 1");
        $verificar->bind_param("i", $id_empleado);
        $verificar->execute();
        $resultado_verificacion = $verificar->get_result();
        if ($resultado_verificacion->num_rows > 0) {
            $esta_en_uso = true;
            break;
        }
    }
    if ($esta_en_uso) {
        echo "<div class='mensaje-error'>No se puede eliminar al empleado porque está relacionado con registros de salida o entrada de productos.</div>";
    } else {
        echo "<h3>¿Seguro que deseas eliminar a " . htmlspecialchars($fila['Nombre']) . " " . htmlspecialchars($fila['Apellido']) . "?</h3>";
        echo "<p>Rol: " . htmlspecialchars($fila['Rol']) . "</p>";
        echo '<form method="post">';
        echo '<input type="hidden" name="id_empleado" value="' . htmlspecialchars($id_empleado) . '" />';
        echo '<button type="submit" name="confirmar" value="true">Confirmar Eliminación</button>';
        echo '</form>';
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar']) && $_POST['confirmar'] === "true") {
    $id_empleado = $_POST['id_empleado'];
    $eliminar = $conexion->prepare("DELETE FROM empleado WHERE Id_Empleado = ?");
    $eliminar->bind_param("i", $id_empleado);
    if ($eliminar->execute()) {
        echo "<div class='mensaje-exito'>Empleado eliminado correctamente.</div>";
        header("refresh:1;url=InicioAdmin.php");
    } else {
        echo "<div class='mensaje-error'>Error al eliminar el empleado.</div>";
        exit();
    }
}
?>

<html>

<head>
    <title>Eliminar Empleado</title>
</head>
<form method="post">
    <input type="text" name="id_empleado" placeholder="ID del empleado a eliminar" required />
    <button type="submit" name="verificar" value="true">Verificar Empleado</button>
    <a href="InicioAdmin.php">Regresar al Panel de Administración</a>
</form>

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