<?php
include('includes/db.php'); // Conectar a la base de datos

// Procesar el formulario si se envía
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pelicula_id = $_POST['pelicula_id'];
    $horario = $_POST['horario'];
    $asientos_seleccionados = $_POST['asientos']; // Recibir como un array
    $nombre = $_POST['nombre'];

    foreach ($asientos_seleccionados as $asiento) {
        // Verificar si el asiento ya ha sido vendido
        $checkQuery = "SELECT * FROM tickets WHERE pelicula_id = '$pelicula_id' AND asiento = '$asiento' AND horario = '$horario'";
        $checkResult = mysqli_query($connection, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            echo "El asiento $asiento ya está vendido. Por favor, elija otro.";
            exit; // Termina la ejecución si se encuentra un asiento vendido
        }
    }

    // Insertar la compra en la tabla de tickets
    foreach ($asientos_seleccionados as $asiento) {
        $query = "INSERT INTO tickets (pelicula_id, asiento, horario, nombre) VALUES ('$pelicula_id', '$asiento', '$horario', '$nombre')";
        if (!mysqli_query($connection, $query)) {
            echo "Error al comprar el boleto para el asiento $asiento: " . mysqli_error($connection);
        }
    }

    echo "Boleto(s) comprado(s) exitosamente.";
}

// Obtener el id de la película y el horario desde el formulario
$pelicula_id = isset($_GET['pelicula_id']) ? $_GET['pelicula_id'] : null;
$horario = isset($_GET['horario']) ? $_GET['horario'] : null;

// Obtener los asientos vendidos
$asientos = range(1, 20); // Supongamos que hay 20 asientos
$vendidos = [];

// Consultar asientos vendidos
$checkAsientosQuery = "SELECT asiento FROM tickets WHERE pelicula_id = '$pelicula_id' AND horario = '$horario'";
$checkAsientosResult = mysqli_query($connection, $checkAsientosQuery);

while ($row = mysqli_fetch_assoc($checkAsientosResult)) {
    $vendidos[] = $row['asiento'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprar Boleto</title>
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

        .matriz {
            display: grid;
            grid-template-columns: repeat(5, 1fr); /* Cambié a 5 columnas */
            gap: 20px; /* Mayor separación entre los asientos */
            justify-items: center;
            margin-bottom: 20px; /* Espacio inferior */
        }

        .asiento {
            position: relative;
            width: 60px;
            height: 60px;
            background-color: #6a9fb5; /* Color base de los asientos */
            border-radius: 12px; /* Bordes redondeados */
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 20px; /* Aumenté el tamaño de la fuente */
            color: white; /* Cambié el color del texto a blanco */
            cursor: pointer;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3); /* Sombra */
            transition: transform 0.3s;
        }

        .asiento::before {
            content: "";
            position: absolute;
            top: 5px; /* Desplazamiento del cuadro superior */
            left: 5px; /* Desplazamiento del cuadro superior */
            right: 5px;
            bottom: 5px;
            background-color: #93c3d7; /* Color del cuadro inferior */
            border-radius: 10px; /* Bordes redondeados */
            z-index: -1; /* Enviar detrás del asiento */
            box-shadow: 0 3px 5px rgba(0, 0, 0, 0.2); /* Sombra del cuadro inferior */
        }

        .vendido {
            background-color: #e57373; /* Color rojo para asientos vendidos */
            cursor: not-allowed; /* Mostrar que no se pueden seleccionar */
            opacity: 0.6; /* Hacerlos semitransparentes */
            color: white; /* Asegurarse de que el texto se vea bien */
        }

        .seleccionado {
            background-color: #4caf50; /* Color verde oscuro para asientos seleccionados */
            transform: scale(1.1); /* Aumentar tamaño al seleccionar */
        }

        h2, h3 {
            color: #333; /* Cambié el color de los encabezados */
            text-align: center; /* Centrar texto */
        }

        label {
            display: block;
            margin: 10px 0 5px;
            color: #333;
            text-align: center; /* Centrar etiqueta */
        }

        input[type="text"] {
            width: 150px; /* Hacer el campo más pequeño */
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            margin: 0 auto; /* Centrar el campo */
            display: block; /* Asegurar que el campo se muestre como bloque */
        }

        input[type="submit"] {
            background-color: blue; /* Color azul morado para el botón */
            color: white; /* Color del texto */
            padding: 10px 20px; /* Espaciado interno */
            border: none; /* Sin borde */
            border-radius: 5px; /* Bordes redondeados */
            cursor: pointer; /* Cursor de puntero */
            font-size: 16px; /* Tamaño de la fuente */
            transition: background-color 0.3s; /* Efecto de transición */
            display: block; /* Asegurar que el botón se muestre como bloque */
            margin: 0 auto; /* Centrar el botón */
        }

        input[type="submit"]:hover {
            background-color: #7b1fa2; /* Color del botón al pasar el ratón */
        }

        #noSeleccionado, #seleccionado {
            margin-top: 20px;
            color: #333;
            text-align: center; /* Centrar el texto */
        }
    </style>
</head>
<body>
    <h2>Comprar Boleto</h2>
    <h3>Película: <?php echo $pelicula_id; ?> - Horario: <?php echo $horario; ?></h3>

    <h3>Asientos Disponibles:</h3>
    <div class="matriz">
        <?php foreach ($asientos as $asiento): ?>
            <?php if (in_array($asiento, $vendidos)): ?>
                <div class="asiento vendido" title="Asiento vendido"><?php echo $asiento; ?></div>
            <?php else: ?>
                <div class="asiento" onclick="selectAsiento(<?php echo $asiento; ?>)" title="Asiento disponible"><?php echo $asiento; ?></div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <form method="POST" action="comprar_boleto.php">
        <input type="hidden" name="pelicula_id" value="<?php echo $pelicula_id; ?>">
        <input type="hidden" name="horario" value="<?php echo $horario; ?>">
        <label for="nombre">Nombre del Comprador:</label>
        <input type="text" id="nombre" name="nombre" required>
        <input type="hidden" id="asientos" name="asientos[]" value="">
        <input type="submit" value="Comprar Boleto">
    </form>

    <div id="noSeleccionado" style="display:none;">
        <p>No se ha seleccionado ningún asiento.</p>
    </div>

    <div id="seleccionado" style="display:none;">
        <p>Asiento(s) seleccionado(s):</p>
        <ul id="listaAsientos"></ul>
    </div>

    <script>
        let asientosSeleccionados = [];

        function selectAsiento(asiento) {
            const index = asientosSeleccionados.indexOf(asiento);
            const asientoDiv = document.querySelectorAll('.asiento')[asiento - 1];

            if (index === -1) {
                asientosSeleccionados.push(asiento);
                // Cambiar el estado a seleccionado
                asientoDiv.classList.add('seleccionado');
                document.getElementById('noSeleccionado').style.display = 'none';
            } else {
                asientosSeleccionados.splice(index, 1);
                // Cambiar el estado a no seleccionado
                asientoDiv.classList.remove('seleccionado');
            }

            document.getElementById('asientos').value = asientosSeleccionados.join(',');
            updateListaAsientos();
        }

        function updateListaAsientos() {
            const lista = document.getElementById('listaAsientos');
            lista.innerHTML = asientosSeleccionados.map(asiento => `<li>Asiento ${asiento}</li>`).join('');
            document.getElementById('seleccionado').style.display = asientosSeleccionados.length > 0 ? 'block' : 'none';
            document.getElementById('noSeleccionado').style.display = asientosSeleccionados.length === 0 ? 'block' : 'none';
        }
    </script>
</body>
</html>
