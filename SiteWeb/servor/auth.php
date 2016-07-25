<?php

include 'utils.php';

// Démarrage de la session
session_start();

if(isset($_GET['action'])){
  switch($_GET['action']){
    case 'auth':
      $retourcode = connexion();
      break;
    case 'deco':
      $retourcode = deconnexion();
      break;
    case 'testAuth':
      $retourcode = testAuth();
      break;
    default:
      $retourcode = 400;
  }
}else{
  $retourcode = 400;
}
http_response_code($retourcode);
return;

/*
 * Test si l'utilisateur est déjà connecté
 */
function testAuth(){
  if(isUserAlreadyConnected()){
    return 200;
  }else{
    return 401;
  }
}

/*
 * Déconnexion de l'utilisateur
 */
function deconnexion(){
  session_unset();
  session_destroy();

  return 200;
}

/*
 * Connexion de l'utilisateur
 */
function connexion(){

  // Test si l'utilisateur est déjà connecté
  if(isUserAlreadyConnected()){
    return 200;
  }

  // Si l'utilisateur n'est pas connecté, on test que les paramètres existent
  $postdata = file_get_contents("php://input");
  $request = json_decode($postdata);
  if(empty($request->userName) || empty($request->userPw)){
    return 400;
  }

  // Test des infos de connexion
  if(!isUserMdpValid($request->userName, $request->userPw)){
    return 401;
  }

  // On connecte l'utilisateur
  $_SESSION['userName']=$request->userName;
  $_SESSION['pwd']=$request->userPw;

  return 200;
}

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
  include 'properties.php';

  $retour = false;
  // Ouverture de le connexion
  try {
    $pdo = getBddConnexion($bdd);
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
