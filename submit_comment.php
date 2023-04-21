<?php include("plantilla/cabecera.php"); ?>

<?php 
  $nombreUsuario = $_SESSION['nombreUsuario'];
  // Connect to database
  $host = "localhost";
  $db = "bbddpeliculas";
  $usuario = "root";
  $contrasenia = "";
  $conexion = mysqli_connect($host, $usuario, $contrasenia, $db);

  // Escape user input to prevent SQL injection
  $movie = mysqli_real_escape_string($conexion, $_POST["movie"]);
  $comment_text = mysqli_real_escape_string($conexion, $_POST["comment-text"]);
  $user_name = mysqli_real_escape_string($conexion, $_POST["user-name"]);
  $rating = mysqli_real_escape_string($conexion, $_POST["rating"]);
  


    // User exists, insert comment
    $query = "INSERT INTO miscomentarios (idpelicula, comentario_texto, usuarionombre, notapelicula) VALUES ('$movie','$comment_text', '$user_name', '$rating')";
    $result = mysqli_query($conexion, $query);

    
    header('Location: comentarios.php');
    
  
  
  
  
?>
