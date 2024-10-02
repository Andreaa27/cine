<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: loginadmi.php'); // Redirigir al login si no hay sesión activa
    exit();
}

include('includes/db.php'); // Conectar a la base de datos

// Inicializamos las variables de los mensajes
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $director = $_POST['director'];
    $genero = $_POST['genero'];
    $imagen = $_POST['imagen'];

    // Validar campos requeridos
    if (empty($titulo) || empty($director) || empty($genero)) {
        $error_message = "Todos los campos son obligatorios.";
    } else {
        // Verificar si el título ya existe
        $check_query = "SELECT * FROM peliculas WHERE titulo = '$titulo'";
        $result = mysqli_query($connection, $check_query);

        if (mysqli_num_rows($result) > 0) {
            $error_message = "El título de la película ya existe.";
        } else {
            // Si no existe, realizar la inserción
            $query = "INSERT INTO peliculas (titulo, director, genero, imagen) VALUES ('$titulo', '$director', '$genero', '$imagen')";
            if (mysqli_query($connection, $query)) {
                $success_message = "Película registrada correctamente.";
            } else {
                $error_message = "Error al registrar la película: " . mysqli_error($connection);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Película</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 400px;
            text-align: center;
        }
        h2 {
            color: #4b3f72;
            margin-bottom: 20px;
        }
        label {
            color: #5a5a5a;
            font-weight: bold;
            display: block;
            margin-bottom: 10px;
        }
        input[type="text"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        input[type="submit"] {
            background-color: #4b3f72;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #7b6f9d;
        }
        .error-message, .success-message {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            display: none; /* Ocultar por defecto */
        }
        .error-message {
            color: #e74c3c;
            background-color: #fdd;
        }
        .success-message {
            color: #2ecc71;
            background-color: #dfd;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Registrar Película</h2>
        <form method="POST" action="registrar_pelicula.php">
            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo" required>
            
            <label for="director">Director:</label>
            <input type="text" id="director" name="director" required>
            
            <label for="genero">Género:</label>
            <input type="text" id="genero" name="genero" required>
            
            <label for="imagen">Imagen (URL):</label>
            <input type="text" id="imagen" name="imagen">
            
            <input type="submit" value="Registrar Película">
        </form>

        <!-- Mensajes de error o éxito -->
        <?php if (!empty($success_message)): ?>
            <div class="success-message" style="display: block;"><?php echo $success_message; ?></div>
        <?php elseif (!empty($error_message)): ?>
            <div class="error-message" style="display: block;"><?php echo $error_message; ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
