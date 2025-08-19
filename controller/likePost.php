<?php
require_once("./../model/db.php");
session_start();

if (!isset($_SESSION['username'])) {
    echo json_encode(["success" => false, "message" => "Usuario no autenticado"]);
    exit();
}

$conn = getDBConnection();
$post_id = $_GET['post_id'] ?? null;
$username = $_SESSION['username'];
$reaction = $_GET['reaction'] ?? null; // Puede ser 'like', 'meh' o 'dislike'


//verificar si el usuario ya ha reaccionado al post
$checkReaction = $conn->prepare("SELECT reaction FROM post_likes WHERE post_id = ? AND username = ?");
$checkReaction->execute([$post_id, $username]);
$existingReaction = $checkReaction->fetchColumn();

if ($existingReaction) {
    if ($existingReaction === $reaction) {
        //si el usuario ya seleccionó esta reacción, eliminarla (cancelar reacción)
        $deleteReaction = $conn->prepare("DELETE FROM post_likes WHERE post_id = ? AND username = ?");
        $deleteReaction->execute([$post_id, $username]);
    } else {
        //si el usuario cambia de reacción, actualizar la fila existente
        $updateReaction = $conn->prepare("UPDATE post_likes SET reaction = ? WHERE post_id = ? AND username = ?");
        $updateReaction->execute([$reaction, $post_id, $username]);
    }
} else {
    //si no ha reaccionado, agregar la nueva reacción
    $addReaction = $conn->prepare("INSERT INTO post_likes (post_id, username, reaction) VALUES (?, ?, ?)");
    $addReaction->execute([$post_id, $username, $reaction]);
}

//contar cada tipo de reacción
$likeCount = $conn->prepare("SELECT COUNT(*) FROM post_likes WHERE post_id = ? AND reaction = 'like'");
$likeCount->execute([$post_id]);
$likes = $likeCount->fetchColumn();

$mehCount = $conn->prepare("SELECT COUNT(*) FROM post_likes WHERE post_id = ? AND reaction = 'meh'");
$mehCount->execute([$post_id]);
$mehs = $mehCount->fetchColumn();

$dislikeCount = $conn->prepare("SELECT COUNT(*) FROM post_likes WHERE post_id = ? AND reaction = 'dislike'");
$dislikeCount->execute([$post_id]);
$dislikes = $dislikeCount->fetchColumn();

echo json_encode(["success" => true, "likes" => $likes, "mehs" => $mehs, "dislikes" => $dislikes]);

header("Location: ../view/home.php");
exit();

?>