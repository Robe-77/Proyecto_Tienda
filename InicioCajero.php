<?php
session_start();
$nombre = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Cajero';
?>
<html>

<head>
    <title>Panel de Cajeros</title>
</head>

<body>
    <h1>Bienvenido, <?php echo htmlspecialchars($nombre); ?></h1>
    <nav>
        <ul>
            <li><a href="RegistrarVenta.php">Registrar Venta</a></li>
            <li><a href="RegistrarDevolucion.php">Registrar Devolución</a></li>
            <li><a href="RegistrarCompra.php">Registrar Compra</a></li>
        </ul>
    </nav>
    <form method="post" action="CerrarSesion.php" style="text-align:center; margin-top:30px;">
        <button type="submit" class="btn-cerrar-sesion">Cerrar Sesión</button>
    </form>
</body>

</html>

<style>
    @import url(https://fonts.googleapis.com/css?family=Open+Sans);

    body {
        width: 100%;
        height: 100vh;
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
        min-height: 100vh;
    }

    h1 {
        color: #fff;
        text-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        letter-spacing: 1px;
        text-align: center;
        margin-top: 40px;
    }

    nav {
        display: flex;
        justify-content: center;
        margin-top: 40px;
    }

    nav ul {
        list-style: none;
        padding: 0;
        background: rgba(0, 0, 0, 0.3);
        border-radius: 8px;
        box-shadow: 0 1px 10px rgba(0, 0, 0, 0.2);
        width: 320px;
    }

    nav ul li {
        margin: 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    nav ul li:last-child {
        border-bottom: none;
    }

    nav ul li a {
        display: block;
        padding: 16px;
        color: #fff;
        text-decoration: none;
        font-size: 16px;
        transition: background 0.2s, color 0.2s;
    }

    nav ul li a:hover {
        background: #4a77d4;
        color: #fff;
        border-radius: 0 0 8px 8px;
    }

    .btn-cerrar-sesion {
        background-color: #ff4d4d;
        color: #fff;
        border: none;
        border-radius: 5px;
        padding: 12px 30px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        margin-top: 20px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        transition: background 0.2s;
    }

    .btn-cerrar-sesion:hover {
        background-color: #c0392b;
    }
</style>