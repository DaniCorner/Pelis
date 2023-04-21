
if(isset($_POST['truncar'])){
    try {
      $conexion = new PDO("mysql:host=$host;dbname=$db", $usuario, $contrasenia);
      $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "TRUNCATE TABLE mispeliculas";
      $conexion->exec($sql);
      echo "La tabla se ha truncado correctamente";
    } catch(PDOException $e) {
      echo $sql . "<br>" . $e->getMessage();
    }
  }