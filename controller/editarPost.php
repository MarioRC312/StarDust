<?php
session_start();
require_once("../controller/controller.php");

if (!isset($_SESSION['username'])) {
    header('Location: ../index.php');
    exit();
}

$conn = getDBConnection();
$postId = $_GET['id'] ?? null;

if (!$postId) {
    header("Location: home.php");
    exit();
}

//obtener los datos del post
$sql = "SELECT * FROM posts WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([':id' => $postId]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

//verificar si el usuario es el autor
if (!$post || $post['username'] !== $_SESSION['username']) {
    header("Location: ./../view/home.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nuevoContenido = $_POST['contenido'];
    
    //actualizar en la base de datos
    $sql = "UPDATE posts SET contenido = :contenido WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':contenido' => $nuevoContenido,
        ':id' => $postId
    ]);

    header("Location: ./../view/home.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es"> 
<head>
    <title>Editar Post | Star Dust</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="../img/Star_Dust.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/editPost.css">
</head>
<body>
    <div class="container edit-post-container mt-5">
        <h2 class="text-center">Editar Post</h2>
        <form id="editPostForm" action="" method="POST">
            <div class="mb-3">
                <textarea class="form-control" name="contenido" required><?= htmlspecialchars($post['contenido']) ?></textarea>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-success">Guardar cambios</button>
                <a href="./../view/home.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <!--las estrellas fugaces se crearán dinámicamente-->
    <script src="./../js/editarPost.js"></script>
</body>
</html>
