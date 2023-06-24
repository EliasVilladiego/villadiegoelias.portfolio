<?php include 'cabecera.php'; ?>


<br /> <br />
<h2 class="center"> Actividades </h2>
<br /> <br />

<p class="center">La <strong>Asociación Casa del Pumarejo</strong> la conformamos quienes habitamos la Casa Grande: las vecinas que
  residimos, los comercios y los colectivos sociales que, con <strong>nuestras actividades</strong> damos vida y
  defendemos este emblemático y valioso edificio.&nbsp;Éstas engloban tanto las
  <strong>actividades&nbsp;habituales,&nbsp;</strong>que de manera continuada realizamos los integrantes de la
  asociación, como las <strong>actividades extraordinarias,</strong> que son las que de modo puntual realizamos tanto
  por nosotros como otros vecinos y colectivos no pertenecientes a la Casa, ya que <strong>nuestros espacios están
    disponibles, <u>de modo gratuito</u>, para cualquiera que desee realizar una actividad.</strong></p>

<?php
$days = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo"];
$hours = ["09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00", "19:00", "20:00", "21:00", "22:00", "23:00", "24:00", "01:00"];

$currentTableIndex = 0;
?>

<br> <br>

<div class="div-btn">
  <button id="diaa" class="btnhorario">&#8592;</button>
  <h2 id="day-header">
    <?php echo ($days[$currentTableIndex]); ?>
  </h2>
  <button id="diad" class="btnhorario">&#8594;</button>
</div>

<?php
foreach ($days as $index => $day) {
  $displayStyle = $index === $currentTableIndex ? "block" : "none";
  echo "<table id='{$day}' class='day-table' style='display:{$displayStyle}'>";
  echo "<thead>";
  echo "<tr class='zone-time'>";
  echo "<th colspan='7'>" . ($day) . "</th>";
  echo "</tr>";
  echo "<tr class='{$day}-header'>";
  echo "<th class='zone-time' name=zona hora>Zona/hora</th>";
  echo "<th class='zone-time' name='bajo 5'>Bajo 5</th>";
  echo "<th class='zone-time' name='bajo 4'>Bajo 4</th>";
  echo "<th class='zone-time' name='monumental'>Monumental</th>";
  echo "<th class='zone-time' name='entreplanta'>Entreplanta</th>";
  echo "<th class='zone-time' name='oficina'>Oficina</th>";
  echo "<th class='zone-time' name='espacio rosa moreno'>Espacio Rosa Moreno</th>";
  echo "</tr>";
  echo "</thead>";
  echo "<tbody>";
  foreach ($hours as $hour) {
    echo "<tr class='hour-row'>";
    echo "<th class='zone-time'>$hour</th>";
    echo "<td class='zone-cell bajo-5'><div class='cell-content'></div></td>";
    echo "<td class='zone-cell bajo-4'><div class='cell-content'></div></td>";
    echo "<td class='zone-cell monumental'><div class='cell-content'></div></td>";
    echo "<td class='zone-cell entreplanta'><div class='cell-content'></div></td>";
    echo "<td class='zone-cell oficina'><div class='cell-content'></div></td>";
    echo "<td class='zone-cell espacio-rosa-moreno'><div class='cell-content'></div></td>";
    echo "</tr>";
  }
  echo "</tbody>";
  echo "</table>";
}
?>


