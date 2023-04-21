<link rel=stylesheet href="./estilos/listado.css" type="text/css">
<?php include("plantilla/cabecera.php"); ?>

<?php include ("conexion.php"); 

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
         || strpos(strtolower($pelicula['pais']), strtolower($busqueda)) !== false ;
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

<div class="container d-flex justify-content-between align-items-center">
  <form method="get" style="display: inline-block; margin-right: 30px;">
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
  <form method="get" style="display: inline-block; margin-right: 20px;">
    <label for="page-number">Página:</label>
    <input type="number" id="page-number" name="page" value="<?php echo $numeroPagina; ?>" min="1" max="<?php echo $totalPaginas; ?>">
    <input type="hidden" id="decade-select" name="decada" value="<?php echo $decada; ?>">
    <input type="hidden" id="especial-select" name="especial" value="<?php echo $especial; ?>">
    <?php if (isset($_GET['busqueda'])) { ?>
      <input type="hidden" name="busqueda" value="<?php echo htmlspecialchars($_GET['busqueda']); ?>">
    <?php } ?>
    <button type="submit">Ir</button>
  </form>
  <div class="search-form d-flex align-items-center flex-grow-1" style="margin-left: 20px;">
    <form method="GET" action="" class="search-form flex-grow-1">
      <div class="input-group">
        <input type="text" name="busqueda" class="form-control" placeholder="¿Qué película, director/a, país o año estás buscando?">
        <div class="input-group-append">
          <button type="submit" class="btn btn-primary">Buscar</button>
        </div>
      </div>
    </form>
  <input type="hidden" name="busqueda" value="<?php echo htmlspecialchars($_GET['busqueda'] ?? '') ?>">
  </div>
</div>

<div class="row">
  <?php 
  

  
  foreach ($subconjunto as $pelicula) {

    // Verificar si la película pertenece a la década/especial seleccionada, a menos que se haya seleccionado "Todos"
      if (!empty($decada) && $pelicula['decada'] !== $decada && $decada !== "Todos") {
        continue;
      }  
      if (!empty($especial) && $pelicula['especial'] !== $especial && $especial !== "Todos") {
        continue;
      }

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

$nota = $pelicula['nota'];
    $color = '';
    if ($nota >= 1 && $nota < 2.5) {
      $color = '#FF000080'; // red
    } else if ($nota >= 2.5 && $nota < 3.2) {
      $color = '#FF5E0080'; // orange
    } else if ($nota >= 3.2 && $nota < 4) {
      $color = '#FFBB0080'; // yellow
    } else if ($nota >= 4 && $nota < 5) {
      $color = '#FFE40080'; // green
    } else if ($nota >= 5 && $nota < 6) {
      $color = '#D0EE0080'; // blue
    } else if ($nota >= 6 && $nota < 7) {
      $color = '#8BC34A80'; // purple
    } else if ($nota >= 7 && $nota < 8) {
      $color = '#4CAF5080'; // purple
    } else if ($nota >= 8 && $nota < 9) {
      $color = '#2196F380'; // purple
    } else if ($nota >= 9 && $nota < 9.5) {
      $color = '#9C27B080'; // purple
    } else if ($nota >= 9.5 && $nota < 10) {
      $color = '#6A1B9A80'; // purple
    }

// add class to first card
static $cardCount = 0;
$cardCount++;
$cardClass = ($cardCount === 1) ? 'first-card' : '';

  ?>
  
  <style>
  .blur {
    filter: blur(5px);
  }
  .nota-overlay {
  position: absolute;
  top: 40%;
  left: 50%;
  transform: translate(-50%, -50%);
  background-color: rgba(0, 0, 0, 0.5);
  border-radius: 50%;
  color: #fff;
  font-size: 2.2rem;
  font-weight: bold;
  text-align: center;
  width: 130px;
  height: 130px;
  display: none;
  box-shadow: 0 0 10px rgba(0, 0, 0, 10);
}

.nota-text {
  margin-top: 30%;
  text-shadow: 0 0 10px rgba(0, 0, 0, 10);
}


</style>

<div class="col-md-2" style="margin-right: 30px;">
  <div class="card <?php echo $cardClass ?>" style="margin-top: 20px;" data-color="<?php echo $color; ?>">
    <img class="card-img-top" src="./imagenes/<?php echo $pelicula['imagen'] ?>" alt="">
    <div class="card-body">
      <h4 class="card-title text-center text-uppercase" id="movie-title"><?php echo $pelicula['nombre']; ?></h4>
      <h6 class="card-title2 text-center text-shadow"><?php echo $pelicula['nombreoriginal'];  ?></h6>
      <div class="card-title3 d-flex justify-content-between align-items-center">
        <h6><?php echo $pelicula['director']; ?></h6>
        <div class="d-flex align-items-center">
          <h6 class="mx-2"><?php echo $pelicula['fecha']; ?></h6>
          <h6><img class="logo-img" src="./banderas/<?php echo $pelicula['pais'] ?>" alt=""></h6>
        </div>
      </div>
      <div class="card-title4 d-flex justify-content-between text-center">
        <h6 class="align-self-center"><strong><?php echo $pelicula['decada']; ?>:</strong> <?php echo $pelicula['rdecada']; ?></h6>
        <h6 class="align-self-center text-right"><strong>General: </strong><?php echo $pelicula['rgeneral']; ?></h6>
      </div>
      <?php
      $nota = $pelicula['nota'];
      $color = '';
      if ($nota >= 1 && $nota < 2.5) {
        $color = '#FF000080'; // red
        } else if ($nota >= 2.5 && $nota < 3.2) {
        $color = '#FF5E0080'; // orange
        } else if ($nota >= 3.2 && $nota < 4) {
        $color = '#FFBB0080'; // yellow
        } else if ($nota >= 4 && $nota < 5) {
        $color = '#FFE40080'; // green
        } else if ($nota >= 5 && $nota < 6) {
        $color = '#D0EE0080'; // blue
        } else if ($nota >= 6 && $nota < 7) {
        $color = '#8BC34A80'; // purple
        }
        else if ($nota >= 7 && $nota < 8) {
        $color = '#4CAF5080'; // purple
        }
        else if ($nota >= 8 && $nota < 9) {
        $color = '#2196F380'; // purple
        }
        else if ($nota >= 9 && $nota < 9.5) {
        $color = '#9C27B080'; // purple
        }
        else if ($nota >= 9.5 && $nota < 10) {
        $color = '#6A1B9A80'; // purple
        }
      ?>
      <div class="nota-overlay" style="display:none; background-color:<?php echo $color; ?>">
        <div class="nota-text">
          <?php echo $nota; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php } ?>
