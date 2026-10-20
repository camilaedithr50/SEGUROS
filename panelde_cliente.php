<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['id_cliente'])) {
    header('Location: Index.php');
    exit();
}

$idCliente = $_SESSION['id_cliente'];

$sqlPoliza = "
    SELECT p.id_poliza, p.fecha_inicio, p.fecha_fin, p.estado, p.precio_mensual,
           ts.nombre_seguro, ts.descripcion, ts.cobertura,
           v.tipo_vehiculo, v.marca, v.modelo, v.patente
    FROM polizas p
    INNER JOIN tipos_seguros ts ON ts.id_tipo_seguro = p.id_tipo_seguro
    INNER JOIN vehiculos v ON v.id_vehiculo = p.id_vehiculo
    WHERE p.id_cliente = ?
";

$stmtPoliza = $mysqli->prepare($sqlPoliza);
$stmtPoliza->bind_param('i', $idCliente);
$stmtPoliza->execute();
$resultPoliza = $stmtPoliza->get_result();

$polizas = [];
while ($fila = $resultPoliza->fetch_assoc()) {
    $polizas[] = $fila;
}

$sqlCuotas = "
    SELECT c.id_cuota, c.numero_cuota, c.fecha_vencimiento, c.importe, c.estado,
           p.id_poliza
    FROM cuotas c
    INNER JOIN polizas p ON p.id_poliza = c.id_poliza
    WHERE p.id_cliente = ?
    ORDER BY p.id_poliza, c.numero_cuota
";

$stmtCuotas = $mysqli->prepare($sqlCuotas);
$stmtCuotas->bind_param('i', $idCliente);
$stmtCuotas->execute();
$resultCuotas = $stmtCuotas->get_result();

$cuotas = [];
while ($cuota = $resultCuotas->fetch_assoc()) {
    $cuotas[] = $cuota;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del cliente</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f7fb;
            margin: 0;
            padding: 20px;
            color: #1f2937;
        }
        .container {
            max-width: 1100px;
            margin: auto;
        }
        .header {
            background: #1e3a8a;
            color: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 18px;
            justify-content: space-between;
        }
        .identidad { display: flex; align-items: center; gap: 18px; }
        .boton {
            display: inline-block;
            padding: 10px 14px;
            border-radius: 7px;
            background: #fff;
            color: #1e3a8a;
            text-decoration: none;
            font-weight: bold;
            white-space: nowrap;
        }
        .logo {
            width: 100px;
            height: 100px;
            object-fit: contain;
            border-radius: 8px;
            background: #fff;
        }
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }
        .card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        th, td {
            text-align: left;
            padding: 12px 14px;
            border-bottom: 1px solid #e5e7eb;
        }
        th {
            background: #e2e8f0;
        }
        .tabla-contenedor { overflow-x: auto; }
        @media (max-width: 600px) {
            body { padding: 14px; }
            .header { align-items: flex-start; }
            .identidad { align-items: flex-start; gap: 12px; }
            .logo { width: 70px; height: 70px; }
        }
        .estado {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: bold;
        }
        .activa { background: #dcfce7; color: #166534; }
        .pendiente { background: #fef3c7; color: #92400e; }
        .vencida { background: #fee2e2; color: #991b1b; }
        .cancelada { background: #e5e7eb; color: #374151; }
        .pagada { background: #dbeafe; color: #1d4ed8; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="identidad">
                <img class="logo" src="logo-seguros.jpeg" alt="RM Seguros">
                <div>
                    <h1>Mi panel</h1>
                    <p>Bienvenido/a, <?php echo htmlspecialchars($_SESSION['cliente_nombre'] . ' ' . $_SESSION['cliente_apellido'], ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
            </div>
            <a class="boton" href="cerrar_sesion.php">Cerrar sesión</a>
        </div>

        <div class="cards">
            <div class="card">
                <h3>Polizas activas</h3>
                <p><?php echo count($polizas); ?></p>
            </div>
            <div class="card">
                <h3>Cuotas pendientes</h3>
                <p><?php echo count(array_filter($cuotas, fn($c) => $c['estado'] === 'Pendiente')); ?></p>
            </div>
            <div class="card">
                <h3>Próximo vencimiento</h3>
                <p>
                    <?php
                    $proximas = array_values(array_filter($cuotas, fn($c) => $c['estado'] === 'Pendiente'));
                    if (!empty($proximas)) {
                        echo $proximas[0]['fecha_vencimiento'];
                    } else {
                        echo 'Sin pendientes';
                    }
                    ?>
                </p>
            </div>
        </div>

        <h2>Mis pólizas</h2>
        <?php if (empty($polizas)): ?>
            <p>No tenés pólizas registradas.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Cobertura</th>
                        <th>Vehículo</th>
                        <th>Patente</th>
                        <th>Vigencia</th>
                        <th>Estado</th>
                        <th>Cuota mensual</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($polizas as $poliza): ?>
                        <tr>
                            <td><?php echo $poliza['nombre_seguro']; ?></td>
                            <td><?php echo $poliza['cobertura']; ?></td>
                            <td><?php echo $poliza['tipo_vehiculo'] . ' ' . $poliza['marca'] . ' ' . $poliza['modelo']; ?></td>
                            <td><?php echo $poliza['patente']; ?></td>
                            <td><?php echo $poliza['fecha_inicio'] . ' al ' . $poliza['fecha_fin']; ?></td>
                            <td>
                                <span class="estado <?php echo strtolower($poliza['estado']); ?>"><?php echo $poliza['estado']; ?></span>
                            </td>
                            <td>$<?php echo number_format($poliza['precio_mensual'], 2, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <h2 style="margin-top: 30px;">Mis cuotas</h2>
        <?php if (empty($cuotas)): ?>
            <p>No tenés cuotas registradas.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Póliza</th>
                        <th>Cuota N°</th>
                        <th>Vencimiento</th>
                        <th>Importe</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cuotas as $cuota): ?>
                        <tr>
                            <td><?php echo $cuota['id_poliza']; ?></td>
                            <td><?php echo $cuota['numero_cuota']; ?></td>
                            <td><?php echo $cuota['fecha_vencimiento']; ?></td>
                            <td>$<?php echo number_format($cuota['importe'], 2, ',', '.'); ?></td>
                            <td>
                                <span class="estado <?php echo strtolower($cuota['estado']); ?>"><?php echo $cuota['estado']; ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>