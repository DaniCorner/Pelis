<?php 
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$host = 'localhost';
$db = 'bbddpeliculas';
$usuario = 'root';
$contrasenia = '';

// Crear conexión a la base de datos
$conn = new mysqli($host, $usuario, $contrasenia, $db);

if ($conn->connect_error) {
  die('Error en la conexión: ' . $conn->connect_error);
  }

$usuario = mysqli_real_escape_string($conn, $_POST['usuario']);
$contrasenia = mysqli_real_escape_string($conn, $_POST['contrasenia']);

// Consulta para el inicio de sesión
$sql = "SELECT * FROM usuarios WHERE usuarionombre='$usuario' AND contraseniausuario='$contrasenia'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
        
    // Inicio de sesión exitoso, establecer variables de sesión
    $_SESSION['usuario'] = "ok";
    $_SESSION['nombreUsuario'] = $row['usuarionombre'];
    $_SESSION['permisos'] = $row['permisos'];

    // Establecer la variable $nombreUsuario
    $nombreUsuario = $row['usuarionombre'];

// Redirigir a la página de inicio
if ($row['permisos'] == 1) {
  header('Location: index.php');
  exit();
} else if ($row['permisos'] == 2) {
  header('Location: saludo.php');
  exit();
}

    }
    else {
    // Inicio de sesión fallido, mostrar mensaje de error
    $mensaje = 'Error: El usuario o contraseña son incorrectos';
    }

$conn->close();
  } else {
  // Verificar si el usuario ya inició sesión
    if (isset($_SESSION['usuario']) && $_SESSION['usuario'] === 'ok') {
    if ($_SESSION['permisos'] === 1) {
    header('Location: index.php');
    exit();
      } else if ($_SESSION['permisos'] === 2) {
      header('Location: saludo.php');
      exit();
      }
    }
}
?>

<!doctype html>
<html lang="en">
  <head>
    <title>Title</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel=styleSheet href="./estilos/login.css" type="text/css">    
  </head>
  <body>
      <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-header">
                        Login
                    </div>
                    <div class="card-body">
                        <?php if(isset($mensaje)){ ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $mensaje; ?>
                        </div>
                        <?php } ?> 
                        <form method="POST">
                            <div class="form-group">
                                <label for="usuario">Usuario</label>
                                <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Tu nombre">
                            </div>
                            <div class="form-group">
                                <label for="contrasenia">Contraseña</label>
                                <input type="password" class="form-control" name="contrasenia" placeholder="Contraseña">
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <button type="submit" class="btn btn-primary">Entrar</button>
                                <div class="d-flex align-items-center">
                                    <span class="text-muted small">¿Sin cuenta?</span>
                                    <a href="registro.php" class="btn btn-secondary ml-2">Registro</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> 
<!-- Add Bootstrap dependencies -->



      
  </body>
</html>