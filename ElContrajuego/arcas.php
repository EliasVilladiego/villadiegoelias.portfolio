<?php


session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Redirigir al usuario a la página de login
    header('Location: https://casapumarejo.es/ElContrajuego/login.php');
    exit;
}


// Datos de conexión
$host = "casapumarejo.es.mysql";
$dbname = "casapumarejo_espumarejo";
$username = "casapumarejo_espumarejo";
$password = "ElPumaRuge";


try {
    // Conexión a la base de datos
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Configuración de excepciones PDO para manejo de errores
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta a la tabla "arcas"
    $query = "SELECT * FROM arcas ";
    $stmt = $conn->query($query);

    // Obtener un array completo de cada fila de la tabla
    $arcas_array = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($arcas_array as $row) {
        $id = $row['id'];
        $nombre = $row['nombre'];
        $fondo = $row['fondo'];

    }
} catch (PDOException $e) {
    echo "Error al conectar a la base de datos: " . $e->getMessage();
}



try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Configuración de excepciones PDO para manejo de errores
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error en la conexión a la base de datos: " . $e->getMessage() . "\n";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si los campos requeridos han sido completados correctamente
    if (!empty($_POST['beneficiario']) && !empty($_POST['cantidad']) && !empty($_POST['concepto']) && !empty($_POST['fecha'])) {
        // Obtener valores del formulario HTML
        $beneficiario = $_POST["beneficiario"];
        $cantidad = $_POST["cantidad"];
        $concepto = $_POST["concepto"];
        $fecha = $_POST["fecha"];

        // Inserción de datos en la tabla "cuentas"
        $stmt1 = $conn->prepare("INSERT INTO cuentas (beneficiario, cantidad, concepto, fecha) VALUES (:beneficiario, :cantidad, :concepto, :fecha)");
        $stmt1->bindParam(":beneficiario", $beneficiario);
        $stmt1->bindParam(":cantidad", $cantidad);
        $stmt1->bindParam(":concepto", $concepto);
        $stmt1->bindParam(":fecha", $fecha);
        try {
            $stmt1->execute();
            echo "Datos insertados correctamente en la tabla 'cuentas'\n";
        } catch (PDOException $e) {
            echo "Error en la inserción de datos en la tabla 'cuentas': " . $e->getMessage() . "\n";
        }

        // Actualización del valor de "fondo" en la tabla "arcas" para el beneficiario correspondiente
        $nuevoFondo = $cantidad; // Valor de "cantidad" para sumar a "fondo"
        $stmt2 = $conn->prepare("UPDATE arcas SET fondo = fondo + :nuevoFondo WHERE nombre = :beneficiario");
        $stmt2->bindParam(":nuevoFondo", $nuevoFondo);
        $stmt2->bindParam(":beneficiario", $beneficiario);
        try {
            $stmt2->execute();
            echo "Valor de 'fondo' actualizado correctamente en la tabla 'arcas' para el beneficiario: " . $beneficiario . "\n";
        } catch (PDOException $e) {
            echo "Error en la actualización del valor de 'fondo' en la tabla 'arcas': " . $e->getMessage() . "\n";
        }
    }
}




?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css.css">
    <title>Arcas</title>
</head>


<body>


    <br /><br />
    <div class="panel-container-modal">

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">


            <label class="label-bold" for="beneficiario">Beneficiario:</label>
            <select class="select-style" name="beneficiario" required>
                <option value="Marc">Marc</option>
                <option value="Elias">Elias</option>
                <option value="Bote">Bote</option>
            </select><br>


            <label class="label-bold" for="cantidad">cantidad:</label>
            <input class="text-input-style" type="int" name="cantidad" required><br>

            <label class="label-bold" for="concepto">Concepto:</label>
            <input class="text-input-style" type="text" name="concepto" required><br>

            <label class="label-bold" for="fecha">Dia:</label>
            <input class="date-input-style" type="date" id="fecha" name="fecha" required>


            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" name="modificar" value="<?php echo $row['id']; ?>">
                    Añadir entrada
                </button>
        </form>
        <button class="btn btn-primary">
            <a class="ahorario" href="https://casapumarejo.es/index.php">Volver a la pagina principal</a>
        </button>
    </div>

    </div>


    <br />

    <div class="panel-container-modal">
        <table class="admin-table">
            <thead>
                <tr class="admin-header">
                    <th>Nombre</th>
                    <th>Cuentas</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($arcas_array as $row): ?>
                    <tr>
                        <td class="admin-cell">
                            <?php echo $row['nombre']; ?>
                        </td>
                        <td
                            class="admin-cell <?php echo ($row['fondo'] > 0) ? 'positivo' : (($row['fondo'] < 0) ? 'negativo' : 'cero'); ?>">
                            <?php echo $row['fondo']; ?>
                            €</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>


    <br />

    <?php
    try {
        // Conexión a la base de datos
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        // Configuración de excepciones PDO para manejo de errores
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Consulta a la tabla "cuentas"
        $query = "SELECT * FROM cuentas ORDER BY fecha DESC"; // Ordenar por fecha descendente
        $stmt = $conn->query($query);

        // Obtener un array completo de cada fila de la tabla
        $cuentas_array = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Obtener la fecha actual
        $fecha_actual = date("Y-m-d");

        // Ordenar el arreglo por la columna "fecha"
        usort($cuentas_array, function ($a, $b) {
            return strtotime($b['fecha']) - strtotime($a['fecha']); // Ordenar por fecha descendente
        });

    } catch (PDOException $e) {
        echo "Error al conectar a la base de datos: " . $e->getMessage();
    }

    ?>

<div class="panel-container-modal">
        <table class="admin-table">
            <thead>
                <tr class="admin-header">
                    <th>Beneficiario</th>
                    <th>Cantidad</th>
                    <th>Concepto</th>
                    <th>Dia</th>
                </tr>
            </thead>
            <tbody>

        <?php foreach ($cuentas_array as $row): ?>
            <tr>
                <td class="admin-cell">
                    <?php echo $row['beneficiario']; ?>
                </td>
                <td class="admin-cell <?php echo ($row['cantidad'] > 0) ? 'positivo' : (($row['cantidad'] < 0) ? 'negativo' : 'cero'); ?>">
                    <?php echo $row['cantidad']; ?>
                    €</td>
                <td class="admin-cell">
                    <?php echo $row['concepto']; ?>
                </td>
                <td class="admin-cell">
                    <?php echo $row['fecha']; ?>
                </td>
            </tr> 
        <?php endforeach; ?>
        </tbody>
        </table>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>

</body>

</html>