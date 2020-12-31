<?php
    include 'utils/config.php';
    include 'utils/conexion.php';
    session_start();

    $flag = false;
    if (isset($_GET['id'])) {
        unset($_SESSION['user']);
        $stmt = $conn->prepare("SELECT * FROM libros WHERE id = :id;");
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT); 
        $stmt->execute();  

        $libro = $stmt->fetch();
        $id = $libro['id'];
        $nombre = $libro['nombre'];
        $autor = $libro['autor'];
        $descripcion = $libro['descripcion'];
        $genero = $libro['genero'];
        $editorial = $libro['editorial'];
        $unidades = $libro['unidades'];

        $flag = true;
    }

    $stmt = $conn->prepare("SELECT * FROM libros;");
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute();  

    $libros = $stmt->fetchAll();
?>

<!DOCTYPE html>

<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Listado de libros</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
            <a class="navbar-brand">CasaDelLibro</a>
            <button class="navbar-toggler" data-target="#my-nav" data-toggle="collapse" aria-controls="my-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div id="my-nav" class="collapse navbar-collapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="index.php">Home <span class="sr-only"></span></a>
                    </li>
                    <?php if (isset($_SESSION['user']) && $_SESSION['user']['roles'][1]['nombre'] == 'admin') { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-toggle="modal" data-target="#exampleModal">Crear libro</a>
                        </li>
                    <?php } else {} ?>
                </ul>
            </div>
        </nav>
        <div class="container">
            <h1>Listado de Libros</h1>

            <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] != "") { ?>
                <div class="alert alert-success" role="alert">
                    <?= $_GET['mensaje'] ?>
                </div>
            <?php } ?>

            <?php if (!$libros) { ?>
                <div class="jumbotron text-center">
                    <h1 class="display-4">No se han encontrado registros de libros!</h1>
                    <p class="lead">Numero de registros encontrados (0).</p>
                    <hr class="my-4">
                    <p>En este apartado encontraras una gran variedad de libros para cualquier tipo de gusto.</p>
                    <p class="lead">
                        <a class="badge badge-pill badge-primary" href="#" data-toggle="modal" data-target="#exampleModal">Crear libro.</a>
                    </p>
                </div>
            <?php } ?>

            <?php if ($libros) { ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#Id</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Autor</th>
                            <th scope="col">Descripción</th>
                            <th scope="col">Genero</th>
                            <th scope="col">Editorial</th>
                            <th scope="col">Unidades</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($libros as $libro) { ?>
                            <tr>
                                <td><?= $libro['id'] ?></td>
                                <td><?= $libro['nombre'] ?></td>
                                <td><?= $libro['autor'] ?></td>
                                <td><?= $libro['descripcion'] ?></td>
                                <td><?= $libro['genero'] ?></td>
                                <td><?= $libro['editorial'] ?></td>
                                <td><?= $libro['unidades'] ?></td>
                                <td>
                                    <a href="index.php?id=<?= $libro['id'] ?>" class="btn btn-info">Modificar</a>
                                    <form action="procLibro.php" method="POST">
                                        <input type="hidden" name="id" id="id" value="<?= $libro['id'] ?>">
                                        <button type="submit" class="btn btn-danger" name="btnAccion" value="Eliminar">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
            
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Crear libro</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="procLibro.php" method="POST">
                                <input type="hidden" name="id" id="id" value="<?= (isset($id)? $id : '') ?>">
                                <div class="form-group">
                                    <label for="nombre">Nombre</label>
                                    <input type="text" class="form-control" name="nombre" id="nombre" aria-describedby="nameHelp" placeholder="introduce el nombre del libro" value="<?= (isset($nombre)? $nombre : '') ?>">
                                </div>
                                <div class="form-group">
                                    <label for="autor">Autor</label>
                                    <input type="text" class="form-control" name="autor" id="autor" aria-describedby="autorHelp" placeholder="introduce el autor del libro" value="<?= (isset($autor)? $autor : '') ?>">
                                </div>
                                <div class="form-group">
                                    <label for="descripcion">Descripción</label>
                                    <textarea class="form-control" name="descripcion" id="descripcion" rows="3" aria-describedby="descripcionHelp" placeholder="introduce la descripción del libro"><?= (isset($descripcion)? $descripcion : '') ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="genero">Genero</label>
                                    <input type="text" class="form-control" name="genero" id="genero" aria-describedby="generoHelp" placeholder="introduce el genero del libro" value="<?= (isset($genero)? $genero : '') ?>">
                                </div>
                                <div class="form-group">
                                    <label for="editorial">Editorial</label>
                                    <input type="text" class="form-control" name="editorial" id="editorial" aria-describedby="editorialHelp" placeholder="introduce la editorial del libro" value="<?= (isset($editorial)? $editorial : '') ?>">
                                </div>
                                <div class="form-group">
                                    <label for="unidades">Unidades</label>
                                    <input type="text" class="form-control" name="unidades" id="unidades" aria-describedby="unidadesHelp" placeholder="introduce la cantidad de unidades de este libro" value="<?= (isset($unidades)? $unidades : '') ?>">
                                </div>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary" name="btnAccion" value="<?= (isset($id)? 'Actualizar' : 'Guardar') ?>"><?= (isset($id)? 'Actualizar' : 'Guardar') ?></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Modal -->
        </div>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <?php 
            if ($flag) {
                echo '<script type="text/javascript">
                    $(document).ready(function(e) {
                        $("#exampleModal").modal("show");
                    });
                </script>';
            }
        ?>
    </body>
</html>