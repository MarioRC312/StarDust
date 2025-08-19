<?php
// chdir("..");
require "../controller/controller.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
  if(!empty($_GET)){
    isset($_GET["code"]) ? $resetPassCode = $_GET["code"] : $resetPassCode ='';
    isset($_GET["mail"]) ? $mail = $_GET["mail"] : $mail = '';

    if(!verifyResetPassCode($mail, $resetPassCode) || !verifyTimeLeft($mail, $resetPassCode))
    {
      header('Location: ../index.php?resetPass=error');
      exit();
    }
    else 
    {
      $firstPass = $_POST["firstPassword"];
      $scndPass = $_POST["scndPassword"];
    
      if($firstPass != $scndPass) $msgError = "Las contraseñas no coinciden";
      else if(updatePassword($mail, $firstPass)){
        //mail de confirmacion
        sendConfirmationEmail($mail);
        header('Location: ../index.php?resetPass=success');
        exit();
      }
      else 
      {
        header('Location: ../index.php?resetPass=error');
        exit();
      }
    }
  }
}



?>

<!DOCTYPE html>
<html lang="es">

    <head>
        <title>Sign Up</title>
        <meta charset="utf-8">
        <meta name="author" content="StarDust">
        <meta name="description" content="description">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="../img/Star_Dust.png">
        <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../css/index.css">
    </head>

    <body id="screen">
      <section class="text-animation-box">
        <div>
          <h1 class="typeHeader">Welcome back!</h1>
          <p class="typeText"></p>
        </div>
      </section>
      <section class="form-box">
        <h1>Reset Password</h1>

          <form class="login-form" action="<?php htmlspecialchars($_SERVER["REQUEST_METHOD"]) ?>" method="POST">

              <div class="input-box" id="input-lstPwd">
                <label for="firstPassword"><ion-icon name="lock-closed-outline"></ion-icon></label>
                <input type="password" id="firstPassword" name="firstPassword" required="true" placeholder="">
                <span>New Password</span>
                <p id="userError" class="inactive"></p>
              </div>

              <div class="input-box" id="input-newPwd">
                <label for="scndPassword"><ion-icon name="lock-closed-outline"></ion-icon></label>
                <input type="password" id="scndPassword" name="scndPassword" required placeholder="">
                <span>Repeat Password</span>
                <p id="error" class="inactive"></p>
              </div>

            <button class="button-86" id="form-button">Change Password</button>
          </form>
          
          <div class="change-form">
            <p>Go Back to Login</p>
            <a href="/index.php">Sign In</a>
          </div>
      </section>
    </body>
</html>
