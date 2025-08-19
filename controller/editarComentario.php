<?php
session_start();
require_once('./db.php');
require_once("./badWords.php");

if (!isset($_GET['id'])) {
    header("Location: ../index.php");
    exit;
}

$commentId = $_GET['id'];
$conn = getDBConnection();

//obtener el comentario
$stmt = $conn->prepare("SELECT * FROM comments WHERE id = ?");
$stmt->execute([$commentId]);
$coment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$coment || $coment['username'] !== $_SESSION['username']) {
    echo "No tienes permiso para editar este comentario.";
    exit;
}

//si se envía el formulario, actualizar el comentario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevoContenido = $_POST['contenido'];

    //censurar palabras inadecuadas
    foreach ($palabrasProhibidas as $palabra) {
        $nuevoContenido = str_ireplace($palabra, str_repeat('*', strlen($palabra)), $nuevoContenido );
    }
    
    //actualizar comentario y la fecha de modificacion
    $updateStmt = $conn->prepare("UPDATE comments SET contenido = ?, fecha = NOW() WHERE id = ?");
    $updateStmt->execute([$nuevoContenido, $commentId]);
    
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Comentario</title>
    <link rel="icon" href="../img/Star_Dust.png">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/editComentario.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- contenedor con la clase personalizada -->
    <div class="edit-post-container">
        <h3>Editar Comentario</h3>
        <form action="editarComentario.php?id=<?php echo $commentId; ?>" method="POST">
            <div class="mb-3">
                <textarea name="contenido" class="form-control" rows="4" required><?php echo htmlspecialchars($coment['contenido']); ?></textarea>
            </div>
            <!-- usamos los botones personalizados -->
            <button type="submit" class="btn btn-success">Actualizar Comentario</button>
            <a href="../index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
