<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['id_empleado']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header('Location: Index.php');
    exit();
}

if (empty($_SESSION['token_admin'])) {
    $_SESSION['token_admin'] = bin2hex(random_bytes(32));
}
$mensajeAdmin = $_SESSION['mensaje_admin'] ?? '';
unset($_SESSION['mensaje_admin']);

$sql = "
    SELECT c.id_cliente, c.nombre, c.apellido, c.dni, c.telefono, c.email,
           p.id_poliza, p.estado AS estado_poliza, p.fecha_inicio, p.fecha_fin,
           p.precio_mensual, ts.nombre_seguro, ts.cobertura,
           v.tipo_vehiculo, v.marca, v.modelo, v.patente,
           COALESCE(pagos.cuotas_total, 0) AS cuotas_total,
           COALESCE(pagos.cuotas_pendientes, 0) AS cuotas_pendientes,
           COALESCE(pagos.cuotas_vencidas, 0) AS cuotas_vencidas,
           COALESCE(pagos.cuotas_sin_pagar, 0) AS cuotas_sin_pagar,
           COALESCE(pagos.saldo_pendiente, 0) AS saldo_pendiente
    FROM clientes c
    LEFT JOIN polizas p ON p.id_cliente = c.id_cliente
    LEFT JOIN tipos_seguros ts ON ts.id_tipo_seguro = p.id_tipo_seguro
    LEFT JOIN vehiculos v ON v.id_vehiculo = p.id_vehiculo
    LEFT JOIN (
        SELECT id_poliza,
               COUNT(*) AS cuotas_total,
               SUM(CASE WHEN estado = 'Pendiente' THEN 1 ELSE 0 END) AS cuotas_pendientes,
               SUM(CASE
                   WHEN estado = 'Vencida' OR (estado = 'Pendiente' AND fecha_vencimiento < CURDATE())
                   THEN 1 ELSE 0
               END) AS cuotas_vencidas,
               SUM(CASE WHEN estado <> 'Pagada' THEN 1 ELSE 0 END) AS cuotas_sin_pagar,
               SUM(CASE WHEN estado <> 'Pagada' THEN importe ELSE 0 END) AS saldo_pendiente
        FROM cuotas
        GROUP BY id_poliza
    ) pagos ON pagos.id_poliza = p.id_poliza
    ORDER BY c.apellido, c.nombre, p.id_poliza
";

$result = $mysqli->query($sql);
$registros = [];
$clientesContados = [];
$polizasContadas = 0;
$cuotasPendientes = 0;
$saldoPendienteTotal = 0;

while ($fila = $result->fetch_assoc()) {
    $registros[] = $fila;
    $clientesContados[$fila['id_cliente']] = true;
    if ($fila['id_poliza'] !== null) {
        $polizasContadas++;
        $cuotasPendientes += (int) $fila['cuotas_sin_pagar'];
        $saldoPendienteTotal += (float) $fila['saldo_pendiente'];
    }
}

