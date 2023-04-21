<?php

$host="localhost";
$db="bbddpeliculas";
$usuario="root";
$contrasenia="";

try {
    $conexion=new PDO("mysql:host=$host;dbname=$db", $usuario, $contrasenia);
    
} catch (Exception $ex) {
    echo $ex->getMessage();
}

session_start();
if(!isset($_SESSION['usuario'])){
  header("Location:login.php");
} else {
  if($_SESSION['usuario']=="ok"){
    $nombreUsuario=$_SESSION["nombreUsuario"];
    
    $query = "SELECT permisos FROM usuarios WHERE usuarionombre='$nombreUsuario'";
    $stmt = $conexion->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if($row['permisos'] != 2) {
        // Redirect user to a different page
        header("Location: index.php");
        exit();
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Gestor</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel=stylesheet href="./../estilos/cabeceragestor.css" type="text/css">
  </head>
  <body>

<?php $url="http://".$_SERVER['HTTP_HOST']."/webpeliculas" ?>

<!-- Barra de Navegación -->
  <nav class="navbar navbar-expand-md navbar-dark bg-dark sticky-top">
      <a class="navbar-brand" href="#">Página de gestión</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end ml-auto" id="navbarNav">
          <ul class="navbar-nav">
              <li class="nav-item ml-auto">
                  <a class="nav-link" href="<?php echo $url."/saludo.php";?>">Inicio</a>
              </li>
              <li class="nav-item ml-auto">
                  <a class="nav-link" href="<?php echo $url."/basededatos.php";?>">Base de Datos</a>
              </li>            
              <li class="nav-item ml-auto">
                  <a class="nav-link" href="<?php echo $url;?>">Web</a>
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

  <div class="container mt-5">
    <div class="row justify-content-center">
