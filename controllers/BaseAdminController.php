<?php
/**
 * Code: Dx.M
 * Email: 2424428867@qq.com
 * Date: 2018/6/7
 * Time: 11:23 PM
 */

namespace app\controllers;


use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class BaseAdminController extends Controller
{
    public function init()
    {
        \Yii::$app->language = 'zh-CN';
        Yii::$app->user->identityClass = 'app\models\User';
    }
}