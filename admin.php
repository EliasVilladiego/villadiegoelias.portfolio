<?php

session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Redirigir al usuario a la página de login
    header('Location: http://casapumarejo.es/login.php');
    exit;
}


// Datos de conexión
$host = "casapumarejo.es.mysql";
$dbname = "casapumarejo_espumarejo";
$username = "casapumarejo_espumarejo";
$password = "ElPumaRuge";


try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Configuración de excepciones PDO para manejo de errores
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error en la conexión a la base de datos: " . $e->getMessage() . "\n";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si los campos requeridos han sido completados correctamente
    if (!empty($_POST['dia']) && !empty($_POST['colectivo']) && !empty($_POST['zona']) && !empty($_POST['actividad']) && !empty($_POST['horai']) && !empty($_POST['horac'])) {
        // Obtener valores del formulario HTML
        $dia = $_POST["dia"];
        $colectivo = $_POST["colectivo"];
        $zona = $_POST["zona"];
        $actividad = $_POST["actividad"];
        $horai = $_POST["horai"];
        $horac = $_POST["horac"];

        // Inserción de datos
        $stmt = $conn->prepare("INSERT INTO horario (dia, colectivo, zona, actividad, horai, horac) VALUES (:dia, :colectivo, :zona, :actividad, :horai, :horac)");
        $stmt->bindParam(":dia", $dia);
        $stmt->bindParam(":colectivo", $colectivo);
        $stmt->bindParam(":zona", $zona);
        $stmt->bindParam(":actividad", $actividad);
        $stmt->bindParam(":horai", $horai);
        $stmt->bindParam(":horac", $horac);
        try {
            $stmt->execute();
            echo "Datos insertados correctamente\n";
        } catch (PDOException $e) {
            echo "Error en la inserción de datos: " . $e->getMessage() . "\n";
        }
    }
}



if (isset($_POST['eliminar'])) {
    $id = $_POST['eliminar'];
    $stmt = $conn->prepare("DELETE FROM horario WHERE id = :id");
    $stmt->bindParam(":id", $id);
    if ($stmt->execute()) {
        echo "La fila se eliminó correctamente";
    } else {
        echo "Hubo un error al eliminar la fila";
    }
}


if (isset($_POST['modificar'])) {
    $id = $_POST['modificar'];
    $dia = $_POST['dia'];
    $zona = $_POST['zona'];
    $horai = $_POST['horai'];
    $horac = $_POST['horac'];
    $actividad = $_POST['actividad'];

    $stmt = $conn->prepare("UPDATE horario SET dia = :dia, zona = :zona, horai = :horai, horac = :horac, actividad = :actividad WHERE id = :id");
    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":dia", $dia);
    $stmt->bindParam(":zona", $zona);
    $stmt->bindParam(":horai", $horai);
    $stmt->bindParam(":horac", $horac);
    $stmt->bindParam(":actividad", $actividad);

    if ($stmt->execute()) {
        echo "La fila se modificó correctamente";
    } else {
        echo "Hubo un error al modificar la fila";
    }
}




if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si los campos requeridos han sido completados correctamente
    if (!empty($_POST['fecha']) && !empty($_POST['hora']) && !empty($_POST['actividad']) && !empty($_POST['acceso']) && !empty($_POST['espacio']) && !empty($_POST['organiza'])) {
        // Obtener valores del formulario HTML
        $fecha = $_POST["fecha"];
        $hora = $_POST["hora"];
        $actividad = $_POST["actividad"];
        $acceso = $_POST["acceso"];
        $espacio = $_POST["espacio"];
        $organiza = $_POST["organiza"];

        // Inserción de datos
        $stmt = $conn->prepare("INSERT INTO calendario (fecha, hora, actividad, acceso, espacio, organiza) VALUES (:fecha, :hora, :actividad, :acceso, :espacio, :organiza)");
        $stmt->bindParam(":fecha", $fecha);
        $stmt->bindParam(":hora", $hora);
        $stmt->bindParam(":actividad", $actividad);
        $stmt->bindParam(":acceso", $acceso);
        $stmt->bindParam(":espacio", $espacio);
        $stmt->bindParam(":organiza", $organiza);
        try {
            $stmt->execute();
            echo "Datos insertados correctamente\n";
        } catch (PDOException $e) {
            echo "Error en la inserción de datos: " . $e->getMessage() . "\n";
        }
    }
}