<p>
<script>
const cards = document.querySelectorAll('.card');

cards.forEach(card => {
  let isCardClicked = false;

  card.addEventListener('click', function() {
    if (!isCardClicked) {
      card.classList.add('card-blur');
      const notaOverlay = card.querySelector('.nota-overlay');
      notaOverlay.style.display = 'block';
      const notaText = notaOverlay.querySelector('.nota-text');
      notaText.classList.add('nota-text-large');
      card.classList.add('clicked');
      const color = card.dataset.color;
      card.style.backgroundColor = color;
      isCardClicked = true;
    } else {
      card.classList.remove('card-blur');
      const notaOverlay = card.querySelector('.nota-overlay');
      notaOverlay.style.display = 'none';
      const notaText = notaOverlay.querySelector('.nota-text');
      notaText.classList.remove('nota-text-large');
      card.classList.remove('clicked');
      card.style.backgroundColor = '';
      isCardClicked = false;
    }
  });
});


</script>

<div class="pagination-wrapper d-flex justify-content-center">
  <form action="" method="GET" class="mr-3">
    <input type="hidden" id="decade-select" name="decada" value="<?php echo $decada; ?>">
    <input type="hidden" id="especial-select" name="especial" value="<?php echo $especial; ?>">
    <?php if (isset($_GET['busqueda'])) { ?>
      <input type="hidden" name="busqueda" value="<?php echo htmlspecialchars($_GET['busqueda']); ?>">
    <?php } ?>
    <ul class="pagination">
      <?php if ($numeroPagina > 1) { ?>
        <li  class="page-item"><a id="atras-link" class="page-link" href="?page=<?php echo $numeroPagina - 1; ?>&decada=<?php echo $decada; ?>&especial=<?php echo $especial; ?><?php if (isset($_GET['busqueda'])) { echo '&busqueda=' . htmlspecialchars($_GET['busqueda']); } ?>">Atrás</a></li>
      <?php } ?>
      <?php if ($numeroPagina < $totalPaginas) { ?>
        <li  class="page-item"><a id="siguiente-link" class="page-link" href="?page=<?php echo $numeroPagina + 1; ?>&decada=<?php echo $decada; ?>&especial=<?php echo $especial; ?><?php if (isset($_GET['busqueda'])) { echo '&busqueda=' . htmlspecialchars($_GET['busqueda']); } ?>">Siguiente</a></li>
      <?php } ?>
    </ul>

<div class="d-none d-lg-block">
  <?php
  echo '<ul class="pagination">';
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

     // Limitar el número de links de página mostrados y agregar elipsis
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
      <style>
.card.clicked {
  box-shadow: 0px 0px 20px rgba(0,0,0,1);
  
}

.card:first-of-type {
      background-color: <?php
  $nota = $pelicula['nota'];
  if ($nota >= 1 && $nota < 2.5) {
    $color = '#FF000080'; // red
    } else if ($nota >= 2.5 && $nota < 3.2) {
    $color = '#FF5E0080'; // orange
    } else if ($nota >= 3.2 && $nota < 4) {
    $color = '#FFBB0080'; // yellow
    } else if ($nota >= 4 && $nota < 5) {
    $color = '#FFE40080'; // green
    } else if ($nota >= 5 && $nota < 6) {
    $color = '#D0EE0080'; // blue
    } else if ($nota >= 6 && $nota < 7) {
    $color = '#8BC34A80'; // purple
    }
    else if ($nota >= 7 && $nota < 8) {
    $color = '#4CAF5080'; // purple
    }
    else if ($nota >= 8 && $nota < 9) {
    $color = '#2196F380'; // purple
    }
    else if ($nota >= 9 && $nota < 9.5) {
    $color = '#9C27B080'; // purple
    }
    else if ($nota >= 9.5 && $nota < 10) {
    $color = '#6A1B9A80'; // purple
    } 
    
  
?> !important;
    }


