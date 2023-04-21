<link rel=stylesheet href="./estilos/basededatos.css" type="text/css">
<?php include("plantilla/cabeceragestor.php"); ?>
<?php
$txtIDPelicula = isset($_POST['txtIDPelicula']) ? $_POST['txtIDPelicula'] : "";
$txtNombre = isset($_POST['txtNombre']) ? $_POST['txtNombre'] : "";
$txtImagen = isset($_FILES['txtImagen']['name']) ? $_FILES['txtImagen']['name'] : "";
$txtNombreoriginal = isset($_POST['txtNombreoriginal']) ? $_POST['txtNombreoriginal'] : "";
$txtDirector = isset($_POST['txtDirector']) ? $_POST['txtDirector'] : "";
$txtFecha = isset($_POST['txtFecha']) ? $_POST['txtFecha'] : "";
$txtPais = isset($_FILES['txtPais']['name']) ? $_FILES['txtPais']['name'] : "";
$txtDecada = isset($_POST['txtDecada']) ? $_POST['txtDecada'] : "";
$txtNota = isset($_POST['txtNota']) ? $_POST['txtNota'] : "";
$accion = isset($_POST['accion']) ? $_POST['accion'] : "";

include("conexion.php");

switch ($accion) {
    case "Agregar":
        $sentenciaSQL = $conexion->prepare("INSERT INTO mispeliculas (nombre, imagen, nombreoriginal, director, fecha, pais, decada, nota) 
        VALUES (:nombre, :imagen, :nombreoriginal, :director, :fecha, :pais, :decada, :nota);");
        $sentenciaSQL->bindParam(':nombre', $txtNombre);
        $sentenciaSQL->bindParam(':imagen', $txtImagen);
        $sentenciaSQL->bindParam(':nombreoriginal', $txtNombreoriginal);
        $sentenciaSQL->bindParam(':director', $txtDirector);
        $sentenciaSQL->bindParam(':fecha', $txtFecha);
        $sentenciaSQL->bindParam(':pais', $txtPais);
        $sentenciaSQL->bindParam(':decada', $txtDecada);
        $sentenciaSQL->bindParam(':nota', $txtNota);
    
        if(isset($_FILES["txtPais"]) && $_FILES["txtPais"]["error"] == 0){
            $nombreArchivo = $_FILES["txtPais"]["name"];
            $rutaImagen = "./banderas/" . $nombreArchivo;

            if(file_exists($rutaImagen)){
                // Generar mensaje de error si existe
                $error = "La imagen ya existe";
            } else {
                // Mover imagen a carpeta
                move_uploaded_file($_FILES["txtPais"]["tmp_name"], $rutaImagen);
                $sentenciaSQL->bindParam(':pais', $nombreArchivo);
            }
        } else {
            // Si la imagen no está subida, usar imagen default
            $nombreArchivo = "default.jpg";
            $rutaImagen = "./banderas/" . $nombreArchivo;
            $sentenciaSQL->bindParam(':pais', $nombreArchivo);
        }

        if(isset($_FILES["txtImagen"]) && $_FILES["txtImagen"]["error"] == 0){
            $nombreArchivo = $_FILES["txtImagen"]["name"];
            $tmpImagen = $_FILES["txtImagen"]["tmp_name"];
            $rutaImagen = "./imagenes/" . $nombreArchivo;
    
            if(file_exists($rutaImagen)){
                // Mensaje si el archivo existe
                $error = "La imagen ya existe";
            } else {
                // Mover imagen a carpeta
                move_uploaded_file($tmpImagen, $rutaImagen);
                $sentenciaSQL->bindParam(':imagen', $nombreArchivo);
            }
        } else {
            $nombreArchivo = "default.jpg";
            $rutaImagen = "./imagenes/" . $nombreArchivo;
            $sentenciaSQL->bindParam(':imagen', $nombreArchivo);
        }
    
        $sentenciaSQL->execute();
        header("Location:basededatos.php");
        break;

    case "Modificar":
        $sentenciaSQL = $conexion->prepare("UPDATE mispeliculas SET nombre=:nombre, nombreoriginal=:nombreoriginal, director=:director, fecha=:fecha, pais=:pais, decada=:decada, nota=:nota WHERE idpelicula=:idpelicula");
        $sentenciaSQL->bindParam(':nombre', $txtNombre);
        $sentenciaSQL->bindParam(':nombreoriginal', $txtNombreoriginal);
        $sentenciaSQL->bindParam(':director', $txtDirector);
        $sentenciaSQL->bindParam(':fecha', $txtFecha);
        $sentenciaSQL->bindParam(':pais', $txtPais);
        $sentenciaSQL->bindParam(':decada', $txtDecada);
        $sentenciaSQL->bindParam(':nota', $txtNota);
        $sentenciaSQL->bindParam(':idpelicula', $txtIDPelicula);
        $sentenciaSQL->execute();
    
        if ($txtImagen != "") {
            $fecha = new DateTime();
            $nombreArchivo = $fecha->getTimestamp() . "_" . $_FILES["txtImagen"]["name"];
            $tmpImagen = $_FILES["txtImagen"]["tmp_name"];
    
            move_uploaded_file($tmpImagen, "./imagenes/" . $nombreArchivo);
    
            $sentenciaSQL = $conexion->prepare("SELECT * FROM mispeliculas WHERE idpelicula=:idpelicula");
            $sentenciaSQL->bindParam(':idpelicula', $txtIDPelicula);
            $sentenciaSQL->execute();
            $pelicula = $sentenciaSQL->fetch(PDO::FETCH_LAZY);
    
            if (isset($pelicula["imagen"]) && ($pelicula["imagen"] != "")) {
                if (file_exists("./imagenes/" . $pelicula["imagen"])) {
                    unlink("./imagenes/" . $pelicula["imagen"]);
                }
            }
    
            $sentenciaSQL = $conexion->prepare("UPDATE mispeliculas SET imagen=:imagen WHERE idpelicula=:idpelicula");
            $sentenciaSQL->bindParam(':imagen', $nombreArchivo);
            $sentenciaSQL->bindParam(':idpelicula', $txtIDPelicula);
            $sentenciaSQL->execute();
        }
        header("Location:basededatos.php");
        break;
    

    case "Cancelar":
      header("Location:basededatos.php");
      break;

    case "Seleccionar":
        $sentenciaSQL = $conexion->prepare("SELECT * FROM mispeliculas WHERE idpelicula=:idpelicula");
        $sentenciaSQL->bindParam(':idpelicula', $txtIDPelicula);
        $sentenciaSQL->execute();
        $pelicula = $sentenciaSQL->fetch(PDO::FETCH_LAZY);
        $txtNombre = $pelicula['nombre'];
        $txtImagen = $pelicula['imagen'];
        $txtNombreoriginal = $pelicula['nombreoriginal'];
        $txtDirector = $pelicula['director'];
        $txtFecha = $pelicula['fecha'];
        $txtPais = $pelicula['pais'];
        $txtDecada = $pelicula['decada'];
        $txtNota = $pelicula['nota'];
        break;    

  case "Borrar":   
    $sentenciaSQL = $conexion->prepare("SELECT * FROM mispeliculas WHERE idpelicula=:idpelicula");
    $sentenciaSQL->bindParam(':idpelicula', $txtIDPelicula);
    $sentenciaSQL->execute();
    $pelicula = $sentenciaSQL->fetch(PDO::FETCH_LAZY);

    if(isset($pelicula["imagen"])&&($pelicula["imagen"]!="imagen.jpg")){
        if(file_exists("./imagenes/".$pelicula["imagen"])){
            unlink("./imagenes/".$pelicula["imagen"]);
        }
    }


    $sentenciaSQL = $conexion->prepare("DELETE FROM mispeliculas WHERE idpelicula=:idpelicula");
    $sentenciaSQL->bindParam(':idpelicula', $txtIDPelicula);
    $sentenciaSQL->execute();
    header("Location:basededatos.php");
    break;
  }




  if(isset($_POST['truncar'])){
    try {
      $conexion = new PDO("mysql:host=$host;dbname=$db", $usuario, $contrasenia);
      $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "TRUNCATE TABLE mispeliculas";
      $conexion->exec($sql);
      
    } catch(PDOException $e) {
      echo $sql . "<br>" . $e->getMessage();
    }
  }

  

if(isset($_POST['reload'])) {
  $sql = "LOAD DATA INFILE 'C:/Users/Utilizateur/OneDrive/Escritorio/definitivowebback.csv'
          INTO TABLE mispeliculas
          FIELDS TERMINATED BY ';'
          LINES TERMINATED BY '\n'
          IGNORE 0 ROWS";
  $conexion->exec($sql);
  }



$sentenciaSQL = $conexion->prepare("SELECT * FROM mispeliculas");
$sentenciaSQL->execute();
$listamispeliculas = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);



