<link rel=stylesheet href="./estilos/comentarios.css" type="text/css">
<?php include("plantilla/cabecera.php"); ?>

<script>
  // Función para deshabilitar la opción predeterminada en un select
  function disableSelect() {
  var select = document.getElementById("rating");
  var option = select.options[0];
  if (option.disabled) {
  return; // no hacer nada si ya está deshabilitado
  }
  option.disabled = true;
  }
  // Añadir un event listener al select "movie" para actualizar el valor del input "idpelicula" cuando cambie su valor
  document.getElementById("movie").addEventListener("change", function() {
  document.getElementById("idpelicula").value = this.value;
  });
</script>

<!--PRIMERA SECCION DE COMENTARIOS -->
<section id="introducircomentario" style="max-width: 600px; margin: auto;">
<br>
<div style="text-align:center; font-size:20px; margin-bottom:20px;">Comenta tus películas favoritas aquí.</div>
  <form method="POST" action="submit_comment.php">
    <div class="form-group">
      <label for="movie">Título de la Película:</label>
      <select id="movie" name="movie" class="form-control">
      <?php
      session_start();
          // Conectarse a la base de datos
          $host = "localhost";
          $db = "bbddpeliculas";
          $usuario = "root";
          $contrasenia = "";
          $conexion = mysqli_connect($host, $usuario, $contrasenia, $db);

      if (!$conexion) {
        die("Connection failed: " . mysqli_connect_error());
      }    

      // Buscar películas de la base de datos
      $query = "SELECT idpelicula, nombre FROM mispeliculas";
      $result = mysqli_query($conexion, $query);

      // Más opciones
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<option value=\"" . $row["idpelicula"] . "\">" . $row["nombre"] . "</option>";
      }
     
      // Cerrar la base de datos
      mysqli_close($conexion);
?>
        
    </select>
  </div>


  <div class="form-group">
    <label for="comment-text">Comentario:</label>
    <textarea id="comment-text" name="comment-text" class="form-control" style="height: 150px;"></textarea>
  </div>

  <div class="form-group">
    <label for="rating">Nota:</label>
    <select id="rating" name="rating" class="form-control" onchange="disableSelect()">
      <option value="" selected disabled>Selecciona un Número</option>
      <?php for($i=1; $i<=10; $i++): ?>
        <option value="<?= $i ?>"><?= $i ?></option>
      <?php endfor; ?>
    </select>
  </div>

    <div class="form-group">
      <label for="user-name">Nombre:</label>
      <input type="text" name="user-name" class="form-control" placeholder="Escribe tu nombre" value="<?php echo htmlspecialchars($nombreUsuario); ?>" readonly>
    </div>
    <button type="submit" class="btn btn-primary" style="margin-top: 20px;">Enviar Comentario</button>
    <br>
    <br>
</form>

</section>
<div class="jumbotron text-center">
<img id="myImage" width="400" src="<?php echo $randomImagen2; ?>" class="img-fluid rounded mx-auto d-block" alt="Imagen de inicio">
</div>

<!--SEGUNDA SECCION DE COMENTARIOS -->
<section id="comments">
     <br>
  <h2 class="section-title">Comentarios de otros Usuarios</h2>
  <div class="table-responsive">
    <table class="table table-striped table-bordered comment-table">
      <thead>
        <tr>
          <th>Usuario</th>
          <th>Película</th>
          <th>Comentario</th>
          <th>Nota</th>
          <th>Fecha</th>
        </tr>
      </thead>
      <tbody id="comment-rows">

<?php
$host = "localhost";
$db = "bbddpeliculas";
$usuario = "root";
$contrasenia = "";

        try {
          $conexion = new PDO("mysql:host=$host;dbname=$db", $usuario, $contrasenia);
          $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $ex) {
          echo $ex->getMessage();
        }

        $date = date('Y-m-d H:i:s');

        // SQL Petición
        $sql = "SELECT mc.usuarionombre, p.nombre, mc.comentario_texto, mc.notapelicula, mc.comentario_id, mc.fecha_comentario 
                FROM miscomentarios mc 
                JOIN mispeliculas p ON mc.idpelicula = p.idpelicula";

        $result = $conexion->query($sql);

        // Bucle de búsqueda de comentarios
        if ($result->rowCount() > 0) {
          while($row = $result->fetch()) {
            echo "<tr>";
            echo "<td>" . $row["usuarionombre"] . "</td>";
            echo "<td>" . $row["nombre"] . "</td>";
            echo "<td>" . $row["comentario_texto"] . "</td>";
            echo "<td>" . $row["notapelicula"] . "</td>";
            echo "<td>" . $row["fecha_comentario"] . "</td>";            
            echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='5'>No comments found</td></tr>";
        }

        $conexion = null;
        ?>
      </tbody>
    </table>
  </div>
</section>


    <script>
      // Código para llenar de comentarios las filas
      const commentRows = document.querySelector('#comment-rows');
    </script>

</section>


<?php include("plantilla/pie.php"); ?>
