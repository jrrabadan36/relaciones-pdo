<?php
    include 'utils/config.php';
    include 'utils/conexion.php';

    if (isset($_POST['btnAccion'])) {
        $passwordEncrypt = openssl_encrypt($_POST['password'], $configDb['crypt']['method'], $configDb['crypt']['pass']);
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, password, edad, profesion) VALUES (:nombre, :password, :edad, :profesion)");
        $stmt->bindParam(':nombre', $_POST['nombre'], PDO::PARAM_STR); 
        $stmt->bindParam(':password', $passwordEncrypt, PDO::PARAM_STR); 
        $stmt->bindParam(':edad', $_POST['edad'], PDO::PARAM_INT); 
        $stmt->bindParam(':profesion', $_POST['profesion'], PDO::PARAM_STR); 
        $stmt->execute(); 

        $idUsuario = $conn->lastInsertId();
        $idRol = $_POST['rol'];

        $stmt = $conn->prepare("INSERT INTO roles_usuarios (id_usuario, id_rol) VALUES (:id_usuario, :id_rol)");
        $stmt->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT); 
        $stmt->bindParam(':id_rol', $idRol, PDO::PARAM_INT); 
        $stmt->execute(); 

        header('location: registro.php?mensaje=Usuario registrado correctamente');
        die();
    }

    $stmt = $conn->prepare("SELECT * FROM roles;");
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute();  

    $roles = $stmt->fetchAll();
?>
            
<!DOCTYPE html>

<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registro de usuarios</title>
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
            <h1>registro de usuarios</h1>
            <form action="registro.php" method="POST">
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Introduzca el nombre">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Introduzca la contraseña">
                </div>
                <div class="form-group">
                    <label for="edad">Edad</label>
                    <input type="text" class="form-control" id="edad" name="edad" placeholder="Introduzca la edad">
                </div>
                <div class="form-group">
                    <label for="profesion">Profesión</label>
                    <input type="text" class="form-control" id="profesion" name="profesion" placeholder="Introduzca la profesión">
                </div>
                <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <label class="input-group-text" for="inputGroupSelect01">Opciones</label>
                </div>
                <select class="custom-select" id="rol" name="rol">
                    <option selected>Elegir...</option>
                    <?php foreach ($roles as $rol) { ?>
                        <option value="<?= $rol['id'] ?>"><?= $rol['nombre'] ?></option>
                    <?php } ?>
                </select>
                </div>
                <button type="submit" class="btn btn-primary" name="btnAccion">Registrarse</button>
            </form>
        </div>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </body>
</html>