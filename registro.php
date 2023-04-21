<!DOCTYPE html>
<html>
  <head>
      <title>Register</title>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
      <link rel=stylesheet href="./estilos/registro.css" type="text/css">
  </head>
<body class="futurible">

<?php
    // Establecer conexión con la base de datos
    $host = 'localhost';
    $db = 'bbddpeliculas';
    $usuario = 'root';
    $contrasenia = '';

    $conn = new mysqli($host, $usuario, $contrasenia, $db);

    // Comprobar conexión
    if (!$conn) {
        die("Falló la conexión: " . mysqli_connect_error());
    }

    // Procesar los datos del formulario para insertar un nuevo usuario en la base de datos
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $usuario = $_POST['usuario'];
        $contrasenia = $_POST['contrasenia'];

        // Consulta SQL para insertar un nuevo usuario en la base de datos
        $sql = "INSERT INTO usuarios (usuarionombre, contraseniausuario) VALUES ('$usuario', '$contrasenia')";

        // Ejecutar la consulta
        if (mysqli_query($conn, $sql)) {
            // Redirigir al usuario a una página de éxito
            header('Location: index.php');
            exit;
        } else {
            $mensaje = "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        mysqli_close($conn);
    }
?>

      <div class="container">
        <div class="row">
          <div class="col-12 col-md-6 col-lg-4 mx-auto">
            <div class="card futurible mt-5">
              <div class="card-header futurible">
                Registro
              </div>
              <div class="card-body futurible">
                <?php if(isset($mensaje)){ ?>
                <div class="alert-danger futurible" role="alert">
                  <?php echo $mensaje; ?>
                </div>
                <?php } ?> 
                <form method="POST">
                  <div class="form-group futurible">
                    <label for="usuario" class="futurible">Usuario</label>
                    <input type="text" class="form-control futurible" id="usuario" name="usuario" placeholder="Nombre de Usuario">
                  </div>
                  <div class="form-group futurible">
                    <label for="contrasenia" class="futurible">Contraseña</label>
                    <input type="password" class="form-control futurible" name="contrasenia" placeholder="Contraseña">
                  </div>
                  <div class="d-flex align-items-center">
                    <button type="submit" class="btn btn-primary futurible">Registrarse</button>
                    <div class="ml-auto d-flex align-items-center">
                    <span class="text-muted small ml-5 futurible">¿No tienes cuenta?   </span>
                      <a href="login.php" class="btn btn-secondary futurible">Login</a>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </body>
</html>