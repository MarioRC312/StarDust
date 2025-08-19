<?php
require_once("./../controller/controller.php");
//iniciar SESSION
session_start();

//comprobar si el usuario no tiene SESSION activa
if (!isset($_SESSION['username'])) {
    //Redirigir al index si no hay SESSION
    header('Location: ../index.php');
    exit();
}

$user = obtenirDadesUsu(); // Obtener datos del usuario

// Guardar la imagen de perfil en la sesión para que se actualice correctamente
$_SESSION['profilePic'] = $user['imatgeDePerfil'] ?? '../img/default-profile.png';


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Home | Star Dust</title>
    <meta charset="utf-8">
    <meta name="author" content="Star Dust">
    <meta name="description" content="Página de inicio de la red social simulada">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="../img/Star_Dust.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/enviarPost.css">
</head>

<body id="screen">
    <nav class="navbar navbar-light bg-light fixed-top">
        <div class="container-fluid">
            <img src="../img/Star_Dust.png" alt="Star Dust Logo" class="logo">
            <a class="navbar-brand">Star Dust</a>
            <!-- Dropdown para perfil -->
            <div class="dropdown">
              <a class="dropdown-toggle" href="#" role="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="<?= htmlspecialchars($_SESSION['profilePic']) ?>" alt="Perfil" width="40" height="40" class="rounded-circle">
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                <li><a class="dropdown-item" href="editProfile.php">Editar Perfil</a></li>
                <li><a class="dropdown-item" href="../controller/logOut.php">Cerrar Sesión</a></li>
              </ul>
            </div>
        </div>
    </nav>

    
    
    <div class="container mt-5" id="containerPrincipal" >

        <h1 class="text-center">¡Bienvenido, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
        <p class="text-center">Esta es tu página de inicio.</p>

        <!-- INCIO POP UP para crear un nuevo post -->
        <div class="modal fade" id="nuevoPostModal" tabindex="-1" aria-labelledby="nuevoPostModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="nuevoPostModalLabel">Crear un nuevo post</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="newPostForm" action="../controller/crearPost.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <textarea class="form-control" name="contenido" placeholder="¿Qué estás pensando?" required></textarea>
                            </div>
                            <div class="mb-3">
                                <input type="file" class="form-control" name="archivo" accept="image/*, video/*">
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" name="tags" placeholder="Ingresa etiquetas separadas por coma">
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-success">Publicar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- INCIO POP UP para crear un nuevo post -->
        <div class="modal fade" id="nuevoPostModal" tabindex="-1" aria-labelledby="nuevoPostModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="nuevoPostModalLabel">Crear un nuevo post</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="newPostForm" action="../controller/crearPost.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <textarea class="form-control" name="contenido" placeholder="¿Qué estás pensando?" required></textarea>
                            </div>
                            <div class="mb-3">
                                <input type="file" class="form-control" name="archivo" accept="image/*, video/*">
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" name="tags" placeholder="Ingresa etiquetas separadas por coma">
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-success">Publicar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenedor para la animación (se agregará dinámicamente) -->
        <div id="animationContainer"></div>
        <!-- FINAL POP UP para crear un nuevo post -->


        <!-- INICIO mostrar post -->
        <div class="container mt-5">
        <h2 class="text-center">Últimos Posts</h2>
        
        <div class="etiquetas text-center mb-4">
            <!-- Enlace para mostrar TODOS los posts -->
            <a href="home.php?tag=todos" class="badge">Todos</a>
            <?php
                $conn = getDBConnection();
                $stmt = $conn->query("SELECT name FROM tags ORDER BY name ASC");
                while ($tag = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<a href='home.php?tag=" . urlencode($tag['name']) . "' class='badge'>" . htmlspecialchars($tag['name']) . "</a>";
                }
            ?>
        </div>
        
        <?php
            obtenirPosts();
        ?>  
        <!-- FINAL creacion post -->
    </div>
    <div class="btn-newPost">
        <button id="boton" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoPostModal">+</button>
    </div>

    <script src="./../js/enviarPost.js"></script>
    <script src="./../js/likes.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
</body>