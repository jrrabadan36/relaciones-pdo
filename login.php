<?php
    include 'utils/config.php';
    include 'utils/conexion.php';
    session_start();

    if (isset($_POST['btnAccion'])) {

        $stmt = $conn->prepare("SELECT COUNT(*) FROM usuarios WHERE nombre=:nombre;");
        $stmt->bindParam(':nombre', $_POST['nombre'], PDO::PARAM_STR); 
        $stmt->execute();  

        if ($stmt->fetchColumn() > 0) {
            $stmt = $conn->prepare("SELECT * FROM usuarios WHERE nombre=:nombre;");
            $stmt->bindParam(':nombre', $_POST['nombre'], PDO::PARAM_STR); 
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();  

            $usuario = $stmt->fetch();
            if ($_POST['password'] == openssl_decrypt($usuario['password'], $configDb['crypt']['method'], $configDb['crypt']['pass'])) {
                $idUsuario = $usuario['id'];
                // $stmt = $conn->prepare("SELECT ro.nombre FROM usuarios us, roles_usuarios ru, roles ro WHERE us.id=:idUsuario AND us.id=ru.id_usuario AND ru.id_rol=ro.id;");
                $stmt = $conn->prepare("SELECT ro.nombre FROM usuarios us
                                            INNER JOIN roles_usuarios ru ON us.id=:idUsuario AND us.id=ru.id_usuario 
                                            INNER JOIN roles ro ON ru.id_rol=ro.id;");
                $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT); 
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                $stmt->execute();  

                $rolNombre = $stmt->fetchAll();
                //var_export($rolNombre); die();
                $_SESSION['user'] = array(
                    'id' => $usuario['id'],
                    'nombre' => $usuario['nombre'],
                    'edad' => $usuario['edad'],
                    'profesion' => $usuario['profesion'],
                    'roles' => $rolNombre
                );

                header('location: index.php');
                die();
            } else {
                header('location: login.php?mensaje=usuario incorrecto');
                die();
            }
        } else {
            header('location: login.php?mensaje=usuario incorrecto');
            die();
        }
    }
?>
            
<!DOCTYPE html>

<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    </head>
    <body>
        <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] != "") { ?>
            <div class="alert alert-success" role="alert">
                <?= $_GET['mensaje'] ?>
                <a href="login.php" class="badge badge-pill badge-primary">Entrar</a>
            </div>
        <?php } ?>
        <div class="container">
            <h1>Login</h1>
            <form action="login.php" method="POST"> 
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" aria-describedby="nameHelp" placeholder="Introduce el nombre">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Introduce la contraseña">
                </div>
                <button type="submit" class="btn btn-primary" name="btnAccion">Entrar</button>
            </form>
        </div>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </body>
</html>