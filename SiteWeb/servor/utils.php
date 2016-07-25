<?php
function getBddConnexion($bddCo) {
  $strConnection = 'mysql:host='.$bddCo['host'].';dbname='.$bddCo['baseName'];
  $arrExtraParam= array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
  $pdo = new PDO($strConnection, $bddCo['user'], $bddCo['pwd'], $arrExtraParam);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  return $pdo;
}
?>
