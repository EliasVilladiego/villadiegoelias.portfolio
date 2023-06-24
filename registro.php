<?php

/* Datos de conexión
$host = "casapumarejo.es.mysql";
$dbname = "casapumarejo_espumarejo";
$username = "casapumarejo_espumarejo";
$password = "ElPumaRuge";

// Función para insertar un nuevo usuario y contraseña cifrada en la base de datos
function insertarUsuario($usuario, $contrasena) {
global $host, $dbname, $username, $password;
try {
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
// Configuración de excepciones PDO para manejo de errores
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// Cifrar la contraseña antes de insertarla en la base de datos
$contrasena_cifrada = password_hash($contrasena, PASSWORD_DEFAULT);
// Insertar el usuario y la contraseña cifrada en la tabla "admin"
$stmt = $conn->prepare("INSERT INTO admin (usuario, contrasena) VALUES (:usuario, :contrasena)");
$stmt->bindParam(":usuario", $usuario);
$stmt->bindParam(":contrasena", $contrasena_cifrada);
$stmt->execute();
// Cerrar la conexión a la base de datos
$conn = null;
return true;
} catch (PDOException $e) {
echo "Error en la conexión a la base de datos: " . $e->getMessage() . "\n";
return false;
}
}
// Ejemplo de uso de la función
$usuario = "Admin";
$contrasena = "1234";
$resultado = insertarUsuario($usuario, $contrasena);
if ($resultado) {
echo "El usuario ha sido insertado correctamente en la base de datos.";
} else {
echo "Error al insertar el usuario en la base de datos.";
} */


