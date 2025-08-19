<?php
require_once("./../model/db.php");
require_once("./badWords.php");

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = getDBConnection();
    $post_id = $_POST['post_id'];
    $contenido = trim($_POST['contenido']);
    $username = $_SESSION['username'];

    //censurar palabras inadecuadas
    foreach ($palabrasProhibidas as $palabra) {
        $contenido = str_ireplace($palabra, str_repeat('*', strlen($palabra)), $contenido);
    }

    if (!empty($contenido)) {
        $stmt = $conn->prepare("INSERT INTO comments (post_id, username, contenido) VALUES (?, ?, ?)");
        $stmt->execute([$post_id, $username, $contenido]);
    }

    header("Location: ../view/home.php");
    exit();
}
?>