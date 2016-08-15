<?php

include 'properties.php';
include 'utils.php';
include 'userUtils.php';
include 'objects/tag.php';

// Démarrage de la session
session_start();

// Test si l'utilisateur est connecté
if(!isUserAlreadyConnected()){
  $retourcode = 401;
} else {
  if(isset($_GET['action'])){
    switch($_GET['action']){
      case 'list':
        //$retourcode = listTags();
        break;
      case 'add':
        $retourcode = ajoutProjet();
        break;
      default:
        $retourcode = 400;
    }
  }else{
    $retourcode = 400;
  }
}

function ajoutProjet(){
  
}

http_response_code($retourcode);
return;

?>
