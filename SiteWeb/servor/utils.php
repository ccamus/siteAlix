<?php
function getBddConnexion() {
  $strConnection = 'mysql:host='.Constants::BDD_HOST.';dbname='.Constants::BDD_BASENAME;
  $arrExtraParam= array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
  $pdo = new PDO($strConnection, Constants::BDD_USER, Constants::BDD_PWD, $arrExtraParam);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  return $pdo;
}
?>
