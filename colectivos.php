
<?php include 'cabecera.php'; ?>


<?php
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

  // Consulta a la tabla "colectivos"
  $query = "SELECT * FROM colectivos";
  $stmt = $conn->query($query);

  // Obtener un array completo de cada fila de la tabla
  $colectivos_array = $stmt->fetchAll(PDO::FETCH_ASSOC);

  foreach ($colectivos_array as $row) {
    $id = $row['id'];
    $img = $row['img'];
    $titulo = $row['titulo'];
    $colectivo1 = $row['colectivo'];
    $descripcion = $row['descripcion'];

    // Realizar acciones con los datos obtenidos, por ejemplo, mostrarlos en una página web o hacer alguna otra operación
  }

} catch (PDOException $e) {
  echo "Error al conectar a la base de datos: " . $e->getMessage();
}

try {
  // Conexión a la base de datos
  $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  // Configuración de excepciones PDO para manejo de errores
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


  // Consulta a la tabla "horario" con condición WHERE para filtrar por colectivo = valor de la variable
  $query = "SELECT dia, actividad FROM horario WHERE colectivo = :colectivo";
  $stmt = $conn->prepare($query);
  $stmt->bindParam(':colectivo', $colectivo1); // Utilizar el valor de la variable $colectivo1 en la consulta
  $stmt->execute();

  // Obtener un array completo de cada fila de la tabla
  $horario_array = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
  echo "Error al conectar a la base de datos: " . $e->getMessage();
}


try {
  // Conexión a la base de datos
  $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  // Configuración de excepciones PDO para manejo de errores
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


  // Consulta a la tabla "horario" con condición WHERE para filtrar por colectivo = valor de la variable
  $query = "SELECT img, urlx FROM redes WHERE colectivo = :colectivo";
  $stmt = $conn->prepare($query);
  $stmt->bindParam(':colectivo', $colectivo1); // Utilizar el valor de la variable $colectivo1 en la consulta
  $stmt->execute();

  // Obtener un array completo de cada fila de la tabla
  $horario_array = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
  echo "Error al conectar a la base de datos: " . $e->getMessage();
}


?>

<br><br><br><br>



  <div class="card-container">
  <?php foreach ($colectivos_array as $row): ?>
    <div class="card" id="<?php echo $row['colectivo']; ?>">
      <!-- Agrega el identificador único en el atributo id -->
      <div class="card-img">
        <img src="<?php echo $row['img']; ?>" alt="Imagen">
      </div>
      <div class="card-text">
        <h4>
          <?php echo $row['titulo']; ?>
        </h4>
        <p>
          <?php echo $row['descripcion']; ?>
        </p>
        <p> Actividades: <br>
          <?php
          $colectivo = $row['colectivo'];
          $query = "SELECT dia, actividad FROM horario WHERE colectivo = :colectivo";
          $stmt = $conn->prepare($query);
          $stmt->bindParam(':colectivo', $colectivo);
          $stmt->execute();
          $horario_array = $stmt->fetchAll(PDO::FETCH_ASSOC);
          if (!empty($horario_array)) {
            foreach ($horario_array as $horario) {
              if (isset($horario['dia']) && isset($horario['actividad'])) {
                echo $horario['dia'] . " " . $horario['actividad'] . "<br>";
              }
            }
          } else {
            echo "No hay actividades disponibles para este colectivo.";
          }
          ?>
        </p>

        <div class="div-redes">
    <?php
    $colectivo = $row['colectivo'];
    $query = "SELECT img, urlx FROM redes WHERE colectivo = :colectivo";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':colectivo', $colectivo);
    $stmt->execute();
    $redes_array = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($redes_array)) {
        foreach ($redes_array as $red) {
            if (isset($red['img']) && isset($red['urlx'])) {
                echo '<a href="' . $red['urlx'] . '"><img src="' . $red['img'] . '"></a><br>';
            }
        }
    } 
    ?>
</div>
</div>
</div>
<?php endforeach; ?>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
    crossorigin="anonymous"></script>
</body>

</html>