<script>
  const diaaBtn = document.getElementById("diaa");
  const diadBtn = document.getElementById("diad");
  const tables = document.querySelectorAll(".day-table");
  const days = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo"];

  let currentIndex = 0;

  diaaBtn.addEventListener("click", () => {
    if (currentIndex > 0) {
      tables[currentIndex].style.display = "none";
      currentIndex--;
      tables[currentIndex].style.display = "block";
      document.getElementById("day-header").innerText = ucfirst(days[currentIndex]);
    }
  });

  diadBtn.addEventListener("click", () => {
    if (currentIndex < tables.length - 1) {
      tables[currentIndex].style.display = "none";
      currentIndex++;
      tables[currentIndex].style.display = "block";
      document.getElementById("day-header").innerText = ucfirst(days[currentIndex]);
    }
  });

  function ucfirst(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
  }






  function cambiarColor(idTabla, nombreColumna, hora1, hora2, hexColor, texto) {
    const tabla = document.getElementById(idTabla);
    const columnasPermitidas = ["zona hora", "bajo 5", "bajo 4", "monumental", "entreplanta", "oficina", "espacio rosa moreno"];
    if (!columnasPermitidas.includes(nombreColumna.toLowerCase())) {
      console.log("La columna especificada no es válida");
      return;
    }
    const indexColumna = Array.from(tabla.querySelectorAll("thead th")).findIndex(th => th.textContent.trim().toLowerCase() === nombreColumna.toLowerCase());
    const indexHora1 = Array.from(tabla.querySelectorAll("tbody tr.hour-row th")).findIndex(th => th.textContent.trim().toLowerCase() === hora1.toLowerCase());
    const indexHora2 = Array.from(tabla.querySelectorAll("tbody tr.hour-row th")).findIndex(th => th.textContent.trim().toLowerCase() === hora2.toLowerCase());
    if (indexColumna < 0 || indexHora1 < 0 || indexHora2 < 0) {
      console.log("No se encontró la columna o alguna de las horas");
      return;
    }
    const minIndex = Math.min(indexHora1, indexHora2) + 1;
    const maxIndex = Math.max(indexHora1, indexHora2) + 1;
    const genericColor = "orange";
    const highlightColor = /^#[0-9A-F]{6}$/i.test(hexColor) ? hexColor : genericColor;
    for (let i = minIndex; i < maxIndex; i++) {
      const td = tabla.querySelector(`tbody tr:nth-child(${i}) > td:nth-child(${indexColumna})`);
      td.style.backgroundColor = highlightColor;
      td.style.borderTopColor = highlightColor;
      td.style.borderBottomColor = highlightColor;
    }
    const tdHora1 = tabla.querySelector(`tbody tr:nth-child(${minIndex}) > td:nth-child(${indexColumna})`);
    tdHora1.querySelector('div').textContent = texto;
  }



</script>

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

  // Consulta a la tabla "horario"
  $query = "SELECT * FROM horario";
  $stmt = $conn->query($query);

  // Obtener un array completo de cada fila de la tabla
  $horario_array = $stmt->fetchAll(PDO::FETCH_ASSOC);

  foreach ($horario_array as $row) {
    $dia = $row['dia'];
    $zona = $row['zona'];
    $horai = $row['horai'];
    $horac = $row['horac'];
    $actividad = $row['actividad'];
    $colectivo = $row['colectivo'];

    // Llamar directamente a la función cambiarColor() con las variables correspondientes
    echo "<script>cambiarColor('$dia', '$zona', '$horai', '$horac', '$colectivo', '$actividad')</script>";
  }


} catch (PDOException $e) {
  echo "Error al conectar a la base de datos: " . $e->getMessage();
}
?>

<br /> <br /> 
<h2 class="center"> Calendario de eventos </h2>
<br /> <br />

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
<br /> <br />

<table class="day-table">
  <thead>
    <tr class="zone-time">
      <th class="zone-time">Fecha</th>
      <th class="zone-time">Hora</th>
      <th class="zone-time">Actividad</th>
      <th class="zone-time">Zona</th>
      <th class="zone-time">Acceso</th>
      <th class="zone-time">Organiza</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($calendario_array as $row): ?>
      <tr>
        <td class="zone-cell">
          <?php echo $row['fecha']; ?>
        </td>
        <td class="zone-cell">
          <?php echo $row['hora']; ?>
        </td>
        <td class="zone-cell">
          <?php echo $row['actividad']; ?>
        </td>
        <td class="zone-cell">
          <?php echo $row['zona']; ?>
        </td>
        <td class="zone-cell">
          <?php echo $row['acceso']; ?>
        </td>
        <td class="zone-cell">
          <?php echo $row['organiza']; ?>
        </td>

      </tr>
    <?php endforeach; ?>
  </tbody>
</table>


<br />
<div>
  <p class="center">Si deseáis realizar una actividad en nuestra Casa debéis ponerte en contacto con nuestra
    <strong>Comisión de
      Acogida</strong> a través del&nbsp;correo electrónico de la <a href="mailto:acogida@pumarejo.org" target="_blank"
      rel="noreferrer noopener">comisión de Acogida</a><a rel="noreferrer noopener" href="mailto:acogida@pumarejo.es"
      target="_blank"></a>. Por favor, <strong>verifica previamente la disponibilidad de los espacios</strong> en los
    días y horarios que pretendes real </p>
</div>


<br /><br /> <br /> <br /> <br /> <br /> <br /> <br />

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>

</html>