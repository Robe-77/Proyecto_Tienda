<?php
session_start();
include_once 'Conexion.php';
$fechaFiltro = $_POST['fecha'] ?? '';
$codigoFiltro = $_POST['codigo'] ?? '';
$condicionesEntradas = [];
$tiposEntradas = '';
$paramsEntradas = [];
if (!empty($fechaFiltro)) {
    $condicionesEntradas[] = "DATE(ep.Fecha) = ?";
    $tiposEntradas .= 's';
    $paramsEntradas[] = $fechaFiltro;
}
if (!empty($codigoFiltro)) {
    $condicionesEntradas[] = "de.Código_Producto = ?";
    $tiposEntradas .= 'i';
    $paramsEntradas[] = $codigoFiltro;
}
$whereEntradas = count($condicionesEntradas) ? 'WHERE ' . implode(' AND ', $condicionesEntradas) : '';
$sqlEntradas = "
    SELECT ep.Fecha, ep.Id_Empleado, de.Código_Producto, de.Tipo, de.Cantidad
    FROM entrada_producto ep
    JOIN detalle_entrada de ON ep.Id_Entrada = de.Id_Entrada
    $whereEntradas
    ORDER BY ep.Fecha DESC
";
$stmtEntradas = $conexion->prepare($sqlEntradas);
if ($paramsEntradas) {
    $stmtEntradas->bind_param($tiposEntradas, ...$paramsEntradas);
}
$stmtEntradas->execute();
$resultEntradas = $stmtEntradas->get_result();
$condicionesSalidas = [];
$tiposSalidas = '';
$paramsSalidas = [];
if (!empty($fechaFiltro)) {
    $condicionesSalidas[] = "DATE(s.Fecha) = ?";
    $tiposSalidas .= 's';
    $paramsSalidas[] = $fechaFiltro;
}
if (!empty($codigoFiltro)) {
    $condicionesSalidas[] = "ds.Código_Producto = ?";
    $tiposSalidas .= 's';
    $paramsSalidas[] = $codigoFiltro;
}
$whereSalidas = count($condicionesSalidas) ? 'WHERE ' . implode(' AND ', $condicionesSalidas) : '';
$sqlSalidas = "
    SELECT s.Fecha, s.Id_Empleado, ds.Código_Producto, ds.Cantidad
    FROM salida s
    JOIN detalle_salida ds ON s.Id_Salida = ds.Id_Salida
    $whereSalidas
    ORDER BY s.Fecha DESC
";
$stmtSalidas = $conexion->prepare($sqlSalidas);
if ($paramsSalidas) {
    $stmtSalidas->bind_param($tiposSalidas, ...$paramsSalidas);
}
$stmtSalidas->execute();
$resultSalidas = $stmtSalidas->get_result();
?>

<html>

<head>
    <title>Consultar Movimientos</title>
</head>

<body>
    <h1 style="text-align:center; margin-top: 20px;">Filtrar Movimientos</h1>
    <form method="post" action="" class="filtro-form">
        <div class="form-group">
            <label for="fecha">Fecha:</label>
            <input type="date" name="fecha" id="fecha" value="<?php echo htmlspecialchars($fechaFiltro); ?>">
        </div>
        <div class="form-group">
            <label for="codigo">Código de Producto:</label>
            <input type="text" name="codigo" id="codigo" value="<?php echo htmlspecialchars($codigoFiltro); ?>">
        </div>
        <div class="form-group">
            <button type="submit" class="btn-filtro">Filtrar</button>
            <a href="ConsultarMovimientos.php" class="btn-filtro limpiar">Limpiar Filtros</a>
        </div>
    </form>
    <h2>Entradas de Productos</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>ID Empleado</th>
                <th>Código Producto</th>
                <th>Tipo</th>
                <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $resultEntradas->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['Fecha']); ?></td>
                    <td><?php echo htmlspecialchars($row['Id_Empleado']); ?></td>
                    <td><?php echo htmlspecialchars($row['Código_Producto']); ?></td>
                    <td><?php echo htmlspecialchars($row['Tipo']); ?></td>
                    <td><?php echo htmlspecialchars($row['Cantidad']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <h2>Salidas de Productos</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>ID Empleado</th>
                <th>Código Producto</th>
                <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $resultSalidas->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['Fecha']); ?></td>
                    <td><?php echo htmlspecialchars($row['Id_Empleado']); ?></td>
                    <td><?php echo htmlspecialchars($row['Código_Producto']); ?></td>
                    <td><?php echo htmlspecialchars($row['Cantidad']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="InicioAdmin.php" class="btn-regresar">Regresar al Panel de Administración</a>
</body>

</html>

<style>
    body {
        font-family: "Open Sans", sans-serif;
        background: #092756;
        color: #fff;
        margin: 0;
        padding: 0;
    }

    h2 {
        text-align: center;
        margin-top: 30px;
        color: #fff;
    }

    table {
        width: 90%;
        margin: 30px auto;
        border-collapse: collapse;
        background: rgba(0, 0, 0, 0.3);
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 1px 10px rgba(0, 0, 0, 0.2);
    }

    th,
    td {
        padding: 10px;
        text-align: center;
        border-bottom: 1px solid #444;
    }

    th {
        background: #4a77d4;
        color: #fff;
    }

    tr:last-child td {
        border-bottom: none;
    }

    .btn-regresar {
        display: block;
        width: 200px;
        margin: 30px auto;
        padding: 12px 0;
        background-color: #4a77d4;
        color: #fff;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
        text-align: center;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        transition: background 0.2s;
    }

    .btn-regresar:hover {
        background-color: #3762bc;
    }

    .filtro-form {
        width: 90%;
        max-width: 600px;
        margin: 0 auto 40px auto;
        padding: 20px;
        background-color: rgba(0, 0, 0, 0.3);
        border-radius: 10px;
        box-shadow: 0 1px 10px rgba(0, 0, 0, 0.2);
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .filtro-form .form-group {
        display: flex;
        flex-direction: column;
        color: #fff;
    }

    .filtro-form label {
        margin-bottom: 5px;
        font-weight: bold;
    }

    .filtro-form input[type="date"],
    .filtro-form input[type="text"] {
        padding: 10px;
        border: none;
        border-radius: 5px;
        background-color: #fff;
        color: #000;
        font-size: 14px;
    }

    .btn-filtro {
        display: inline-block;
        padding: 10px 15px;
        margin-top: 10px;
        background-color: #4a77d4;
        color: #fff;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
        text-align: center;
        cursor: pointer;
        transition: background 0.2s;
        width: fit-content;
    }

    .btn-filtro:hover {
        background-color: #3762bc;
    }

    .btn-filtro.limpiar {
        background-color: #d9534f;
        margin-left: 10px;
    }

    .btn-filtro.limpiar:hover {
        background-color: #c9302c;
    }
</style>