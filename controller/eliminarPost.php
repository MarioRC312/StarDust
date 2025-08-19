<?php
session_start();
require_once("controller.php");

if (!isset($_SESSION['username'])) {
    header('Location: ../index.php');
    exit();
}

$conn = getDBConnection();
$postId = $_GET['id'] ?? null;

if ($postId) {
    //obtener el autor del post
    $sql = "SELECT username FROM posts WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $postId]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    //verificar si el usuario es el autor
    if ($post && $post['username'] === $_SESSION['username']) {
        // Eliminar el post
        $sql = "DELETE FROM posts WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $postId]);
    }
}

header("Location: ../view/home.php");
exit();