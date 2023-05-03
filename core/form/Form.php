<?php 
namespace app\core\form;



class Form {
  public static function begin($action, $method) {
    echo sprintf('<form action="%s" method="%s">', $action, $method);
    return new Form();
  }

  public static function end() {
    return '</form>'; 
  }

  /**
   * @param \app\core\Model $model
   * @param string $attribute
   */

  public function field(\app\core\Model $model, string $attribute) {
    return new Field($model, $attribute);
  }
}
?>