<?php

include 'properties.php';
include 'utils.php';
include 'userUtils.php';
include 'objects/categorie.php';

// Démarrage de la session
session_start();

// Test si l'utilisateur est connecté
if(!isUserAlreadyConnected()){
  $retourcode = 401;
} else {
  if(isset($_GET['action'])){
    switch($_GET['action']){
      case 'list':
        $retourcode = listCategories();
        break;
      case 'update':
        $retourcode = update();
        break;
      default:
        $retourcode = 400;
    }
  }else{
    $retourcode = 400;
  }
}

http_response_code($retourcode);
return;


/*
 * Renvoi la liste des catégories
 */
function listCategories(){
  $categorieList = array();
  try {
    $pdo = getBddConnexion();

    // Création de la requête
    $query = 'select * FROM CATEGORIE;';

    // On boucle sur les résultats
    foreach($pdo->query($query) as $row) {
      $categorie = new Categorie($row['id'], $row['categorie']);
      array_push($categorieList, $categorie->toString());
    }
  }
  catch(PDOException $e) {
      $msg = 'ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage();
      die($msg);
  }
  // On les affiche au format JSON
  echo json_encode($categorieList);
  return 200;
}

/*
 * Met à jour les catégories (ajout/suppression)
 */
function update(){

}

?>
