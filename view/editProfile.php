<?php
session_start();
require_once ("../controller/controller.php");

//comprobar si el usuario está logueado
if (!isset($_SESSION['username'])) {
    header('Location: ../index.php');
    exit();
}

//obtener los datos actuales del usuario
$user = obtenirDadesUsu();
if ($user) {
    $profilePic = !empty($user['imatgeDePerfil']) ? $user['imatgeDePerfil'] : '../img/default-profile.png';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Editar Perfil | Star Dust</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="../img/Star_Dust.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <!-- Incluimos el CSS personalizado -->
    <link rel="stylesheet" href="../css/editPerfil.css">
</head>
<body>
    <div class="container edit-profile-container">
        <h2 class="text-center">Editar Perfil</h2>
        <form action="../controller/updatePerfil.php" method="POST" enctype="multipart/form-data">
            <div class="mb-4 text-center">
                <div class="profile-img-container">
                    <img src="<?= htmlspecialchars($profilePic) ?>" alt="Imagen de Perfil" class="profile-img">
                </div>
                <input type="file" name="profileImage" class="form-control mt-3">
            </div>

            <div class="mb-3">
                <label class="form-label">Nombre de Usuario:</label>
                <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Biografía:</label>
                <textarea name="biografia" class="form-control" rows="3"><?= htmlspecialchars($user['biografia'] ?? '') ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Ubicación:</label>
                <input type="text" name="ubicacio" class="form-control" value="<?= htmlspecialchars($user['ubicacio'] ?? '') ?>">
            </div>

            <div class="mb-4">
                <label class="form-label">Fecha de Nacimiento:</label>
                <input type="date" name="dataNaix" class="form-control" value="<?= htmlspecialchars($user['dataNaix'] ?? '') ?>">
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                <a href="home.php" class="btn btn-secondary">Volver</a>
            </div>
        </form>
    </div>

</body>
</html>
