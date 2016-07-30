<?php
class Tag{
  private $id;
  private $tagFr;
  private $tagEn;

  public function __construct($id, $tagFr, $tagEn){
    $this->id = $id;
    $this->tagFr = $tagFr;
    $this->tagEn = $tagEn;
  }

  public function getId(){
    return $this->id;
  }

  public function getTagFr(){
    return $this->tagFr;
  }

  public function getTagEn(){
    return $this->tagEn;
  }

  public function toString() {
    return get_object_vars($this);
  }
}
?>
