<?php 
  /**
   * @var $model \app\models\User
   */
?>

<h1>Create an account</h1>
$form = \app\core\form\Form::begin('', "post") ?>
<div class="row">
  <div class="col">
    <?php echo $form->field($model, "firstName");
    ?>
  </div>
  <div class="col">
  <?php echo $form->field($model, "secondName");?>
  </div>
</div>
  <?php echo $form->field($model, "email");?>
  <?php echo $form->field($model, "password")->passwordField();?>
  <?php echo $form->field($model, "confirmingPassword")->passwordField();?>
  <button type="submit" class="btn btn-primary">Submit</button>
<?php echo \app\core\form\Form::end()?>

<!-- <form action="" method="post">
  <div class="row">
    <div class="col" name="firstName">
      <div class="form-group">
        <label>First name</label>
        <input type="text" name="firstName" class="form-control" >
        <div class="invalid-feedback">
        </div>
      </div> 
    </div>
    <div class="col" name="secondName">
    <div class="form-group">
        <label>Second name</label>
        <input type="text" name="secondName" class="form-control">
      </div>
    </div>
  </div>
  <div class="form-group">
    <label>Email</label>
    <input type="email" name="email" class="form-control">
  </div>
  <div class="form-group">
    <label>Password</label>
    <input type="password" name="password" class="form-control">
  </div>
  <div class="form-group">
    <label>Confirm Password</label>
    <input type="password" name="confirmingPassword" class="form-control">
  </div>
</form> -->