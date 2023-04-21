<?php include("plantilla/cabeceragestor.php"); ?>
        <div class="col-md-10">
            <div class="jumbotron text-center">
                <h1 class="display-3">¡Hola, <?php echo $nombreUsuario; ?>!</h1>
                    <p class="lead">Estás en la puerta de entrada del Gestor de Películas, donde puedes agregar, eliminar, seleccionar y modificar las películas listadas.</p>
                        <hr class="my-4">
                    <p>¿Qué te gustaría hacer?</p>
                <div class="d-flex justify-content-center">
                    <a class="btn btn-primary btn-lg mx-3" href="basededatos.php">Ir a la Base de Datos</a>
                    <?php if(isset($mensaje)){ ?>
                        <p><?php echo $mensaje; ?></p>
                    <?php } ?>
                </div>
            </div>
        </div>
<?php include("plantilla/piegestor.php"); ?>