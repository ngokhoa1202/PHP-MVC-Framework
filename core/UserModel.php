<?php
namespace app\core;

use app\models;

abstract class UserModel extends DBModel {
  abstract public function getDisplayName() : string;
}
?>