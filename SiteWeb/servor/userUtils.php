<?php

/*
 * Test si l'utilisateur est connecté
 */
function isUserAlreadyConnected(){
  return session_status() === PHP_SESSION_ACTIVE
    && isset($_SESSION['userName']) && isset($_SESSION['pwd'])
    && isUserMdpValid($_SESSION['userName'], $_SESSION['pwd']);
}

/**
 * Test si le username et mot de passe passé en paramètre sont valides.
 */
function isUserMdpValid($userName, $userMdp){

  $retour = false;
  // Ouverture de le connexion
  try {
    $pdo = getBddConnexion();
  }
  catch(PDOException $e) {
      $msg = 'ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage();
      die($msg);
  }

  // Création de la requête
  $query = 'select 1 FROM UTILISATEUR WHERE login=? AND PASSWORD = PASSWORD(?);';
  $prep = $pdo->prepare($query);
  $prep->bindValue(1, $userName, PDO::PARAM_STR);
  $prep->bindValue(2, $userMdp, PDO::PARAM_STR);

  // Exécution et visualisation des résultats
  $prep->execute();
  if($row = $prep->fetch()){
    $retour = true;
  }

  // Fermeture et envoi résultat
  $prep->closeCursor();
  $prep = NULL;

  return $retour;
}

?>