?>




<div class="columna-1">
    <div class="card bg-dark text-white">
      <div class="card-header text-center mb-3">
          <h3>Datos</h3>
      </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="txtIDPelicula">ID:</label>
                    <input type="text"  class="form-control" name="txtIDPelicula" id="txtIDPelicula" value="<?php echo $txtIDPelicula; ?>" placeholder="ID">
                </div>
                <div class="form-group">
                    <label for="txtNombre">Nombre:</label>
                    <input type="text"  class="form-control" name="txtNombre" id="txtNombre" value="<?php echo $txtNombre; ?>" placeholder="Nombre de la película">
                </div>
                <div class="form-group text-center">
                    <label for="txtImagen">Imagen:</label>
                    <br>

                <?php if($txtImagen!=""){        ?>
                    <img class="img-thumbnail rounded" src="./imagenes/<?php echo $txtImagen; ?>" width="100">   
                    <?php }?>
                <input type="file"  class="form-control mt-2" name="txtImagen" id="txtImagen" placeholder="Movie Image">
                </div>
                <div class="form-group">
                    <label for="txtNombreoriginal">Nombre Original:</label>
                    <input type="text"  class="form-control" name="txtNombreoriginal" id="txtNombreoriginal" value="<?php echo $txtNombreoriginal; ?>" placeholder="Nombre original">
                </div>
                <div class="form-group">
                    <label for="txtDirector">Director:</label>
                    <input type="text"  class="form-control" name="txtDirector" id="txtDirector" value="<?php echo $txtDirector; ?>" placeholder="Nombre director">
                </div>
                <div class="form-group">
                    <label for="txtFecha">Año:</label>
                    <input type="text"  class="form-control" name="txtFecha" id="txtFecha" value="<?php echo $txtFecha; ?>" placeholder="Año de publicación">
                </div>                
                <div class="form-group text-center">
                    <label for="txtPais">País:</label>
                    <br>
                    <?php if($txtPais!=""){ ?>
                        <img class="logo-img" src="./banderas/<?php echo $txtPais; ?>" width="100">   
                    <?php }?>
                    <input type="file" class="form-control mt-2" name="txtPais" id="txtPais" placeholder="País de origen">
                </div>
              <div class="form-group">
                <div class="form-group">
                    <label for="txtDecada">Década:</label>
                    <input type="text"  class="form-control" name="txtDecada" id="txtDecada" value="<?php echo $txtDecada; ?>" placeholder="Década">
                </div>
                <div class="form-group">
                    <label for="txtNota">Nota:</label>
                    <input type="text"  class="form-control" name="txtNota" id="txtNota" value="<?php echo $txtNota; ?>" placeholder="Nota de la película">
                </div>
                <div class="d-flex justify-content-center flex-wrap">
                  <button type="submit" name="accion" <?php echo ($accion=="Seleccionar")?"disabled":""; ?> value="Agregar" class="btn btn-success mr-2 mb-2">Añadir</button>
                  <button type="submit" name="accion" <?php echo ($accion!="Seleccionar")?"disabled":""; ?> value="Modificar" class="btn btn-warning mr-2 mb-2">Editar</button>
                  <button type="submit" name="accion" <?php echo ($accion!="Seleccionar")?"disabled":""; ?> value="Cancelar" class="btn btn-info mb-2">Cancelar</button>              
                  <form action="truncartabla.php" method="POST">
                  <button type="submit" name="truncar" class="btn btn-danger mr-2 mb-2">Truncar</button>
                </form>                  
                <button type="submit" name="reload" value="true" class="btn btn-primary mr-2 mb-2">Relistar</button>              
                </div>
              </form>              
          </div>
        </div>
    </div>
