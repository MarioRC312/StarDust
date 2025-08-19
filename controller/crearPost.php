<?php
session_start();
require_once("controller.php"); 
require_once("./badWords.php"); 

if (!isset($_SESSION['username'])) {
    header('Location: ../index.php');
    exit();
}

$conn = getDBConnection();
$username = $_SESSION['username'];
$contenido = $_POST['contenido'];
$archivo = null;

//censurar palabras inadecuadas
foreach ($palabrasProhibidas as $palabra) {
    $contenido = str_ireplace($palabra, str_repeat('*', strlen($palabra)), $contenido);
}

// Procesar la subida de archivos (imagen o video)
if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === 0) {
    $directorioDestino = "../uploads/";
    $nombreArchivo = basename($_FILES['archivo']['name']);
    $rutaDestino = $directorioDestino . $nombreArchivo;

    // Verificar si es una imagen o un video
    $tipoArchivo = mime_content_type($_FILES['archivo']['tmp_name']);
    $extensionesPermitidas = ["image/jpeg", "image/png", "image/gif", "video/mp4", "video/webm", "video/ogg"];

    if (in_array($tipoArchivo, $extensionesPermitidas)) {
        move_uploaded_file($_FILES['archivo']['tmp_name'], $rutaDestino);
        $archivo = $rutaDestino;
    }
}

//insertar en la base de datos
$sql = "INSERT INTO posts (username, contenido, archivo, fecha) VALUES (:username, :contenido, :archivo, NOW())";
$stmt = $conn->prepare($sql);
$stmt->execute([
    ':username' => $username,
    ':contenido' => $contenido,
    ':archivo' => $archivo
]);

//despues de insertar el post creamos el tag
$post_id = $conn->lastInsertId();

if (isset($_POST['tags']) && !empty($_POST['tags'])) {
    //separar etiquetas por coma para obtener los tags
    $tags = explode(',', $_POST['tags']);
    
    foreach ($tags as $tag) {
        $tag = trim($tag);
        if ($tag != "") {
            //verificar si la etiqueta ya existe
            $stmt = $conn->prepare("SELECT id FROM tags WHERE name = :name");
            $stmt->execute([':name' => $tag]);
            $tag_id = $stmt->fetchColumn();

            //si no existe, insertarla
            if (!$tag_id) {
                $stmt = $conn->prepare("INSERT INTO tags (name) VALUES (:name)");
                $stmt->execute([':name' => $tag]);
                $tag_id = $conn->lastInsertId();
            }

            //insertar la relación en post_tags
            $stmt = $conn->prepare("INSERT INTO post_tags (post_id, tag_id) VALUES (:post_id, :tag_id)");
            $stmt->execute([':post_id' => $post_id, ':tag_id' => $tag_id]);
        }
    }
}

header("Location: ../view/home.php");
exit();


?>