background-color: <?php
  $nota = $pelicula['nota'];
  if ($nota >= 1 && $nota < 2.5) {
    $color = '#FF000080'; // red
    } else if ($nota >= 2.5 && $nota < 3.2) {
    $color = '#FF5E0080'; // orange
    } else if ($nota >= 3.2 && $nota < 4) {
    $color = '#FFBB0080'; // yellow
    } else if ($nota >= 4 && $nota < 5) {
    $color = '#FFE40080'; // green
    } else if ($nota >= 5 && $nota < 6) {
    $color = '#D0EE0080'; // blue
    } else if ($nota >= 6 && $nota < 7) {
    $color = '#8BC34A80'; // purple
    }
    else if ($nota >= 7 && $nota < 8) {
    $color = '#4CAF5080'; // purple
    }
    else if ($nota >= 8 && $nota < 9) {
    $color = '#2196F380'; // purple
    }
    else if ($nota >= 9 && $nota < 9.5) {
    $color = '#9C27B080'; // purple
    }
    else if ($nota >= 9.5 && $nota < 10) {
    $color = '#6A1B9A80'; // purple
    }
  
?>;

.pagination-wrapper {
  display: flex;
  justify-content: center;
  margin-top: 2rem;
  margin-bottom: 2rem;
}

.pagination-wrapper form {
  display: flex;
  justify-content: center;
  align-items: center;
}

.pagination-wrapper input[type="text"] {
  width: 3rem;
  margin-left: 0.5rem;
  margin-right: 0.5rem;
  text-align: center;
}

.pagination-wrapper .pagination .page-item.disabled .page-link {
  color: #6c757d;
  background-color: #343a40;
  border-color: #444;
  pointer-events: none;
}

.pagination-wrapper .pagination .page-item.disabled .page-link:hover {
  color: #6c757d;
  background-color: #343a40;
  border-color: #444;
}

.pagination-wrapper .pagination .page-link {
  color: #fff;
  background-color: #343a40;
  border: 1px solid #444;
}

.pagination-wrapper .pagination .page-link:hover {
  color: #fff;
  background-color: #007bff;
  border-color: #007bff;
}

.pagination-wrapper .pagination .page-item.active .page-link {
  color: #fff;
  background-color: #007bff;
  border-color: #007bff;
}

.pagination-wrapper .pagination .page-item.active .page-link:hover {
  background-color: #007bff;
  border-color: #007bff;
}

.pagination-wrapper .pagination .page-item:first-child .page-link,
.pagination-wrapper .pagination .page-item:last-child .page-link {
  border-radius: 0.25rem;
}

.pagination-wrapper .pagination .page-item:first-child .page-link:hover {
  border-top-right-radius: 0.25rem;
  border-bottom-right-radius: 0.25rem;
}

.pagination-wrapper .pagination .page-item:last-child .page-link:hover {
  border-top-left-radius: 0.25rem;
  border-bottom-left-radius: 0.25rem;
}

.pagination-wrapper .pagination .page-item:not(:first-child):not(:last-child) .page-link:hover {
  background-color: #007bff;
  border-color: #007bff;
  color: #fff;
}




@media (max-width: 768px) {
.pagination-wrapper ul.pagination li input.page-input {
width: 30px;
}
}

</style>

<script>

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

// Obtener todos los elementos de títulos de películas
  var movieTitles = document.getElementsByClassName("card-title");

// Recorrer todos los títulos de películas
  for (var i = 0; i < movieTitles.length; i++) {
    var movieTitle = movieTitles[i];

// Obtener la longitud del título de la película
    var titleLength = movieTitle.textContent.length;

// Establecer el tamaño de fuente en función de la longitud del título
    if (titleLength <= 15) {
      movieTitle.style.fontSize = "14px";
    } else if (titleLength <= 15 && titleLength > 10) {
      movieTitle.style.fontSize = "14px";
    }  else if (titleLength <= 20 && titleLength > 15) {
      movieTitle.style.fontSize = "13px";
    } else if (titleLength <= 30 && titleLength > 20) {
      movieTitle.style.fontSize = "11px";
      movieTitle.style.padding = "1.5px";
    } else if (titleLength <= 40 && titleLength > 30) {
      movieTitle.style.fontSize = "10px";
      movieTitle.style.padding = "1.7px";
    } else if (titleLength <= 50 && titleLength > 40) {
      movieTitle.style.fontSize = "9px";
      movieTitle.style.padding = "1.8px";
    } else {
      movieTitle.style.fontSize = "9px";
      movieTitle.style.padding = "2px";
    }
  }
</script>

<?php include("plantilla/pie.php"); ?>

