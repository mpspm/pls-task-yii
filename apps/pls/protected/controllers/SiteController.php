<?php

/**
 * @class      SiteController
 *
 * This is the controller that contains the /site actions.
 *
 * @author     Developer
 * @copyright  PLS 3rd Learning, Inc. All rights reserved.
 */

class SiteController extends Controller
{

  /**
   * Specifies the action filters.
   *
   * @return array action filters
   */
  public function filters()
  {
    return [
      'accessControl',
    ];
  }

  /**
   * Specifies the access control rules.
   *
   * @return array access control rules
   */
  public function accessRules()
  {
    return [
      [
        'allow',  // allow all users to access specified actions.
        'actions' => ['index', 'login', 'about', 'error'],
        'users'   => ['*'],
      ],
      [
        'allow', // allow authenticated users to access all actions
        'users' => ['@'],
      ],
      [
        'deny',  // deny all users
        'users' => ['*'],
      ],
    ];
  }

  /**
   * Initialize the controller.
   *
   * @return void
   */
  public function init()
  {
    $this->defaultAction = 'login';
  }

  /**
   * Renders the about page.
   *
   * @return void
   */
  public function actionAbout()
  {
    $this->render('about');
  }

  /**
   * Renders the login page.
   *
   * @return void
   */
  public function actionLogin()
  {
    if (!defined('CRYPT_BLOWFISH') || !CRYPT_BLOWFISH) {
      throw new CHttpException(500, 'This application requires that PHP was compiled with Blowfish support for crypt().');
    }
    $model = new LoginForm();
    if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }
    if (isset($_POST['LoginForm'])) {
      $model->attributes = $_POST['LoginForm'];
      if ($model->validate() && $model->login()) {
        $this->redirect(Yii::app()->user->returnUrl);
      }
    }
    $latestProductUpdates = RssHelper::getItemsByLimit(1, Yii::app()->params['productUpdatesFeedUrl'], Yii::app()->basePath . Yii::app()->params['productUpdatesFeedCacheDir'], Yii::app()->params['productUpdatesFeedCacheExp'], Yii::app()->params['curlUserAgent']);
    if (count($latestProductUpdates) > 0) {
      foreach ($latestProductUpdates as $item) {
        $item->description = trim(str_replace(' [&#8230;]', '...', $item->description));
        $item->description = preg_replace('/The post.*appeared first on .*\./', '', $item->description);
      }
    }
    $latestBlogUpdates = RssHelper::getItemsByLimit(1, Yii::app()->params['blogFeedUrl'], Yii::app()->basePath . Yii::app()->params['blogFeedCacheDir'], Yii::app()->params['blogFeedCacheExp'], Yii::app()->params['curlUserAgent']);

    $this->render('login', ['model' => $model, 'latestProductUpdates' => $latestProductUpdates, 'latestBlogUpdates' => $latestBlogUpdates]);
  }

  /**
   * Logs out the current user and redirects to homepage.
   *
   * @return void
   */
  public function actionLogout()
  {
    Yii::app()->user->logout();
    $this->redirect(Yii::app()->homeUrl);
  }

  /**
   * The action that handles external exceptions.
   *
   * @return void
   */
  public function actionError()
  {
    if ($error = Yii::app()->errorHandler->error) {
      if (Yii::app()->request->isAjaxRequest) {
        echo $error['message'];
      } else {
        $this->render('//site/error', $error);
      }
    }
  }

}
