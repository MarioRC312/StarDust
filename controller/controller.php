<?php

use PHPMailer\PHPMailer\PHPMailer;
require __DIR__ .'/../vendor/autoload.php';


/********************************************************LOGIN USER********************************************************/

$ruta = getcwd();

require_once __DIR__ .'/../model/db.php';

function loginUser($userOrEmail, $pass)
{
  return loginUserDB($userOrEmail, $pass);
}

function insertUser($user)
{
  return insertUserDB($user);
}

function verifyExistentUser($mail)
{
  $user = [
    'email' => $mail,
    'username' => '',
  ];
  return verifyExistentUserDB($user);
}

function generateActivationCode()
{
  return hash("sha256", rand(0, 9999));
}

function generateResetPassCode($mail)
{
  require_once __DIR__ .'/../controller/generarResetPassCode.php';
  $code = generateResetPassCodeDB($mail);
  return $code;
}

/********************************************************ENVIAR MAIL********************************************************/

function sendEmail($user, $type)
{
  $mail = new PHPMailer(true);
  $mail->IsSMTP();
  //Configuració del servidor de Correu
  //Modificar a 0 per eliminar msg error
  $mail->SMTPDebug = 0;
  $mail->SMTPAuth = true;
  $mail->SMTPSecure = 'tls';
  $mail->Host = 'smtp.gmail.com';
  $mail->Port = 587;
  //Credencials del compte GMAIL
  $mail->Username = 'stardustmail001@gmail.com';
  $mail->Password = 'ezsy jqxp dnpr etgm';

  //Dades del correu electrònic
  $mail->SetFrom('stardustmail001@gmail.com', 'Soporte Elemental Echoes');
  $mail->Subject = ($type == "verification") ? 'Verificación de correo' : 'Restablecer contraseña';
  $mail->isHTML(true);
  $mail->CharSet = 'UTF-8';
  $mail->Body = mailBodyConstructor($user, $type);
  //Destinatari
  $address = $user['email'];
  $mail->AddAddress($address);

  //Enviament
  $result = $mail->Send();
  if (!$result) {
    echo 'Error: ' . $mail->ErrorInfo;
  } else {
    echo "Correu enviat";
  }
}

function mailBodyConstructor($user, $type)
{
  if ($type == "verification") {
    $verificationLink = 'http://localhost/controller/mailCheckAccount.php?code=' . $user['activationCode'] . '&mail=' . $user['email'];

      $body = "
          <html>
          <body>
              <p>Hola " . $user['username'] . ",</p>
              <p>Gracias por registrarte en nuestro sitio. Por favor haz clic en el siguiente botón para verificar tu correo electrónico:</p>
              <a href='" . $verificationLink . "' style='display:inline-block;background-color:#4CAF50;color:white;padding:14px 20px;text-align:center;text-decoration:none;display:inline-block;font-size:16px;margin:4px 2px;cursor:pointer;border-radius:10px;'>Verificar Correo</a>
              <p>Si el botón no funciona, también puedes copiar y pegar el siguiente enlace en tu navegador:</p>
              <p><a href='" . $verificationLink . "'>" . $verificationLink . "</a></p>
              <p>Gracias,<br>Tu equipo</p>
          </body>
          </html>
      ";
  } else if ($type == "password") {
    $passwordLink = 'http://localhost/view/reset_password.php?code=' . $user['resetPassCode'] . '&mail=' . $user['email'];
    $body = "
      <html>
      <body>
        <p>Hola,</p>
        <p>Recibiste este correo porque solicitaste restablecer tu contraseña en nuestro sitio web.</p>
        <p>Si no hiciste esta solicitud, puedes ignorar este mensaje.</p>
        <p>Para restablecer tu contraseña, haz clic en el siguiente botón:</p>
        <a href='" . $passwordLink . "' style='display:inline-block;background-color:#4CAF50;color:white;padding:14px 20px;text-align:center;text-decoration:none;display:inline-block;font-size:16px;margin:4px 2px;cursor:pointer;border-radius:10px;'>Restablecer Contraseña</a>
        <p>Si el botón no funciona, puedes copiar y pegar el siguiente enlace en tu navegador:</p>
        <p><a href='" . $passwordLink . "'>" . $passwordLink . "</a></p>
        <p>Gracias,</p>
        <p>Tu equipo de soporte</p>
      </body>
      </html>";
  }
 

  return $body;
}

