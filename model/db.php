<?php
function getDBConnection()
{
  $connString = 'mysql:host=localhost:3335;dbname=eduforum;charset=utf8';
  $user = 'root';
  $pass = '';
  $db = null;

  try {
      $db = new PDO($connString, $user, $pass,array(PDO::ATTR_PERSISTENT => true));
  } catch (PDOException $e) {
      error_log("Error al conectar con la base de datos: " . $e->getMessage());
  } finally {
      return $db;
  }
}

/***********************************Logear Usuario***********************************/

function loginUserDB($userOrEmail, $pass)
{
  $result = false;
  $conn = getDBConnection();
  $sql = "SELECT * FROM `users` WHERE `mail`=:userOrEmail OR `username`=:userOrEmail";
  try {
      $usuaris = $conn->prepare($sql);
      $usuaris->execute([':userOrEmail' => $userOrEmail]);
      if ($usuaris->rowCount() == 1) {
          $dadesUsuari = $usuaris->fetch(PDO::FETCH_ASSOC);
          if ($dadesUsuari['active'] == 1) {
              if (password_verify($pass, $dadesUsuari['passHash'])) {
                  $result = $dadesUsuari;
                  updateLastSignIn($userOrEmail);
              } else {
                  $result = "Wrong password";
              }
          } else {
              $result = "User not activated";
          }
      } else {
          $result = "User or email does not exist";
      }
  } catch (PDOException $e) {
      error_log("Error en loginUserDB: " . $e->getMessage());
  } finally {
      return $result;
  }
}

function insertUserDB($user)
{$result = false;

  $conn = getDBConnection();
  if ($conn == null) {
      echo "No connexion";
  } else {
      $userExists = verifyExistentUserDB($user);
      if ($userExists == true) {
          $result = "User or email already exist";
      } else {

         
        $sql = 'INSERT INTO users (mail, username, passHash, userFirstName, userLastName, creationDate, removeDate, lastSignIn, active, activationCode) 
        VALUES (:mail, :username ,:passHash, :userFirstName, :userLastName, now(), null, null, 0, :activationCode)';

          $mail = $user['email'];
          $pass = $user['passHash'];
          $username = $user['username'];
          $userFirstName = $user['userFirstName'];
          $userLastName = $user['userLastName'];
          $activationCode = $user['activationCode'];

          try {
              $resultat = $conn->prepare($sql);
              $resultat->execute([
                  ':mail' => $mail,
                  ':username' => $username,
                  ':passHash' => $pass,
                  ':userFirstName' => $userFirstName,
                  ':userLastName' => $userLastName,
                  ':activationCode' => $activationCode
              ]);

              if ($resultat) {
                  $result = true;
              }
          } catch (PDOException $e) {
              echo $e->getMessage();
          } finally {
              return $result;
          }
      }
  }
}

function verifyExistentUserDB($user)
{
    $result = false;
    $conn = getDBConnection();
    $sql = "SELECT * FROM `users` WHERE `mail`=:userMail OR `username`=:userName";
    try {
        $usuaris = $conn->prepare($sql);
        $usuaris->execute([':userMail' => $user['email'], ':userName' => $user['username']]);
        if ($usuaris->rowCount() == 1) {
            $result = true;
        }
    } catch (PDOException $e) {
        echo "";
    } finally {
        return $result;
    }
}

function updateLastSignIn($userOrEmail)
{
    $result = false;
  $conn = getDBConnection();
  $sql = "UPDATE `users` SET `lastSignIn`=now() WHERE `mail`=:userOrEmail OR `username`=:userOrEmail";
  try {
    $usuaris = $conn->prepare($sql);
    $rslt = $usuaris->execute([':userOrEmail' => $userOrEmail]);
    if ($rslt) {
      $result = true;
    }

  } catch (PDOException $e) {
    echo "";
  } finally {
    return $result;
  }
}

function getActivationCode($mail)
{
    $result = false;
  $conn = getDBConnection();
  $sql = "SELECT activationCode FROM `users` WHERE `mail`=:userMail";
  try {
    $usuaris = $conn->prepare($sql);
    $usuaris->execute([':userMail' => $mail]);
    if ($usuaris->rowCount() == 1) {
      $result = $usuaris->fetchColumn();
    }
  } catch (PDOException $e) {
    echo "";
  } finally {
    return $result;
  }
}

function updateActiveDB($mail)
{
    $result = false;
  $conn = getDBConnection();
  $sql = "UPDATE `users` SET `active`=1, `activationDate`=now() WHERE `mail`=:mail";
  try {
    $usuaris = $conn->prepare($sql);
    $rslt = $usuaris->execute([':mail' => $mail]);
    if ($rslt) {
      $result = true;
    }

  } catch (PDOException $e) {
    echo "";
  } finally {
    return $result;
  }
}