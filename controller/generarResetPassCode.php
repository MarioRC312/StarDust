<?php
require_once('./db.php'); 

function generateResetPassCodeDB($mail)
{ 
  $result = false;
  $conn = getDBConnection();
  $sql = "UPDATE `users` SET `resetPassCode`= :resetPassCode, `resetPassExpiry`=DATE_ADD(NOW(), INTERVAL 30 MINUTE) WHERE `mail`=:mail";
  try {
    $resetPassCode = hash("sha256", rand(0, 9999));
    $usuaris = $conn->prepare($sql);
    $rslt = $usuaris->execute([':mail' => $mail, ':resetPassCode' => $resetPassCode]);
    if ($rslt) {
      $result = $resetPassCode;
    }

  } catch (PDOException $e) {
    echo "";
  } finally {
    return $result;
  }
}