/********************************************************VERIFICAR CUENTA********************************************************/
function verifyAccount($code, $mail)
{
  if ($code == getActivationCode($mail))
    return true;
  else
    return false;
}

function updateActive($mail)
{
  updateActiveDB($mail);
}

/********************************************************RESET PASSWORD********************************************************/

function verifyResetPassCode($mail, $resetPassCode)
{
  $result = false;
  $conn = getDBConnection();
  $sql = "SELECT resetPassCode FROM `users` WHERE `mail`=:userMail";
  try {
    $usuaris = $conn->prepare($sql);
    $usuaris->execute([':userMail' => $mail]);
    if ($usuaris->fetchColumn() == $resetPassCode) {
      $result = true;
    }
  } catch (PDOException $e) {
  } finally {
    return $result;
  }
}

function verifyTimeLeft($mail, $resetPassCode)
{
  $result = false;
  $conn = getDBConnection();
  $sql = "SELECT resetPassExpiry FROM `users` WHERE `mail`=:userMail AND `resetPassCode`=:resetPassCode";
  try {
    $usuaris = $conn->prepare($sql);
    $usuaris->execute([':userMail' => $mail, ':resetPassCode' => $resetPassCode]);
    if ($usuaris->fetchColumn() > time()) {
      $result = true;
    }
  } catch (PDOException $e) {
  } finally {
    return $result;
  }
}

function updatePassword($mail, $firstPass)
{
  $result = false;
  $conn = getDBConnection();
  $sql = "UPDATE `users` SET `passHash`= :resetPass WHERE `mail`=:mail";
  $passHash = password_hash($firstPass, PASSWORD_BCRYPT);
  try {
    $usuaris = $conn->prepare($sql);
    $rslt = $usuaris->execute([':mail' => $mail, ':resetPass' => $passHash]);
    isset($rslt) ? $result = true : $result = false;
  } catch (PDOException $e) {
    echo "";
  } finally {
    return $result;
  }
}

/********************************************************MALI DE CONFIRMACION********************************************************/

function sendConfirmationEmail($email)
{
  $mail = new PHPMailer(true);
  $mail->IsSMTP();
  //Configuració del servidor de Correu
  //Modificar a 0 per eliminar msg error
  $mail->SMTPDebug = 0;
  $mail->SMTPAuth = true;
  $mail->SMTPSecure = 'tls';
  $mail->Host = 'smtp.gmail.com';
  $mail->Port = 587;
  //Credencials del compte GMAIL
  $mail->Username = 'stardustmail001@gmail.com';
  $mail->Password = 'ezsy jqxp dnpr etgm';

  //Datos del correo
  $mail->SetFrom('stardustmail001@gmail.com', 'Soporte StarDust');
  $mail->Subject = 'Confirmation email';
  $mail->isHTML(true);
  $mail->CharSet = 'UTF-8';
  $mail->Body = confirmationEmailBodyConstructor();

  //Destinatario
  $address = $email;
  $mail->AddAddress($address);

  //Enviament
  $result = $mail->Send();
  if (!$result) {
    echo 'Error: ' . $mail->ErrorInfo;
  } else {
    echo "Correu enviat";
  }
}

function confirmationEmailBodyConstructor()
{
  $body = '
  <html>
  <body>
    <div class="container">
        <div class="header">
            <h2>Cambio de Contraseña</h2>
        </div>
        <div class="content">
            <p>Su contraseña ha sido cambiada correctamente. Si usted no ha realizado este cambio, por favor póngase en contacto con nosotros de inmediato.</p>
        </div>
        <div class="footer">
            <p>Este es un mensaje automático. Por favor, no responda a este correo.</p>
        </div>
    </div>
  </body>
  </html>
  ';
  return $body;
}

