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
    $query = "select * FROM CATEGORIE;";

    // On boucle sur les résultats
    foreach($pdo->query($query) as $row) {
      $categorie = new Categorie($row['id'], $row['categorie']);
      array_push($categorieList, $categorie->toString());
    }
  }catch(PDOException $e) {
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
  // Récupération des données depuis la requête
  $postdata = file_get_contents("php://input");
  $request = json_decode($postdata);
  $newCategories = transformToCategorieList($request->categories);

  // Supprime les élements
  $retour = deleteElements($newCategories);
  if($retour != 200){
    return $retour;
  }

  // Ajoute les élements nouveaux
  $retour = addNewElements($newCategories);

  return $retour;
}

/**
 * Ajoute les éléments de la liste qui on un id = -1
 */
function addNewElements($categories){
  $hasItemToInsert = false;
  $requete = "INSERT INTO CATEGORIE (categorie) VALUES";
  // Création de la requête d'ajout
  foreach($categories as $item){
    if($item->getId() == -1){
      if(!$hasItemToInsert){
        $hasItemToInsert = true;
        $requete .= " (\"" . $item->getCategorie() . "\")";
      } else {
        $requete .= ", (\"" . $item->getCategorie() . "\")";
      }
    }
  }

  // S'il y a des items à ajouter, on lance la requête
  if($hasItemToInsert){
    try {
      $pdo = getBddConnexion();
      $nbAdded = $pdo->exec($requete . ";");
      if($nbAdded == 0){
        echo "Erreur lors de l'ajout des catégories";
        return 500;
      }
    }catch(PDOException $e) {
        $msg = 'ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage();
        die($msg);
    }
  }
  return 200;
}

/**
 * Supprime en base les éléments qui ne sont pas présents dans la liste passée en paramètre
 */
function deleteElements($categorieLists){
  try {
    $pdo = getBddConnexion();

    $requete = "SELECT id FROM CATEGORIE;";
    $hasItemToDelete = false;
    $requeteDelete = "DELETE FROM CATEGORIE WHERE";
    // On boucle sur les résultats pour déterminer la liste des items à supprimer
    foreach($pdo->query($requete) as $row) {
      // Si l'item de la base n'est pas dans la liste envoyée, on l'ajoute à la requête de suppression
      if(!isIdCategorieInList($row['id'], $categorieLists)){
        // Si c'est le prmier, on le met juste dans le where, sinon on le met dans un OR
        if(!$hasItemToDelete){
          $requeteDelete .= " id = ".$row['id'];
          $hasItemToDelete = true;
        }else{
          $requeteDelete .= " OR id = ".$row['id'];
        }
      }
    }
    // S'il y a des items à supprimer, on lance la requête
    if($hasItemToDelete){
      $nbDeleted = $pdo->exec($requeteDelete.";");
      if($nbDeleted == 0){
        echo "Erreur lors de la suppression des catégories";
        return 500;
      }
    }
  }catch(PDOException $e) {
      $msg = 'ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage();
      die($msg);
  }
  return 200;
}

/*
 * Transforme une liste en liste de catégories
 */
function transformToCategorieList($list){
  $newCategories = array();
  foreach($list as $item){
    $categorie = new Categorie($item->id, $item->categorie);
    array_push($newCategories, $categorie);
  }
  return $newCategories;
}

/*
 * Recherche dans une liste de catégories si un id est présent
 */
function isIdCategorieInList($idCategorie, $list){
  $return = false;
  foreach($list as $categorie){
    if($categorie->getId() == $idCategorie){
      $return = true;
      break;
    }
  }
  return $return;
}

?>
