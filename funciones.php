<?php
include('includes/db.php'); // Conectar a la base de datos

// Obtener todas las películas disponibles
$query = "SELECT DISTINCT titulo, director, genero, imagen, id FROM peliculas";
$result = mysqli_query($connection, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funciones de Películas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #4A00E0;
        }
        .movies-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
        .movie-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            width: 250px;
            padding: 15px;
            text-align: center;
        }
        .movie-container img {
            width: 200px; /* Define un ancho fijo */
            height: 300px; /* Define un alto fijo */
            object-fit: cover; /* Asegura que la imagen llene el espacio manteniendo proporciones */
            border-radius: 5px;
        }
        .movie-container h3 {
            color: #4A00E0;
        }
        .movie-container p {
            color: #333;
        }
        select, input[type="submit"] {
            margin-top: 10px;
            padding: 8px;
            width: 100%;
            border: none;
            background-color: #4A00E0;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #8E2DE2;
        }
        hr {
            border: none;
            height: 1px;
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <h2>Funciones de Películas</h2>
    <div class="movies-container">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="movie-container">
                <h3><?php echo $row['titulo']; ?></h3>
                <p>Director: <?php echo $row['director']; ?></p>
                <p>Género: <?php echo $row['genero']; ?></p>

                <!-- Mostrar la imagen de la película -->
                <?php if (!empty($row['imagen'])): ?>
                    <img src="<?php echo $row['imagen']; ?>" alt="<?php echo $row['titulo']; ?>">
                <?php else: ?>
                    <img src="ruta/de/imagen/por_defecto.jpg" alt="Imagen no disponible">
                <?php endif; ?>

                <!-- Formulario para seleccionar horarios -->
                <h4>Horarios Disponibles:</h4>
                <form method="GET" action="comprar_boleto.php">
                    <input type="hidden" name="pelicula_id" value="<?php echo $row['id']; ?>">
                    <label for="horario">Selecciona Horario:</label>
                    <select name="horario" required>
                        <option value="10:00">10:00 AM</option>
                        <option value="13:00">1:00 PM</option>
                        <option value="16:00">4:00 PM</option>
                        <option value="19:00">7:00 PM</option>
                    </select>
                    <input type="submit" value="Comprar Boleto">
                </form>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
