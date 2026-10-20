<?php
$mysqli = new mysqli('localhost', 'root', '', 'seguros_vehiculares');

if ($mysqli->connect_error) {
    die('Error de conexión: ' . $mysqli->connect_error);
}

$mysqli->set_charset('utf8mb4');
?>
