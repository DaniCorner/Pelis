<link rel=stylesheet href="./estilos/index.css" type="text/css">
<?php include("plantilla/cabecera.php"); ?>


<div class="jumbotron text-center">
    <h1 class="display-3"><small>Bienvenid@ <?php echo $nombreUsuario; ?></small> <br> al Séptimo Arte según Yo</h1>
      <p class="lead">¡Descubre el mundo del cine con nosotros!</p>
      <hr class="my-2">
      <img id="myImage" width="400" src="<?php echo $randomImagen; ?>" class="img-fluid rounded mx-auto d-block" alt="Imagen de inicio">
      <br><p>Mi sitio web es una recopilación personal de películas, cuidadosamente ordenadas de mejor a peor, incluyendo mis favoritas de todos los tiempos. Los usuarios pueden seleccionar sus propias películas favoritas también. Como un verdadero apasionado del cine, he creado este espacio para compartir mi amor por el séptimo arte con otros cinéfilos. Aquí encontrarás mis preferencias y una lista completa de todas las películas que he visto en mi vida. <br>¡Explora mi colección y descubre nuevas películas para disfrutar!</p>
      <p class="lead">
        <a class="btn btn-primary btn-lg" href="listado.php" role="button">Mis películas favoritas</a>
      </p>
</div>

<div class="container">
  <div class="row">
    <div class="col-md-4 text-center mb-5">
      <i class="fas fa-film fa-5x mb-3"></i>
      <h4>+1400 Películas</h4>
      <p>Encuentra información sobre las películas más populares y descubre nuevas obras maestras.</p>
    </div>
    <div class="col-md-4 text-center mb-5">
      <i class="fas fa-newspaper fa-5x mb-3"></i>
      <h4>Rankings</h4>
      <p>Mantente al día con mis últimos descubrimientos y su posición en mis listas.</p>
    </div>
    <div class="col-md-4 text-center mb-5">
      <i class="fas fa-comment fa-5x mb-3"></i>
      <h4>Comentarios</h4>
      <p>Deja tus comentarios y opiniones sobre tus películas favoritas y conoce a otros amantes del cine.</p>
    </div>
  </div>
</div>


<?php include("plantilla/pie.php"); ?>