if (isset($_POST['eliminarc'])) {
    $id = $_POST['eliminarc'];
    $stmt = $conn->prepare("DELETE FROM calendario WHERE id = :id");
    $stmt->bindParam(":id", $id);
    if ($stmt->execute()) {
        echo "La fila se eliminó correctamente";
    } else {
        echo "Hubo un error al eliminar la fila";
    }
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["usuario"]) && isset($_POST["contrasena"]) && isset($_POST["nuevaContrasena"])) {
        $usuario = $_POST["usuario"];
        $contrasena = $_POST["contrasena"];
        $nuevaContrasena = $_POST["nuevaContrasena"];


    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT contrasena FROM admin WHERE usuario = :usuario");
        $stmt->bindParam(":usuario", $usuario);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($rows) > 0) {
            foreach ($rows as $row) {
                $contrasena_cifrada = $row['contrasena'];

                if (password_verify($contrasena, $contrasena_cifrada)) {
                    $nuevaContrasenaCifrada = password_hash($nuevaContrasena, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("UPDATE admin SET contrasena = :nuevaContrasena WHERE usuario = :usuario");
                    $stmt->bindParam(":nuevaContrasena", $nuevaContrasenaCifrada);
                    $stmt->bindParam(":usuario", $usuario);
                    $stmt->execute();

                    echo "La contraseña se ha actualizado exitosamente.";
                } else {
                    $error = "La contraseña actual es incorrecta.";
                }
            }
        } else {
            $error = "El usuario no existe en la base de datos.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    $conn = null;
} }

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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>administración</title>
</head>