</div>



<div class="col-12 columna-2">
  
  <?php
   
  //FILTRO
  $decada = isset($_GET['decada']) ? $_GET['decada'] : null;
  $especial = isset($_GET['especial']) ? $_GET['especial'] : null;

  $sentenciaSQL = $conexion->prepare("SELECT * FROM mispeliculas");

  if (!empty($_GET['decada']) && in_array($_GET['decada'], array('1920s', '1930s', '1940s', '1950s', '1960s', '1970s', '1980s', '1990s', '2000s', '2010s', '2020s'))) {
    $sentenciaSQL = $conexion->prepare("SELECT * FROM mispeliculas WHERE decada = :decada");
    $sentenciaSQL->bindParam(':decada', $decada);
  } else if (!empty($_GET['especial']) && in_array($_GET['especial'], array('Documentary', 'Animation', 'Oscar', 'Cannes'))) {
    $sentenciaSQL = $conexion->prepare("SELECT * FROM mispeliculas WHERE especial = :especial");
    $sentenciaSQL->bindParam(':especial', $especial);
  }

  $sentenciaSQL->execute();
  $listamispeliculas = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

  //BUSQUEDAS
  $busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';
  // Filtra las busquedas
  if (!empty($busqueda)) {
    $listamispeliculas = array_filter($listamispeliculas, function($pelicula) use ($busqueda) {
      return strpos(strtolower($pelicula['nombre']), strtolower($busqueda)) !== false 
        || strpos(strtolower($pelicula['fecha']), strtolower($busqueda)) !== false 
        || strpos(strtolower($pelicula['director']), strtolower($busqueda)) !== false
        || strpos(strtolower($pelicula['pais']), strtolower($busqueda)) !== false;
    });
}


  // ELEMENTOS POR PAGINA
  $porPagina = 10;
  // Número total de páginas
  $totalPaginas = ceil(count($listamispeliculas) / $porPagina);
  // Página actual según URL
  $numeroPagina = isset($_GET['page']) ? (int) $_GET['page'] : 1;
  // Calcular el desplazamiento
  $desplazamiento = ($numeroPagina - 1) * $porPagina;
  // Obtener un subconjunto de elementos del array usando el desplazamiento y el límite
  $subconjunto = array_slice($listamispeliculas, $desplazamiento, $porPagina);
