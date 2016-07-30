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
        $retourcode = listTags();
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
 * Renvoi la liste des tags
 */
function listTags(){
  $tagList = array();
  try {
    $pdo = getBddConnexion();

    // Création de la requête
    $query = "select * FROM TAG;";

    // On boucle sur les résultats
    foreach($pdo->query($query) as $row) {
      $tag = new Tag($row['id'], $row['tagFr'], $row['tagEn']);
      array_push($tagList, $tag->toString());
    }
  }catch(PDOException $e) {
      $msg = 'ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage();
      die($msg);
  }
  // On les affiche au format JSON
  echo json_encode($tagList);
  return 200;
}

/*
 * Met à jour les tags (ajout/suppression)
 */
function update(){
  // Récupération des données depuis la requête
  $postdata = file_get_contents("php://input");
  $request = json_decode($postdata);
  $newTags = transformToTagList($request->tags);

  // Supprime les élements
  $retour = deleteElements($newTags);
  if($retour != 200){
    return $retour;
  }

  // Ajoute les élements nouveaux
  $retour = addNewElements($newTags);

  return $retour;
}

/**
 * Ajoute les éléments de la liste qui on un id = -1
 */
function addNewElements($tags){
  $hasItemToInsert = false;
  $requete = "INSERT INTO TAG (tagFr, tagEn) VALUES";
  // Création de la requête d'ajout
  foreach($tags as $item){
    if($item->getId() == -1){
      if(!$hasItemToInsert){
        $hasItemToInsert = true;
        $requete .= " (?, ?)";
      } else {
        $requete .= ", (?, ?)";
      }
    }
  }
  // S'il y a des items à ajouter
  if($hasItemToInsert){
    try {
      $pdo = getBddConnexion();
      // On prépare la requête
      $prep = $pdo->prepare($requete);
      $index = 1;
      foreach($tags as $item){
        if($item->getId() == -1){
          $prep->bindValue($index, $item->getTagFr(), PDO::PARAM_STR);
          $prep->bindValue($index+1, $item->getTagEn(), PDO::PARAM_STR);
          $index = $index + 2;
        }
      }
      // Puis on l'exécute
      $reqOk = $prep->execute();
      if(!$reqOk){
        echo "Erreur lors de l'ajout des tags";
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
function deleteElements($tagLists){
  try {
    $pdo = getBddConnexion();

    $requete = "SELECT id FROM TAG;";
    $hasItemToDelete = false;
    $requeteDelete = "DELETE FROM TAG WHERE";
    // On boucle sur les résultats pour déterminer la liste des items à supprimer
    foreach($pdo->query($requete) as $row) {
      // Si l'item de la base n'est pas dans la liste envoyée, on l'ajoute à la requête de suppression
      if(!isIdTagInList($row['id'], $tagLists)){
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
 * Transforme une liste en liste de tags
 */
function transformToTagList($list){
  $newTags = array();
  foreach($list as $item){
    $tagFr = isset($item->tagFr) ? $item->tagFr : "";
    $tagEn = isset($item->tagEn) ? $item->tagEn : "";
    $tag = new Tag($item->id, $tagFr, $tagEn);
    array_push($newTags, $tag);
  }
  return $newTags;
}

/*
 * Recherche dans une liste de tags si un id est présent
 */
function isIdTagInList($idTag, $list){
  $return = false;
  foreach($list as $tag){
    if($tag->getId() == $idTag){
      $return = true;
      break;
    }
  }
  return $return;
}

?>
