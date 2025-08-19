<?php
session_start();
require_once('./db.php'); 

if (!isset($_GET['id'])) {
    header("Location: ../index.php");
    exit;
}

$commentId = $_GET['id'];
$conn = getDBConnection();

//obtener el comentario para verificar permisos
$stmt = $conn->prepare("SELECT * FROM comments WHERE id = ?");
$stmt->execute([$commentId]);
$coment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$coment || $coment['username'] !== $_SESSION['username']) {
    echo "No tienes permiso para eliminar este comentario.";
    exit;
}

// Eliminar el comentario
$deleteStmt = $conn->prepare("DELETE FROM comments WHERE id = ?");
$deleteStmt->execute([$commentId]);

header("Location: ../index.php"); //redirige a la página principal u otra según tu flujo
exit;
?>