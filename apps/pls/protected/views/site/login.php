<?php

/**
 * @var SiteController $this
 * @var LoginForm      $model
 * @var SimpleXMLElement[] $latestProductUpdates
 * @var SimpleXMLElement[] $latestBlogUpdates
 */
$txtLogin = Yii::t('pls', 'Login');
$this->pageTitle = Yii::app()->name . ' - ' . $txtLogin;
$this->breadcrumbs = [
  $txtLogin,
];
?>

<div class="container">
  <div class="row">
    <div class="col-md-6 align-self-center">
      <?php
      $this->renderPartial('_login', ['model' => $model]);
      ?>
    </div>
    <div class="col-md-6">
      <?php
      $this->renderPartial('_slides', ['latestProductUpdates' => $latestProductUpdates, 'latestBlogUpdates' => $latestBlogUpdates]);
      ?>
    </div>
  </div>
</div>