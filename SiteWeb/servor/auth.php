<?php

include 'properties.php';
include 'utils.php';
include 'userUtils.php';

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

?>
