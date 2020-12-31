<?php
    include 'utils/config.php';
    include 'utils/conexion.php';

    if (isset($_POST['btnAccion'])) {
        switch ($_POST['btnAccion']) {
            case 'Guardar':
                $stmt = $conn->prepare("INSERT INTO libros (nombre, autor, descripcion, genero, editorial, unidades) VALUES (:nombre, :autor, :descripcion, :genero, :editorial, :unidades)");
                $stmt->bindParam(':nombre', $_POST['nombre'], PDO::PARAM_STR); 
                $stmt->bindParam(':autor', $_POST['autor'], PDO::PARAM_STR); 
                $stmt->bindParam(':descripcion', $_POST['descripcion'], PDO::PARAM_STR); 
                $stmt->bindParam(':genero', $_POST['genero'], PDO::PARAM_STR); 
                $stmt->bindParam(':editorial', $_POST['editorial'], PDO::PARAM_STR); 
                $stmt->bindParam(':unidades', $_POST['unidades'], PDO::PARAM_INT); 
                $stmt->execute(); 

                header('location: index.php?mensaje=Libro guardado correctamente');
                die();
            break;
            case 'Actualizar':
                $stmt = $conn->prepare("UPDATE libros SET nombre=:nombre, autor=:autor, descripcion=:descripcion, genero=:genero, editorial=:editorial, unidades=:unidades WHERE id=:id;");
                $stmt->bindParam(':nombre', $_POST['nombre'], PDO::PARAM_STR); 
                $stmt->bindParam(':autor', $_POST['autor'], PDO::PARAM_STR); 
                $stmt->bindParam(':descripcion', $_POST['descripcion'], PDO::PARAM_STR); 
                $stmt->bindParam(':genero', $_POST['genero'], PDO::PARAM_STR); 
                $stmt->bindParam(':editorial', $_POST['editorial'], PDO::PARAM_STR); 
                $stmt->bindParam(':unidades', $_POST['unidades'], PDO::PARAM_INT); 
                $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT); 
                $stmt->execute(); 

                header('location: index.php?mensaje=Libro actualizado correctamente');
                die();
            break;
            case 'Eliminar':
                $stmt = $conn->prepare("DELETE FROM libros WHERE `id`=:id;");
                $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT); 
                $stmt->execute(); 

                header('location: index.php?mensaje=Libro eliminado correctamente');
                die();
            break;
        }
    } else {

    }