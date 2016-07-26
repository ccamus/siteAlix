<?php
class Categorie{
  private $id;
  private $categorie;

  public function __construct($id, $categorie){
    $this->id = $id;
    $this->categorie = $categorie;
  }

  public function toString() {
    return get_object_vars($this);
  }
}
?>