?>

  <nav aria-label="Page navigation" style="display: inline-block; vertical-align: middle">
    <form method="get" style="display: inline-block; vertical-align:baseline; margin-left: 10px;">
    <label for="decade-select">Escoge:</label>
      <select id="decade-select" name="decada">
        <option value="">Todos</option>
        <option value="1920s"<?php echo $decada === '1920s' ? ' selected' : ''; ?>>1920s</option>
        <option value="1930s"<?php echo $decada === '1930s' ? ' selected' : ''; ?>>1930s</option>
        <option value="1940s"<?php echo $decada === '1940s' ? ' selected' : ''; ?>>1940s</option>
        <option value="1950s"<?php echo $decada === '1950s' ? ' selected' : ''; ?>>1950s</option>
        <option value="1960s"<?php echo $decada === '1960s' ? ' selected' : ''; ?>>1960s</option>
        <option value="1970s"<?php echo $decada === '1970s' ? ' selected' : ''; ?>>1970s</option>
        <option value="1980s"<?php echo $decada === '1980s' ? ' selected' : ''; ?>>1980s</option>
        <option value="1990s"<?php echo $decada === '1990s' ? ' selected' : ''; ?>>1990s</option>
        <option value="2000s"<?php echo $decada === '2000s' ? ' selected' : ''; ?>>2000s</option>
        <option value="2010s"<?php echo $decada === '2010s' ? ' selected' : ''; ?>>2010s</option>
        <option value="2020s"<?php echo $decada === '2020s' ? ' selected' : ''; ?>>2020s</option>
      </select>
      <select id="especial-select" name="especial">
        <option value="">Todos</option>
        <option value="Documentary"<?php echo $especial === 'Documentary' ? ' selected' : ''; ?>>Documental</option>
        <option value="Animation"<?php echo $especial === 'Animation' ? ' selected' : ''; ?>>Animacion</option>
        <option value="Oscar"<?php echo $especial === 'Oscar' ? ' selected' : ''; ?>>
          <img src="./banderas/oscar.png" alt="Oscar"> Oscar </option>
          <option value="Cannes"<?php echo $especial === 'Cannes' ? ' selected' : ''; ?>>Cannes</option>
      </select>
    <button type="submit">Filtrar</button>
    <input type="hidden" id="page-number" name="page" value="<?php echo $numeroPagina; ?>">
    </form>
    <form method="get" style="display: inline-block; margin-left: 10px;">
      <label for="page-number">Elige Página:</label>
      <input type="number" id="page-number" name="page" value="<?php echo $numeroPagina; ?>" min="1" max="<?php echo $totalPaginas; ?>">
      <?php if (isset($_GET['busqueda'])) { ?>
      <input type="hidden" name="busqueda" value="<?php echo htmlspecialchars($_GET['busqueda']); ?>">
    <?php } ?>
      <button type="submit">Ir</button>
      <input type="hidden" id="decade-select" name="decada" value="<?php echo $decada; ?>">
    </form>
  </nav>
  <div class="search-form d-flex align-items-center flex-grow-1">
  <form method="GET" action="" class="search-form flex-grow-1">
    <div class="input-group">
      <input type="text" name="busqueda" class="form-control" placeholder="¿Qué película, director/a, país o año estás buscando?">
      <div class="input-group-append">
        <button type="submit" class="btn btn-primary">Buscar</button>
      </div>
    </div>
  </form>
  </div>
  <div class="table-responsive">
  <table class="table table-bordered table-dark" style="font-size: 11px;">
    <thead>
      <tr>
        <th>ID</th>
        <th>Opciones</th>
        <th>Nombre</th>
        <th>Imagen</th>
        <th>Original</th>
        <th>Director</th>
        <th>Año</th>
        <th>País</th>
        <th>Década</th>
        <th>Nota</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($subconjunto as $pelicula) { ?>
        <tr class="table-row">
          <td><?php echo $pelicula['idpelicula']; ?></td>
          <td>
            <form method="post" class="my-form">
              <input type="hidden" name="txtIDPelicula" value="<?php echo $pelicula['idpelicula']; ?>">
              <button type="submit" name="accion" value="Seleccionar" class="btn btn-primary btn-smaller">S</button>
              <button type="submit" name="accion" value="Borrar" class="btn btn-danger btn-smaller">B</button>
            </form>
          </td>
          <td><?php echo $pelicula['nombre']; ?></td>
          <td>
            <img class="peli" src="./imagenes/<?php echo $pelicula['imagen']; ?>" width="50">
          </td>
          <td><?php echo $pelicula['nombreoriginal']; ?></td>
          <td><?php echo $pelicula['director']; ?></td>
          <td><?php echo $pelicula['fecha']; ?></td>
          <td>
            <img class="custom-img-style" src="./banderas/<?php echo $pelicula['pais']; ?>" width="50">
          </td>
          <td><?php echo $pelicula['decada']; ?></td>
          <td><?php echo $pelicula['nota']; ?></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
      </div>
<br>
  <div class="pagination-wrapper d-flex justify-content-center">
  <form action="" method="GET" class="mr-3">
    <input type="hidden" id="decade-select" name="decada" value="<?php echo $decada; ?>">
    <input type="hidden" id="especial-select" name="especial" value="<?php echo $especial; ?>">
    <?php if (isset($_GET['busqueda'])) { ?>
      <input type="hidden" name="busqueda" value="<?php echo htmlspecialchars($_GET['busqueda']); ?>">
    <?php } ?>
    <ul class="pagination">
      <?php if ($numeroPagina > 1) { ?>
        <li class="page-item"><a id="atras-link" class="page-link" href="?page=<?php echo $numeroPagina - 1; ?>&decada=<?php echo $decada; ?>&especial=<?php echo $especial; ?><?php if (isset($_GET['busqueda'])) { echo '&busqueda=' . htmlspecialchars($_GET['busqueda']); } ?>">Atrás</a></li>
      <?php } ?>
      <?php if ($numeroPagina < $totalPaginas) { ?>
        <li class="page-item"><a id="siguiente-link" class="page-link" href="?page=<?php echo $numeroPagina + 1; ?>&decada=<?php echo $decada; ?>&especial=<?php echo $especial; ?><?php if (isset($_GET['busqueda'])) { echo '&busqueda=' . htmlspecialchars($_GET['busqueda']); } ?>">Siguiente</a></li>
      <?php } ?>
    </ul>
<div class="d-none d-lg-block">
  <?php
    echo '<ul class="pagination pagination-sm">';
    for ($i = 1; $i <= $totalPaginas; $i++) {
        // Agregar los filtros a los links de paginación
        $paginaUrl = '?page=' . $i;
        if (!empty($decada)) {
            $paginaUrl .= '&decada=' . $decada;
        }
        if (!empty($especial)) {
            $paginaUrl .= '&especial=' . $especial;
        }
        if (isset($_GET['busqueda'])) {
          $paginaUrl .= '&busqueda=' . $_GET['busqueda'];
        }

        // Resaltar la página actual
        $claseActiva = ($i == $numeroPagina) ? 'active' : '';

        // Limitar el número de links de página mostrados y agregar ellipses
        if ($totalPaginas > 14 && abs($i - $numeroPagina) > 4) {
          if ($i == 1 || $i == $totalPaginas) {
              echo '<li class="page-item"><a class="page-link" href="' . $paginaUrl . '">' . $i . '</a></li>';
          } elseif (abs($i - $numeroPagina) == 5) {
              echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
              echo '<li class="page-item"><input type="text" class="page-link page-input" id="page-input" placeholder="Buscar"></li>';
          }
      } else {
          echo '<li class="page-item ' . $claseActiva . '"><a class="page-link" href="' . $paginaUrl . '">' . $i . '</a></li>';
      }
  }
  if (isset($_GET['busqueda'])) {
    echo '<input type="hidden" name="busqueda" value="' . htmlspecialchars($_GET['busqueda']) . '">';
  }
  echo '</ul>';
?>
</div>
</div>

<?php
// Add JavaScript to submit the form when the input field changes
echo '<script>';
echo 'document.getElementById("page-input").addEventListener("change", function() {';
echo '  var page = parseInt(this.value);';
echo '  if (page >= 1 && page <= ' . $totalPaginas . ') {';
echo '    var urlParams = new URLSearchParams(window.location.search);';
echo '    urlParams.set("page", page);';
echo '    window.location.search = urlParams.toString();';
echo '  }';
echo '});';
echo '</script>';
 ?>
 </div>
</form> 
</div>
  
</div>
</div>

<script>
$(document).ready(function() {
  $("#truncar-btn").click(function(e) {
    e.preventDefault();
    alert("Button clicked!");
    $.ajax({
      type: "POST",
      url: "truncartabla.php",
      success: function(data) {
        alert(data);
      }
    });
  });
});








//Para moverse con el teclado izquierda y derecha

document.addEventListener('keydown', function(event) {
    if (event.keyCode === 37) { // Código tecla izquierda
      var atrasLink = document.getElementById('atras-link');
      if (atrasLink) {
        atrasLink.click();
      }
    } else if (event.keyCode === 39) { // Código tecla derecha
      var siguienteLink = document.getElementById('siguiente-link');
      if (siguienteLink) {
        siguienteLink.click();
      }
    }
  });




  </script>

<style>
@media (max-width: 576px) {
    .card-header h3 {
      font-size: 35px;
    }
  }

  @media (max-width: 576px) {
  .columna-1 {
    width: 100% !important;
    margin: 0 !important;
    display: block !important;
  }
  .columna-2{
    width: 100% !important;
    margin: 0 !important;
    display: block !important;
  }
}

@media (max-width: 576px) {
  .row {
    display: block;
  }
  .columna-1, .columna-2 {
    width: 100%;
    margin-right: 0;
    float: none;
    display: flex;
  }
} 

.todo {
  display: flex;
  justify-content: center;
}

.columna-1, .columna-2 {
  display: inline-block;
  vertical-align: top;
  margin-right: 20px;
}

.logo-img {
  width: 30px;
  height: 22px;
  border-radius: 20%;
  object-fit: cover;
  margin-top:-5px;
}

form.my-form {
  display: flex;
}

form.my-form button {
  margin-right: 10px;
}
    
    .custom-img-style {
  border: 2px solid;
  border-radius: 10px;
  box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
  width: 30px;
}

    .table-row:hover {
        background-color: #2c2f33;
    }

    .columna-1 {
        width: 25%;
    float: left;
    margin: 0 10px 20px -30px;
}

body {
  background: #bfbfbf;
  background-image: linear-gradient(to bottom, rgba(191, 191, 191, 0.8), rgba(191, 191, 191, 0.8)), url("path/to/your/image.jpg");
  background-repeat: no-repeat;
  background-size: cover;
}



    .columna-2{
        max-width: 62%;
        font-size: small;
    }

    .btn-smaller {
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 12px;
        font-weight: bold;
        padding: 0.25rem 0.5rem;
        border-radius: 100px;
        margin-bottom: 15px;
        width: 20px;
    }

    .peli {
 border: 2px solid #ddd;
  border-radius: 8px;
  box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
  max-width: 100%;
  height: auto;


}

.card-body label,
.card-body input[type="text"],
.card-body input[type="file"] {
  font-size: 12px;
}

</style>

<?php include("plantilla/piegestor.php"); ?>