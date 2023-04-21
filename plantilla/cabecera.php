<?php
session_start();
if(!isset($_SESSION['usuario'])){
  header("Location:login.php");
} else {
  if($_SESSION['usuario']=="ok"){
    $nombreUsuario=$_SESSION["nombreUsuario"];
  }
}

// Establecer la ruta de la carpeta que contiene las imágenes
$carpeta = "galeria/";

// Obtener una lista de todos los archivos de imágenes en la carpeta
$imagencarpeta = glob($carpeta . "*.jpg");

// Seleccionar una imagen aleatoria de la lista
$randomIndice = array_rand($imagencarpeta);
$randomImagen = $imagencarpeta[$randomIndice];

$carpeta2 = "galeria2/";

// Obtener una lista de todos los archivos de imágenes en la carpeta
$imagencarpeta2 = glob($carpeta2 . "*.jpg");

// Seleccionar una imagen aleatoria de la lista
$randomIndice = array_rand($imagencarpeta2);
$randomImagen2 = $imagencarpeta2[$randomIndice];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PaginaPeliculas</title>
    <link rel="stylesheet" href="./estilos/bootstrap.min.css" />
    <link rel=stylesheet href="./../estilos/cabecera.css" type="text/css">
</head>
<body>

<!-- Barra de Navegación -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
      <a class="navbar-brand" href="#">Taste can be educated, but adolescence comes only once.</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end ml-auto" id="navbarNav">
          <ul class="navbar-nav">
          <li class="nav-item">
                  <a class="nav-link text-primary" href="basededatos.php"><?php echo $nombreUsuario; ?></a>
              </li>
              <li class="nav-item ml-auto">
                  <a class="nav-link ml-auto" href="index.php">Inicio</a>
              </li>
              <li class="nav-item ml-auto">
                  <a class="nav-link ml-auto" href="listado.php">Películas</a>
              </li>
              <li class="nav-item ml-auto">
                  <a class="nav-link" href="comentarios.php">Comentarios</a>
              </li>            
              <li class="nav-item ml-auto">
                  <a class="nav-link text-danger" href="cerrar.php">Cerrar</a>
              </li>
          </ul>
      </div> 
  </nav>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

  <div class="container mt-5" style="max-width: 1200px;">
    <div class="row justify-content-center">

