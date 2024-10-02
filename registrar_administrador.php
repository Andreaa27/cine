<?php
include('includes/db.php'); // Conectar a la base de datos

// Inicializamos las variables de los mensajes
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT); // Encriptar la contraseña

    // Validar campos requeridos
    if (empty($usuario) || empty($contraseña)) {
        $error_message = "Todos los campos son obligatorios.";
    } else {
        $query = "INSERT INTO administradores (usuario, contraseña) VALUES ('$usuario', '$contraseña')";
        if (mysqli_query($connection, $query)) {
            $success_message = "Administrador registrado correctamente.";
        } else {
            $error_message = "Error al registrar el administrador: " . mysqli_error($connection);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Administrador</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center; /* Centrar todo */
        }

        h2 {
            color: #333; /* Cambié el color del encabezado */
            text-align: center; /* Centrar texto */
        }

        form {
            background-color: white; /* Fondo blanco para el formulario */
            padding: 20px; /* Espaciado interno */
            border-radius: 10px; /* Bordes redondeados */
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2); /* Sombra */
            width: 300px; /* Ancho del formulario */
            display: flex;
            flex-direction: column; /* Colocar los elementos en columna */
            align-items: center; /* Centrar elementos dentro del formulario */
        }

        label {
            margin: 10px 0 5px; /* Espaciado entre etiquetas */
            color: #333; /* Color del texto */
            text-align: left; /* Alinear a la izquierda */
            width: 100%; /* Ancho completo */
        }

        input[type="text"], input[type="password"] {
            width: 100%; /* Ancho completo */
            padding: 10px; /* Espaciado interno */
            border: 1px solid #ccc; /* Borde */
            border-radius: 5px; /* Bordes redondeados */
            font-size: 16px; /* Tamaño de la fuente */
            margin-bottom: 15px; /* Espaciado inferior */
        }

        input[type="submit"] {
            background-color: #8e24aa; /* Color azul morado para el botón */
            color: white; /* Color del texto */
            padding: 10px 20px; /* Espaciado interno */
            border: none; /* Sin borde */
            border-radius: 5px; /* Bordes redondeados */
            cursor: pointer; /* Cursor de puntero */
            font-size: 16px; /* Tamaño de la fuente */
            transition: background-color 0.3s; /* Efecto de transición */
            width: 100%; /* Ancho completo */
        }

        input[type="submit"]:hover {
            background-color: #7b1fa2; /* Color del botón al pasar el ratón */
        }

        .mensaje {
            margin-top: 20px;
            color: #333; /* Color del texto de los mensajes */
            text-align: center; /* Centrar texto */
        }
    </style>
</head>
<body>
    <h2>Registrar Administrador</h2>
    <form method="POST" action="registrar_administrador.php">
        <label for="usuario">Usuario:</label>
        <input type="text" id="usuario" name="usuario" required>
        
        <label for="contraseña">Contraseña:</label>
        <input type="password" id="contraseña" name="contraseña" required>
        
        <input type="submit" value="Registrar Administrador">
    </form>

    <?php if (!empty($success_message)): ?>
        <p class="mensaje"><?php echo $success_message; ?></p>
    <?php elseif (!empty($error_message)): ?>
        <p class="mensaje"><?php echo $error_message; ?></p>
    <?php endif; ?>
</body>
</html>
