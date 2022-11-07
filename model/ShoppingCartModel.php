<?php
class ShoppingCartModel {

  public function getValue($colname) { return $this->id; }
  private function setValue($colname, $value) { $this->id = $id; }

  public function __construct() {}

  public static function GetProductList () {
    $models = [];
    $db = new DatabaseClass();
    $statement = $db->query('SELECT `name`, `qty`, `price`, `offer_values`, `id` FROM `products` WHERE `is_deleted` = "N"')->fetchAll();
    $db->close();
    return $statement;
  }
}
?>