function escapar($valor)
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de administración</title>
    <style>
        body {
            margin: 0;
            padding: 24px;
            background: #f3f6fb;
            color: #1f2937;
            font-family: Arial, sans-serif;
        }
        .container { max-width: 1200px; margin: 0 auto; }
        .header {
            padding: 22px;
            margin-bottom: 22px;
            border-radius: 12px;
            color: #fff;
            background: #1e3a8a;
            display: flex;
            align-items: center;
            gap: 18px;
        }
        .logo {
            width: 100px;
            height: 100px;
            object-fit: contain;
            border-radius: 8px;
            background: #fff;
        }
        .resumen {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }
        .tarjeta {
            padding: 18px;
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .07);
        }
        .tarjeta h2 { margin: 0 0 8px; font-size: 15px; color: #4b5563; }
        .tarjeta p { margin: 0; font-size: 24px; font-weight: bold; }
        .tabla-contenedor { overflow-x: auto; }
        table {
            width: 100%;
            min-width: 1050px;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .07);
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
            vertical-align: top;
        }
        th { background: #e2e8f0; }
        .estado {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 999px;
            background: #e5e7eb;
            font-size: 12px;
            font-weight: bold;
        }
        .activa, .al-dia { color: #166534; background: #dcfce7; }
        .vencida, .con-deuda { color: #991b1b; background: #fee2e2; }
        .cancelada, .sin-poliza { color: #374151; background: #e5e7eb; }
        .sin-cuotas { color: #92400e; background: #fef3c7; }
        .sin-resultados { padding: 20px; background: #fff; }
        .acciones { display: flex; flex-wrap: wrap; gap: 8px; }
        .boton, .boton-peligro {
            display: inline-block;
            padding: 8px 10px;
            border: 0;
            border-radius: 6px;
            background: #1e3a8a;
            color: #fff;
            text-decoration: none;
            font: inherit;
            cursor: pointer;
        }
        .boton-peligro { background: #b91c1c; }
        .mensaje { padding: 14px 18px; margin: 0 0 18px; border-radius: 8px; background: #dbeafe; color: #1e3a8a; }
        .barra-acciones { display: flex; justify-content: space-between; gap: 12px; align-items: center; margin: 20px 0; }
        .header { justify-content: space-between; }
        .identidad { display: flex; align-items: center; gap: 18px; }
        @media (max-width: 600px) {
            body { padding: 14px; }
            .barra-acciones { align-items: flex-start; flex-direction: column; }
            .header { align-items: flex-start; }
            .identidad { align-items: flex-start; }
            .logo { width: 70px; height: 70px; }
        }
    </style>
</head>
<body>
    <main class="container">
        <header class="header">
            <div class="identidad">
                <img class="logo" src="logo-seguros.jpeg" alt="RM Seguros">
                <div>
                    <h1>Panel de administración</h1>
                    <p>Clientes, pólizas y estado de pagos.</p>
                </div>
            </div>
            <a class="boton" href="cerrar_sesion.php">Cerrar sesión</a>
        </header>

        <?php if ($mensajeAdmin !== ''): ?>
            <p class="mensaje"><?php echo escapar($mensajeAdmin); ?></p>
        <?php endif; ?>

        <section class="resumen" aria-label="Resumen">
            <article class="tarjeta">
                <h2>Clientes</h2>
                <p><?php echo count($clientesContados); ?></p>
            </article>
            <article class="tarjeta">
                <h2>Pólizas</h2>
                <p><?php echo $polizasContadas; ?></p>
            </article>
            <article class="tarjeta">
                <h2>Cuotas sin pagar</h2>
                <p><?php echo $cuotasPendientes; ?></p>
            </article>
            <article class="tarjeta">
                <h2>Saldo por cobrar</h2>
                <p>$<?php echo number_format($saldoPendienteTotal, 2, ',', '.'); ?></p>
            </article>
        </section>

        <div class="barra-acciones">
            <h2>Clientes y sus pólizas</h2>
            <a class="boton" href="admin_gestion.php?accion=nuevo">Registrar cliente, vehículo y póliza</a>
        </div>
        <?php if (empty($registros)): ?>
            <p class="sin-resultados">No hay clientes registrados.</p>
        <?php else: ?>
            <div class="tabla-contenedor">
                <table>
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>DNI</th>
                            <th>Contacto</th>
                            <th>Póliza</th>
                            <th>Vehículo</th>
                            <th>Vigencia</th>
                            <th>Estado póliza</th>
                            <th>Pago</th>
                            <th>Saldo pendiente</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registros as $fila): ?>
                            <?php
                            if ($fila['id_poliza'] === null) {
                                $estadoPago = 'Sin póliza';
                                $clasePago = 'sin-poliza';
                            } elseif ((int) $fila['cuotas_total'] === 0) {
                                $estadoPago = 'Sin cuotas';
                                $clasePago = 'sin-cuotas';
                            } elseif ((int) $fila['cuotas_pendientes'] === 0 && (float) $fila['saldo_pendiente'] === 0.0) {
                                $estadoPago = 'Al día';
                                $clasePago = 'al-dia';
                            } elseif ((int) $fila['cuotas_vencidas'] > 0) {
                                $estadoPago = 'Con cuotas vencidas';
                                $clasePago = 'con-deuda';
                            } elseif ((int) $fila['cuotas_pendientes'] > 0) {
                                $estadoPago = 'Con cuotas pendientes';
                                $clasePago = 'sin-cuotas';
                            }
                            ?>
                            <tr>
                                <td><?php echo escapar($fila['apellido'] . ', ' . $fila['nombre']); ?></td>
                                <td><?php echo escapar($fila['dni']); ?></td>
                                <td>
                                    <?php echo escapar($fila['telefono'] ?: 'Sin teléfono'); ?><br>
                                    <?php echo escapar($fila['email'] ?: 'Sin email'); ?>
                                </td>
                                <td>
                                    <?php if ($fila['id_poliza'] === null): ?>
                                        <span class="estado sin-poliza">Sin póliza</span>
                                    <?php else: ?>
                                        <?php echo escapar($fila['nombre_seguro'] . ' (' . $fila['cobertura'] . ')'); ?><br>
                                        $<?php echo number_format((float) $fila['precio_mensual'], 2, ',', '.'); ?> / mes
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($fila['id_poliza'] !== null): ?>
                                        <?php echo escapar($fila['tipo_vehiculo'] . ' ' . $fila['marca'] . ' ' . $fila['modelo']); ?><br>
                                        Patente: <?php echo escapar($fila['patente']); ?>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($fila['id_poliza'] !== null): ?>
                                        <?php echo escapar($fila['fecha_inicio'] . ' al ' . $fila['fecha_fin']); ?>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($fila['id_poliza'] !== null): ?>
                                        <span class="estado <?php echo escapar(strtolower($fila['estado_poliza'])); ?>">
                                            <?php echo escapar($fila['estado_poliza']); ?>
                                        </span>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="estado <?php echo $clasePago; ?>"><?php echo escapar($estadoPago); ?></span>
                                    <?php if ($fila['id_poliza'] !== null): ?>
                                        <br>Cuotas sin pagar: <?php echo (int) $fila['cuotas_sin_pagar']; ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($fila['id_poliza'] !== null): ?>
                                        $<?php echo number_format((float) $fila['saldo_pendiente'], 2, ',', '.'); ?>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="acciones">
                                        <a class="boton" href="admin_gestion.php?accion=editar&amp;id_cliente=<?php echo (int) $fila['id_cliente']; ?><?php if ($fila['id_poliza'] !== null) { echo '&amp;id_poliza=' . (int) $fila['id_poliza']; } ?>">Editar</a>
                                        <form method="post" action="admin_gestion.php" onsubmit="return confirm('Se eliminará el cliente y todos sus vehículos, pólizas, cuotas, pagos e historial de siniestros. Esta acción no se puede deshacer. ¿Continuar?');">
                                            <input type="hidden" name="token_admin" value="<?php echo escapar($_SESSION['token_admin']); ?>">
                                            <input type="hidden" name="accion" value="eliminar_cliente">
                                            <input type="hidden" name="id_cliente" value="<?php echo (int) $fila['id_cliente']; ?>">
                                            <button class="boton-peligro" type="submit">Eliminar cliente</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
