<?php

$host = "casapumarejo.es.mysql";
$dbname = "casapumarejo_espumarejo";
$username = "casapumarejo_espumarejo";
$password = "ElPumaRuge";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $contrasena = $_POST["contrasena"];

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT contrasena FROM adminc WHERE usuario = :usuario");
        $stmt->bindParam(":usuario", $usuario);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Verificar si se encontraron filas con el usuario dado
        if (count($rows) > 0) {
            foreach ($rows as $row) {
                $contrasena_cifrada = $row['contrasena'];

                if (password_verify($contrasena, $contrasena_cifrada)) {
                    // Iniciar sesión y redirigir al usuario a la página de administración
                    session_start();
                    $_SESSION['logged_in'] = true; // Establecer la variable de sesión como verdadera
                    header('Location: https://casapumarejo.es/ElContrajuego/arcas.php');
                    exit;
                }
            }
        }

        $error = 'Usuario o contraseña incorrectos';

    } catch (PDOException $e) {
        echo "Error en la conexión a la base de datos: " . $e->getMessage() . "\n";
    }
}

?>


<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="css.css">
</head>
<body>
    <div class="login-box">
        <h2 class="titulo">Login</h2>
        <?php if (isset($error)) { ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php } ?>
        <form action="login.php" method="post">
            <label for="usuario" class="label">Usuario</label>
            <input type="text" name="usuario" id="usuario" class="input" required>
            <label for="contrasena" class="label">Contraseña</label>
            <input type="password" name="contrasena" id="contrasena" class="input" required>
            <input type="submit" value="Iniciar sesión" class="boton">
        </form>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
crossorigin="anonymous"></script>
</body>
</html>