/********************************************************OBTENER DATOS USUARIO********************************************************/


function obtenirDadesUsu()
{
  $result = false;
  $conn = getDBConnection();

  if (isset($_SESSION['username'])) {
      $sql = "SELECT username, imatgeDePerfil, biografia, ubicacio, dataNaix FROM users WHERE username = :username";
      try {
          $stmt = $conn->prepare($sql);
          $stmt->execute([':username' => $_SESSION['username']]);
          $result = $stmt->fetch(PDO::FETCH_ASSOC);

          // Si no hay imagen, usa la predeterminada
          if (empty($result['imatgeDePerfil'])) {
              $result['imatgeDePerfil'] = '../img/default-profile.png';
          } else {
              $result['imatgeDePerfil'] = '../uploads/' . $result['imatgeDePerfil']; // Ruta completa
          }
      } catch (PDOException $e) {
          error_log("Error al obtener datos del usuario: " . $e->getMessage());
      }
  }
  return $result;
}

/********************************************************OBTENER POST DEL USER********************************************************/

function obtenirPosts(){
  
  $conn = getDBConnection();

    // Verificar si se pasa un filtro de etiqueta
    if (isset($_GET['tag']) && !empty($_GET['tag'])) {
        if ($_GET['tag'] === 'todos') {
            // Si el tag es "todos", mostrar todos los posts sin filtrar
            $sql = "SELECT posts.*, 
                           (SELECT COUNT(*) FROM post_likes 
                            WHERE post_likes.post_id = posts.id 
                              AND post_likes.reaction = 'like') AS like_count 
                    FROM posts 
                    ORDER BY like_count DESC, fecha DESC";
            $stmt = $conn->query($sql);
        } else {
            $tagFilter = $_GET['tag'];
            $sql = "SELECT p.*, 
                           (SELECT COUNT(*) FROM post_likes 
                            WHERE post_likes.post_id = p.id 
                              AND post_likes.reaction = 'like') AS like_count 
                    FROM posts p
                    JOIN post_tags pt ON p.id = pt.post_id
                    JOIN tags t ON t.id = pt.tag_id
                    WHERE t.name = :tag
                    ORDER BY like_count DESC, p.fecha DESC";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':tag' => $tagFilter]);
        }
    } else {
        $sql = "SELECT posts.*, 
                       (SELECT COUNT(*) FROM post_likes 
                        WHERE post_likes.post_id = posts.id 
                          AND post_likes.reaction = 'like') AS like_count 
                FROM posts 
                ORDER BY like_count DESC, fecha DESC";
        $stmt = $conn->query($sql);
    }

    while ($post = $stmt->fetch(PDO::FETCH_ASSOC)) {
        //para mostrar cada post
        echo "<div class='card mt-3'>
                <div class='card-body'>
                    <h5 class='card-title'>" . htmlspecialchars($post['username']) . "</h5>
                    <p class='card-text'>" . htmlspecialchars($post['contenido']) . "</p>";
        
        if ($post['archivo']) {
            $tipoArchivo = mime_content_type($post['archivo']);
            if (strpos($tipoArchivo, "image") !== false) {
                echo "<img src='" . htmlspecialchars($post['archivo']) . "' class='img-fluid' alt='Imagen del post'>";
            } elseif (strpos($tipoArchivo, "video") !== false) {
                echo "<video controls class='w-100'>
                        <source src='" . htmlspecialchars($post['archivo']) . "' type='" . $tipoArchivo . "'>
                        Tu navegador no soporta el video.
                      </video>";
            }
        }
        
        echo "<p class='text-muted'>" . $post['fecha'] . "</p>";

        // Obtener la reacción actual del usuario
        $checkReaction = $conn->prepare("SELECT reaction FROM post_likes WHERE post_id = ? AND username = ?");
        $checkReaction->execute([$post['id'], $_SESSION['username']]);
        $userReaction = $checkReaction->fetchColumn();

        if ($_SESSION['username'] === $post['username']) {
          echo " 
              <a href='../controller/editarPost.php?id=" . $post['id'] . "' class='btn btn-warning btn-sm'>Editar</a>
              <a href='../controller/eliminarPost.php?id=" . $post['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Seguro que quieres eliminar este post?\");'>Eliminar</a>
              ";
      }

        //Contar las reacciones
        $likeCount = $conn->query("SELECT COUNT(*) FROM post_likes WHERE post_id = {$post['id']} AND reaction = 'like'")->fetchColumn();
        $mehCount = $conn->query("SELECT COUNT(*) FROM post_likes WHERE post_id = {$post['id']} AND reaction = 'meh'")->fetchColumn();
        $dislikeCount = $conn->query("SELECT COUNT(*) FROM post_likes WHERE post_id = {$post['id']} AND reaction = 'dislike'")->fetchColumn();

        //Botones de reacción
       echo "<div class='mt-2'>
        <a href='./../controller/likePost.php?post_id={$post['id']}&reaction=like' class='btn " . ($userReaction === 'like' ? "btn-primary" : "btn-outline-primary") . " btn-sm reaction-btn' 
           data-post-id='{$post['id']}' data-reaction='like'>👍 ($likeCount)
        </a>
        <a href='./../controller/likePost.php?post_id={$post['id']}&reaction=meh' class='btn " . ($userReaction === 'meh' ? "btn-warning" : "btn-outline-warning") . " btn-sm reaction-btn' 
           data-post-id='{$post['id']}' data-reaction='meh'>😐  ($mehCount)
        </a>
        <a href='./../controller/likePost.php?post_id={$post['id']}&reaction=dislike' class='btn " . ($userReaction === 'dislike' ? "btn-danger" : "btn-outline-danger") . " btn-sm reaction-btn' 
           data-post-id='{$post['id']}' data-reaction='dislike'>👎 ($dislikeCount)
        </a>
      </div>";

      echo "<button class='btn btn-link text-primary' data-bs-toggle='collapse' data-bs-target='#comentarios-{$post['id']}'>
          Ver comentarios
      </button>";
  
      echo "<div class='collapse mt-2' id='comentarios-{$post['id']}'>";
      
      //Muestra los comentarios existentes
      $comentSql = "SELECT * FROM comments WHERE post_id = ? ORDER BY fecha ASC";
      $comentStmt = $conn->prepare($comentSql);
      $comentStmt->execute([$post['id']]);
      
      while ($coment = $comentStmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<div class='border p-2 mt-2'>
                <strong id='nombreUsuComentario'>" . htmlspecialchars($coment['username']) . ":</strong>
                <p class='mb-0'>" . htmlspecialchars($coment['contenido']) . "</p>
                <small id='fechaBlancoComentario' class='text-muted'>" . $coment['fecha'] . "</small>";
        
        //Si el usuario es el autor del comentario, mostrar botones para editar y eliminar
        if ($_SESSION['username'] === $coment['username']) {
            echo "<div class='mt-2'>
                    <a href='../controller/editarComentario.php?id=" . $coment['id'] . "' class='btn btn-warning btn-sm'>Editar</a>
                    <a href='../controller/eliminarComentario.php?id=" . $coment['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Seguro que deseas eliminar este comentario?\");'>Eliminar</a>
                  </div>";
        }
        
        echo "</div>";
    }
  
        //Formulario para añadir un comentario
        echo "<form action='../controller/agregarComentario.php' method='POST' class='mt-2'>
                <input type='hidden' name='post_id' value='{$post['id']}'>
                <textarea name='contenido' class='form-control' rows='2' placeholder='Añadir un comentario...' required></textarea>
                <button type='submit' class='btn btn-primary btn-sm mt-2'>Comentar</button>
              </form>";
        
        echo "</div>"; // Cierre del contenedor de comentarios
        echo "  </div> <!-- Cierra .card-body -->
            </div> <!-- Cierra .card -->";
  }
              
}