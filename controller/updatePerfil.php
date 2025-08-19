<?php
session_start();
require_once("./controller.php");

if (!isset($_SESSION['username'])) {
    header('Location: ../index.php');
    exit();
}

$conn = getDBConnection();
if ($conn) {
    //obtener valores del formulario
    $username = trim($_POST['username']);
    $biografia = trim($_POST['biografia']);
    $ubicacio = trim($_POST['ubicacio']);
    $dataNaix = $_POST['dataNaix'];

    //definir la variable de la imagen
    $imagePath = null;

    //procesar la imagen de perfil si se ha subido
    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['size'] > 0) {
        $uploadDir = "../uploads/users/" . $_SESSION['username'] . "/";

        //crear el directorio si no existe
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        //obtener extensión del archivo
        $imageFileType = strtolower(pathinfo($_FILES["profileImage"]["name"], PATHINFO_EXTENSION));
        $allowedTypes = ["jpg", "jpeg", "png", "gif"];

        if (in_array($imageFileType, $allowedTypes)) {
            $imagePath = $uploadDir . "profile." . $imageFileType;
            move_uploaded_file($_FILES["profileImage"]["tmp_name"], $imagePath);
        }
    }

    //actualizar datos en la base de datos
    try {
        $sql = "UPDATE users SET username = :username, biografia = :biografia, ubicacio = :ubicacio, dataNaix = :dataNaix";

        if ($imagePath) {
            $sql .= ", imatgeDePerfil = :imatgeDePerfil";
        }

        $sql .= " WHERE username = :sessionUsername";

        $stmt = $conn->prepare($sql);

        $params = [
            ':username' => $username,
            ':biografia' => $biografia,
            ':ubicacio' => $ubicacio,
            ':dataNaix' => $dataNaix,
            ':sessionUsername' => $_SESSION['username']
        ];

        if ($imagePath) {
            $params[':imatgeDePerfil'] = $imagePath;
        }

        $stmt->execute($params);

        //actualizar la sesión con el nuevo nombre de usuario
        $_SESSION['username'] = $username;

        //actualizar la sesión con la nueva imagen
        if ($imagePath) {
            $_SESSION['profilePic'] = $imagePath;
        }

        //redirigir al perfil
        header('Location: ../view/home.php');
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        error_log("Error al actualizar perfil: " . $e->getMessage());
        die("Error al actualizar perfil.");
    }
}