<body>

    <br /> <br /> <br />


    <div class="panel-container-modal">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModadmin">
            Agregar nuevo horario semanal
        </button>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModadminC">
            Agregar nuevo evento al calendario
        </button>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModadminP">
            Cambiar contraseña
        </button>
        <button class="btn btn-primary">
            <a class="ahorario" href="http://casapumarejo.es/actividades.php">Volver a la vista del horario</a>
        </button>
    </div>

    <br /> <br />


    <?php
    try {
        // Conexión a la base de datos
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        // Configuración de excepciones PDO para manejo de errores
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Consulta a la tabla "horario"
        $query = "SELECT * FROM horario";
        $stmt = $conn->query($query);

        // Obtener un array completo de cada fila de la tabla
        $horario_array = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($horario_array as $row) {
            $id = $row['id'];
            $dia = $row['dia'];
            $zona = $row['zona'];
            $horai = $row['horai'];
            $horac = $row['horac'];
            $actividad = $row['actividad'];
        }


    } catch (PDOException $e) {
        echo "Error al conectar a la base de datos: " . $e->getMessage();
    }
    ?>

    <div class="divadmin">
        <table class="admin-table">
            <thead>
                <tr class="admin-header">
                    <th>Día</th>
                    <th>Zona</th>
                    <th>Hora de inicio</th>
                    <th>Hora de cierre</th>
                    <th>Actividad</th>
                    <th>Modificar</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($horario_array as $row): ?>
                    <tr>
                        <td class="admin-cell">
                            <?php echo $row['dia']; ?>
                        </td>
                        <td class="admin-cell">
                            <?php echo $row['zona']; ?>
                        </td>
                        <td class="admin-cell">
                            <?php echo $row['horai']; ?>
                        </td>
                        <td class="admin-cell">
                            <?php echo $row['horac']; ?>
                        </td>
                        <td class="admin-cell">
                            <?php echo $row['actividad']; ?>
                        </td>
                        <td class="admin-cell"> <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#exampleModal<?php echo $row['id']; ?>">
                                Modificar
                            </button></td>

                        <td class="admin-cell">
                            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                <button type="submit" class="btn btn-danger" name="eliminar"
                                    value="<?php echo $row['id']; ?>">
                                    Eliminar
                                </button>
                            </form>
                        </td>

                    </tr>


                    <div class="modal fade" id="exampleModal<?php echo $row['id']; ?>" tabindex="-1"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Modificar datos</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                                    <label class="label-bold" for="dia">Día:</label>
                                    <select class="select-style" name="dia" required>
                                        <option value="">Selecciona un día</option>
                                        <option value="lunes-table">Lunes</option>
                                        <option value="martes-table">Martes</option>
                                        <option value="miércoles-table">Miércoles</option>
                                        <option value="jueves-table">Jueves</option>
                                        <option value="viernes-table">Viernes</option>
                                        <option value="sábado-table">Sábado</option>
                                        <option value="domingo-table">Domingo</option>
                                    </select><br>


                                    <label class="label-bold" for="zona">Zona:</label>
                                    <select class="select-style" name="zona" required>
                                        <option value="bajo 5">Bajo 5</option>
                                        <option value="bajo 4">Bajo 4</option>
                                        <option value="monumental">Monumental</option>
                                        <option value="entreplanta">Entreplanta</option>
                                        <option value="oficina">Oficina</option>
                                        <option value="espacio rosa moreno">Espacio Rosa Moreno</option>
                                    </select><br>

                                    <label class="label-bold" for="horai">Hora de inicio:</label>
                                    <select class="select-style" name="horai" required>
                                        <option value="09:00">09:00</option>
                                        <option value="10:00">10:00</option>
                                        <option value="11:00">11:00</option>
                                        <option value="12:00">12:00</option>
                                        <option value="13:00">13:00</option>
                                        <option value="14:00">14:00</option>
                                        <option value="15:00">15:00</option>
                                        <option value="16:00">16:00</option>
                                        <option value="17:00">17:00</option>
                                        <option value="18:00">18:00</option>
                                        <option value="19:00">19:00</option>
                                        <option value="20:00">20:00</option>
                                        <option value="21:00">21:00</option>
                                        <option value="22:00">22:00</option>
                                        <option value="23:00">23:00</option>
                                        <option value="24:00">24:00</option>
                                    </select><br>

                                    <label class="label-bold" for="horac">Hora de cierre:</label>
                                    <select class="select-style" name="horac" required>
                                        <option value="10:00">10:00</option>
                                        <option value="11:00">11:00</option>
                                        <option value="12:00">12:00</option>
                                        <option value="13:00">13:00</option>
                                        <option value="14:00">14:00</option>
                                        <option value="15:00">15:00</option>
                                        <option value="16:00">16:00</option>
                                        <option value="17:00">17:00</option>
                                        <option value="18:00">18:00</option>
                                        <option value="19:00">19:00</option>
                                        <option value="20:00">20:00</option>
                                        <option value="21:00">21:00</option>
                                        <option value="22:00">22:00</option>
                                        <option value="23:00">23:00</option>
                                        <option value="24:00">24:00</option>
                                        <option value="01:00">01:00</option>
                                    </select><br>


                                    <label class="label-bold" for="actividad">Actividad:</label>
                                    <input class="text-input-style" type="text" name="actividad" required><br>


                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary" name="modificar"
                                            value="<?php echo $row['id']; ?>">
                                            Guardar cambios
                                        </button>
                                </form>




                            </div>
                        </div>
                    </div>
        </div>


    <?php endforeach; ?>
    </tbody>
    </table>
    </div>





    <div class="modal fade" id="exampleModadmin" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Crear evento de horario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">


                    <label class="label-bold" for="dia">Día:</label>
                    <select class="select-style" name="dia" required>
                        <option value="Lunes">Lunes</option>
                        <option value="Martes">Martes</option>
                        <option value="Míercoles">Miércoles</option>
                        <option value="Jueves">Jueves</option>
                        <option value="Viernes">Viernes</option>
                        <option value="Sábado">Sábado</option>
                        <option value="Domingo">Domingo</option>
                    </select><br>


                    <label class="label-bold" for="colectivo">Colectivo:</label>
                    <select class="select-style" name="colectivo" required>

                        <option value="#A9DFBF">Asociación Vecinal La Revuelta</option>
                        <option value="#DDE5F5">Asociación Trabajadoras y Trabajadores del Hogar de Sevilla</option>
                        <option value="#ADD8E6">Aula de Teatro Antropológico Pumera</option>
                        <option value="#C5E5D5">Bibliopuma</option>
                        <option value="#EAD5F2">Buruzbera Cía</option>
                        <option value="#D1E7C7">El Contrajuego</option>
                        <option value="#F3B3A6">Mercadillo Cultural Pumarejo</option>
                        <option value="#F8D1CB">Mujeres Supervivientes</option>
                        <option value="#F2D8B3">Oficina de Derechos Sociales de Sevilla (ODS)</option>
                        <option value="#FFFF99">PAH - Plataforma de Afectados por la Hipoteca</option>
                        <option value="#D2B4DE">Plataforma Salva tus Árboles</option>
                        <option value="#E9F1F7">Taller de Fotografía</option>
                        <option value="#A8C3A8">Taller de Italiano</option>
                        <option value="#F7E2F5">Yoga, Alimentación, Salud y Crecimiento Personal</option>

                    </select><br>

                    <label class="label-bold" for="zona">Zona:</label>
                    <select class="select-style" name="zona" required>
                        <option value="bajo 5">Bajo 5</option>
                        <option value="bajo 4">Bajo 4</option>
                        <option value="monumental">Monumental</option>
                        <option value="entreplanta">Entreplanta</option>
                        <option value="oficina">Oficina</option>
                        <option value="espacio rosa moreno">Espacio Rosa Moreno</option>
                    </select><br>

                    <label class="label-bold" for="horai">Hora de inicio:</label>
                    <select class="select-style" name="horai" required>
                        <option value="09:00">09:00</option>
                        <option value="10:00">10:00</option>
                        <option value="11:00">11:00</option>
                        <option value="12:00">12:00</option>
                        <option value="13:00">13:00</option>
                        <option value="14:00">14:00</option>
                        <option value="15:00">15:00</option>
                        <option value="16:00">16:00</option>
                        <option value="17:00">17:00</option>
                        <option value="18:00">18:00</option>
                        <option value="19:00">19:00</option>
                        <option value="20:00">20:00</option>
                        <option value="21:00">21:00</option>
                        <option value="22:00">22:00</option>
                        <option value="23:00">23:00</option>
                        <option value="24:00">24:00</option>
                    </select><br>

                    <label class="label-bold" for="horac">Hora de cierre:</label>
                    <select class="select-style" name="horac" required>
                        <option value="10:00">10:00</option>
                        <option value="11:00">11:00</option>
                        <option value="12:00">12:00</option>
                        <option value="13:00">13:00</option>
                        <option value="14:00">14:00</option>
                        <option value="15:00">15:00</option>
                        <option value="16:00">16:00</option>
                        <option value="17:00">17:00</option>
                        <option value="18:00">18:00</option>
                        <option value="19:00">19:00</option>
                        <option value="20:00">20:00</option>
                        <option value="21:00">21:00</option>
                        <option value="22:00">22:00</option>
                        <option value="23:00">23:00</option>
                        <option value="24:00">24:00</option>
                        <option value="01:00">01:00</option>
                    </select><br>



                    <label class="label-bold" for="actividad">Actividad:</label>
                    <input class="text-input-style" type="text" name="actividad" required><br>



                    <input class="text-input-style" type="submit" value="Crear Evento">
                </form>

            </div>
        </div>
    </div>
    </div>
    







    <div class="modal fade" id="exampleModadminC" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Crear evento de calendario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <?php if (isset($error)) { ?>
                    <p class="error">
                        <?php echo htmlspecialchars($error); ?>
                    </p>
                <?php } ?>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="modal-calendario">
                        <label class="label-bold" for="fecha">Fecha:</label>
                        <input class="date-input-style" type="date" id="fecha" name="fecha" required>
                        <label class="label-bold" for="hora">Horas:</label>
                        <input class="text-input-style" type="text" id="hora" name="hora" required>
                        <label class="label-bold" for="actividad">Actividad:</label>
                        <input class="text-input-style" type="text" id="actividad" name="actividad" required>
                        <label class="label-bold" for="acceso">Acceso:</label>
                        <input class="text-input-style" type="text" id="acceso" name="acceso" required>
                        <label class="label-bold" for="espacio">Espacio:</label>
                        <input class="text-input-style" type="text" id="espacio" name="espacio" required>
                        <label class="label-bold" for="organiza">Organiza:</label>
                        <input class="text-input-style" type="text" id="organiza" name="organiza" required>
                        <br/>
                        <input type="submit" class="btn btn-primary" value="Crear evento">
                    </div>
                </form>
            </div>
        </div>
    </div>
   



    <div class="modal fade" id="exampleModadminP" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cambiar contraseña</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="modal-calendario">
                        <form action="registro.php" method="post">
                            <label class="label-bold" for="usuario">Usuario:</label>
                            <input class="text-input-style" type="text" name="usuario" id="usuario" required>
                            <label class="label-bold" for="contrasena">Contraseña actual:</label>
                            <input class="text-input-style" type="password" name="contrasena" id="contrasena" required>
                            <label class="label-bold" for="nuevaContrasena">Nueva contraseña:</label>
                            <input class="text-input-style" type="password" name="nuevaContrasena" id="nuevaContrasena"
                                required>
                            <br/>
                            <input type="submit" class="btn btn-primary" value="Actualizar Contraseña">
                        </form>
                    </div>
                </form>
            </div>
        </div>
    </div>
   

    <?php
    try {
        // Conexión a la base de datos
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        // Configuración de excepciones PDO para manejo de errores
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Consulta a la tabla "calendario"
        $query = "SELECT * FROM calendario";
        $stmt = $conn->query($query);

        // Obtener un array completo de cada fila de la tabla
        $calendario_array = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Ordenar el arreglo por la columna "fecha"
        usort($calendario_array, function ($a, $b) {
            return strtotime($a['fecha']) - strtotime($b['fecha']);
        });

        // Obtener la fecha actual
        $fecha_actual = date("Y-m-d");

        // Recorrer el arreglo y eliminar filas cuya fecha haya pasado
        foreach ($calendario_array as $row) {
            if ($row['fecha'] < $fecha_actual) {
                // Ejecutar consulta SQL para eliminar la fila de la base de datos
                $eliminarc = $row['id'];
                $sql = "DELETE FROM calendario WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$eliminarc]);
            }
        }

    } catch (PDOException $e) {
        echo "Error al conectar a la base de datos: " . $e->getMessage();
    }
    ?>

    

    <div class="divadmin">
        <table class="admin-table ">
            <thead>
                <tr class="admin-header">
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Actividad</th>
                    <th>Zona</th>
                    <th>Acceso</th>
                    <th>Organiza</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($calendario_array as $row): ?>
                    <tr>

                        <td class="admin-cell">
                            <?php echo $row['fecha']; ?>
                        </td>
                        <td class="admin-cell">
                            <?php echo $row['hora']; ?>
                        </td>
                        <td class="admin-cell">
                            <?php echo $row['actividad']; ?>
                        </td>
                        <td class="admin-cell">
                            <?php echo $row['zona']; ?>
                        </td>
                        <td class="admin-cell">
                            <?php echo $row['acceso']; ?>
                        </td>
                        <td class="admin-cell">
                            <?php echo $row['organiza']; ?>
                        </td>

                        <td class="admin-cell">
                            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                <button type="submit" class="btn btn-danger" name="eliminarc"
                                    value="<?php echo $row['id']; ?>">
                                    Eliminar
                                </button>
                            </form>
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