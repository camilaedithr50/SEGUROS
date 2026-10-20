<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión | RM Seguros</title>
    <style>
        body {
            min-height: 100vh;
            display: grid;
            place-items: center;
            margin: 0;
            background: #f3f6fb;
            color: #1f2937;
            font-family: Arial, sans-serif;
        }
        .login {
            width: min(360px, calc(100% - 48px));
            padding: 28px;
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 8px 28px rgba(15, 35, 65, .12);
        }
        .logo {
            display: block;
            width: 190px;
            max-width: 100%;
            height: auto;
            margin: 0 auto 18px;
        }
        h1 { color: #15325b; text-align: center; }
        label { display: block; margin: 14px 0 6px; }
        input {
            box-sizing: border-box;
            width: 100%;
            padding: 11px;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
        }
        button {
            width: 100%;
            margin-top: 20px;
            padding: 12px;
            border: 0;
            border-radius: 7px;
            background: #1e3a8a;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <main class="login">
        <img class="logo" src="logo-seguros.jpeg" alt="RM Seguros - Tu tranquilidad, nuestro compromiso">
        <h1>Iniciar sesión</h1>
        <form action="script_iniciosesion.php" method="post">
            <label for="usuario">Usuario</label>
            <input type="text" name="user" id="usuario" required>
            <label for="contrasena">Contraseña</label>
            <input type="password" name="password" id="contrasena" required>
            <button type="submit">Iniciar sesión</button>
        </form>
    </main>
</body>
</html>